@extends('theme.base')

@push('meta')
    <title>Product List</title>
@endpush

@section('content')
    <div class="section section-hero section-shaped">
        <div class="shape shape-style-3 shape-default">
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
                            <h1 class="text-white display-1">Product List</h1>
                            <h2 class="display-4 font-weight-normal text-white">Find the variety of products</h2>
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
            <div class="row align-items-center">
                <div class="col-12">
                    <h1>Products</h1>
                </div>
                @forelse($products as $k => $product)
                    <div class="col-sm-12 col-md-12 col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    <img class="img-fluid" src="https://picsum.photos/300?random={{$k}}">
                                </div>
                                <div class="mt-3 text-center">
                                    <strong>{{ $product->name }}</strong>
                                    <a href="{{ route('productDetail',['productId' => $product->id]) }}" class="btn btn-primary btn-block">$ {{ $product->price }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <strong>No Products</strong>
                    </div>
                @endforelse
                <div class="col-12">
                    {!! $products->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
