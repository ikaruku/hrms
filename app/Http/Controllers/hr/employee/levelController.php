<?php
 
namespace App\Http\Controllers\hr\employee;
 
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
 
class levelController extends Controller
{
    // METHOD level
	public function index()
	{
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
		$levellist = DB::table('hr_leveltable')->get();
		return view('hr/employee/level',compact('levellist','menus'));
	}
    public function add(Request $request)
    {
        DB::table('hr_leveltable')->insert([
            'id' => $request->frm_id,
            'levelname' => $request->frm_levelname,
        ]);
        return redirect('/hr/employee/level');
    }
	public function update(Request $request)
    {
        DB::table('hr_leveltable')->where('id',$request->frm_id)->update([
            'levelname' => $request->edit_levelname,
        ]);
        return redirect('/hr/employee/level');
    }
	public function delete($id)
    {
        DB::table('hr_leveltable')->where('id',$id)->delete();
        return redirect('/hr/employee/level');
    }
}