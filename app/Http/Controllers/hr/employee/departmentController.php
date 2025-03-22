<?php
 
namespace App\Http\Controllers\hr\employee;
 
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
 
class departmentController extends Controller
{
    // METHOD DEPARTMENT
	public function index()
	{
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
		$deptlist = DB::table('hr_depttable')->get();
		return view('hr/employee/department',compact('deptlist','menus'));
	}
    public function add(Request $request)
    {
        DB::table('hr_depttable')->insert([
            'deptname' => $request->frm_deptname,
        ]);
        return redirect('/hr/employee/department');
    }
	public function update(Request $request)
    {
        DB::table('hr_depttable')->where('deptid',$request->deptid)->update([
            'deptname' => $request->edit_deptname,
        ]);
        return redirect('/hr/employee/department');
    }
	public function delete($deptid)
    {
        DB::table('hr_depttable')->where('deptid',$deptid)->delete();
        return redirect('/hr/employee/department');
    }
}