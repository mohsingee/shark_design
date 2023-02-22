<?php

namespace App\Http\Controllers;

use App\Models\SuggestedProducts;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

use Illuminate\Support\Str;
/*
 * Admin create product, edit, inactive, active
 * */
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=Product::getAllProduct();
        // return $products;
        return view('backend.product.index')->with('products',$products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brand=Brand::get();
        //$category = Category::where('is_parent',1)->get();
        $category = Category::where('parent_id',Null)->get();
        $products = Product::get();
        // return $category;
        return view('backend.product.create')->with('categories',$category)->with('brands',$brand)->with('products',$products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        // $data=$request->all();
        // echo $data;

        $validation = $this->validate($request,[
            'title'=>'string|required',
            'summary'=>'string|nullable',
            'quantity_in_stock'=>'required|numeric',
            'photo'=>'image|nullable',
            'status'=>'required|in:active,inactive',
            'cat_id'=>'nullable|exists:categories,id',
            'width'=>'nullable|numeric',
            'length'=>'nullable|numeric',
            'meter_per_box'=>'nullable|numeric',
        ]);

        error_log('HERE WE GO WITH ERROR');
        $data= $request->except(['photo']);
        if ($request->photo) {
            $img_name = strtotime(now());
            $data['photo'] = 'uploads/' .$img_name . '.png';
            $img_uploaded = move_uploaded_file($_FILES["photo"]['tmp_name'], 'uploads/' . $img_name . '.png');
        }

        $slug=Str::slug($request->title);
        $count=Product::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
        $data['slug']=$slug;
        $data['is_featured']=$request->input('is_featured',0);
        $suggested_prod_id=$request->input('suggested_prod_id');
        //unset($data['suggested_prod_id']);

        $data['suggested_prod_id'] = '';
        if($suggested_prod_id){
            $data['suggested_prod_id']=implode(',',$suggested_prod_id);
        }
        // return $size;
        // return $data;

        $status=Product::create($data);
        // echo $status;
        if($status){
            request()->session()->flash('success','Product Successfully added');

        }
        else{
            error_log('HERE WE GO WITH ERROR');
            request()->session()->flash('error','Please try again!!');
        }
        return redirect()->back()->with('success', 'Saved!');
        // return redirect()->route('product.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brand=Brand::get();
        $product=Product::findOrFail($id);
        //$category=Category::where('is_parent',1)->get();
        $category=Category::where('parent_id',Null)->get();
        $products = Product::where('id','!=',$id)->get();
        $items=Product::where('id',$id)->get();
        // return $items;
        return view('backend.product.edit')->with('product',$product)
                    ->with('brands',$brand)
                    ->with('categories',$category)->with('items',$items)->with('products',$products);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product=Product::findOrFail($id);
        $this->validate($request,[
            'title'=>'string|required',
            'summary'=>'string|nullable',
            'quantity_in_stock'=>'required|numeric',
            'photo'=>'image|nullable',
            'status'=>'required|in:active,inactive',
            'cat_id'=>'nullable|exists:categories,id',
            'width'=>'nullable|numeric',
            'length'=>'nullable|numeric',
            'meter_per_box'=>'nullable|numeric',
        ]);
        $data= $request->except(['photo']);
        if ($request->photo) {
            $img_name = strtotime(now());
            $data['photo'] = 'uploads/' .$img_name . '.png';
            $img_uploaded = move_uploaded_file($_FILES["photo"]['tmp_name'], 'uploads/' . $img_name . '.png');
        }
        //var_dump($data);die;
        $data['is_featured']=$request->input('is_featured',0);

        $suggested_prod_id = $request->input('suggested_prod_id');
        unset($data['suggested_prod_id']);
        $data['suggested_prod_id'] = '';
        //var_dump($suggested_prod_id);die;
        if(is_array($suggested_prod_id) && count($suggested_prod_id)>0){
            $data['suggested_prod_id'] = implode(',',$suggested_prod_id);
        }
        //dd($data);
        $status=$product->fill($data)->save();
        if($status){
            request()->session()->flash('success','Product Successfully updated');
        }
        else{
            request()->session()->flash('error','Please try again!!');
        }
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product=Product::findOrFail($id);
        $product->status = 'inactive';
        $status=$product->save();

        if($status){
            request()->session()->flash('success','Product successfully inactive');
        }
        else{
            request()->session()->flash('error','Error while deleting product');
        }
        return redirect()->route('product.index');
    }
}
