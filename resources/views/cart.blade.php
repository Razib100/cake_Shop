@if(isset($cart_products) && !empty($cart_products))
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10">
            <div class="section-tittle mb-60">
                <h2 style="float: left;">Your Order</h2> <h3 style="color:#000;float: right;" id="total_amount">₱ {{$cart_total}}</h3>
            </div>
        </div>
    </div>
    <div class="orderProcess">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10">
                <div class="item-list">
                    @foreach ($cart_products as $list)
                        @php
                            $proImage = '';
                            if(isset($list['product']['ProductImages'][0])){
                                $proImage = url('storage/'.$list['product']['ProductImages'][0]['image']);
                            }                                
                        @endphp
                        <div class="single-items">
                            <div class="items-left">
                                <img src="{{$proImage}}" alt="" class="item-img">
                                <div class="items-cnt">
                                    <h4><a href="#">{{$list['product']['name']}} </a></h4>
                                    <p>₱ {{$list['product']['price']}} X {{$list['quantity']}} = {{number_format($list['sub_total'], 2, '.', '')}}</p>
                                </div>
                            </div>
                            <div class="items-right">
                                <div class="qtyBox">
                                    <button type="button" class="btn" onclick="updateCart({{$list['product']['id']}},0)" id="btnMinusCart{{$list['product']['id']}}"><i class="fa fa-minus"></i></button>
                                    <input type="number" name="qty" id="proQtyCart{{$list['product']['id']}}" min="1" step="1" value="{{$list['quantity']}}">
                                    <button type="button" class="btn" onclick="updateCart({{$list['product']['id']}},1)" id="btnPlusCart{{$list['product']['id']}}"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
