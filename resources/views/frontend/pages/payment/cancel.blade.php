@extends('frontend.layout.app')

@section('page-title')
    Checkout Cancel
@endsection

@section('page-css')

@endsection


@section('body-content')

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5 text-center">
                        
                        <!-- Icon -->
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 rounded-circle" style="width: 80px; height: 80px;">
                                <i class="bi bi-x text-danger" style="font-size: 4rem;"></i>
                            </div>
                        </div>

                        <!-- Title & Message -->
                        <h4 class="card-title mb-2">Payment Cancelled</h4>
                        <p class="card-text text-muted mb-4">
                            The payment process was interrupted. No money was deducted.
                        </p>

                        <!-- Buttons -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i> Return to Checkout
                            </a>
                            <a href="{{ route('shop') }}" class="btn btn-light">
                                Continue Shopping
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


@endsection
