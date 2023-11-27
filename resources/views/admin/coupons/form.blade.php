{!! Form::hidden('redirects_to', URL::previous()) !!}
<div class="row">
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('coupon_name') ? ' has-error' : '' }}">
            <label class="control-label" for="coupon_name">Coupon Name <span class="text-red">*</span></label>
            {!! Form::text('coupon_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Coupon Name', 'id' => 'coupon_name']) !!}
            @if ($errors->has('coupon_name'))
                <span class="text-danger">
                    <strong>{{ $errors->first('coupon_name') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
            <label class="control-label" for="amount">Coupon Amount <span class="text-red">*</span></label>
            {!! Form::number('amount', null, ['class' => 'form-control', 'placeholder' => 'Enter Coupon Amount', 'id' => 'amount']) !!}
            @if ($errors->has('amount'))
                <span class="text-danger">
                    <strong>{{ $errors->first('amount') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label" for="coupon_name">Coupon Code <span class="text-red">*</span></label>
            {!! Form::hidden('coupon_code', $coupon_code) !!}
            {!! Form::text('display_coupon_code', $coupon_code, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
            @if ($errors->has('coupon_code'))
                <span class="text-danger">
                    <strong>{{ $errors->first('coupon_code') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('expire_date') ? ' has-error' : '' }}">
            <label for="expire_date" class="form-label">Coupon Expire Date :<span class="text-red">*</span></label></label>
            {!! Form::date('expire_date', null, ['class' => 'form-control', 'placeholder' => 'Enter Coupon Expire Date', 'id' => 'expire_date']) !!}
            @if ($errors->has('expire_date'))
                <span class="text-danger">
                    <strong>{{ $errors->first('expire_date') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('min_order_amount') ? ' has-error' : '' }}">
            <label class="control-label" for="min_order_amount">Minimum Order Amount</label>
            {!! Form::number('min_order_amount', null, ['class' => 'form-control', 'placeholder' => 'Enter Minimum Order Amount', 'id' => 'min_order_amount']) !!}
            @if ($errors->has('min_order_amount'))
                <span class="text-danger">
                    <strong>{{ $errors->first('min_order_amount') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('max_order_amount') ? ' has-error' : '' }}">
            <label class="control-label" for="max_order_amount">Maximum Order Amount</label>
            {!! Form::number('max_order_amount', null, ['class' => 'form-control', 'placeholder' => 'Enter Maximum Order Amount', 'id' => 'max_order_amount']) !!}
            @if ($errors->has('max_order_amount'))
                <span class="text-danger">
                    <strong>{{ $errors->first('max_order_amount') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
            <label class="col-md-12 control-label" for="status">Status <span class="text-red">*</span></label>
            <div class="col-md-12">
                @foreach (\App\Models\Coupons::$status as $key => $value)
                    @php $checked = !isset($coupons) && $key == 'active'?'checked':''; @endphp
                    <label>
                        {!! Form::radio('status', $key, null, ['class' => 'flat-red',$checked]) !!} <span style="margin-right: 10px">{{ $value }}</span>
                    </label>
                @endforeach
                <br class="statusError">
                @if ($errors->has('status'))
                    <span class="text-danger" id="statusError">
                        <strong>{{ $errors->first('status') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
