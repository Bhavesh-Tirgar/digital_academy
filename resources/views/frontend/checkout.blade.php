@extends('frontend.master')

@section('title', 'Enroll in ' . $course->name . ' – Digital Academy')

@section('content')
<div class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">

            <div class="text-center mb-4">
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-3 py-1 rounded-pill small mb-2 fw-semibold">
                    <i class="fas fa-lock me-1"></i> Secure 256-Bit SSL Checkout
                </span>
                <h2 class="edu-section-title mb-1">Complete Course Enrollment</h2>
                <p class="edu-section-sub mb-0">Logged in as <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }}).</p>
            </div>

            <div class="edu-card-panel p-4 p-md-5 shadow-lg">
                <!-- Course Info Header -->
                <div class="row g-4 align-items-center mb-4 pb-4 border-bottom" style="border-color: var(--border-color) !important;">
                    <div class="col-sm-4">
                        <img src="{{ $course->thumbnail ?? 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800' }}" alt="{{ $course->name }}" class="img-fluid rounded border shadow-sm">
                    </div>
                    <div class="col-sm-8">
                        <span class="badge bg-primary bg-opacity-10 text-primary mb-2 small">{{ $course->level }}</span>
                        <h4 class="fw-bold mb-2 fs-5">{{ $course->name }}</h4>
                        <div class="text-muted small mb-1">
                            <i class="fas fa-chalkboard-teacher text-primary me-1"></i> Taught by <strong>{{ $course->instructor->name ?? 'Lead Mentor' }}</strong>
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-infinity text-success me-1"></i> Lifetime Access • Certificate of Completion Included
                        </div>
                        <div class="mt-2 p-2 rounded small text-primary" style="background: var(--primary-light); border: 1px solid var(--primary-border);">
                            <i class="fas fa-shield-alt text-primary me-1"></i> <strong>Anti-Fraud Guarantee:</strong> Your enrollment payment is held in platform escrow and only released once the instructor completes the syllabus.
                        </div>
                    </div>
                </div>

                <!-- Order Summary with Transparent Fee Split -->
                <h5 class="fw-bold mb-3"><i class="fas fa-receipt text-primary me-2"></i> Order Summary</h5>
                <div class="p-3 rounded mb-4" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                    @if($price > 0)
                        <div class="d-flex justify-content-between py-1 small">
                            <span class="text-muted">Course Retail Price</span>
                            <span class="fw-bold" data-price-usd="{{ $price }}">${{ number_format($price, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 small text-success">
                            <span><i class="fas fa-user-check me-1"></i> Instructor Earnings (80% net share)</span>
                            <span class="fw-bold" data-price-usd="{{ $instructorShare }}">${{ number_format($instructorShare, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 small text-muted">
                            <span><i class="fas fa-server me-1"></i> Platform Service Fee (20% CDN streaming)</span>
                            <span data-price-usd="{{ $platformFee }}">${{ number_format($platformFee, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between pt-2 mt-2 border-top fw-bold fs-5" style="border-color: var(--border-color) !important;">
                            <span>Total Due Today:</span>
                            <span class="text-primary" id="checkoutTotalAmount" data-price-usd="{{ $price }}">${{ number_format($price, 2) }}</span>
                        </div>
                    @else
                        <div class="d-flex justify-content-between py-1 small">
                            <span class="text-muted">Course Price</span>
                            <span class="badge bg-success bg-opacity-20 text-success fw-bold px-3 py-1">100% Free Enrollment</span>
                        </div>
                        <div class="d-flex justify-content-between pt-2 mt-2 border-top fw-bold fs-5" style="border-color: var(--border-color) !important;">
                            <span>Total Due Today:</span>
                            <span class="text-success">$0.00 (Free)</span>
                        </div>
                    @endif
                </div>

                <!-- Process Enrollment Form -->
                <form action="{{ route('courses.enroll', $course->id) }}" method="POST" id="enrollmentForm">
                    @csrf
                    <input type="hidden" name="plan" value="{{ $plan }}">
                    <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="{{ $price > 0 ? 'card' : 'free' }}">
                    <input type="hidden" name="currency" id="selectedCurrency" value="USD">
                    <input type="hidden" name="razorpay_payment_id" id="razorpayPaymentId" value="">

                    @if($price > 0)
                        <!-- Multi-Payment Gateway Options -->
                        <h5 class="fw-bold mb-3"><i class="fas fa-credit-card text-primary me-2"></i> Select Payment Method</h5>

                        <!-- Gateway Tabs -->
                        <ul class="nav nav-pills mb-4 gap-2" id="paymentMethodTabs" role="tablist">
                            <li class="nav-item flex-fill">
                                <button class="btn btn-edu-secondary active w-100 text-center py-2" id="card-tab" data-bs-toggle="pill" data-bs-target="#tab-card" type="button">
                                    <i class="far fa-credit-card me-1 text-primary"></i> Credit / Debit Card (Stripe)
                                </button>
                            </li>
                            <li class="nav-item flex-fill">
                                <button class="btn btn-edu-secondary w-100 text-center py-2" id="upi-tab" data-bs-toggle="pill" data-bs-target="#tab-upi" type="button">
                                    <i class="fas fa-qrcode me-1 text-success"></i> UPI / Razorpay
                                </button>
                            </li>
                            <li class="nav-item flex-fill">
                                <button class="btn btn-edu-secondary w-100 text-center py-2" id="paypal-tab" data-bs-toggle="pill" data-bs-target="#tab-paypal" type="button">
                                    <i class="fab fa-paypal me-1 text-info"></i> PayPal
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content mb-4" id="paymentTabContent">
                            <!-- TAB 1: Stripe Card -->
                            <div class="tab-pane fade show active" id="tab-card">
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Cardholder Name:</label>
                                    <input type="text" name="card_name" class="form-control" value="{{ Auth::user()->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Card Number:</label>
                                    <div class="input-group">
                                        <input type="text" name="card_number" class="form-control" placeholder="•••• •••• •••• ••••" maxlength="19" autocomplete="cc-number">
                                        <span class="input-group-text bg-secondary bg-opacity-25 border-0">
                                            <i class="fab fa-cc-visa text-primary me-1"></i>
                                            <i class="fab fa-cc-mastercard text-danger"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="text-muted small mb-1">Expires (MM/YY):</label>
                                        <input type="text" name="card_expiry" class="form-control" placeholder="MM/YY" maxlength="5" autocomplete="cc-exp">
                                    </div>
                                    <div class="col-6">
                                        <label class="text-muted small mb-1">CVC / CVV:</label>
                                        <input type="password" name="card_cvc" class="form-control" placeholder="CVC" maxlength="4" autocomplete="cc-csc">
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 2: Razorpay (UPI, GPay, Cards, NetBanking) -->
                            <div class="tab-pane fade" id="tab-upi">
                                <div class="p-4 rounded text-center mb-3" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                                    <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: var(--primary-light); color: var(--primary);">
                                        <i class="fas fa-bolt fa-2x"></i>
                                    </div>
                                    <h5 class="fw-bold mb-1">Razorpay Smart Gateway</h5>
                                    <p class="text-muted small mb-3">Instant checkout via UPI (Google Pay, PhonePe, Paytm), Debit/Credit Cards, NetBanking, and Wallets.</p>

                                    <button type="button" class="btn btn-edu-primary w-100 py-2.5 fs-6 rounded-pill mb-3" onclick="launchRazorpayCheckout()">
                                        <i class="fas fa-lock me-2"></i> Pay with Razorpay (<span class="razorpayPriceLabel">${{ number_format($price, 2) }}</span>)
                                    </button>

                                    <div class="d-flex align-items-center justify-content-center gap-2 text-muted small">
                                        <span><i class="fas fa-shield-alt text-success me-1"></i> RBI Compliant</span>
                                        <span>•</span>
                                        <span><i class="fas fa-bolt text-warning me-1"></i> Instant Course Activation</span>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 3: PayPal -->
                            <div class="tab-pane fade" id="tab-paypal">
                                <div class="p-4 rounded text-center" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                                    <i class="fab fa-paypal text-primary display-4 mb-2"></i>
                                    <h6 class="fw-bold mb-1">Pay with PayPal Account or Balance</h6>
                                    <p class="text-muted small mb-3">You will be securely routed to PayPal to confirm the transaction.</p>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">Buyer Protection Guarantee Active</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Free Enrollment Notice -->
                        <div class="p-4 rounded text-center mb-4" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                            <div class="text-success fs-1 mb-2"><i class="fas fa-check-circle"></i></div>
                            <h5 class="fw-bold mb-1">Complimentary Course Access</h5>
                            <p class="text-muted small mb-0">This course is open at zero cost. Complete your enrollment with one click below to begin learning immediately.</p>
                        </div>
                    @endif

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="termsCheck" checked required>
                        <label class="form-check-label text-muted small" for="termsCheck">
                            I agree to the Digital Academy Student Terms of Service and understand I receive lifetime access, live class schedule access, and downloadable study materials.
                        </label>
                    </div>

                    <div class="d-flex flex-wrap gap-3">
                        <button type="submit" class="btn btn-edu-primary flex-grow-1 justify-content-center py-2.5 fs-6 rounded-pill">
                            @if($price > 0)
                                <i class="fas fa-shield-alt me-2"></i> Pay & Confirm Enrollment (<span id="payBtnAmount" data-price-usd="{{ $price }}">${{ number_format($price, 2) }}</span>)
                            @else
                                <i class="fas fa-graduation-cap me-2"></i> Claim Free Enrollment
                            @endif
                        </button>
                        <a href="{{ route('courses.detail', $course->id) }}" class="btn btn-edu-secondary py-2.5 px-4 rounded-pill">
                            Cancel
                        </a>
                    </div>
                </form>

                <div class="text-center mt-4 text-muted small">
                    <i class="fas fa-lock text-success me-1"></i> 256-bit SSL encrypted checkout • 30-day money-back guarantee.
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
@if($price > 0)
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    // Listen for currency change and update live payment labels
    window.addEventListener('daCurrencyChanged', function (e) {
        const curr = e.detail;
        const basePrice = {{ $price }};
        const converted = window.formatPlatformPrice(basePrice, curr.code);
        const btnAmt = document.getElementById('payBtnAmount');
        const totAmt = document.getElementById('checkoutTotalAmount');
        const rzpLbl = document.querySelector('.razorpayPriceLabel');
        const currInput = document.getElementById('selectedCurrency');
        if (btnAmt) btnAmt.textContent = converted;
        if (totAmt) totAmt.textContent = converted;
        if (rzpLbl) rzpLbl.textContent = converted;
        if (currInput) currInput.value = curr.code;
    });

    // Update hidden payment method & button UI on tab switch
    const cardTab = document.getElementById('card-tab');
    const upiTab = document.getElementById('upi-tab');
    const paypalTab = document.getElementById('paypal-tab');
    const submitBtn = document.querySelector('#enrollmentForm button[type="submit"]');

    function updateSubmitBtnText(icon, label) {
        if (!submitBtn) return;
        const amtSpan = document.getElementById('payBtnAmount');
        const currentAmt = amtSpan ? amtSpan.textContent : '${{ number_format($price, 2) }}';
        submitBtn.innerHTML = `<i class="${icon} me-2"></i> ${label} (<span id="payBtnAmount" data-price-usd="{{ $price }}">${currentAmt}</span>)`;
    }

    if (cardTab) {
        cardTab.addEventListener('click', function() {
            document.getElementById('selectedPaymentMethod').value = 'card';
            updateSubmitBtnText('fas fa-shield-alt', 'Pay with Card');
        });
    }
    if (upiTab) {
        upiTab.addEventListener('click', function() {
            document.getElementById('selectedPaymentMethod').value = 'razorpay';
            updateSubmitBtnText('fas fa-bolt', 'Pay with Razorpay / UPI');
        });
    }
    if (paypalTab) {
        paypalTab.addEventListener('click', function() {
            document.getElementById('selectedPaymentMethod').value = 'paypal';
            updateSubmitBtnText('fab fa-paypal', 'Continue with PayPal');
        });
    }

    // Intercept form submit if Razorpay is selected
    const enrollmentForm = document.getElementById('enrollmentForm');
    if (enrollmentForm) {
        enrollmentForm.addEventListener('submit', function (e) {
            const method = document.getElementById('selectedPaymentMethod').value;
            const rzpId = document.getElementById('razorpayPaymentId').value;
            if (method === 'razorpay' && !rzpId) {
                e.preventDefault();
                launchRazorpayCheckout();
            }
        });
    }

    // Launch Razorpay Official Checkout Modal
    function launchRazorpayCheckout() {
        document.getElementById('selectedPaymentMethod').value = 'razorpay';

        const rzpKey = "{{ config('services.razorpay.key') ?: env('RAZORPAY_KEY') }}";
        if (!rzpKey) {
            alert("Razorpay is not configured on this server yet. Please select Credit / Debit Card or PayPal, or configure RAZORPAY_KEY in .env.");
            return;
        }

        const courseTitle = "{{ addslashes($course->name) }}";
        const studentName = "{{ Auth::user()->name }}";
        const studentEmail = "{{ Auth::user()->email }}";
        const basePrice = {{ $price }};
        const activeCurrency = document.getElementById('selectedCurrency') ? document.getElementById('selectedCurrency').value : 'USD';

        if (typeof Razorpay !== 'undefined') {
            const options = {
                "key": rzpKey,
                "amount": Math.round(basePrice * 100),
                "currency": activeCurrency || "USD",
                "name": "Digital Academy",
                "description": "Enrollment: " + courseTitle,
                "image": "https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=120",
                "handler": function (response) {
                    if (response && response.razorpay_payment_id) {
                        document.getElementById('razorpayPaymentId').value = response.razorpay_payment_id;
                        document.getElementById('enrollmentForm').submit();
                    } else {
                        alert("Payment verification failed. Please try again.");
                    }
                },
                "prefill": {
                    "name": studentName,
                    "email": studentEmail
                },
                "theme": {
                    "color": "#4f46e5"
                },
                "modal": {
                    "ondismiss": function() {
                        console.log('Razorpay checkout closed by user');
                    }
                }
            };

            try {
                const rzp = new Razorpay(options);
                rzp.on('payment.failed', function (response){
                    alert("Razorpay Payment Notice: " + (response.error ? response.error.description : 'Transaction failed'));
                });
                rzp.open();
            } catch (err) {
                alert("Could not initialize Razorpay checkout. Please check gateway credentials.");
            }
        } else {
            alert("Razorpay checkout SDK is not currently available. Please select another payment method.");
        }
    }
</script>
@endif
@endsection
