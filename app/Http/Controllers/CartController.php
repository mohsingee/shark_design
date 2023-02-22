<?php
namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Cart;
use Illuminate\Support\Str;
use Helper;

/*
 * Create cart
 * to add product into cart
 * if product has calculator then it will add custom width, length and calculated price in cart...
 * */
class CartController extends Controller
{
    protected $product=null;
    public function __construct(Product $product){
        $this->product=$product;
    }

    public function addToCart(Request $request){
        // dd($request->all());
        if (empty($request->slug)) {
            request()->session()->flash('error','Invalid Products');
            return back();
        }
        $product = Product::where('slug', $request->slug)->first();
        // return $product;
        if (empty($product)) {
            request()->session()->flash('error','Invalid Products');
            return back();
        }

        $already_cart = Cart::where('user_id', auth()->user()->id)->where('order_id',null)->where('product_id', $product->id)->first();
        // return $already_cart;
        if($already_cart) {
            // dd($already_cart);
            $already_cart->quantity = $already_cart->quantity + 1;
            $already_cart->amount = $product->price+ $already_cart->amount;
            // return $already_cart->quantity;
            if ($product->quantity_in_stock < $already_cart->quantity || $product->quantity_in_stock <= 0) return back()->with('error','Stock not sufficient!.');
            $already_cart->save();

        }else{
            $cart = new Cart;
            $cart->user_id = auth()->user()->id;
            $cart->product_id = $product->id;
            $cart->price = ($product->price - ($product->price * $product->discount) / 100);
            if ($request->buy_qty) {
                $cart->quantity = $request->buy_qty;
            } else {
                $cart->quantity = 1;
            }
//dd($cart);
//dd($product);
            //$cart->quantity = $request->buy_qty;
            $cart->amount=$cart->price*$cart->quantity;
            if ($product->quantity_in_stock < $cart->quantity || $product->quantity_in_stock <= 0) {
                return back()->with('error','Stock not sufficient!.');
            }
            $cart_id = $cart->save();
            //var_dump($cart_id);die;
            $wishlist = Wishlist::where('user_id',auth()->user()->id)->where('cart_id',null)->update(['cart_id'=>$cart->id]);
        }
        request()->session()->flash('success','Product successfully added to cart');
        return back();
    }

    public function singleAddToCart(Request $request){

        // dd($request->quant[1]);
        $product = Product::where('slug', $request->slug)->first();
        //dd($product);
        if ($request->width == 0) {
            if($product->quantity_in_stock < $request->buy_qty){
                return back()->with('error','Out of stock, You can add other products.');
                //return back()->with('error',$product->reached_limit_error);
            }
            if ( ($request->buy_qty < 1) || empty($product) ) {
                request()->session()->flash('error','Invalid Products');
                return back();
            }
        } else {
            //meter add to cart
            $room_area = $request->buy_qty;
            $meter_total_in_stock = $product->quantity_in_stock * $product->meter_per_box;
            if ($request->order_unit == 'cm') {
                $room_area = ($request->length * $request->width)/100;//room area
            } elseif($request->order_unit == 'meter') {
                $room_area = $request->length * $request->width;//room area
            }
            if ($meter_total_in_stock < $room_area) {
                request()->session()->flash('error','Stock not sufficient!');
                return back();
            }

        }

        $already_cart = Cart::where('user_id', auth()->user()->id)->where('order_id',null)->where('product_id', $product->id)->first();

        // return $already_cart;
        if($already_cart) {
            if ($product->calculator_show == 2) {
                $already_cart->quantity = $already_cart->quantity + $request->buy_qty;
                // $already_cart->price = ($product->price * $request->buy_qty) + $already_cart->price ;
                $already_cart->amount = ($product->price * $request->buy_qty)+ $already_cart->amount;

                if ($already_cart->product->quantity_in_stock < $already_cart->quantity || $already_cart->product->quantity_in_stock <= 0) return back()->with('error',$product->reached_limit_error);

                $already_cart->save();
            } else {
                $already_cart->width = $request->width;
                $already_cart->length = $request->length;
                $already_cart->amount = $request->amount;
                $already_cart->unit = $request->unit;

                $already_cart->save();
            }
        }
        else{
            if ($request->width == 0) {
                $cart = new Cart;
                $cart->user_id = auth()->user()->id;
                $cart->product_id = $product->id;
                $cart->price = ($product->price - ($product->price*$product->discount)/100);
                $cart->quantity = $request->buy_qty;
                $cart->amount = ($product->price * $request->buy_qty);
                if ($product->quantity_in_stock < $cart->quantity || $product->quantity_in_stock <= 0) {
                    return back()->with('error','Stock not sufficient!');
                }
                // return $cart;
                $cart->save();
            } else {
                $cart = new Cart;
//                dd($cart);
//                dd($product);
                $cart->user_id = auth()->user()->id;
                $cart->product_id = $product->id;
                $cart->price = ($product->price - ($product->price * $product->discount)/100);
                if ($request->order_unit == 'cm') {
                    $room_area = ($request->length * $request->width)/100;//room area
                } elseif($request->order_unit == 'meter') {
                    $room_area = $request->length * $request->width;//room area
                }
                $cart->quantity = $request->buy_qty;
                $cart->amount = $request->amount;
                $cart->width  = $request->width;
                $cart->length = $request->length;
                $cart->unit   = $request->unit;

                if ($product->quantity_in_stock < $cart->quantity || $product->quantity_in_stock <= 0) {
                    return back()->with('error','Stock not sufficient!');
                }

                $cart->save();
            }
        }
        //dd('here');
        request()->session()->flash('success','Product successfully added to cart.');
        return back();
    }

