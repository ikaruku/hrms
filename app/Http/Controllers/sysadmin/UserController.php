<?php

namespace App\Http\Controllers\sysadmin;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
	{
    	// get data
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
		$userlist = DB::table('users')->get();
    	// sent to view
		return view('systemadministration/users',compact('userlist','menus'));
	}
    // method to add
    public function add(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Menyimpan user baru
        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Redirect ke halaman yang sesuai setelah berhasil menambah user
        return redirect('/sysadmin/usermanager');
    }
    // method to delete
    public function delete($id)
    {
        // delete data
        DB::table('users')->where('id',$id)->delete();
        // redirect to home
        return redirect('/sysadmin/usermanager');
    }
    // method to update
    public function update(Request $request)
    {
        // update data
        DB::table('users')->where('id',$request->id)->update([
            'password' => Hash::make($request->frme_password),
        ]);

        // redirect to home
        return redirect('/sysadmin/usermanager');
    }
}
