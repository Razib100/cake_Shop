<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Products;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use App\Models\Cart;
use App\Models\ContactUs;
use App\Models\ProductImages;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class PayPalController extends Controller{

    public function processPayment(Request $request){
        $validation_status = $this->validateData($request->all());
        if(Auth::check() && $validation_status == 1){

            Session::put('input', $request->all());
            $user = Auth::user()->id;
            $totalPrice = number_format(Cart::where('user_id', $user)->sum('sub_total'),2, '.', '');

            if($request['hidden_payment_type']!='gcash'){

                $apiContext = new ApiContext(
                    new OAuthTokenCredential(
                        config('services.paypal.client_id'),
                        config('services.paypal.secret')
                    )
                );

                $payer = new Payer();
                $payer->setPaymentMethod('paypal');

                $amount = new Amount();
                $amount->setCurrency('PHP');
                $amount->setTotal($totalPrice); 

                $transaction = new Transaction();
                $transaction->setAmount($amount);

                $redirectUrls = new RedirectUrls();
                $redirectUrls->setReturnUrl(route('payment.success'))
                    ->setCancelUrl(route('payment.cancel'));

                $payment = new Payment();
                $payment->setIntent('sale')
                    ->setPayer($payer)
                    ->setTransactions([$transaction])
                    ->setRedirectUrls($redirectUrls);

                try {
                    $payment->create($apiContext);
                    $paymentId = $payment->getId();
                    $approvalUrl = $payment->getApprovalLink();
                    $redirectUrl = $approvalUrl . '&paymentId=' . urlencode($paymentId);
                    return redirect($redirectUrl);
                } catch (\Exception $e) {
                    return $e->getMessage();
                }        
            } else {

                $customer = User::findorFail($user);
                $customer['orderData'] = json_encode($request->all());
                $customer->update($request->all());

                $returnRedirectUrl = route('gcashPaymentSuccess');

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://checkout-test.adyen.com/v68/payments');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"merchantAccount\":\"JoinmomentumECOM\",\n  \"reference\":\"146\",\n  \"amount\":{\n    \"currency\":\"PHP\",\n    \"value\":".($totalPrice*100)."\n  },\n  \"paymentMethod\":{\n    \"type\":\"gcash\"\n  },\n  \"returnUrl\":\"$returnRedirectUrl\"\n}");

                $headers = array();
                $headers[] = 'X-Api-Key: AQEnhmfuXNWTK0Qc+iSanW02quuWTYVZGJ6zvp/ZqroypcJ99yavLO8zEMFdWw2+5HzctViMSCJMYAc=-JdAl7aeVoIwdTPHPSn4sVEVv8rKRZwIwAs2biho7PE8=-WD}2UE$]]E29?fdI';
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    return 'Error:' . curl_error($ch);
                }
                curl_close($ch);

                $resultRecord = json_decode($result);
/*
                echo $resultRecord->action->url;
                exit;*/
                return redirect($resultRecord->action->url);

            }

        }else{
             \Session::flash('danger','Your Order was canceled!');
            return redirect()->route('home');
        }
    }

    public function paymentSuccess(Request $request){
        if(Auth::check() && $request->query('paymentId') !== null){
            $user = Auth::user()->id;
            $cart_products = Cart::with('Product','Product.ProductImages')->where('user_id',$user)->get();
            $transaction_id = $request->query('paymentId');
            $input = Session::get('input');
            $status = $this->addOrder($user, $cart_products, $transaction_id, $input);
            //\Session::flash('success','Payment is done successfully!');
            //return redirect()->route('home');
            return redirect()->route('orderPlaced',['id'=>$status['id']]);
        }
    }

    public function paymentCancel(Request $request){
        \Session::flash('danger','Your Payment was canceled!');
        return redirect()->route('home');
    }

    private function addOrder($user, $cart_products, $transaction_id, $input){
        if(!empty($cart_products)){
            $userDetails = User::with('Orders')->where('id',$user)->first();
            $totalOrder = count($userDetails['Orders']) > 0 ? count($userDetails['Orders']) + 1 : 1;
            
            $input['unique_id'] = strtoupper(substr($userDetails['name'],0,3)).'0'.$totalOrder;
            $input['customer_id'] = $user;
            $input['order_total'] = number_format(Cart::where('user_id', $user)->sum('sub_total'),2, '.', '');
            $input['status'] = 'paid';
            $input['transaction_id'] = $transaction_id;
            $input['order_date'] = $input['hidden_order_date'];
            $input['order_time'] = $input['hidden_order_time'];
            $input['customer_name'] = $input['hidden_customer_name'];
            $input['customer_email'] = $input['hidden_customer_email'];
            $input['customer_phone'] = $input['hidden_customer_phone'];
            $input['short_notes'] = $input['hidden_short_notes'];
            $input['payment_type'] = $input['hidden_payment_type'];

            $order = Orders::create($input);

            $orderTotal = 0;
            $orderItems = [];
            foreach($cart_products as $key => $value){

                $product = Products::where('id',$value['product_id'])->where('status','active')->first();

                $orderItems['order_id'] = $order['id'];
                $orderItems['product_id'] = $value['product_id'];
                $orderItems['sku'] = $product['sku'];
                $orderItems['name'] = $product['name'];
                $orderItems['quantity'] = $value['quantity'];
                $orderItems['price'] = $value['price'];
                $orderItems['total'] = ($value['price']*$value['quantity']);

                $orderTotal = ($orderTotal+($value['price']*$value['quantity']));

                OrderItems::create($orderItems);
            }

            $order_total['order_total'] = $orderTotal;
            Orders::updateOrCreate(['id' => $order['id']], $order_total);
            Cart::where('user_id',$user)->delete();
            Session::forget('input');
            return $order;
        }
    }

    private function cancelOrder(){
        \Session::flash('danger','Your Order was canceled!');
        return redirect()->route('home');
    }

    public function validateData($input){
        $rules = [
            'hidden_order_date' => 'required',
            'hidden_order_time' => 'required',
            'hidden_customer_name' => 'required',
            'hidden_customer_email' => 'required',
            'hidden_customer_phone' => 'required',
            'hidden_payment_type' => 'required',
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return 0;
        }
        return 1;
    }

    public function gcashPaymentSuccess(Request $request){

        if(Auth::check() && $request->query('redirectResult') !== null){
            $user = Auth::user();

            $redirectResult = $request->query('redirectResult');

            // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://checkout-test.adyen.com/v68/payments/details');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n   \"details\": {\n     \"redirectResult\": \"$redirectResult\"\n }\n}");

            $headers = array();
            $headers[] = 'X-Api-Key: AQEnhmfuXNWTK0Qc+iSanW02quuWTYVZGJ6zvp/ZqroypcJ99yavLO8zEMFdWw2+5HzctViMSCJMYAc=-JdAl7aeVoIwdTPHPSn4sVEVv8rKRZwIwAs2biho7PE8=-WD}2UE$]]E29?fdI';
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);

            $resultRecord = json_decode($result);

            $cart_products = Cart::with('Product','Product.ProductImages')->where('user_id',$user->id)->get();
            $transaction_id = $resultRecord->pspReference;
            //$input = Session::get('input');
            $input = json_decode($user->orderData);

            $status = $this->addOrder($user->id, $cart_products, $transaction_id, (array)$input);

            $customer = User::findorFail($user->id);
            $customer['orderData'] = null;
            $customer->update($request->all());

            //\Session::flash('success','Payment is done successfully!');
            //return redirect()->route('home');
            return redirect()->route('orderPlaced',['id'=>$status['id']]);
        }
    }
}
