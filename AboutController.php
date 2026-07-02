<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use App\Models\About;
use App\Models\Projects;
use Auth;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
class AboutController extends Controller
{
    public function index()
    {
        $aboutus =  About::all();

        return view('frontend.pages.about',compact('aboutus')
        );
    }
    public function edit($id)
    {
   $about =    About::find($id);
       return view('backend.dashboard.admin_dashboard.about.update_about' ,compact('about'));
    }

    public function view()
    {
        $about =  About::all();

        return view('backend.dashboard.admin_dashboard.about.about' ,compact('about'));
    }
    public function store(Request $request)

    {
        $request->validate([
            'title' => 'required|',
            'detail' => 'required|',
         'image' => 'required|image|',
        ]);

        $about = new About();
        $about->title  = $request->title;
        $about->detail  = $request->detail;

        if($request->hasFile("image")){

            $dt       = Carbon::now();
            $file =  $request->file('image');
            $name = $dt->format('d-m-y-H-i-s').'-about-'.$file->getClientOriginalExtension();
            $file->move("images/about" , $name );
            $about->image = $name;
         }
         $about->user_id  = Auth::id();
        $done = $about->save();
        Toastr::success('data add successfully :)','Success');
        return redirect()->route('admin.about');

    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|',
            'detail' => 'required|',
            'image' => 'required|image'
        ]);
        $about =    About::find($id);
        $about->title  = $request->title;
        $about->detail  = $request->detail;

        if($request->hasFile("image")){
            $dt       = Carbon::now();
            $file =  $request->file('image');
            $name = $dt->format('d-m-y-H-i-s').'-about-'.$file->getClientOriginalExtension();
            $file->move("images/about" , $name );
            $about->image = $name;
         }
        $about->user_id  = Auth::id();
        $done = $about->save();
        DB::commit();
        Toastr::success('about deleted successfully :)','Success');
        return redirect()->route('admin.about');
}
    public function destroy(Request $request)
    {
        if($request->id){
            About::destroy($request->id);
        }
        DB::commit();
        Toastr::success('about deleted successfully :)','Success');
        return redirect()->route('admin.about');
    }
}
