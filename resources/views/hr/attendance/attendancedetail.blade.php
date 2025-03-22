<!-- Menghubungkan dengan view template master -->
@extends('main')
 
<!-- isi bagian judul halaman -->
<!-- cara penulisan isi section yang pendek -->
@section('title')
<title>Attendance Record - {{ DB::table('hr_empltable')->where('emplid',request()->id)->first()->emplname }}</title>
  <!-- DataTables -->
  <link rel="stylesheet" href="{{url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{url('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection

@section('navi')
  <li class="nav-item d-none d-sm-inline-block">
    <a href="{{url('/hr/attendance')}}" class="nav-link"><i class="nav-icon fas fa-home"></i></a>
  </li>
  <li class="nav-item d-none d-sm-inline-block">
    <a href="{{url('/hr/attendance/record')}}" class="nav-link">Record</a>
  </li>
  <li class="nav-item d-none d-sm-inline-block">
    <a href="{{url('/hr/attendance/leave')}}" class="nav-link">Leave</a>
  </li>
  <li class="nav-item d-none d-sm-inline-block">
    <a href="{{url('/hr/attendance/overtime')}}" class="nav-link">Overtime</a>
  </li>
  <li class="nav-item dropdown">
    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Configuration</a>
    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
      <li><a href="{{url('/hr/attendance/schedule')}}" class="dropdown-item">Schedule </a></li>
      <li><a href="{{url('/hr/attendance/holiday')}}" class="dropdown-item">Holiday</a></li>
    </ul>
  </li>
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Attendance Record - {{ DB::table('hr_empltable')->where('emplid',request()->id)->first()->emplname }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Attendance</a></li>
              <li class="breadcrumb-item active">HR</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="export" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Normal In</th>
                    <th>Normal Out</th>
                    <th>Actual In</th>
                    <th>Actual Out</th>
                    <th>Status</th>
                    <th>Notes</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($attendanceList as $r)
                  <tr>
                    <td>{{ $r->attdate }}</td>
                    <td>{{ $r->day }}</td>
                    <td>{{ $r->normalin1 }}</td>
                    <td>{{ $r->normalout1 }}</td>
                    <td>{{ $r->actin1 }}</td>
                    <td>{{ $r->actout1 }}</td>
                    <td>{{ $r->attstatus }}</td>
                    <td>{{ $r->notes }}</td>
                  </tr>
                  @endforeach
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- /.POP UP CONTENT -->
<div class="modal fade" id="modal-add">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{url('/hr/attendance/record/generateall')}}" method="post">
        <div class="modal-header">
          <h4 class="modal-title">Generate Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- general form elements -->
          <div class="card card-primary">
            <!-- form start -->
              {{ csrf_field() }}
              <div class="card-body">
                <div class="card-header">
                  <h3 class="card-title">
                    General
                  </h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label>Year</label>
                    <input type="text" class="form-control"  name="frm_year">
                  </div>
                  <div class="form-group">
                    <label>Month</label>
                    <select class="form-control" name="frm_month">
                      <option value="1">Januari</option>
                      <option value="2">Februari</option>
                      <option value="3">Maret</option>
                      <option value="4">April</option>
                      <option value="5">Mei</option>
                      <option value="6">Juni</option>
                      <option value="7">Juli</option>
                      <option value="8">Agustus</option>
                      <option value="9">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Generate</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection