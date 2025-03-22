<?php
 
namespace App\Http\Controllers\hr\employee;
 
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class positionController extends Controller
{
    // METHOD DEPARTMENT
	public function index()
	{
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
		$poslist = DB::table('hr_positiontable')->get();
		return view('hr/employee/position',compact('poslist','menus'));
	}
    public function add(Request $request)
    {
        DB::table('hr_positiontable')->insert([
            'posname' => $request->frm_posname,
        ]);
        return redirect('/hr/employee/position');
    }
	public function update(Request $request)
    {
        DB::table('hr_positiontable')->where('posid',$request->posid)->update([
            'posname' => $request->edit_posname,
        ]);
        return redirect('/hr/employee/position');
    }
	public function delete($posid)
    {
        DB::table('hr_positiontable')->where('posid',$posid)->delete();
        return redirect('/hr/employee/position');
    }
}