    public function cartDelete(Request $request){
        $cart = Cart::find($request->id);
        if ($cart) {
            $cart->delete();
            request()->session()->flash('success','Cart successfully removed');
            return back();
        }
        request()->session()->flash('error','Error please try again');
        return back();
    }

    public function cartUpdate(Request $request){
        // dd($request->all());
        if($request->quant){
            $error = array();
            $success = '';
            // return $request->quant;
            foreach ($request->quant as $k=>$quant) {
                // return $k;
                $id = $request->qty_id[$k];
                // return $id;
                $cart = Cart::find($id);
                // return $cart;
                if($quant > 0 && $cart) {
                    // return $quant;

                    if($cart->product->stock < $quant){
                        request()->session()->flash('error','Out of stock');
                        return back();
                    }
                    $cart->quantity = ($cart->product->stock > $quant) ? $quant  : $cart->product->stock;
                    // return $cart;

                    if ($cart->product->stock <=0) continue;
                    $after_price=($cart->product->price-($cart->product->price*$cart->product->discount)/100);
                    $cart->amount = $after_price * $quant;
                    // return $cart->price;
                    $cart->save();
                    $success = 'Cart successfully updated!';
                }else{
                    $error[] = 'Cart Invalid!';
                }
            }
            return back()->with($error)->with('success', $success);
        }else{
            return back()->with('Cart Invalid!');
        }
    }

    // public function addToCart(Request $request){
    //     // return $request->all();
    //     if(Auth::check()){
    //         $qty=$request->quantity;
    //         $this->product=$this->product->find($request->pro_id);
    //         if($this->product->stock < $qty){
    //             return response(['status'=>false,'msg'=>'Out of stock','data'=>null]);
    //         }
    //         if(!$this->product){
    //             return response(['status'=>false,'msg'=>'Product not found','data'=>null]);
    //         }
    //         // $session_id=session('cart')['session_id'];
    //         // if(empty($session_id)){
    //         //     $session_id=Str::random(30);
    //         //     // dd($session_id);
    //         //     session()->put('session_id',$session_id);
    //         // }
    //         $current_item=array(
    //             'user_id'=>auth()->user()->id,
    //             'id'=>$this->product->id,
    //             // 'session_id'=>$session_id,
    //             'title'=>$this->product->title,
    //             'summary'=>$this->product->summary,
    //             'link'=>route('product-detail',$this->product->slug),
    //             'price'=>$this->product->price,
    //             'photo'=>$this->product->photo,
    //         );

    //         $price=$this->product->price;
    //         if($this->product->discount){
    //             $price=($price-($price*$this->product->discount)/100);
    //         }
    //         $current_item['price']=$price;

    //         $cart=session('cart') ? session('cart') : null;

    //         if($cart){
    //             // if anyone alreay order products
    //             $index=null;
    //             foreach($cart as $key=>$value){
    //                 if($value['id']==$this->product->id){
    //                     $index=$key;
    //                 break;
    //                 }
    //             }
    //             if($index!==null){
    //                 $cart[$index]['quantity']=$qty;
    //                 $cart[$index]['amount']=ceil($qty*$price);
    //                 if($cart[$index]['quantity']<=0){
    //                     unset($cart[$index]);
    //                 }
    //             }
    //             else{
    //                 $current_item['quantity']=$qty;
    //                 $current_item['amount']=ceil($qty*$price);
    //                 $cart[]=$current_item;
    //             }
    //         }
    //         else{
    //             $current_item['quantity']=$qty;
    //             $current_item['amount']=ceil($qty*$price);
    //             $cart[]=$current_item;
    //         }

    //         session()->put('cart',$cart);
    //         return response(['status'=>true,'msg'=>'Cart successfully updated','data'=>$cart]);
    //     }
    //     else{
    //         return response(['status'=>false,'msg'=>'You need to login first','data'=>null]);
    //     }
    // }

    // public function removeCart(Request $request){
    //     $index=$request->index;
    //     // return $index;
    //     $cart=session('cart');
    //     unset($cart[$index]);
    //     session()->put('cart',$cart);
    //     return redirect()->back()->with('success','Successfully remove item');
    // }

    public function checkout(Request $request){
        // $cart=session('cart');
        // $cart_index=\Str::random(10);
        // $sub_total=0;
        // foreach($cart as $cart_item){
        //     $sub_total+=$cart_item['amount'];
        //     $data=array(
        //         'cart_id'=>$cart_index,
        //         'user_id'=>$request->user()->id,
        //         'product_id'=>$cart_item['id'],
        //         'quantity'=>$cart_item['quantity'],
        //         'amount'=>$cart_item['amount'],
        //         'status'=>'new',
        //         'price'=>$cart_item['price'],
        //     );

        //     $cart=new Cart();
        //     $cart->fill($data);
        //     $cart->save();
        // }
        return view('frontend.pages.checkout');
    }
}
