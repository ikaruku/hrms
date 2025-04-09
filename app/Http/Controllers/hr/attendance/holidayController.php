<?php

namespace App\Http\Controllers\hr\attendance;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class holidayController extends Controller
{
    // METHOD level
    public function index()
    {
        $menus = DB::table('syspermission')->where('userid', Auth::user()->id)->get()->sortBy('menuname');
        $holidayList = DB::table('hr_holiday')->get();
        return view('hr/attendance/holiday', compact('holidayList', 'menus'));
    }
    public function add(Request $request)
    {
        DB::table('hr_holiday')->insert([
            'holidaydate' => $request->frm_holidaydate,
            'holidayname' => $request->frm_holidayname,
        ]);
        return redirect('/hr/attendance/holiday');
    }
    public function update(Request $request)
    {
        DB::table('hr_holiday')->where('id', $request->frme_id)->update([
            'holidaydate' => $request->frme_holidaydate,
            'holidayname' => $request->frme_holidayname,
        ]);
        return redirect('/hr/attendance/holiday');
    }
    public function delete($id)
    {
        DB::table('hr_holiday')->where('id', $id)->delete();
        return redirect('/hr/attendance/holiday');
    }
}