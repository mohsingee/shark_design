@extends('frontend.layouts.master')
@section('title','Checkout page')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{route('home')}}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0)">Payment Process</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->
            
    <!-- Start Checkout -->
    <section class="shop checkout section">
        <div class="container">
            <div class="row">
                <aside class="col-sm-6 offset-3">
                    <div class="abouttext_div border rounded bg-white">
                        <div class="payment-title d-flex align-items-center justify-content-between"> <h3 class="p-2 title-res">Stripe Payment Gateway</h3>
                        </div>
                        <div id="credit-card" class="tab-pane fade show active pt-3">
                            <form action="{{ url('/payment')}}" id="chargeStripe" method="post">
                                {{ csrf_field() }}
                                <div class="form-group form-card pl-3 pr-3">
                                    <label for="cardNumber">
                                        <h6>Full name (on the card)</h6> </label>
                                    <div class="input-group errorshow">
                                        <input type="text" name="fullName" placeholder="Full Name" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group form-card pl-3 pr-3">
                                    <label for="cardNumber">
                                        <h6>Card number</h6> </label>
                                    <div class="input-group errorshow">
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <input type="number" name="cardNumber" placeholder="Valid card number" class="form-control" min="1">
                                        <div class="input-group-append"> 
                                            <span class="input-group-text text-muted"> 
                                                <i class="fab fa-cc-visa mx-1"></i> 
                                                <i class="fab fa-cc-mastercard mx-1"></i> 
                                                <i class="fab fa-cc-amex mx-1"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pl-3 pr-3">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>
                                                <span class="hidden-xs"><h6>Expiration Date</h6></span>
                                            </label>
                                            <div class="row">
                                                <div class="errorshow col-md-6 col-sm-6 col-6">
                                                    <select class="form-control" name="month">
                                                        <option disabled>MM</option>
                                                        @foreach(range(1, 12) as $month)
                                                            <option value="{{$month}}">{{$month}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="errorshow col-md-6 col-sm-6 col-6">
                                                    <select class="form-control" name="year">
                                                        <option disabled>YYYY</option>
                                                        @foreach(range(date('Y'), date('Y') + 10) as $year)
                                                            <option value="{{$year}}">{{$year}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group mb-4 cvv errorshow">
                                            <label data-toggle="tooltip" title="Three digit CV code on the back of your card">
                                                <h6>CVV <i class="fa fa-question-circle d-inline"></i></h6> 
                                            </label>
                                            <input type="number" name="cvv" class="form-control" min="1" placeholder="CVV"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <button type="submit" class="btn index-get-start-Btn New-cutom-button col-auto"> Confirm Payment </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
<!-- End Shop Newsletter -->
@endsection 
@push('styles')
<style>
    .New-cutom-button, .btn-Edit {
        min-width: 214px;
   }
   .payment-title{
       background-color: #0d28f2;
       color: #fff;
   }
   .payment{
       margin-right: 10px;
   }
    .w-95{
        width: 98%;
   }
    .col-auto{
        width: auto !important;
   }
    @media (max-width:992px){
        .title-res{
            font-size: 20px ;
       }
   }
    @media (max-width:600px){
        .bg-white{
            max-width: 100% !important;
            width: 100% !important;
            flex: unset !important;
       }
        .title-res {
            font-size: 16px;
       }
        .pt-3{
            padding-top: 0px !important;
       }
        .form-group{
            margin-bottom: 0px !important;
       }
        .cvv{
            padding-top: 15px;
       }
        .cvv h6{
            margin-bottom: 0px !important;
       }
        .form-card{
            padding-bottom: 0px !important;
       }
   }
</style>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
    $('#chargeStripe').validate({ 
        rules: {
            fullName: {
                required: true,
            },
            cardNumber: {
                required: true,
                number: true,
                creditcard: true,
            },
            month: {
                required: true,
                number: true,
            },
            year: {
                required: true,
                number: true,
            },
            cvv: {
                required: true,
                number: true,
                maxlength: 4,
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.errorshow').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
</script>
@endpush