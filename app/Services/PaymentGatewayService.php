<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentGatewayService
{
    /**
     * Process payment through selected production gateway.
     * Supports: 'card' (Stripe), 'upi' / 'razorpay' (Razorpay), 'paypal' (PayPal), and 'free' ($0.00 enrollment).
     *
     * @param string $method
     * @param float $amount
     * @param string $currency
     * @param array $payload
     * @return array
     */
    public static function processCharge(string $method, float $amount, string $currency = 'USD', array $payload = []): array
    {
        // 1. Zero-Dollar / Free Tier Course Handling
        if ($amount <= 0.00 || strtolower($method) === 'free') {
            return [
                'success' => true,
                'gateway' => 'Free Enrollment',
                'transaction_id' => 'free_' . Str::random(16),
                'status' => 'completed',
                'fee' => 0.00,
            ];
        }

        $method = strtolower($method);

        switch ($method) {
            case 'upi':
            case 'razorpay':
                return self::processRazorpay($amount, $currency, $payload);

            case 'paypal':
                return self::processPayPal($amount, $currency, $payload);

            case 'card':
            case 'stripe':
            default:
                return self::processStripe($amount, $currency, $payload);
        }
    }

    /**
     * Stripe Production Payment Processor.
     */
    private static function processStripe(float $amount, string $currency, array $payload): array
    {
        $stripeSecret = config('services.stripe.secret') ?: env('STRIPE_SECRET');

        if (empty($stripeSecret)) {
            Log::warning('Stripe processing attempted without STRIPE_SECRET configured.');
            return [
                'success' => false,
                'gateway' => 'Stripe',
                'error' => 'Stripe payment gateway is not configured. Please set STRIPE_SECRET in your production .env environment file.',
            ];
        }

        try {
            // Using HTTP client against Stripe API (v1)
            $response = Http::withToken($stripeSecret)
                ->asForm()
                ->post('https://api.stripe.com/v1/payment_intents', [
                    'amount' => (int) round($amount * 100),
                    'currency' => strtolower($currency),
                    'payment_method_types' => ['card'],
                    'description' => 'Digital Academy: Enrollment for User #' . ($payload['user_id'] ?? 'N/A'),
                    'metadata' => [
                        'user_id' => (string) ($payload['user_id'] ?? ''),
                        'course_id' => (string) ($payload['course_id'] ?? ''),
                        'tier' => (string) ($payload['tier'] ?? ''),
                    ],
                ]);

            if ($response->successful()) {
                $intent = $response->json();
                return [
                    'success' => true,
                    'gateway' => 'Stripe',
                    'transaction_id' => $intent['id'],
                    'status' => $intent['status'] ?? 'succeeded',
                    'fee' => round($amount * 0.029 + 0.30, 2),
                ];
            }

            $errorMsg = $response->json('error.message') ?? 'Payment card processing failed with Stripe.';
            Log::error('Stripe API error: ' . $errorMsg);
            return [
                'success' => false,
                'gateway' => 'Stripe',
                'error' => $errorMsg,
            ];
        } catch (\Throwable $e) {
            Log::error('Stripe processing exception: ' . $e->getMessage());
            return [
                'success' => false,
                'gateway' => 'Stripe',
                'error' => 'Unable to contact Stripe payment server: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Razorpay Production Processor.
     */
    private static function processRazorpay(float $amount, string $currency, array $payload): array
    {
        $keyId = config('services.razorpay.key') ?: env('RAZORPAY_KEY');
        $keySecret = config('services.razorpay.secret') ?: env('RAZORPAY_SECRET');

        if (empty($keyId) || empty($keySecret)) {
            Log::warning('Razorpay processing attempted without RAZORPAY_KEY or RAZORPAY_SECRET configured.');
            return [
                'success' => false,
                'gateway' => 'Razorpay',
                'error' => 'Razorpay payment gateway is not configured. Please set RAZORPAY_KEY and RAZORPAY_SECRET in your production .env file.',
            ];
        }

        try {
            $paymentId = $payload['razorpay_payment_id'] ?? null;

            if ($paymentId) {
                // Verify/Fetch payment from Razorpay API
                $response = Http::withBasicAuth($keyId, $keySecret)
                    ->get("https://api.razorpay.com/v1/payments/{$paymentId}");

                if ($response->successful()) {
                    $payment = $response->json();
                    if (in_array($payment['status'], ['captured', 'authorized'])) {
                        return [
                            'success' => true,
                            'gateway' => 'Razorpay',
                            'transaction_id' => $payment['id'],
                            'status' => $payment['status'],
                            'fee' => round($amount * 0.02, 2),
                        ];
                    }
                }
            }

            // Create Order on Razorpay
            $orderRes = Http::withBasicAuth($keyId, $keySecret)
                ->post('https://api.razorpay.com/v1/orders', [
                    'amount' => (int) round($amount * 100),
                    'currency' => strtoupper($currency) === 'INR' ? 'INR' : 'USD',
                    'receipt' => 'da_' . time() . '_' . Str::random(6),
                ]);

            if ($orderRes->successful()) {
                $order = $orderRes->json();
                return [
                    'success' => true,
                    'gateway' => 'Razorpay',
                    'transaction_id' => $order['id'],
                    'status' => 'order_created',
                    'fee' => round($amount * 0.02, 2),
                ];
            }

            $errMsg = $orderRes->json('error.description') ?? 'Razorpay transaction could not be authorized.';
            Log::error('Razorpay API error: ' . $errMsg);
            return [
                'success' => false,
                'gateway' => 'Razorpay',
                'error' => $errMsg,
            ];
        } catch (\Throwable $e) {
            Log::error('Razorpay processing exception: ' . $e->getMessage());
            return [
                'success' => false,
                'gateway' => 'Razorpay',
                'error' => 'Unable to contact Razorpay payment server: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * PayPal Production Processor.
     */
    private static function processPayPal(float $amount, string $currency, array $payload): array
    {
        $clientId = config('services.paypal.client_id') ?: env('PAYPAL_CLIENT_ID');
        $secret = config('services.paypal.secret') ?: env('PAYPAL_SECRET');

        if (empty($clientId) || empty($secret)) {
            Log::warning('PayPal processing attempted without PAYPAL_CLIENT_ID or PAYPAL_SECRET configured.');
            return [
                'success' => false,
                'gateway' => 'PayPal',
                'error' => 'PayPal gateway is not configured. Please set PAYPAL_CLIENT_ID and PAYPAL_SECRET in your production .env file.',
            ];
        }

        $mode = config('services.paypal.mode', 'sandbox');
        $baseUrl = $mode === 'live' ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';

        try {
            // Get Access Token
            $authRes = Http::withBasicAuth($clientId, $secret)
                ->asForm()
                ->post("{$baseUrl}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials',
                ]);

            if (!$authRes->successful()) {
                return [
                    'success' => false,
                    'gateway' => 'PayPal',
                    'error' => 'PayPal authorization failure. Check PAYPAL_CLIENT_ID and PAYPAL_SECRET in .env.',
                ];
            }

            $token = $authRes->json('access_token');

            // Create PayPal Order
            $orderRes = Http::withToken($token)
                ->post("{$baseUrl}/v2/checkout/orders", [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'amount' => [
                            'currency_code' => strtoupper($currency),
                            'value' => number_format($amount, 2, '.', ''),
                        ],
                        'description' => 'Digital Academy Enrollment',
                    ]],
                ]);

            if ($orderRes->successful()) {
                $order = $orderRes->json();
                return [
                    'success' => true,
                    'gateway' => 'PayPal',
                    'transaction_id' => $order['id'],
                    'status' => $order['status'] ?? 'CREATED',
                    'fee' => round($amount * 0.034 + 0.35, 2),
                ];
            }

            return [
                'success' => false,
                'gateway' => 'PayPal',
                'error' => 'PayPal order creation could not be completed.',
            ];
        } catch (\Throwable $e) {
            Log::error('PayPal processing exception: ' . $e->getMessage());
            return [
                'success' => false,
                'gateway' => 'PayPal',
                'error' => 'Unable to contact PayPal payment server: ' . $e->getMessage(),
            ];
        }
    }
}
