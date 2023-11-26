<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductImages;
use App\Models\Products;
use Illuminate\Http\Request;
use DataTables;

class ProductController extends Controller
{
    public function __construct(Request $request){
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data['menu'] = "Products";
        $data['search'] = $request['search'];

        if ($request->ajax()) {
            $data = Products::select();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function($row){
                    return $row['Category']['name'];
                })
                ->addColumn('price', function($row){
                    return '₱'.number_format($row['price'], 2, '.', '');
                })
                ->addColumn('status', function($row){
                    $statusBtn = '';
                    if ($row->status == "active") {
                        $statusBtn .= '<div class="btn-group-horizontal" id="assign_remove_"'.$row->id.'">
                        <button class="btn btn-success unassign ladda-button" data-style="slide-left" id="remove" url="'.route('products.unassign').'" ruid="'.$row->id.'"  type="button" style="height:28px; padding:0 12px"><span class="ladda-label">Active</span> </button>
                                                </div>';
                        $statusBtn .= '<div class="btn-group-horizontal" id="assign_add_"'.$row->id.'"  style="display: none"  >
                                                    <button class="btn btn-danger assign ladda-button" data-style="slide-left" id="assign" uid="'.$row->id.'" url="'.route('products.assign').'" type="button"  style="height:28px; padding:0 12px"><span class="ladda-label">In Active</span></button>
                                                </div>';
                    } else {
                        $statusBtn .= '<div class="btn-group-horizontal" id="assign_add_"'.$row->id.'">
                                                    <button class="btn btn-danger assign ladda-button" id="assign" data-style="slide-left" uid="'.$row->id.'" url="'.route('products.assign').'"  type="button" style="height:28px; padding:0 12px"><span class="ladda-label">In Active</span></button>
                                                </div>';
                        $statusBtn .= '<div class="btn-group-horizontal" id="assign_remove_"'.$row->id.'" style="display: none" >
                                                    <button class="btn  btn-success unassign ladda-button" id="remove" ruid="'.$row->id.'" data-style="slide-left" url="'.route('products.unassign').'" type="button" style="height:28px; padding:0 12px"><span class="ladda-label">Active</span></button>
                                                </div>';
                    }
                    return $statusBtn;
                })
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group btn-group-sm"><a href="'.route('products.edit',['product'=>$row->id]).'"><button class="btn btn-sm btn-info tip" data-toggle="tooltip" title="Edit Stock" data-trigger="hover" type="submit" ><i class="fa fa-edit"></i></button></a></div>';
                    $btn .= '<span data-toggle="tooltip" title="Delete Stock" data-trigger="hover">
                                    <button class="btn btn-sm btn-danger deleteProduct" data-id="'.$row->id.'" type="button"><i class="fa fa-trash"></i></button>
                                </span>';
                    return $btn;
                })
                ->rawColumns(['category','price','status','action'])
                ->make(true);
        }

        return view('admin.products.index', $data);
    }

    public function create()
    {
        $data['menu'] = "Products";
        $data['category'] = Category::where('status','active')->pluck('name','id')->prepend('Please Select','');
        return view("admin.products.create",$data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'category' => 'required',
            'name' => 'required',
            'images' => 'required',
            'price' => 'required',
            'status' => 'required',
        ]);

        $input = $request->all();
        $allPros = Products::count();
        $totalCount = $allPros > 0 ? $allPros + 1 : 1;
        $input['sku'] =  substr($input['name'],0,4).$totalCount;
        $product = Products::create($input);
        if(!empty($request['images'])) {
            foreach ($request->file('images') as $imagefile) {
                $in['product_id'] = $product['id'];
                $in['image'] = $this->image($imagefile, 'products');
                ProductImages::create($in);
            }
        }

        \Session::flash('success', 'Product has been inserted successfully!');
        return redirect()->route('products.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $data['menu'] = "Products";
        $data['product'] = Products::with('ProductImages')->findorFail($id);
        $data['category'] = Category::where('status','active')->pluck('name','id')->prepend('Please Select','');
        return view('admin.products.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'category' => 'required',
            'name' => 'required',
            'price' => 'required',
            'status' => 'required',
        ]);

        $input = $request->all();
        $product = Products::findorFail($id);
        $product->update($input);

        if(!empty($request['images'])) {
            foreach ($request->file('images') as $imagefile) {
                $in['product_id'] = $product['id'];
                $in['image'] = $this->image($imagefile, 'products');
                ProductImages::create($in);
            }
        }

        \Session::flash('success','Product has been updated successfully!');
        return redirect()->route('products.index');
    }

    public function destroy($id)
    {
        $products = Products::findOrFail($id);
        if(!empty($products)){
            $products->delete();
            return 1;
        }else{
            return 0;
        }
    }

    public function assign(Request $request)
    {
        $product = Products::findorFail($request['id']);
        $product['status'] = "active";
        $product->update($request->all());
    }

    public function unassign(Request $request)
    {
        $product = Products::findorFail($request['id']);
        $product['status'] = "inactive";
        $product->update($request->all());
    }

    public function deleteProductImg(Request $request){
        $proImage = ProductImages::where('id',$request['imgId'])->first();
        if(!empty($proImage)){
            $file_path=storage_path('app/public/'.$proImage->image);
            if (!empty($proImage->image) && file_exists($file_path)) {
                unlink($file_path);
            }
            $proImage->delete();
            return 1;
        }else{
            return 0;
        }
    }
}
