<?php

namespace App\Http\Controllers\sysadmin;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index()
    {
        $menus = DB::table('syspermission')->where('userid', Auth::user()->id)->get()->sortBy('menuname');
        $permissionlist = DB::table('syspermission')->get();
        $menulist = DB::table('sysmenu')->get()->sortBy('name');
        $userlist = DB::table('users')->get();

        // Kirim data ke view
        return view('systemadministration/permissions', compact('menus', 'menulist', 'userlist', 'permissionlist'));
    }

    public function permdetail($id)
    {
        $menus = DB::table('syspermission')->where('userid', Auth::user()->id)->get()->sortBy('menuname');
        $menulist = DB::table('sysmenu')->get()->sortBy('name');
        $selectedMenus = DB::table('syspermission')->where('userid', $id)->pluck('menuid')->toArray();

        // Kirim data ke view
        return view('systemadministration/permdetails', compact('menus', 'menulist', 'selectedMenus'));
    }

    // Fungsi untuk menyimpan perubahan permission
    public function savePermission(Request $request)
    {
        $userId = $request->user_id; // ID user yang sedang diedit
        $menus = $request->menus; // Menu yang dipilih
        $userdb = DB::table('users')->where('id', $userId)->first();

        // Hapus semua permission yang ada untuk user ini
        DB::table('syspermission')->where('userid', $userId)->delete();

        // Tambahkan permission yang baru
        if (!empty($menus)) {
            foreach ($menus as $menuId) {
                $menudb = DB::table('sysmenu')->where('menuid', $menuId)->first();

                DB::table('syspermission')->insert([
                    'userid' => $userId,
                    'username' => $userdb->name,
                    'menuid' => $menuId,
                    'menuname' => $menudb->name,
                    'url' => $menudb->url,
                    'icon' => $menudb->icon,
                    'parentid' => $menudb->parentid,
                ]);
            }
        }

        return redirect('/sysadmin/userpermission');
    }
}
