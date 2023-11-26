<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DataTables;

class SettingController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $data['menu']="Settings";
        $data['settings'] = Setting::findorFail(1);
        return view('admin.settings.edit',$data);
    }

    public function update(Request $request, $id){
        $this->validate($request, [
            'name' => 'required',
            'image' => 'mimes:jpeg,jpg,bmp,png',
            'gcash_mobile' => 'required',
            'gcash_screenshot_mobile' => 'required',
        ]);

        $input = $request->all();
        $settings = Setting::findorFail($id);

        if($photo = $request->file('image')){
            $file_path=storage_path('app/public/'.$settings->image);
            if (!empty($settings->image) && file_exists($file_path)) {
                unlink($file_path);
            }
            $input['image'] = $this->image($photo,'settings');
        }

        $settings->update($input);

        \Session::flash('success','Setting has been updated successfully!');
        return redirect()->route('settings.index');
    }
}
