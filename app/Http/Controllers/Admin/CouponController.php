<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupons;
use Illuminate\Http\Request;
use DataTables;

class CouponController extends Controller
{
    public function __construct(Request $request){
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data['menu'] = "Coupons";
        $data['search'] = $request['search'];

        if ($request->ajax()) {
            $data = Coupons::select();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $statusBtn = '';
                    if ($row->status == 1) {
                        $statusBtn .= '<div class="btn-group-horizontal" id="assign_remove_"'.$row->id.'">
                        <button class="btn btn-success unassign ladda-button" data-style="slide-left" id="remove" url="'.route('coupons.unassign').'" ruid="'.$row->id.'"  type="button" style="height:28px; padding:0 12px"><span class="ladda-label">Active</span> </button>
                                                </div>';
                        $statusBtn .= '<div class="btn-group-horizontal" id="assign_add_"'.$row->id.'"  style="display: none"  >
                                                    <button class="btn btn-danger assign ladda-button" data-style="slide-left" id="assign" uid="'.$row->id.'" url="'.route('coupons.assign').'" type="button"  style="height:28px; padding:0 12px"><span class="ladda-label">In Active</span></button>
                                                </div>';
                        //$statusBtn = 'Active';
                    } else {
                        $statusBtn .= '<div class="btn-group-horizontal" id="assign_add_"'.$row->id.'">
                                                    <button class="btn btn-danger assign ladda-button" id="assign" data-style="slide-left" uid="'.$row->id.'" url="'.route('coupons.assign').'"  type="button" style="height:28px; padding:0 12px"><span class="ladda-label">In Active</span></button>
                                                </div>';
                        $statusBtn .= '<div class="btn-group-horizontal" id="assign_remove_"'.$row->id.'" style="display: none" >
                                                    <button class="btn  btn-success unassign ladda-button" id="remove" ruid="'.$row->id.'" data-style="slide-left" url="'.route('coupons.unassign').'" type="button" style="height:28px; padding:0 12px"><span class="ladda-label">Active</span></button>
                                                </div>';
                        //$statusBtn = 'Inactive';
                    }
                    return $statusBtn;
                })
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group btn-group-sm"><a href="'.route('coupons.edit',['coupon'=>$row->id]).'"><button class="btn btn-sm btn-info tip" data-toggle="tooltip" title="Edit Coupon" data-trigger="hover" type="submit" ><i class="fa fa-edit"></i></button></a></div>';
                    $btn .= '<span data-toggle="tooltip" title="Delete Category" data-trigger="hover">
                                    <button class="btn btn-sm btn-danger deleteCoupon" data-id="'.$row->id.'" type="button"><i class="fa fa-trash"></i></button>
                                </span>';
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('admin.coupons.index', $data);
    }

    public function create()
    {
        $data['menu'] = "Coupons";
        $random_number = sprintf('%06d', rand(1, 1000000));
        $cupon_code = 'YSA';
        $cupon_code .= $random_number;
        $data['coupon_code'] = $cupon_code;
        return view("admin.coupons.create",$data);
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'coupon_name' => 'required',
            'expire_date' => 'required',
            'amount' => 'required|numeric',
            'status' => 'required',
        ]);

        $input = $request->all();
        $input['status'] = $input['status'] == 'active' ? 1 : 2;
        Coupons::create($input);

        \Session::flash('success', 'Coupons has been inserted successfully!');
        return redirect()->route('coupons.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $data['menu'] = "Coupons";
        $data['coupons'] = Coupons::findorFail($id);
//        dd($data['coupons']->coupon_code);
        $data['coupon_code'] = $data['coupons']->coupon_code;
//        $data['status'] = $data['status'] == 1 ? 'active' : 'inactive';
        return view('admin.coupons.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);

        $input = $request->all();
        $stock = Category::findorFail($id);
        $stock->update($input);
        \Session::flash('success','Category has been updated successfully!');
        return redirect()->route('category.index');
    }

    public function destroy($id)
    {
        $category = Coupons::findOrFail($id);
        if(!empty($category)){
            $category->delete();
            return 1;
        }else{
            return 0;
        }
    }

    public function assign(Request $request)
    {
        $category = Coupons::findorFail($request['id']);
        $category['status'] = 1;
        $category->update($request->all());
    }

    public function unassign(Request $request)
    {
        $category = Coupons::findorFail($request['id']);
        $category['status'] = 2;
        $category->update($request->all());
    }
}
