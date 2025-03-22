<?php

namespace App\Http\Controllers\sysadmin;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;;

class MenuController extends Controller
{
    public function index()
	{
    	// get data
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
		$menulist = DB::table('sysmenu')->get()->sortBy('name');
		$parentlist = DB::table('sysmenu')->where('parentid',null)->get();
		$parentlistedit = DB::table('sysmenu')->where('parentid',null)->get();
    	// sent to view
		return view('systemadministration/menus',compact('menulist','menus','parentlistedit','parentlist'));
	}
    // method to add
    public function add(Request $request)
    {
        // insert data to table
        DB::table('sysmenu')->insert([
            'name' => $request->frm_name,
            'url' => $request->frm_url,
            'icon' => $request->frm_icon,
            'parentid' => $request->frm_parent
        ]);
        // redirect to home
        return redirect('/sysadmin/menumanager');
    }
    // method to delete
    public function delete($menuid)
    {
        // delete data
        DB::table('sysmenu')->where('menuid',$menuid)->delete();
            
        // redirect to home
        return redirect('/sysadmin/menumanager');
    }
    // method to update
    public function update(Request $request)
    {
        // update data
        DB::table('sysmenu')->where('menuid',$request->menuid)->update([
            'name' => $request->frme_name,
            'url' => $request->frme_url,
            'icon' => $request->frme_icon,
            'parentid' => $request->frme_parent
        ]);
        // redirect to home
        return redirect('/sysadmin/menumanager');
    }
}
