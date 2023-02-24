@extends('frontend.layouts.master')

@section('meta')
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name='copyright' content=''>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="keywords" content="online shop, purchase, cart, ecommerce site, best online shopping">
	<meta name="description" content="{{$product_detail->summary}}">
	<meta property="og:url" content="{{route('product-detail',$product_detail->slug)}}">
	<meta property="og:type" content="article">
	<meta property="og:title" content="{{$product_detail->title}}">
	<meta property="og:image" content="{{$product_detail->photo}}">
	<meta property="og:description" content="{{$product_detail->description}}">
@endsection
@section('title','Shark-Design || PRODUCT DETAIL')
@section('main-content')

		<!-- Breadcrumbs -->
		<div class="breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="bread-inner">
							<ul class="bread-list">
								<li><a href="{{route('home')}}">Home<i class="ti-arrow-right"></i></a></li>
								<li class="active"><a href="">Shop Details</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Breadcrumbs -->

		<!-- Shop Single -->
		<section class="shop single section">
					<div class="container">
						<div class="row">
							<div class="col-12">
								<div class="row">
									<div class="col-lg-6 col-12">
										<!-- Product Slider -->
										<div class="product-gallery">
											<!-- Images slider -->
											<div class="flexslider-thumbnails">
												<ul class="slides">
													@php
														$photo=explode(',',$product_detail->photo);
													// dd($photo);
													@endphp
													@foreach($photo as $data)
                                                        @php
                                                            if ($data) {
                                                                $photo_img = asset($data);
                                                            } else {
                                                                $photo_img = asset('backend/img/thumbnail-default.jpg');
                                                            }
                                                        @endphp
														<li data-thumb="{{$photo_img}}" rel="adjustX:10, adjustY:">
															<img src="{{$photo_img}}" alt="{{$photo_img}}">
														</li>
													@endforeach
												</ul>
											</div>
											<!-- End Images slider -->
										</div>
										<!-- End Product slider -->
									</div>
									<div class="col-lg-6 col-12">
										<div class="product-des">
											<!-- Description -->
											<div class="short">
												<h4>{{$product_detail->title}}</h4>
												<div class="rating-main">
													<ul class="rating">
														@php
															$rate=ceil($product_detail->getReview->avg('rate'))
														@endphp
															@for($i=1; $i<=5; $i++)
																@if($rate>=$i)
																	<li><i class="fa fa-star"></i></li>
																@else
																	<li><i class="fa fa-star-o"></i></li>
																@endif
															@endfor
													</ul>
													<a href="#" class="total-review">({{$product_detail['getReview']->count()}}) Review</a>
                                                </div>
                                                @php
                                                    $after_discount=($product_detail->price-(($product_detail->price*$product_detail->discount)/100));
                                                @endphp
												<p class="price" data-price="{{number_format($after_discount,2)}}">
                                                    @if($product_detail->discount > 0)
                                                        <span id="price-div" class="discount">
                                                            ${{number_format($after_discount,2)}}
                                                        </span>
                                                        <s>${{number_format($product_detail->price,2)}}</s>
                                                    @else
                                                        <span id="price-div" class="discount">
                                                            ${{number_format($product_detail->price,2)}}
                                                        </span>
                                                    @endif
                                                </p>
												<p class="description">{!!($product_detail->summary)!!}</p>
											</div>
											<!--/ End Description -->

											<!-- Product Buy -->
											<div class="product-buy">
												<form action="{{route('single-add-to-cart')}}" method="POST" {{$product_detail->calculator_show}}>
													@csrf
                                                    @if ($product_detail->calculator_show == 1)
                                                        @if ($product_detail->calculator_type == 1)
                                                            <div class="row calculators-section" >
                                                                {{-- <div class="form-group col-md-12">
                                                                    <select name="order_unit" class="form-control unit_for_calculation col-md-12">
                                                                        <option value="">Select Unit</option>
                                                                        <option value="cm">CM</option>
                                                                        <option value="meter">Meter</option>
                                                                    </select>
                                                                </div> --}}
                                                                <input type="hidden" name="order_unit" class="unit_for_calculation" value="cm">
                                                                <div class="col-md-12 calculator_wood calculators">
                                                                    <div class="input-group">
                                                                        <input id="cal-width" placeholder="Width" class="form-control" name="width" value="" type="number">
                                                                    </div>
                                                                    <div class="input-group">
                                                                        <input id="cal-length" class="form-control" placeholder="Length" name="length" value="" type="number">
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-primary btn-custom-design">Calculate</button>
                                                                    </div>
                                                                    <input id="product_id" value="{{$product_detail->id}}" type="hidden">
                                                                    <div class="col-md-12">
                                                                        <div style="color:#fff" id="product_custom_qty"></div>
                                                                    </div>
                                                                    <input type="hidden" name="slug" value="{{$product_detail->slug}}">
                                                                    <input type="hidden" id="product_custom_unit" name="unit" value="">
                                                                    <input id="product_id-price" name="amount" value="{{number_format($product_detail->price,2)}}" type="hidden">
                                                                </div>
                                                                <div style="display: none" class="quantity">
                                                                    <h6>Quantity :</h6>
                                                                    <!-- Input Order -->
                                                                    <div class="input-group">
                                                                        <div class="button minus">
                                                                            <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="buy_qty">
                                                                                <i class="ti-minus"></i>
                                                                            </button>
                                                                        </div>
                                                                        <input type="text" name="buy_qty" class="input-number"  data-min="1" data-max="1000" value="1" id="quantity">
                                                                        <div class="button plus">
                                                                            <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="buy_qty">
                                                                                <i class="ti-plus"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @elseif($product_detail->calculator_type == 2)
                                                            <div class="row calculators-section" >
                                                                {{-- <div class="form-group col-md-12">
                                                                    <select name="order_unit" class="form-control unit_for_calculation col-md-12">
                                                                        <option value="">Select Unit</option>
                                                                        <option value="cm">CM</option>
                                                                        <option value="meter">Meter</option>
                                                                    </select>
                                                                </div> --}}
                                                                <input type="hidden" name="order_unit" class="unit_for_calculation" value="cm">
                                                                <div class="col-md-12 calculator_wood calculators">
                                                                    <div class="input-group">
                                                                        <input id="cal-width" placeholder="Width" class="form-control" name="width" value="" type="number">
                                                                    </div>
                                                                    <div class="input-group">
                                                                        <input id="cal-length" class="form-control" placeholder="Height" name="length" value="" type="number">
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-primary btn-custom-meter_box">Calculate</button>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div style="color:#fff" id="product_custom_qty"></div>
                                                                    </div>
                                                                    <input id="product_id" value="{{$product_detail->id}}" type="hidden">
                                                                    <input type="hidden" name="slug" value="{{$product_detail->slug}}">
                                                                    <input type="hidden" id="product_custom_unit" name="unit" value="">
                                                                    <input id="product_id-price" name="amount" value="{{number_format($product_detail->price,2)}}" type="hidden">
                                                                </div>
                                                                <div class="quantity">
                                                                    <h6>Quantity :</h6>
                                                                    <!-- Input Order -->
                                                                    <div class="input-group">
                                                                        <div class="button minus">
                                                                            <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="buy_qty">
                                                                                <i class="ti-minus"></i>
                                                                            </button>
                                                                        </div>
                                                                        <input type="text" name="buy_qty" class="input-number"  data-min="1" data-max="1000" value="1" id="quantity">
                                                                        <div class="button plus">
                                                                            <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="buy_qty">
                                                                                <i class="ti-plus"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @elseif($product_detail->calculator_type == 3)
                                                            <div class="row calculators-section">
                                                                {{-- <div class="form-group col-md-12">
                                                                    <select name="order_unit" class="form-control unit_for_calculation col-md-12">
                                                                        <option value="">Select Unit</option>
                                                                        <option value="cm">CM</option>
                                                                        <option value="meter">Meter</option>
                                                                    </select>
                                                                </div> --}}
                                                                <input type="hidden" name="order_unit" class="unit_for_calculation" value="cm">
                                                                <div class="col-md-12 calculator_wood calculators">
                                                                    <div class="input-group">
                                                                        <input id="cal-width" placeholder="Width" class="form-control" name="width" value="" type="number">
                                                                    </div>
                                                                    <div class="input-group">
                                                                        <input id="cal-length" class="form-control" placeholder="Height" name="length" value="" type="number">
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-primary btn-custom-roll">Calculate</button>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div style="color:#fff" id="product_custom_qty"></div>
                                                                    </div>
                                                                    <input id="product_id" value="{{$product_detail->id}}" type="hidden">
                                                                    <input type="hidden" name="slug" value="{{$product_detail->slug}}">
                                                                    <input type="hidden" id="product_custom_unit" name="unit" value="">
                                                                    <input id="product_id-price" name="amount" value="{{number_format($product_detail->price,2)}}" type="hidden">
                                                                </div>
                                                                <div class="quantity">
                                                                    <h6>Quantity :</h6>
                                                                    <!-- Input Order -->
                                                                    <div class="input-group">
                                                                        <div class="button minus">
                                                                            <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="buy_qty">
                                                                                <i class="ti-minus"></i>
                                                                            </button>
                                                                        </div>
                                                                        <input type="hidden" name="slug" value="{{$product_detail->slug}}">
                                                                        <input type="text" name="buy_qty" class="input-number"  data-min="1" data-max="1000" value="1" id="quantity">
                                                                        <div class="button plus">
                                                                            <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="buy_qty">
                                                                                <i class="ti-plus"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="quantity">
                                                            <h6>Quantity :</h6>
                                                            <!-- Input Order -->
                                                            <div class="input-group">
                                                                <div class="button minus">
                                                                    <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="buy_qty">
                                                                        <i class="ti-minus"></i>
                                                                    </button>
                                                                </div>
                                                                <input type="hidden" name="slug" value="{{$product_detail->slug}}">
                                                                <input type="text" name="buy_qty" class="input-number"  data-min="1" data-max="1000" value="1" id="quantity">
                                                                <div class="button plus">
                                                                    <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="buy_qty">
                                                                        <i class="ti-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
													<div class="add-to-cart mt-4">
														<button type="submit" class="btn">Add to cart</button>
														<a href="{{route('add-to-wishlist',$product_detail->slug)}}" class="btn min"><i class="ti-heart"></i></a>
													</div>
												</form>

												<p class="cat">Category :<a href="{{route('product-cat',$product_detail->cat_info['slug'])}}">{{$product_detail->cat_info['title']}}</a></p>
												@if($product_detail->sub_cat_info)
												<p class="cat mt-1">Sub Category :<a href="{{route('product-sub-cat',[$product_detail->cat_info['slug'],$product_detail->sub_cat_info['slug']])}}">{{$product_detail->sub_cat_info['title']}}</a></p>
												@endif
												<p class="availability">Stock : @if($product_detail->quantity_in_stock>0)<span class="badge badge-success">{{$product_detail->quantity_in_stock}}</span>@else <span class="badge badge-danger">{{$product_detail->quantity_in_stock}}</span>  @endif</p>
											</div>
											<!--/ End Product Buy -->
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-12">
										<div class="product-info">
											<div class="nav-main">
												<!-- Tab Nav -->
												<ul class="nav nav-tabs" id="myTab" role="tablist">
													<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#description" role="tab">Description</a></li>
													<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Reviews</a></li>
												</ul>
												<!--/ End Tab Nav -->
											</div>
											<div class="tab-content" id="myTabContent">
												<!-- Description Tab -->
												<div class="tab-pane fade show active" id="description" role="tabpanel">
													<div class="tab-single">
														<div class="row">
															<div class="col-12">
																<div class="single-des">
																	<p>{!! ($product_detail->description) !!}</p>
																</div>
															</div>
														</div>
													</div>
												</div>
												<!--/ End Description Tab -->
												<!-- Reviews Tab -->
												<div class="tab-pane fade" id="reviews" role="tabpanel">
													<div class="tab-single review-panel">
														<div class="row">
															<div class="col-12">

																<!-- Review -->
																<div class="comment-review">
																	<div class="add-review">
																		<h5>Add A Review</h5>
																		<p>Your email address will not be published. Required fields are marked</p>
																	</div>
																	<h4>Your Rating <span class="text-danger">*</span></h4>
																	<div class="review-inner">
																			<!-- Form -->
																@auth
																<form class="form" method="post" action="{{route('review.store',$product_detail->slug)}}">
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-12">
                                                                            <div class="rating_box">
                                                                                  <div class="star-rating">
                                                                                    <div class="star-rating__wrap">
                                                                                      <input class="star-rating__input" id="star-rating-5" type="radio" name="rate" value="5">
                                                                                      <label class="star-rating__ico fa fa-star-o" for="star-rating-5" title="5 out of 5 stars"></label>
                                                                                      <input class="star-rating__input" id="star-rating-4" type="radio" name="rate" value="4">
                                                                                      <label class="star-rating__ico fa fa-star-o" for="star-rating-4" title="4 out of 5 stars"></label>
                                                                                      <input class="star-rating__input" id="star-rating-3" type="radio" name="rate" value="3">
                                                                                      <label class="star-rating__ico fa fa-star-o" for="star-rating-3" title="3 out of 5 stars"></label>
                                                                                      <input class="star-rating__input" id="star-rating-2" type="radio" name="rate" value="2">
                                                                                      <label class="star-rating__ico fa fa-star-o" for="star-rating-2" title="2 out of 5 stars"></label>
                                                                                      <input class="star-rating__input" id="star-rating-1" type="radio" name="rate" value="1">
																					  <label class="star-rating__ico fa fa-star-o" for="star-rating-1" title="1 out of 5 stars"></label>
																					  @error('rate')
																						<span class="text-danger">{{$message}}</span>
																					  @enderror
                                                                                    </div>
                                                                                  </div>
                                                                            </div>
                                                                        </div>
																		<div class="col-lg-12 col-12">
																			<div class="form-group">
																				<label>Write a review</label>
																				<textarea name="review" rows="6" placeholder="" ></textarea>
																			</div>
																		</div>
																		<div class="col-lg-12 col-12">
																			<div class="form-group button5">
																				<button type="submit" class="btn">Submit</button>
																			</div>
																		</div>
																	</div>
																</form>
																@else
																<p class="text-center p-5">
																	You need to <a href="{{route('login.form')}}" style="color:rgb(54, 54, 204)">Login</a> OR <a style="color:blue" href="{{route('register.form')}}">Register</a>

																</p>
																<!--/ End Form -->
																@endauth
																	</div>
																</div>

																<div class="ratting-main">
																	<div class="avg-ratting">
																		{{-- @php
																			$rate=0;
																			foreach($product_detail->rate as $key=>$rate){
																				$rate +=$rate
																			}
																		@endphp --}}
																		<h4>{{ceil($product_detail->getReview->avg('rate'))}} <span>(Overall)</span></h4>
																		<span>Based on {{$product_detail->getReview->count()}} Comments</span>
																	</div>
																	@foreach($product_detail['getReview'] as $data)
																	<!-- Single Rating -->
																	<div class="single-rating">
																		<div class="rating-author">
																			@if($data->user_info['photo'])
																			<img src="{{$data->user_info['photo']}}" alt="{{$data->user_info['photo']}}">
																			@else
																			<img src="{{asset('backend/img/avatar.png')}}" alt="Profile.jpg">
																			@endif
																		</div>
																		<div class="rating-des">
																			<h6>{{$data->user_info['name']}}</h6>
																			<div class="ratings">

																				<ul class="rating">
																					@for($i=1; $i<=5; $i++)
																						@if($data->rate>=$i)
																							<li><i class="fa fa-star"></i></li>
																						@else
																							<li><i class="fa fa-star-o"></i></li>
																						@endif
																					@endfor
																				</ul>
																				<div class="rate-count">(<span>{{$data->rate}}</span>)</div>
																			</div>
																			<p>{{$data->review}}</p>
																		</div>
																	</div>
																	<!--/ End Single Rating -->
																	@endforeach
																</div>

																<!--/ End Review -->

															</div>
														</div>
													</div>
												</div>
												<!--/ End Reviews Tab -->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
		</section>
		<!--/ End Shop Single -->

		<!-- Start Most Popular -->
	<div class="product-area most-popular related-product section">
        <div class="container">
            <div class="row">
				<div class="col-12">
					<div class="section-title">
						<h2>Related Products</h2>
					</div>
				</div>
            </div>
            <div class="row">
                {{-- {{$product_detail->rel_prods}} --}}
                <div class="col-12">
                    <div class="owl-carousel popular-slider">
                        @foreach($suggested_product as $data)
                            @if($data->id !==$product_detail->id)
                                <!-- Start Single Product -->
                                <div class="single-product">
                                    <div class="product-img">
										<a href="{{route('product-detail',$data->slug)}}">
											@php
												$photo=explode(',',$data->photo);
                                                if (strpos($photo[0], str_replace(['https://', 'http://'],'',URL::to('/')))!=false) {
                                                    $photo_img = $photo[0];
                                                } else {
                                                    $photo_img = asset('backend/img/thumbnail-default.jpg');
                                                }
											@endphp
                                            <img class="default-img" src="{{$photo_img}}" alt="{{$data->title}}">
                                            <img class="hover-img" src="{{$photo_img}}" alt="{{$data->title}}">
                                            <span class="price-dec">{{$data->discount}} % Off</span>
                                                                    {{-- <span class="out-of-stock">Hot</span> --}}
                                        </a>
                                        <div class="button-head">
                                            <div class="product-action">
                                                <a data-toggle="modal" data-target="#modelExample" title="Quick View" href="#"><i class=" ti-eye"></i><span>Quick Shop</span></a>
                                                <a title="Wishlist" href="#"><i class=" ti-heart "></i><span>Add to Wishlist</span></a>
                                                <a title="Compare" href="#"><i class="ti-bar-chart-alt"></i><span>Add to Compare</span></a>
                                            </div>
                                            <div class="product-action-2">
                                                <a title="Add to cart" href="#">Add to cart</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3><a href="{{route('product-detail',$data->slug)}}">{{$data->title}}</a></h3>
                                        <div class="product-price">
                                            @php
                                                $after_discount=($data->price-(($data->discount*$data->price)/100));
                                            @endphp
                                            <span class="old">${{number_format($data->price,2)}}</span>
                                            <span>${{number_format($after_discount,2)}}</span>
                                        </div>

                                    </div>
                                </div>
                                <!-- End Single Product -->

                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
	<!-- End Most Popular Area -->

