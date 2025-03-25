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

        $enroll = DB::table('hr_empltable')->where('status', 'Active')->orWhere('leavedate', '>=', today())->count();
        $present = DB::table('hr_attendancerecord')->where('attdate', today())->where('attstatus', ['N', 'HD'])->count();
        $absent = DB::table('hr_attendancerecord')->where('attdate', today())->where('attstatus', 'A')->count();
        $late = DB::table('hr_attendancerecord')->where('attdate', today())->where('attstatus', 'Late')->count();
		
		$generated = DB::table('hr_attendancegenerated')->where('year', '=', date('Y'))->get();

		return view('hr/attendance/home',compact('menus','generated','enroll','present','absent','late'));
	}
}