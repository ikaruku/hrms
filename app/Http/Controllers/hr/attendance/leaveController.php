<?php
 
namespace App\Http\Controllers\hr\attendance;
 
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
 
 
class leaveController extends Controller
{
	public function index()
	{
        $menus = DB::table('syspermission')->where('userid',Auth::user()->id)->get()->sortBy('menuname');
		$leavelist = DB::table('hr_leavereq')->get();
		$empllist = DB::table('hr_empltable')->where('status','Active')->orderBy('emplname')->get();
		return view('hr/attendance/leave',compact('leavelist','menus','empllist'));
	}

    public function add(Request $request)
    {
        $empllist = DB::table('hr_empltable')->where('emplid',$request->frm_emplid)->first();
        DB::table('hr_leave')->insert([
            'emplid' => $request->frm_emplid,
            'emplname' => $empllist->emplname,
            'leavedate' => $request->frm_leavedate,
            'leavetype' => $request->frm_leavetype,
            'notes' => $request->frm_notes,
        ]);
        return redirect('/hr/attendance/leave');
    }

    public function addleave(Request $request)
    {
        $timein = Carbon::parse($request->frm_fromdate);

        // Ambil semua tanggal libur dari tabel 'holidays' sebagai array
        $holidays = DB::table('hr_holiday')->pluck('holidaydate')->toArray(); // Mengambil tanggal libur
        $emplname = DB::table('hr_empltable')->where('emplid',$request->frm_emplid)->first();

        // Touch record untuk membuat Leave ID
        $newleaveid = $this->generateCode();

        $selisih = 0;
        // Melakukan iterasi dari tanggal awal hingga tanggal akhir
        while ($timein->lte($request->frm_todate)) {
            // Jika hari bukan Minggu (Sunday) dan tanggal bukan libur, tambahkan 1 ke selisih
            if (!$timein->isSunday() && !in_array($timein->toDateString(), $holidays)) {
                $selisih++;

                // Menambahkan record ke tabel 'hr_leave' untuk setiap tanggal yang valid
                DB::table('hr_leave')->insert([
                    'leaveid' => $newleaveid,
                    'emplid' => $request->frm_emplid,
                    'emplname' => $emplname->emplname,
                    'leavedate' => $timein->toDateString(),
                    'leavetype' => $request->frm_leavetype,
                    'notes' => $request->frm_notes,
                ]);
            }
            // Pindah ke hari berikutnya
            $timein->addDay();
        }
        
        DB::table('hr_leavereq')->insert([
            'leaveid' => $newleaveid,
            'emplid' => $request->frm_emplid,
            'emplname' => $emplname->emplname,
            'fromdate' => $request->frm_fromdate,
            'todate' => $request->frm_todate,
            'days' => $selisih,
            'leavetype' => $request->frm_leavetype,
            'notes' => $request->frm_notes,
        ]);

        return redirect('/hr/attendance/leave');
    }

	public function delete($id)
    {
        DB::table('hr_leavereq')->where('leaveid',$id)->delete();
        DB::table('hr_leave')->where('leaveid',$id)->delete();
        return redirect('/hr/attendance/leave');
    }

    public function generateCode()
    {
        // Mendapatkan tahun dan bulan sekarang
        $year = date("y");  // Gunakan format dua digit untuk tahun, seperti 25 untuk 2025
        $month = date("m"); // Format bulan menjadi dua digit, seperti 02 untuk Februari

        // Mengambil kode terakhir untuk bulan dan tahun yang sama dari tabel `hr_leave`
        $lastCode = DB::table('hr_leave')
                    ->where('leaveid', 'like', "LV.$year$month%")
                    ->orderBy('id', 'desc')
                    ->first();

        // Tentukan counter default jika belum ada kode sebelumnya
        $counter = 1;

        if ($lastCode) {
            // Ambil urutan nomor terakhir
            $counter = (int)substr($lastCode->leaveid, -4) + 1;
        }

        // Format urutan menjadi empat digit
        $counterFormatted = str_pad($counter, 4, "0", STR_PAD_LEFT);

        // Format kode sesuai template
        $code = "LV.$year$month.$counterFormatted";

        return $code;
    }
}