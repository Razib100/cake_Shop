<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportOrders;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\User;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Setting;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(Request $request){
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data['menu'] = "Orders";
        $data['search'] = $request['search'];

        if ($request->ajax()) {
            $whereInfo = [];
            if(!empty($request->status)){
                $whereInfo[] = ['status', '=', strtolower($request->status)];
            }

            if(!empty($request->customer)){
                $whereInfo[] = ['customer_name', 'like', '%'.$request->customer.'%'];
            }

            if($request->start_date != ''){
                $whereInfo[] = ['created_at', '>=', $request->start_date.' 00:00:00'];
            }

            if($request->end_date != ''){
                $whereInfo[] = ['created_at', '<=', $request->end_date.' 23:59:59'];
            }

            $data = Orders::orderBy('id','DESC')->select()->where($whereInfo);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function($row){
                    return $row['created_at']->format('M d, Y h:i A');
                })
                ->addColumn('order_date', function($row){
                    return date('M d, Y', strtotime($row['order_date'])).' '.date('h:i A', strtotime($row['order_time']));
                })
                ->addColumn('order_total', function($row){
                    return '₱'.number_format($row['order_total'], 2, '.', '');
                })
                ->addColumn('order_items', function($row){
                    $data = OrderItems::select('*')->where('order_id', $row['id'])->get();

                    $orderItems = [];
                    if(!empty($data)){
                        foreach ($data as $key => $value) {
                            $orderItems[] = $value['name'];
                        }
                    }
                    return implode('<hr style="margin: 5px;">', $orderItems);
                })
                ->addColumn('status', function($row){
                    $orderPaid = '';
                    $orderPending = '';
                    $orderRejected = '';
                    if($row->status == 'paid'){
                        $orderPaid = 'selected';
                    }
                    if ($row->status == 'pending'){
                        $orderPending = 'selected';
                    }
                    if($row->status == 'rejected'){
                        $orderRejected = 'selected';
                    }
                    $select = '<select class="form-control select2 orderStatus" data-id="'.$row->id.'" >
                                    <option value="paid" '.$orderPaid.'>Paid</option>
                                    <option value="pending" '.$orderPending.'>Pending</option>
                                    <option value="rejected" '.$orderRejected.'>Rejected</option>
                                </select>';

                    /*$statusBtn = '';
                    if ($row->status == "paid") {
                        $statusBtn .= '<span class="btn btn-success" type="button" style="padding:0 12px">'.ucwords($row->status).'</span>';
                    } else if ($row->status == "pending") {
                        $statusBtn .= '<span class="btn btn-warning" type="button" style="padding:0 12px">'.ucwords($row->status).'</span>';
                    } else if ($row->status == "reject") {
                        $statusBtn .= '<span class="btn btn-danger" type="button" style="padding:0 12px">'.ucwords($row->status).'</span>';
                    }*/
                    return $select;
                })
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group btn-group-sm"><a href="'.route('orders.edit',['order'=>$row->id]).'"><button class="btn btn-sm btn-info tip" data-toggle="tooltip" title="Edit Order" data-trigger="hover" type="submit" ><i class="fa fa-edit"></i></button></a></div>';
                    $btn .= '<span data-toggle="tooltip" title="Delete Stock" data-trigger="hover">
                                <button class="btn btn-sm btn-danger deleteOrder" data-id="'.$row->id.'" type="button"><i class="fa fa-trash"></i></button>
                            </span>';
                    $btn .= '<div class="btn-group btn-group-sm">
                                <a href="'.route('orders.print',['id'=>$row->id]).'">
                                    <button class="btn btn-sm btn-success tip" data-toggle="tooltip" title="Generate Invoice" data-trigger="hover" type="submit" >
                                        <i class="fa fa-download"></i>
                                    </button>
                                </a>
                            </div>';
                    return $btn;
                })
                ->rawColumns(['user','order_total','status','action','order_date', 'order_items'])
                ->make(true);
        }

        return view('admin.orders.index', $data);
    }

    public function create()
    {
        $data['menu'] = "Orders";
        $data['products'] = Products::where('status','active')->pluck('name','id')->prepend('Please Select','');
        $data['users'] = User::where('status','active')->where('role','customer')->pluck('name','id')->prepend('Please Select','');
        return view("admin.orders.create",$data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'customer_id' => 'required',
            'order_date' => 'required',
            'order_time' => 'required',
            'status' => 'required',
            'address' => 'required',
        ]);

        $input = $request->all();
        $userDetails = User::with('Orders')->where('id',$input['customer_id'])->first();
        $input['customer_name'] = $userDetails['name'];
        $input['customer_email'] = $userDetails['email'];
        $input['customer_phone'] = $userDetails['phone'];
        $input['payment_type'] = 'cod';

        $totalOrder = count($userDetails['Orders']) > 0 ? count($userDetails['Orders']) + 1 : 1;
        $input['unique_id'] = strtoupper(substr($userDetails['name'],0,3)).'0'.$totalOrder;
        $order = Orders::create($input);

        $orderTotal = 0;
        $orderItems = [];
        if(!empty($input['product_id'])){
            foreach($input['product_id'] as $key => $value){
                $product = Products::where('id',$value)->where('status','active')->first();

                $orderItems['order_id'] = $order['id'];
                $orderItems['product_id'] = $value;
                $orderItems['sku'] = $product['sku'];
                $orderItems['name'] = $product['name'];
                $orderItems['quantity'] = $input['quantity'][$key];
                $orderItems['price'] = $input['price'][$key];
                $orderItems['total'] = ($input['price'][$key]*$input['quantity'][$key]);

                $orderTotal = ($orderTotal+($input['price'][$key]*$input['quantity'][$key]));

                OrderItems::create($orderItems);
            }
        }

        $order_total['order_total'] = $orderTotal;
        Orders::updateOrCreate(['id' => $order['id']], $order_total);

        $mail_status = $this->sendCustomerMail($order, 'status', Auth::user());

        \Session::flash('success', 'Order has been inserted successfully!');
        return redirect()->route('orders.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data['menu'] = "Orders";
        $data['order'] = Orders::with('OrderItems')->findorFail($id);
        $data['products'] = Products::where('status','active')->pluck('name','id')->prepend('Please Select','');
        $data['users'] = User::where('status','active')->where('role','customer')->pluck('name','id')->prepend('Please Select','');
        return view("admin.orders.edit",$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'customer_id' => 'required',
            'order_date' => 'required',
            'order_time' => 'required',
            'status' => 'required',
            'address' => 'required',
        ]);

        $input = $request->all();
        $userDetails = User::with('Orders')->where('id',$input['customer_id'])->first();
        $input['customer_name'] = $userDetails['name'];
        $input['customer_email'] = $userDetails['email'];
        $input['customer_phone'] = $userDetails['phone'];
        $input['payment_type'] = 'cod';
        $totalOrder = count($userDetails['Orders']) > 0 ? count($userDetails['Orders'])+1 : 1;
        $input['unique_id'] = strtoupper(substr($userDetails['name'],0,3)).'0'.$totalOrder;

        $order = Orders::findorFail($id);
        $order->update($input);

        OrderItems::where('order_id', $id)->delete();

        $orderTotal = 0;
        $orderItems = [];
        if(!empty($input['product_id'])){
            foreach($input['product_id'] as $key => $value){
                $product = Products::where('id',$value)->where('status','active')->first();

                $orderItems['order_id'] = $order['id'];
                $orderItems['product_id'] = $value;
                $orderItems['sku'] = $product['sku'];
                $orderItems['name'] = $product['name'];
                $orderItems['quantity'] = $input['quantity'][$key];
                $orderItems['price'] = $input['price'][$key];
                $orderItems['total'] = ($input['price'][$key]*$input['quantity'][$key]);

                $orderTotal = ($orderTotal+($input['price'][$key]*$input['quantity'][$key]));

                OrderItems::create($orderItems);
            }
        }

        $order_total['order_total'] = $orderTotal;
        Orders::updateOrCreate(['id' => $order['id']], $order_total);

        $mail_status = $this->sendCustomerMail($order, 'status', Auth::user());

        \Session::flash('success', 'Order has been updated successfully!');
        return redirect()->route('orders.index');

    }

    public function destroy($id)
    {
        $order = Orders::findOrFail($id);
        if(!empty($order)){
            OrderItems::where('order_id', $id)->delete();
            $order->delete();
            return 1;
        }else{
            return 0;
        }
    }

    public function getProductPrice(Request $request){
        $product = Products::where('id',$request['product_id'])->where('status','active')->first();
        return number_format($product['price'], 2, '.', '');
    }

    public function exportOrder(){
        $fileName = 'order'.time().'.csv';
        $orders = Orders::with('OrderItems')->select('id','customer_name','customer_email','customer_phone','address',
            'order_date','order_time','short_notes','payment_type','order_total','status')->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array(
            '#',
            'Customer Name',
            'Customer Email',
            'Customer Phone',
            'Customer Address',
            'Order Date',
            'Order Time',
            'Short Notes',
            'Payment Type',
            'Order Total',
            'Order Status',
        );

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $row['#'] = $order['id'];
                $row['Customer Name'] = $order['customer_name'];
                $row['Customer Email'] = $order['customer_email'];
                $row['Customer Phone'] = $order['customer_phone'];
                $row['Customer Address'] = $order['address'];
                $row['Order Date'] = $order['order_date'];
                $row['Order Time'] = $order['order_time'];
                $row['Short Notes'] = $order['short_notes'];
                $row['Payment Type'] = $order['payment_type'];
                $row['Order Total'] = $order['order_total'];
                $row['Order Status'] = $order['status'];

                fputcsv($file, array($row['#'],$row['Customer Name'],$row['Customer Email'],$row['Customer Phone'],$row['Customer Address'],$row['Order Date'],$row['Order Time'],$row['Short Notes'],$row['Payment Type'],$row['Order Total'],$row['Order Status']));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function invoicePrint(Request $request, $id)
    {
        $orderData = Orders::with('OrderItems')->findorFail($id);
        $settingsData = Setting::findorFail(1);
        $invoice['logo'] = $settingsData['image'];
        //return view('admin.orders.invoice', compact('invoice', 'orderData'));


        /*\Mail::send('admin/mailtemplates/order_email',
        array(
            'name' => $orderData['customer_name'],
            'email' => $orderData['customer_email'],
        ), function($message) use ($request)
        {
            $message->from('cakshop@ysabelles.ph');
            $message->to($orderData['customer_email']);
            $message->subject("Your order has been placed!");
            $message->attach(url('storage/'.$orderData['unique_id'].'-INVOICE.pdf'));
        });*/

        $pdf = PDF::loadView('admin.orders.invoice', compact('invoice', 'orderData'));
        return $pdf->download($orderData['unique_id'].'-INVOICE.pdf');
    }

    public function statusUpdate(Request $request){
        $order = Orders::where('id',$request['id'])->first();
        if(!empty($order)){
            $input['status'] = $request['status'];
            $order->update($input);
            $mail_status = $this->sendCustomerMail($order, 'status', Auth::user());
            return 1;
        }else{
            return 0;
        }
    }
}
