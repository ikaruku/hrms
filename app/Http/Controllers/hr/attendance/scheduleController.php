<?php

namespace App\Http\Controllers\hr\attendance;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class scheduleController extends Controller
{
    // METHOD level
    public function index()
    {
        $menus = DB::table('syspermission')->where('userid', Auth::user()->id)->get()->sortBy('menuname');
        $scheduleList = DB::table('hr_attendanceschedule')->get();
        return view('hr/attendance/schedule', compact('scheduleList', 'menus'));
    }

    public function add(Request $request)
    {
        DB::table('hr_attendanceschedule')->insert([
            'schedulename' => $request->frm_schedulename,
            'normalin1' => $request->frm_normalin1,
            'normalout1' => $request->frm_normalout1,
        ]);
        return redirect('/hr/attendance/schedule');
    }

    public function update(Request $request)
    {
        DB::table('hr_attendanceschedule')->where('scheduleid', $request->frme_schid)->update([
            'schedulename' => $request->frme_schedulename,
            'normalin1' => $request->frme_normalin1,
            'normalout1' => $request->frme_normalout1,
        ]);
        return redirect('/hr/attendance/schedule');
    }

    public function delete($id)
    {
        DB::table('hr_attendanceschedule')->where('scheduleid', $id)->delete();
        return redirect('/hr/attendance/schedule');
    }
}