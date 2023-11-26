<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use DataTables;

class CategoryController extends Controller
{
    public function __construct(Request $request){
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data['menu'] = "Category";
        $data['search'] = $request['search'];

        if ($request->ajax()) {
            $data = Category::select();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $statusBtn = '';
                    if ($row->status == "active") {
                        $statusBtn .= '<div class="btn-group-horizontal" id="assign_remove_"'.$row->id.'">
                        <button class="btn btn-success unassign ladda-button" data-style="slide-left" id="remove" url="'.route('category.unassign').'" ruid="'.$row->id.'"  type="button" style="height:28px; padding:0 12px"><span class="ladda-label">Active</span> </button>
                                                </div>';
                        $statusBtn .= '<div class="btn-group-horizontal" id="assign_add_"'.$row->id.'"  style="display: none"  >
                                                    <button class="btn btn-danger assign ladda-button" data-style="slide-left" id="assign" uid="'.$row->id.'" url="'.route('category.assign').'" type="button"  style="height:28px; padding:0 12px"><span class="ladda-label">In Active</span></button>
                                                </div>';
                        //$statusBtn = 'Active';
                    } else {
                        $statusBtn .= '<div class="btn-group-horizontal" id="assign_add_"'.$row->id.'">
                                                    <button class="btn btn-danger assign ladda-button" id="assign" data-style="slide-left" uid="'.$row->id.'" url="'.route('category.assign').'"  type="button" style="height:28px; padding:0 12px"><span class="ladda-label">In Active</span></button>
                                                </div>';
                        $statusBtn .= '<div class="btn-group-horizontal" id="assign_remove_"'.$row->id.'" style="display: none" >
                                                    <button class="btn  btn-success unassign ladda-button" id="remove" ruid="'.$row->id.'" data-style="slide-left" url="'.route('category.unassign').'" type="button" style="height:28px; padding:0 12px"><span class="ladda-label">Active</span></button>
                                                </div>';
                        //$statusBtn = 'Inactive';
                    }
                    return $statusBtn;
                })
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group btn-group-sm"><a href="'.route('category.edit',['category'=>$row->id]).'"><button class="btn btn-sm btn-info tip" data-toggle="tooltip" title="Edit Category" data-trigger="hover" type="submit" ><i class="fa fa-edit"></i></button></a></div>';
                    $btn .= '<span data-toggle="tooltip" title="Delete Category" data-trigger="hover">
                                    <button class="btn btn-sm btn-danger deleteCategory" data-id="'.$row->id.'" type="button"><i class="fa fa-trash"></i></button>
                                </span>';
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('admin.category.index', $data);
    }

    public function create()
    {
        $data['menu'] = "Category";
        return view("admin.category.create",$data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);

        $input = $request->all();
        Category::create($input);

        \Session::flash('success', 'Category has been inserted successfully!');
        return redirect()->route('category.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $data['menu'] = "Category";
        $data['category'] = Category::findorFail($id);
        return view('admin.category.edit',$data);
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
        $category = Category::findOrFail($id);
        if(!empty($category)){
            $category->delete();
            return 1;
        }else{
            return 0;
        }
    }

    public function assign(Request $request)
    {
        $category = Category::findorFail($request['id']);
        $category['status'] = "active";
        $category->update($request->all());
    }

    public function unassign(Request $request)
    {
        $category = Category::findorFail($request['id']);
        $category['status'] = "inactive";
        $category->update($request->all());
    }
}
