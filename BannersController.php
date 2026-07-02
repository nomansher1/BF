<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Banners;
use Auth;
use DB;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
class BannersController extends Controller
{
    public function index()
    {

    $banners  =  Banners::all();
        return view('backend.dashboard.admin_dashboard.banner.banner', compact('banners'));
    }

    public function edit($id)
    {
    $banners =    Banners::find($id);
       return view('backend.dashboard.admin_dashboard.banner.update_banner',compact('banners'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|',
            'detail' => 'required|',
         'image' => 'required|image|',
        ]);

        $banner = new Banners();
        $banner->title  = $request->title;
        $banner->detail  = $request->detail;

        if($request->hasFile("image")){
            $dt       = Carbon::now();
            $file =  $request->file('image');
            $name = $dt->format('d-m-y-H-i-s').'-slider-'.$file->getClientOriginalExtension();
            $file->move("images/sliders" , $name );
            $banner->image = $name;
         }
         $banner->user_id  = Auth::id();
        $done = $banner->save();
        Toastr::success('data add successfully :)','Success');
        return redirect()->route('admin.slider');

    }


    public function update(Request $request, $id)
    {
        $validatedData=$request->validate([
            'title' => 'required|',
            'detail' => 'required|',
            'image' => 'required|image'
        ]);
        $banner =    Banners::find($id);
        $banner->title  = $request->title;
        $banner->detail  = $request->detail;

        if ($request->hasFile('image')) {
            $dt       = Carbon::now();
            $file =  $request->file('image');
            $name = $dt->format('d-m-y-H-i-s').'-slider-'.$file->getClientOriginalExtension();
            $file->move("images/sliders" , $name );
            $banner->image = $name;
        }

        $banner->update();
        Toastr::success('Banner updated successfully :)','Success');
        return redirect()->route('admin.slider');
}
    public function destroy(Request $request)
    {
        if($request->id){
            Banners::destroy($request->id);
        }
        DB::commit();
        Toastr::success('Banner deleted successfully :)','Success');
        return redirect()->route('admin.slider');
    }
}
