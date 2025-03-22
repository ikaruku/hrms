<?php
 
namespace App\Http\Controllers\hr\attendance;
 
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
 
class attendanceController extends Controller
{
	public function index()
	{
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
        $generated = DB::table('hr_attendancegenerated')->where('year', '=', date('Y'))->get();
		return view('hr/attendance/home',compact('menus','generated'));
	}
}