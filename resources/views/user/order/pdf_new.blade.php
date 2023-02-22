<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<style>
    #invoice{
        padding: 30px;
    }
    .invoice {
        position: relative;
        background-color: #FFF;
        min-height: 680px;
        padding: 15px
    }
    .invoice header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #3989c6
    }
    .invoice .company-details {
        text-align: center;
    }
    .invoice .company-details .name {
        margin-top: 0;
        margin-bottom: 0
    }
    .invoice .contacts {
        margin-bottom: 20px
    }
    .invoice .invoice-to {
        text-align: left
    }
    .invoice .invoice-to .to {
        margin-top: 0;
        margin-bottom: 0
    }
    .invoice .invoice-details {
        text-align: right
    }
    .invoice .invoice-details .invoice-id {
        margin-top: 0;
        color: #3989c6
    }
    .invoice main {
        padding-bottom: 50px
    }
    .invoice main .thanks {
        margin-top: -100px;
        font-size: 1em;
        margin-bottom: 50px
    }
    .invoice main .notices {
        padding-left: 6px;
        border-left: 6px solid #3989c6
    }
    .invoice main .notices .notice {
        font-size: 16em
    }
    .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px
    }
    .invoice table td,.invoice table th {
        padding: 5px;
        background: #eee;
        border-bottom: 1px solid #fff
    }
    .invoice table th {
        white-space: nowrap;
        font-weight: 400;
        font-size: 13px
    }
    .invoice table td h3 {
        margin: 0;
        font-weight: 400;
        color: #3989c6;
        font-size: 14px;
    }
    .invoice table .qty,.invoice table .total,.invoice table .unit {
        text-align: right;
        font-size: 14px;
    }
    .invoice table .no {
        color: #fff;
        font-size: 14px;
        background: #3989c6
    }
    .invoice table .unit {
        background: #ddd
    }
    .invoice table .total {
        background: #3989c6;
        color: #fff
    }
    .invoice table tbody tr:last-child td {
        border: none
    }
    .invoice table tfoot td {
        background: 0 0;
        border-bottom: none;
        white-space: nowrap;
        text-align: right;
        padding: 10px 20px;
        font-size: 14px;
        border-top: 1px solid #aaa
    }
    .invoice table tfoot tr:first-child td {
        border-top: none
    }
    .invoice table tfoot tr:last-child td {
        color: #3989c6;
        font-size: 13px;
        border-top: 1px solid #3989c6
    }
    .invoice table tfoot tr td:first-child {
        border: none
    }
    .invoice footer {
        width: 100%;
        text-align: center;
        color: #777;
        border-top: 1px solid #aaa;
        padding: 8px 0
    }
    @media print {
        .invoice {
            font-size: 11px!important;
            overflow: hidden!important
        }
        .invoice footer {
            position: absolute;
            bottom: 10px;
            page-break-after: always
        }
        .invoice>div:last-child {
            page-break-before: always
        }
    }
</style>
<div id="invoice">

    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <header>
                <div class="row">
                    <!--                    <div class="col">
                        <a href="#">
                            <img src="{{asset('backend/img/logo.png')}}" data-holder-rendered="true" />
                        </a>
                    </div>-->
                    <div class="col company-details">
                        <h2 class="name">
                            <a target="_blank" href="https://lobianijs.com">
                                {{env('APP_NAME')}}
                            </a>
                        </h2>
                        <div>{{env('APP_ADDRESS')}}</div>
                        <div><a href="tel:{{env('APP_PHONE')}}">{{env('APP_PHONE')}}</div>
                        <div><a href="mailto:{{env('APP_EMAIL')}}">{{env('APP_EMAIL')}}</div>
                    </div>
                </div>
            </header>
            <main>
                <div class="row contacts">
                    <div class="col invoice-to">
                        <div class="text-gray-light">INVOICE TO:</div>
                        <h2 class="to">{{$order->first_name}} {{$order->last_name}}</h2>
                        <div class="address">{{ $order->address1 }} OR {{ $order->address2}}</div>
                        <div class="email"><a href="mailto:{{ $order->email }}">{{ $order->email }}</a></div>
                        <div class="email">{{ $order->phone }}</div>
                    </div>
                    <div class="col invoice-details">
                        <h1 class="invoice-id">INVOICE # {{$order->cart_id}}</h1>
                        <div class="date">Date of Invoice: {{ $order->created_at->format('D d m Y') }}</div>
                    </div>
                </div>
                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-left">Product</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">QTY</th>
                        <th class="text-right">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $total_amount = 0;
                        $cart_info = App\Models\Cart::where('id',$order->id)->get();
                    @endphp
                    @foreach($cart_info as $key => $cart)
                        @php
                            $total_amount += (number_format($cart->price,2) * $cart->quantity);
                            $key++;
                        @endphp
                        <tr>
                            <td class="no">{{$key}}</td>
                            <td class="text-left">
                                <h3>
                                    {{App\Models\Product::where('id',$cart->product_id)->value('title')}}
                                </h3>
                            </td>
                            <td class="unit">${{number_format($cart->price,2)}}</td>
                            <td class="qty">
                                <table>
                                    <tr><td>{{$cart->quantity}}</td></tr>
                                    @if($cart->unit == 'cm' || $cart->unit == 'meter' || $cart->unit == 'metter')
                                        <tr><td>Width {{$cart->width}} - Height {{$cart->length}}</td></tr>
                                    @endif
                                </table>
                            </td>
                            <td class="total">${{(number_format($cart->price,2) * $cart->quantity)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2">GRAND TOTAL</td>
                        <td>${{number_format($total_amount,2)}}</td>
                    </tr>
                    </tfoot>
                </table>

                <!--                <div class="notices">
                                    <div>NOTICE:</div>
                                    <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
                                </div>-->
            </main>
            <footer>
                Invoice was created on a computer and is valid without the signature and seal.
            </footer>
        </div>
        <div></div>
    </div>
</div>
