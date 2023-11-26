@if(isset($cart_products) && !empty($cart_products))
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
                        <h4><a href="#">{{$list['product']['name']}}</a></h4>
                        <p>₱ {{number_format($list['price'], 2, '.', '')}} X {{$list['quantity']}}</p>
                    </div>
                </div>
                <div class="items-right">
                    <p class="price">₱ {{number_format($list['sub_total'], 2, '.', '')}}</p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="pickup-txt">
        <h6>Pickup<span>Free</span></h6>
        <p>Quezon City • <span id="selectedTimeAjax"></span></p>
    </div>
    <div class="total-txt">
        <h5>Total</h5>
        <h3>₱ {{number_format($cart_total, 2, '.', '')}}</h3>
    </div>
@endif