@endsection
@push('styles')
	<style>
		/* Rating */
		.rating_box {
		display: inline-flex;
		}

		.star-rating {
		font-size: 0;
		padding-left: 10px;
		padding-right: 10px;
		}

		.star-rating__wrap {
		display: inline-block;
		font-size: 1rem;
		}

		.star-rating__wrap:after {
		content: "";
		display: table;
		clear: both;
		}

		.star-rating__ico {
		float: right;
		padding-left: 2px;
		cursor: pointer;
		color: #F7941D;
		font-size: 16px;
		margin-top: 5px;
		}

		.star-rating__ico:last-child {
		padding-left: 0;
		}

		.star-rating__input {
		display: none;
		}

		.star-rating__ico:hover:before,
		.star-rating__ico:hover ~ .star-rating__ico:before,
		.star-rating__input:checked ~ .star-rating__ico:before {
		content: "\F005";
		}

        .calculators {
            padding: 10px;
            background: #000;
            width: 50% !important;
        }
        .calculators-section{
            width: 50% !important;
        }
        .calculators .input-group{
            padding: 10px;
        }

	</style>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

     <script>
        $('.btn-custom-design').on('click',function(){
            var width = $('#cal-width').val();
            var length = $('#cal-length').val();
            var pro_id = $('#product_id').val();
            var unit = $('.unit_for_calculation').val();
            // alert(quantity);

            if (length != '' && width != '') {
                $.ajax({
                    url:"{{route('calculate-cal-wood')}}",
                    type:"POST",
                    datatype:"json",
                    data:{
                        _token:"{{csrf_token()}}",
                        width:width,
                        length:length,
                        cal_type:'Custom',
                        product_id:pro_id,
                        unit:unit
                    },
                    success:function(response){
                        console.log(response);
                        if(typeof(response)!='object'){
                            response=$.parseJSON(response);
                        }
                        if(response.status){
                            console.log(response.price);
                            $('#price-div').text(response.price_show);
                            $('#product_id-price').val(response.price);
                            $('#product_custom_qty').text('You need Quantity '+response.qty);
                            $('#quantity').val(response.qty);
                            $('#product_custom_unit').val(unit);
                        }
                        else{

                        }
                    }
                })
            }

        });
        $('.btn-custom-meter_box').on('click',function(){
            var width = $('#cal-width').val();
            var length = $('#cal-length').val();
            var pro_id = $('#product_id').val();
            var unit = $('.unit_for_calculation').val();


            if (width !== '' || length !== '') {
                $.ajax({
                    url:"{{route('calculate-cal-wood')}}",
                    type:"POST",
                    datatype:"json",
                    data:{
                        _token:"{{csrf_token()}}",
                        width:width,
                        product_id:pro_id,
                        length:length,
                        cal_type:'Box',
                        unit:unit
                    },
                    success:function(response){
                        console.log(response);
                        if(typeof(response)!='object'){
                            response=$.parseJSON(response);
                        }
                        if(response.status){
                            $('#price-div').text(response.price_show);
                            $('#product_id-price').val(response.price);
                            $('#product_custom_qty').text('You need  '+response.qty+ ' boxes for this '+response.area +' meter area. ');
                            $('#quantity').val(response.qty);
                            $('#product_custom_unit').val(unit);
                        }
                        else{

                        }
                    }
                })
            }

        });
        $('.btn-custom-roll').on('click',function(){
            var width = $('#cal-width').val();
            var length = $('#cal-length').val();
            var pro_id = $('#product_id').val();
            var unit = $('.unit_for_calculation').val();


            if (width !== '' || length !== '') {
                $.ajax({
                    url:"{{route('calculate-cal-wood')}}",
                    type:"POST",
                    datatype:"json",
                    data:{
                        _token:"{{csrf_token()}}",
                        width:width,
                        product_id:pro_id,
                        length:length,
                        cal_type:'Roll',
                        unit:unit
                    },
                    success:function(response){
                        console.log(response);
                        if(typeof(response)!='object'){
                            response=$.parseJSON(response);
                        }
                        if(response.status){
                            $('#price-div').text(response.price_show);
                            $('#product_id-price').val(response.price);
                            $('#product_custom_qty').text('You need  '+response.qty+ ' rolls for this '+ response.area +' meter area. ');
                            $('#quantity').val(response.qty);
                            $('#product_custom_unit').val(unit);
                        }
                        else{

                        }
                    }
                })
            }

        });
        $('.unit_for_calculation').on('change', function(){
            var unit = $(this).val();
        });
        // $('.open_calculator').on('click', function(e) {
        //     e.preventDefault();
        //     var price = $('.price').data('price');
        //     if ($(this).hasClass('shown')) {
        //         $('#cal-width').val(0);
        //         $('#cal-length').val(0);
        //         $('.product_id-price').val(price);
        //         $('#price-div').text('$ '+price);
        //         $('.calculators-section').hide();
        //         $('.open_calculator').removeClass('shown');
        //     } else {
        //         $('.calculators-section').show();
        //         $('.open_calculator').addClass('shown');
        //     }
        // });
        // $('.btn-number').click(function(){
        //     console.log('qty changed');
        //     var qty = $('#quantity').val();
        //     var price = $('.price').data('price');
        //     var new_price = qty * price;
        //     $('.product_id-price').val(new_price);
        //     $('#price-div').text('$ '+new_price);
        // });
    </script>

@endpush
