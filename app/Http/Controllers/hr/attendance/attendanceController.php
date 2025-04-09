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
        $menus = DB::table('syspermission')->where('userid', Auth::user()->id)->get()->sortBy('menuname');

        $enroll = DB::table('hr_empltable')->where('status', 'Active')->orWhere('leavedate', '>=', today())->count();
        $present = DB::table('hr_attendancerecord')->where('attdate', today())->where('attstatus', ['N', 'HD'])->count();
        $absent = DB::table('hr_attendancerecord')->where('attdate', today())->where('attstatus', 'A')->get();
        $late = DB::table('hr_attendancerecord')->where('attdate', today())->where('attstatus', 'Late')->count();
        $generated = DB::table('hr_attendancegenerated')->where('year', '=', date('Y'))->get();

        // Ambil data absensi untuk 7 hari terakhir
        $sevenDaysAgo = now()->subDays(7); // Mendapatkan tanggal 7 hari yang lalu

        // Query untuk mengambil data absensi
        $attendanceData = DB::table('hr_attendancerecord')
            ->where('attdate', '>=', $sevenDaysAgo)
            ->get()
            ->groupBy('attdate'); // Mengelompokkan data berdasarkan tanggal

        // Persiapkan data untuk chart
        $labels = [];
        $presentData = [];
        $absentData = [];

        // Menyusun data berdasarkan hari (7 hari terakhir)
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString(); // Mengambil tanggal
            $labels[] = \Carbon\Carbon::parse($date)->isoFormat('dddd'); // Nama hari dalam bahasa Indonesia (Senin, Selasa, dst.)

            // Mengambil data jumlah hadir dan tidak hadir
            $presentCount = $attendanceData->get($date, collect())->where('attstatus', 'N')->count();
            $absentCount = $attendanceData->get($date, collect())->where('attstatus', 'A')->count();

            $presentData[] = $presentCount;
            $absentData[] = $absentCount;
        }

        // Siapkan data untuk chart
        $dataAbsensi = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Present',
                    'backgroundColor' => 'rgba(60,141,188,0.9)',
                    'borderColor' => 'rgba(60,141,188,0.8)',
                    'fill' => false,
                    'data' => $presentData,
                ],
                [
                    'label' => 'Absent',
                    'backgroundColor' => 'rgba(210, 214, 222, 1)',
                    'borderColor' => 'rgba(210, 214, 222, 1)',
                    'fill' => false,
                    'data' => $absentData,
                ]
            ]
        ];

        return view('hr/attendance/home', compact('menus', 'generated', 'enroll', 'present', 'absent', 'late', 'dataAbsensi'));
    }
}