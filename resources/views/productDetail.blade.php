@extends('theme.base')

@push('meta')
    <title>{{ $product->name }} details</title>
@endpush

@section('content')
    <div class="section section-hero section-shaped">
        <div class="shape shape-style-2 shape-dark">
            <span class="span-150"></span>
            <span class="span-50"></span>
            <span class="span-50"></span>
            <span class="span-75"></span>
            <span class="span-100"></span>
            <span class="span-75"></span>
            <span class="span-50"></span>
            <span class="span-100"></span>
            <span class="span-50"></span>
            <span class="span-100"></span>
        </div>
        <div class="page-header">
            <div class="container shape-container d-flex align-items-center py-lg">
                <div class="col px-0">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-lg-6 text-center">
                            <h1 class="text-white display-1">{{ $product->name }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="separator separator-bottom separator-skew zindex-100">
            <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <polygon class="fill-white" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
    </div>
    <div class="section features-6">
        <div class="container">
            <div class="row">
                @if(\Illuminate\Support\Facades\Request::has('success'))
                    <div class="col-12">
                        <div class="alert alert-success" role="alert">
                            Payment successfully captured.
                        </div>
                    </div>
                @endif
                <div class="col-12">
                    <h1>{{ $product->name }}</h1>
                    <hr>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 text-center">
                    <img class="img-fluid border p-2" src="https://picsum.photos/500" alt="{{ $product->name }}">
                    <strong>Price: ${{ $product->price }}</strong>
                    @if(auth()->check())
                    <form id="checkoutForm" method="POST" action="{{ route('processPayment') }}" class="card-form mt-3 mb-3">
                        <input type="hidden" name="product" value="{{ $product->id }}">
                        @csrf
                        <label for="card-holder-name">Card Holder Name</label>
                        <input class="form-control" id="card-holder-name" type="text" value="{{ auth()->user()->first_name.' '.auth()->user()->last_name }}">
                        <div class="form-group">
                            <label for="card-element">Credit or debit card</label>
                            <div id="card-element" class="form-control"></div>
                            <div id="card-errors" role="alert"></div>
                        </div>
                        <div class="stripe-errors"></div>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}<br>
                                @endforeach
                            </div>
                        @endif
                        <div class="form-group text-center">
                            <button type="button"  id="card-button" data-secret="{{ $intent->client_secret }}" class="btn btn-lg btn-primary btn-block">CHECKOUT</button>
                        </div>
                    </form>
                @else
                    <button type="button" class="btn btn-primary btn-block" onclick="showPopup()">
                        Purchase
                    </button>
                @endif
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <div class="info info-horizontal info-hover-primary">
                        <div class="description pl-4">
                            <h5 class="title">Description</h5>
                            <div id="descriptionBox">
                                {!! $product->description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')

@endpush


@push('script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function(){
            $('#descriptionBox').find('form').hide();
        });

        function showPopup(){
            Swal.fire({
                title: 'Login to purchase',
                text: "Please proceed to login",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Login'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('loginForm') }}'
                }
            })
        }
    </script>
    @if(auth()->check())
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            let stripe = Stripe("{{ config('stripe.STRIPE_PUBLIC_KEY') }}")
            let elements = stripe.elements()
            let style = {
                base: {
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            }
            let card = elements.create('card', {hidePostalCode: true, style: style});
            card.mount('#card-element')
            card.addEventListener('change', function(event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
            const cardHolderName = document.getElementById('card-holder-name');
            const cardButton = document.getElementById('card-button');
            const clientSecret = cardButton.dataset.secret;    cardButton.addEventListener('click', async (e) => {
                console.log("attempting");
                const { setupIntent, error } = await stripe.confirmCardSetup(
                    clientSecret, {
                        payment_method: {
                            card: card,
                            billing_details: { name: cardHolderName.value }
                        }
                    }
                );        if (error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = error.message;
                }
                else {
                    paymentMethodHandler(setupIntent.payment_method);
                }
            });
            function paymentMethodHandler(payment_method) {
                let form = document.getElementById('checkoutForm');
                let hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'payment_method');
                hiddenInput.setAttribute('value', payment_method);
                form.appendChild(hiddenInput);
                form.submit();
            }
        </script>
    @endif
@endpush
