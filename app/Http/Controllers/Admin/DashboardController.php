<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Products;
use App\Models\Orders;
use DB;
use DataTables;

class DashboardController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $data['menu'] = 'Dashboard';
        $data['users'] = User::where('role', '!=', 'admin')->count();
        $data['categories'] = Category::count();
        $data['products'] = Products::count();
        $data['orders'] = Orders::count();

        $currentYear = date('Y'); // Get the current year

        $records = Orders::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
            ->get();

        /*$records = Orders::selectRaw('DATE_FORMAT(created_at, "%m") as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();*/

        $data['revenueArray'] = array("01" => 0, "02" => 0, "03" => 0, "04" => 0, "05" => 0, "06" => 0, "07" => 0, "08" => 0, "09" => 0, "10" => 0, "11" => 0, "12" => 0);

        if(!empty($records)){
            foreach ($records as $key => $value) {
                $data['revenueArray'][str_pad($value['month'], 2, '0', STR_PAD_LEFT)] = $value['count'];
            }
        }

        return view('admin.dashboard',$data);
    }

    public function contactUs(Request $request){
        $data['menu'] = "Contact Us";
        if ($request->ajax()) {
            $data = ContactUs::select();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('message', function($row){
                    $btn = '<span data-toggle="tooltip" title="Show Message" data-trigger="hover">
                                    <button class="btn btn-sm btn-info showMsg" data-id="'.$row->id.'" type="button"><i class="fa fa-comment"></i></button>
                                </span>';
                    return $btn;
                })
                ->addColumn('action', function($row){
                    $btn = '<span data-toggle="tooltip" title="Delete ContactUs" data-trigger="hover">
                                <button class="btn btn-sm btn-danger showMsg" data-id="'.$row->id.'" type="button"><i class="fa fa-trash"></i></button>
                            </span>';
                    return $btn;
                })
                ->rawColumns(['message','action'])
                ->make(true);
        }
        return view('admin.contactUs', $data);
    }

    public function contactUs_destroy($id){
        $contact = ContactUs::findOrFail($id);
        if(!empty($contact)){
            $contact->delete();
            return 1;
        }else{
            return 0;
        }
    }

    public function contactUsMsg(Request $request){
        $contact = ContactUs::where('id',$request['id'])->first();
        $data = [];
        if(!empty($contact)){
            $data['name'] = $contact['name'];
            $data['msg'] = $contact['message'];
        }
        return $data;
    }
}
