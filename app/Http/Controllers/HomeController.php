<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $products = Products::paginate(10);
        return view('index',compact('products'));
    }


    public function productDetail($productId = null): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        if(null === $productId){
            abort(404);
        }
        $product = Products::find($productId);
        if(null === $product){
            abort(404);
        }
        $intent = null;
        if(auth()->check()){
            $intent = auth()->user()->createSetupIntent();
        }

        return view('productDetail',compact('product','intent'));
    }


    public function processPayment(Request $request): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        try
        {
            $user = Auth::user();
            $paymentMethod = $request->get('payment_method');
            $product = Products::find(trim($request->get('product')));
            $price = $product->price * 100;
            $user->createOrGetStripeCustomer();
            $user->addPaymentMethod($paymentMethod);
            $user->charge($price, $paymentMethod);
        }
        catch (\Exception $exception)
        {
            return back()->withErrors(['message' => 'Error creating subscription. ' . $exception->getMessage()]);
        }
        return redirect()->route('productDetail',['productId' => $product->id,'success' => 'true']);
    }
}
