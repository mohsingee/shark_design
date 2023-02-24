<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\PostTag;
use App\Models\PostCategory;
use App\Models\Post;
use App\Models\Cart;
use App\Models\Brand;
use App\User;
use Auth;
use Illuminate\Support\Facades\Mail;
use Session;
use Newsletter;
use DB;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
/*
 * showing all pages of frontend side
 * user can login, signup, logout
 * calculators added last of the class
 * home function shows home page
 * about us function shows about us page
 * contact function shows contact us page
 * product detail function show detail of product behalf of product slug
 * productGrids function show all products of category in Grid mode
 * productLists function show all products of category in List mode
 * productFilter function show products of category on filtration
 * productSearch function show products on name filtration
 * productBrand function show products on Brand
 * productCat function show products on category slug
 * productSubCat function show products on subcategory slug
 * productSubSubCat function show products on subcategory child slug
 * */

class FrontendController extends Controller
{

    public function index(Request $request)
    {
        return redirect()->route($request->user()->role);
    }

    public function home()
    {

        $featured = Product::where('status', 'active')->where('is_featured', 1)->orderBy('price', 'DESC')->limit(2)->get();
        $posts = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $banners = Banner::where('status', 'active')->limit(3)->orderBy('id', 'DESC')->get();
        // return $banner;
        $products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(8)->get();
        $category = Category::where('status', 'active')->where('is_parent', 1)->orderBy('title', 'ASC')->get();
        // return $category;
        return view('frontend.index')
            ->with('featured', $featured)
            ->with('posts', $posts)
            ->with('banners', $banners)
            ->with('product_lists', $products)
            ->with('category_lists', $category);
    }
    public function aboutUs()
    {
        return view('frontend.pages.about-us');
    }
    public function contact()
    {
        return view('frontend.pages.contact');
    }
    public function productDetail($slug)
    {
        $product_detail = Product::getProductBySlug($slug);
        $product_suggested = explode(',', $product_detail->suggested_prod_id);
        $suggested_product = Product::whereIn('id', $product_suggested)->get();
        //dd($product_detail->calculator_show);
        return view('frontend.pages.product_detail')->with('product_detail', $product_detail)->with('suggested_product', $suggested_product);
    }
    public function productGrids()
    {
        $products = Product::query();

        if (!empty($_GET['category'])) {
            $slug = explode(',', $_GET['category']);
            // dd($slug);
            $cat_ids = Category::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            // dd($cat_ids);
            $products->whereIn('cat_id', $cat_ids);
            // return $products;
        }
        if (!empty($_GET['brand'])) {
            $slugs = explode(',', $_GET['brand']);
            $brand_ids = Brand::select('id')->whereIn('slug', $slugs)->pluck('id')->toArray();
            return $brand_ids;
            $products->whereIn('brand_id', $brand_ids);
        }
        if (!empty($_GET['sortBy'])) {
            if ($_GET['sortBy'] == 'title') {
                $products = $products->where('status', 'active')->orderBy('title', 'ASC');
            }
            if ($_GET['sortBy'] == 'price') {
                $products = $products->orderBy('price', 'ASC');
            }
        }

        if (!empty($_GET['price'])) {
            $price = explode('-', $_GET['price']);
            // return $price;
            // if(isset($price[0]) && is_numeric($price[0])) $price[0]=floor(Helper::base_amount($price[0]));
            // if(isset($price[1]) && is_numeric($price[1])) $price[1]=ceil(Helper::base_amount($price[1]));

            $products->whereBetween('price', $price);
        }

        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        // Sort by number
        if (!empty($_GET['show'])) {
            $products = $products->where('status', 'active')->paginate($_GET['show']);
        } else {
            $products = $products->where('status', 'active')->paginate(9);
        }
        // Sort by name , price, category


        return view('frontend.pages.product-grids')->with('products', $products)->with('recent_products', $recent_products);
    }
    public function productLists()
    {
        $products = Product::query();

        if (!empty($_GET['category'])) {
            $slug = explode(',', $_GET['category']);
            // dd($slug);
            $cat_ids = Category::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            // dd($cat_ids);
            $products->whereIn('cat_id', $cat_ids)->paginate;
            // return $products;
        }
        if (!empty($_GET['brand'])) {
            $slugs = explode(',', $_GET['brand']);
            $brand_ids = Brand::select('id')->whereIn('slug', $slugs)->pluck('id')->toArray();
            return $brand_ids;
            $products->whereIn('brand_id', $brand_ids);
        }
        if (!empty($_GET['sortBy'])) {
            if ($_GET['sortBy'] == 'title') {
                $products = $products->where('status', 'active')->orderBy('title', 'ASC');
            }
            if ($_GET['sortBy'] == 'price') {
                $products = $products->orderBy('price', 'ASC');
            }
        }

        if (!empty($_GET['price'])) {
            $price = explode('-', $_GET['price']);
            // return $price;
            // if(isset($price[0]) && is_numeric($price[0])) $price[0]=floor(Helper::base_amount($price[0]));
            // if(isset($price[1]) && is_numeric($price[1])) $price[1]=ceil(Helper::base_amount($price[1]));

            $products->whereBetween('price', $price);
        }

        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        // Sort by number
        if (!empty($_GET['show'])) {
            $products = $products->where('status', 'active')->paginate($_GET['show']);
        } else {
            $products = $products->where('status', 'active')->paginate(6);
        }
        // Sort by name , price, category


        return view('frontend.pages.product-lists')->with('products', $products)->with('recent_products', $recent_products);
    }
    public function productFilter(Request $request)
    {
        $data = $request->all();
        // return $data;
        $showURL = "";
        if (!empty($data['show'])) {
            $showURL .= '&show=' . $data['show'];
        }

        $sortByURL = '';
        if (!empty($data['sortBy'])) {
            $sortByURL .= '&sortBy=' . $data['sortBy'];
        }

        $catURL = "";
        if (!empty($data['category'])) {
            foreach ($data['category'] as $category) {
                if (empty($catURL)) {
                    $catURL .= '&category=' . $category;
                } else {
                    $catURL .= ',' . $category;
                }
            }
        }

        $brandURL = "";
        if (!empty($data['brand'])) {
            foreach ($data['brand'] as $brand) {
                if (empty($brandURL)) {
                    $brandURL .= '&brand=' . $brand;
                } else {
                    $brandURL .= ',' . $brand;
                }
            }
        }
        // return $brandURL;

        $priceRangeURL = "";
        if (!empty($data['price_range'])) {
            $priceRangeURL .= '&price=' . $data['price_range'];
        }
        if (request()->is('e-shop.loc/product-grids')) {
            return redirect()->route('product-grids', $catURL . $brandURL . $priceRangeURL . $showURL . $sortByURL);
        } else {
            return redirect()->route('product-lists', $catURL . $brandURL . $priceRangeURL . $showURL . $sortByURL);
        }
    }
    public function productSearch(Request $request)
    {
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $products = Product::where('title', 'like', '%' . $request->search . '%')
            //                    ->orwhere('slug','like','%'.$request->search.'%')
            ->where('status', 'active')
            ->orderBy('id', 'DESC')
            ->paginate('9');
        return view('frontend.pages.product-grids')->with('products', $products)->with('recent_products', $recent_products);
    }
    public function productBrand(Request $request)
    {
        $products = Brand::getProductByBrand($request->slug);
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        if (request()->is('e-shop.loc/product-grids')) {
            return view('frontend.pages.product-grids')->with('products', $products->products)->with('recent_products', $recent_products);
        } else {
            return view('frontend.pages.product-lists')->with('products', $products->products)->with('recent_products', $recent_products);
        }
    }
    public function productCat(Request $request)
    {
        $products = Category::getProductByCat($request->slug);
        // return $request->slug;
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();

        if (request()->is('e-shop.loc/product-grids')) {
            return view('frontend.pages.product-grids')->with('products', $products->products)->with('recent_products', $recent_products);
        } else {
            return view('frontend.pages.product-lists')->with('products', $products->products)->with('recent_products', $recent_products);
        }
    }
    public function productSubCat(Request $request)
    {
        $products = Category::getProductBySubCat($request->sub_slug);
        // return $products;
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();

        if (request()->is('e-shop.loc/product-grids')) {
            return view('frontend.pages.product-grids')->with('products', $products->sub_products)->with('recent_products', $recent_products);
        } else {
            return view('frontend.pages.product-lists')->with('products', $products->sub_products)->with('recent_products', $recent_products);
        }
    }
    public function productSubSubCat(Request $request)
    {
        $products = Category::getProductBySubSubCat($request->sub_slug);
        // return $products;
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();

        if (request()->is('e-shop.loc/product-grids')) {
            return view('frontend.pages.product-grids')->with('products', $products->sub_products)->with('recent_products', $recent_products);
        } else {
            return view('frontend.pages.product-lists')->with('products', $products->sub_products)->with('recent_products', $recent_products);
        }
    }
    public function blog()
    {
        $post = Post::query();

        if (!empty($_GET['category'])) {
            $slug = explode(',', $_GET['category']);
            // dd($slug);
            $cat_ids = PostCategory::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            return $cat_ids;
            $post->whereIn('post_cat_id', $cat_ids);
            // return $post;
        }
        if (!empty($_GET['tag'])) {
            $slug = explode(',', $_GET['tag']);
            // dd($slug);
            $tag_ids = PostTag::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            // return $tag_ids;
            $post->where('post_tag_id', $tag_ids);
            // return $post;
        }

        if (!empty($_GET['show'])) {
            $post = $post->where('status', 'active')->orderBy('id', 'DESC')->paginate($_GET['show']);
        } else {
            $post = $post->where('status', 'active')->orderBy('id', 'DESC')->paginate(9);
        }
        // $post=Post::where('status','active')->paginate(8);
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        return view('frontend.pages.blog')->with('posts', $post)->with('recent_posts', $rcnt_post);
    }
    public function blogDetail($slug)
    {
        $post = Post::getPostBySlug($slug);
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        // return $post;
        return view('frontend.pages.blog-detail')->with('post', $post)->with('recent_posts', $rcnt_post);
    }
    public function blogSearch(Request $request)
    {
        // return $request->all();
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $posts = Post::orwhere('title', 'like', '%' . $request->search . '%')
            ->orwhere('quote', 'like', '%' . $request->search . '%')
            ->orwhere('summary', 'like', '%' . $request->search . '%')
            ->orwhere('description', 'like', '%' . $request->search . '%')
            ->orwhere('slug', 'like', '%' . $request->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(8);
        return view('frontend.pages.blog')->with('posts', $posts)->with('recent_posts', $rcnt_post);
    }
    public function blogFilter(Request $request)
    {
        $data = $request->all();
        // return $data;
        $catURL = "";
        if (!empty($data['category'])) {
            foreach ($data['category'] as $category) {
                if (empty($catURL)) {
                    $catURL .= '&category=' . $category;
                } else {
                    $catURL .= ',' . $category;
                }
            }
        }

        $tagURL = "";
        if (!empty($data['tag'])) {
            foreach ($data['tag'] as $tag) {
                if (empty($tagURL)) {
                    $tagURL .= '&tag=' . $tag;
                } else {
                    $tagURL .= ',' . $tag;
                }
            }
        }
        // return $tagURL;
        // return $catURL;
        return redirect()->route('blog', $catURL . $tagURL);
    }
    public function blogByCategory(Request $request)
    {
        $post = PostCategory::getBlogByCategory($request->slug);
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        return view('frontend.pages.blog')->with('posts', $post->post)->with('recent_posts', $rcnt_post);
    }
    public function blogByTag(Request $request)
    {
        // dd($request->slug);
        $post = Post::getBlogByTag($request->slug);
        // return $post;
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        return view('frontend.pages.blog')->with('posts', $post)->with('recent_posts', $rcnt_post);
    }

    // Login
    public function login()
    {
        return view('frontend.pages.login');
    }
    public function loginSubmit(Request $request)
    {
        $data = $request->all();
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => 'active'])) {
            Session::put('user', $data['email']);
            request()->session()->flash('success', 'Successfully login');
            return redirect()->route('home');
        } else {
            request()->session()->flash('error', 'Invalid email and password pleas try again!');
            return redirect()->back();
        }
    }

    public function logout()
    {
        Session::forget('user');
        Auth::logout();
        request()->session()->flash('success', 'Logout successfully');
        return back();
    }

    public function register()
    {
        return view('frontend.pages.register');
    }
    public function registerSubmit(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'name' => 'string|required|min:2',
            'email' => 'string|required|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);
        $data = $request->all();
        //dd($data);
        $code = rand(10, 1000);
        $check = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => 'inactive',
            'code' => $code
        ]);
        $name = $data['name'];
        $email = $data['email'];
        $title = 'Verification Account';
        $content = "Here is your verification code " . $code;


        Mail::send('emails.test', ['name' => $name, 'email' => $email, 'title' => $title, 'content' => $content], function ($message) use ($data) {
            $message->to($data['email'])->subject('Verification Account');
        });
        Session::put('user', $data['email']);
        if ($check) {
            request()->session()->flash('success', 'Successfully registered');
            return redirect()->route('code-verification');
        } else {
            request()->session()->flash('error', 'Please try again!');
            return back();
        }
    }
    public function verification_code(Request $request)
    {
        if ($request->code) {
            $User = User::where('code', '=', $request->code)->get();
            //dd($User);
            if ($User) {
                User::where('code', $request->code)->update(['status' => 'active', 'code' => '']);
                request()->session()->flash('success', 'Successfully Activated.');
                return redirect()->route('home');
            } else {
                request()->session()->flash('error', 'Please try again!');
                return back();
            }
        }
        return view('frontend.pages.verification');
    }
    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => 'inactive',
            'code' => rand(10, 1000)
        ]);
    }
    // Reset password
    public function showResetForm()
    {
        return view('auth.passwords.old-reset');
    }

    public function ResetForm(Request $request)
    {
        //dd($request);
        $token = $request->token;
        $email  = $request->email;
        return view('auth.passwords.reset', compact('token', 'email'));
    }

    public function subscribe(Request $request)
    {
        if (!Newsletter::isSubscribed($request->email)) {
            Newsletter::subscribePending($request->email);
            if (Newsletter::lastActionSucceeded()) {
                request()->session()->flash('success', 'Subscribed! Please check your email');
                return redirect()->route('home');
            } else {
                Newsletter::getLastError();
                return back()->with('error', 'Something went wrong! please try again');
            }
        } else {
            request()->session()->flash('error', 'Already Subscribed');
            return back();
        }
    }
    /*
     * type will decide which calculation function should call
     * */
    public function calculation_for_rolls(Request $request)
    {
        $product_detail = Product::where('id', $_POST['product_id'])->first();
        $price = 0;
        $data = [];
        $data['status'] = false;

        if ($request->cal_type == 'Roll') {
            $cal_detail = $this->GetRoll($product_detail->width, $product_detail->height, $_POST['width'], $_POST['length'], $_POST['unit'], $product_detail->price);
            $price = $cal_detail['price'];
            $data['area'] = $cal_detail['area'];
            $data['qty'] = ceil($cal_detail['qty']);
            $data['status'] = true;
        } elseif ($request->cal_type == 'Box') {
            $cal_detail = $this->GetBox($product_detail->meter_per_box, $_POST['width'], $_POST['length'], $_POST['unit'], $product_detail->price);
            $price = $cal_detail['price'];
            $data['area'] = $cal_detail['area'];
            $data['qty'] = ceil($cal_detail['qty']);
            $data['status'] = true;
        } elseif ($request->cal_type == 'Custom') {
            $cal_detail = $this->GetCustom($product_detail->price, $_POST['width'], $_POST['length'], $_POST['unit']);
            $price = $cal_detail['price'];
            $data['status'] = true;
            $data['qty'] = ceil($cal_detail['qty']);
        }
        $data['ac_price'] = $product_detail->price;
        $data['price'] = $price;
        $data['price_show'] = '$ ' . $price;
        echo json_encode($data);
        exit;
    }

    public function GetRoll($width_db, $height_db, $width_user, $height_user, $unit_type, $price)
    {
        if ($unit_type == 'meter') {
            $height_user = $height_user * 100;
            $width_user = $width_user * 100;
        }
        $complete_peices = floor($height_db / $height_user); //how many complete pieces the roll does
        if ($complete_peices < 1) $complete_peices = 1;
        $roll_covered_area = ($complete_peices * $width_db);
        $rolls_needed = ceil($width_user / $roll_covered_area);
        $total_price = $price * $rolls_needed;


        $respone['status'] = true;
        $respone['price'] = $total_price;
        $respone['qty'] = $rolls_needed;
        $respone['area'] = round(($width_user * $height_user) / 10000, 2);

        return $respone;
    }

    public function GetBox($meter_per_box, $width_user, $length_user, $unit_type, $price)
    {
        $respone = [];
        $length_width = $length_user * $width_user;
        if ($unit_type == 'cm') {
            $length_width = ($length_user * $width_user) / 100;
            $room_area = $length_width * 1.1;
            $how_much_boxes = ceil($room_area / $meter_per_box);
            $how_much_boxes = ceil($how_much_boxes / 100);
            $room_area = ceil($room_area / 100);
        } else {
            $room_area = $length_width * 1.1;
            $how_much_boxes = ceil($room_area / $meter_per_box);
        }

        $total_price = ceil($how_much_boxes * $price);
        $respone['status'] = true;
        $respone['price'] = $total_price;
        $respone['area'] = round($room_area, 2);
        $respone['qty'] = $how_much_boxes;

        return $respone;
    }

    public function GetCustom($price, $width_user, $length_user, $unit_type)
    {
        $respone = [];
        if ($unit_type == 'cm') {
            $room_area = ($length_user * $width_user) / 100; //room area
        } else {
            $room_area = $length_user * $width_user; //room area
        }
        $total_price = ceil($room_area * $price);
        $respone['status'] = true;
        $respone['price'] = $total_price;
        $respone['qty'] = $room_area;

        return $respone;
    }
}
