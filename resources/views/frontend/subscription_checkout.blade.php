@extends('frontend.master')

@section('title', 'Activate ' . $planDetails['name'] . ' – Digital Academy')

@section('content')
<div class="container py-4 py-md-5">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('pricing.index') }}" class="btn-edu-secondary btn-sm px-3">
            <i class="fas fa-arrow-left me-1"></i> Back to Subscriptions
        </a>
    </div>

    <div class="row g-4 justify-content-center">

        <!-- Left Column: Subscription Summary -->
        <div class="col-lg-6 col-xl-5">
            <div class="edu-card-panel p-4 p-md-5 shadow-lg h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge {{ $tier === 'instructor' ? 'bg-success' : 'bg-primary' }} bg-opacity-20 {{ $tier === 'instructor' ? 'text-success' : 'text-primary' }} border {{ $tier === 'instructor' ? 'border-success' : 'border-primary' }} border-opacity-25 px-3 py-1 rounded-pill small fw-bold mb-3">
                        {{ $planDetails['badge'] }}
                    </span>

                    <h2 class="fw-bold mb-2">{{ $planDetails['name'] }}</h2>
                    <p class="text-muted small mb-4">
                        {{ $tier === 'instructor' ? 'Creator tier privileges with native broadcasting studio, live pop quiz engine, and 80% course sales payout.' : 'Full unlimited access to all engineering courses, live cohort sessions, interactive code scratchpad, and verified certificates.' }}
                    </p>

                    <div class="p-3 rounded mb-4" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Billing Term:</span>
                            <span class="fw-bold text-uppercase small">{{ $billing }} Billing</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Subscription Fee:</span>
                            <span class="fw-bold fs-5 text-primary" data-price-usd="{{ $price }}">${{ number_format($price, 2) }}</span>
                        </div>
                    </div>

                    <div class="small fw-bold mb-3 text-uppercase letter-spacing-1">Included Privileges:</div>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                        @foreach($planDetails['features'] as $feat)
                            <li class="d-flex align-items-start gap-2 small text-muted">
                                <i class="fas fa-check text-success mt-1"></i>
                                <span>{{ $feat }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="pt-4 border-top border-secondary border-opacity-15 mt-4 text-muted small">
                    <i class="fas fa-lock text-success me-1"></i> 256-bit SSL encrypted checkout. Cancel anytime from your account.
                </div>
            </div>
        </div>

        <!-- Right Column: Multi-Gateway Payment Form -->
        <div class="col-lg-6 col-xl-5">
            <div class="edu-card-panel p-4 p-md-5 shadow-lg">
                <h4 class="fw-bold mb-1">Confirm Subscription</h4>
                <p class="text-muted small mb-4">Choose your preferred payment method and activate membership instantly.</p>

                <!-- Gateway Tabs -->
                <ul class="nav nav-pills mb-4 gap-2" id="subPaymentTabs" role="tablist">
                    <li class="nav-item flex-fill">
                        <button class="btn btn-outline-secondary active w-100 text-center py-2 btn-sm" id="sub-card-tab" data-bs-toggle="pill" data-bs-target="#sub-tab-card" type="button">
                            <i class="far fa-credit-card me-1 text-primary"></i> Card (Stripe)
                        </button>
                    </li>
                    <li class="nav-item flex-fill">
                        <button class="btn btn-outline-secondary w-100 text-center py-2 btn-sm" id="sub-upi-tab" data-bs-toggle="pill" data-bs-target="#sub-tab-upi" type="button">
                            <i class="fas fa-qrcode me-1 text-success"></i> UPI / Razorpay
                        </button>
                    </li>
                    <li class="nav-item flex-fill">
                        <button class="btn btn-outline-secondary w-100 text-center py-2 btn-sm" id="sub-paypal-tab" data-bs-toggle="pill" data-bs-target="#sub-tab-paypal" type="button">
                            <i class="fab fa-paypal me-1 text-info"></i> PayPal
                        </button>
                    </li>
                </ul>

                <form action="{{ route('subscription.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tier" value="{{ $tier }}">
                    <input type="hidden" name="billing" value="{{ $billing }}">
                    <input type="hidden" name="payment_method" id="subPaymentMethod" value="card">

                    <div class="tab-content mb-4" id="subPaymentTabContent">
                        <!-- TAB 1: Card -->
                        <div class="tab-pane fade show active" id="sub-tab-card">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Cardholder Name:</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Card Number:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="•••• •••• •••• 4242" value="4242 •••• •••• 4242" required>
                                    <span class="input-group-text bg-secondary bg-opacity-25 border-0">
                                        <i class="fab fa-cc-visa text-primary me-1"></i>
                                        <i class="fab fa-cc-mastercard text-danger"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="text-muted small mb-1">Expires (MM/YY):</label>
                                    <input type="text" class="form-control" placeholder="12/28" value="12/28" required>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted small mb-1">CVC / CVV:</label>
                                    <input type="password" class="form-control" placeholder="•••" value="888" maxlength="4" required>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: UPI / Razorpay -->
                        <div class="tab-pane fade" id="sub-tab-upi">
                            <div class="p-3 rounded text-center mb-3" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                                <div class="text-success fs-1 mb-2"><i class="fas fa-qrcode"></i></div>
                                <h6 class="fw-bold mb-1">UPI Auto-Debit & Recurring Subscription</h6>
                                <p class="text-muted small mb-3">Scan with Google Pay, PhonePe, Paytm, or enter UPI VPA.</p>
                                <div class="d-inline-block p-2 bg-white rounded shadow-sm mb-3">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=upi://pay?pa=digitalacademy@upi&pn=DigitalAcademy&am={{ $price }}&cu=INR" alt="UPI QR" style="width: 140px; height: 140px;">
                                </div>
                                <div>
                                    <input type="text" class="form-control text-center mx-auto" style="max-width: 320px;" placeholder="username@okhdfcbank" value="student@okhdfcbank">
                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: PayPal -->
                        <div class="tab-pane fade" id="sub-tab-paypal">
                            <div class="p-4 rounded text-center" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                                <i class="fab fa-paypal text-primary display-4 mb-2"></i>
                                <h6 class="fw-bold mb-1">PayPal Recurring Subscription</h6>
                                <p class="text-muted small mb-0">Automatic renewal with cancel-anytime protection.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Amount Due -->
                    <div class="d-flex justify-content-between align-items-center p-3 rounded mb-4" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                        <div>
                            <div class="text-muted small">Total Due Today:</div>
                            <div class="fw-bold fs-4 text-primary" id="subTotalDue" data-price-usd="{{ $price }}">${{ number_format($price, 2) }}</div>
                        </div>
                        <div class="text-end text-muted small">
                            <div>Term: <strong class="text-uppercase">{{ $billing }}</strong></div>
                            <div class="text-success small"><i class="fas fa-shield-alt"></i> Risk-Free</div>
                        </div>
                    </div>

                    <button type="submit" class="btn-edu-primary w-100 justify-content-center py-3 fs-6">
                        <i class="fas fa-bolt me-2"></i> Activate {{ ucfirst($tier) }} Membership (<span id="subBtnAmount" data-price-usd="{{ $price }}">${{ number_format($price, 2) }}</span>)
                    </button>
                </form>

                <div class="text-center mt-3 text-muted small" style="font-size: 0.75rem;">
                    By activating, you agree to recurring renewal. Cancel anytime with 1-click in your account.
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

                <div class="text-center mt-3 text-muted small" style="font-size: 0.75rem;">
                    By activating, you agree to recurring renewal. Cancel anytime with 1-click in your account.
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    // Listen for currency change and update live payment labels
    window.addEventListener('daCurrencyChanged', function (e) {
        const curr = e.detail;
        const basePrice = {{ $price }};
        const converted = window.formatPlatformPrice(basePrice, curr.code);
        const btnAmt = document.getElementById('subBtnAmount');
        const totAmt = document.getElementById('subTotalDue');
        if (btnAmt) btnAmt.textContent = converted;
        if (totAmt) totAmt.textContent = converted;
    });

    document.getElementById('sub-card-tab').addEventListener('click', () => document.getElementById('subPaymentMethod').value = 'card');
    document.getElementById('sub-upi-tab').addEventListener('click', () => document.getElementById('subPaymentMethod').value = 'upi');
    document.getElementById('sub-paypal-tab').addEventListener('click', () => document.getElementById('subPaymentMethod').value = 'paypal');
</script>
@endsection
