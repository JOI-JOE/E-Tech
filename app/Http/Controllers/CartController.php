<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller{
    public function addToCart(Request $request)
    {
        $product = Product::with('product_images')->find($request->id);

        if($product == null){
            return response()->json([
               'status' => false,
               'message'  => 'Record not found'
            ]);
        }
        if(Cart::count() > 0){
//            ec/ho "Product already in cart";
            // Products found in cart
            // check if this product already in the cart
            // return as message that product already added in your cart
            // if product not found in the cart, then add product in cart
            $cartContent = Cart::content();
            $productAlreadyExist = false;

            foreach($cartContent as $item){
                if($item->id == $product->id){
                    $productAlreadyExist = true;
                }
            }

            if($productAlreadyExist == false){
                Cart::add($product->id,$product->title,1,$product->price,['productImage' => (!empty($product->product_images) ? $product->product_images->first() : '')]);

                $status = true;
                $message = '<strong>' . $product->title. '</strong>'. ' added in cart successfully.';
                session()->flash('success',$message);
            }else{
                $status = false;
                $message = $product->title. ' already added in cart';
            }
        }else{
            echo "Cart is empty now adding a product in cart";
            Cart::add($product->id,$product->title,1,$product->price,['productImage' => (!empty($product->product_images) ? $product->product_images->first() : '')]);
            $status = true;
            $message = '<strong>' . $product->title. '</strong>'. ' added in cart';
            session()->flash('success',$message);
        }
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function cart()
    {
        $cartContent = Cart::content();
        $data['cartContent'] = $cartContent;
        return view('front.cart',$data);
    }

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;


        $itemInfo = Cart::get($rowId);

        $product = Product::find($itemInfo->id);
        // check qty available in stock
        // The product which have track qty checked in admin will be tracked for stock level
        if($product->track_qty == "Yes"){
            if( $qty <= $product->qty){
                Cart::update($rowId,$qty);
                $message = 'Cart updated successfully';
                $status = true;
                session()->flash('success',$message);

            }else{
                $message = 'Request qty('.$qty.') not available in stock. ';
                $status = false;
                session()->flash('error',$message);
            }
        }else{
            Cart::update($rowId,$qty);
            $message = 'Cart updated successfully';
            $status = true;
            session()->flash('success',$message);
        }


        return response()->json([
           'status' => $status,
           'message' => $message
        ]);
    }

    public function deleteItem(Request $request)
    {
        $itemInfo = Cart::get($request->rowId);
//        Cart::remove($request->rowId);
        if($itemInfo == null){
            $errorMessage = 'Item not found in cart';
            session()->flash('error',$errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }

        Cart::remove($request->rowId);

        $message = 'Item removed from cart successfully.';
        session()->flash('error',$message);
        return response()->json([
           'status' => true,
           'message' => $message
        ]);
    }

    public function checkout()
    {
        // --if cart is empty redirect to cart page
        if(Cart::count() == 0){
            return redirect()->route('front.cart');
        }

        // -- if user is not logged in them redirect to login page
        if(Auth::check() == false){

            if(!session()->has('url.intended')){
                session(['url.intended' => url()->current()]);
            }
            return redirect()->route('account.login');
        }

        session()->forget('url.intended');

        $countries = Country::orderBy('name')->get();

        return view('front.checkout',[
            'countries' => $countries
        ]);
    }

    public function processCheckout(Request $request)
    {
        // Step -1 apply Validator
        $validator = Validator::make($request->all(),[
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required|min:30',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
               'message' => 'Please fix the errors',
               'status' => false,
               'errors' => $validator->errors()
            ]);
        }

        // Step 2 - save user address
        $customerAddress = CustomerAdre
    }
}
