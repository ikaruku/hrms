<!-- Menghubungkan dengan view template master -->
@extends('main')
 
<!-- isi bagian judul halaman -->
<!-- cara penulisan isi section yang pendek -->
@section('title')
<title>Admin | Dashboard</title>
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
          @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
          @endif
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Attendance Record</h1>
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
                <div class="input-group mb-3">
                  <!-- Action Button -->
                  <div class="input-group-prepend">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                      Action
                    </button>
                    <div class="dropdown-menu">
                      <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modal-generate">Generate Schedule</button>
                      <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modal-import">Import Data</button>
                      <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modal-leave">Sync with Leave Data</button>
                      <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modal-overtime">Sync with Overtime Data</button>
                    </div>
                  </div>
                  <!-- Export Button -->
                  <div class="input-group-prepend">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                      Export
                    </button>
                    <div class="dropdown-menu">
                      <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modal-export">Export Data</button>
                      <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modal-exportovt">Export Data with Overtime</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-header">
                <!-- Form untuk memilih apakah ingin melihat semua karyawan atau hanya yang aktif -->
                <form method="GET" action="{{ url('/hr/attendance/record') }}">
                  <label><input type="checkbox" name="active" {{ request('active') == 'on' ? 'checked' : '' }}> Show All Employees </label>
                  <button type="submit" class="btn btn-info">Filter</button>
                </form>
              </div>
              <div class="card-body">
                <!-- Tabel Data Karyawan -->
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Empl ID</th>
                        <th>Empl Name</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($empllist as $r)
                        <tr>
                          <td>{{ $r->emplid }}</td>
                          <td>{{ $r->emplname }}</td>
                          <td>{{ $r->status }}</td>
                          <td>
                            <a href="{{ url('/hr/attendance/record/' . $r->emplid) }}">
                              <button type="button" class="btn btn-info btn-sm">Detail</button>
                            </a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
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
<div class="modal fade" id="modal-generate">
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
                    <input type="number" class="form-control"  name="frm_year" required>
                  </div>
                  <div class="form-group">
                    <label>Month</label>
                    <select class="form-control" name="frm_month" required>
                      <option value="" disabled selected>Select an option</option>
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

<div class="modal fade" id="modal-import">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{url('/hr/attendance/record/import')}}" method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <h4 class="modal-title">Import Data</h4>
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
                    <label>Import File</label>
                    <input type="file" class="form-control" name="frm_import">
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Import</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-leave">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{url('/hr/attendance/record/syncwithleave')}}" method="post">
        <div class="modal-header">
          <h4 class="modal-title">Sync with Leave Data</h4>
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
                    <input type="number" class="form-control"  name="frml_year" required>
                  </div>
                  <div class="form-group">
                    <label>Month</label>
                    <select class="form-control" name="frml_month" required>
                      <option value="" disabled selected>Select an option</option>
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
          <button type="submit" class="btn btn-primary">Import</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-overtime">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{url('/hr/attendance/record/syncwithovertime')}}" method="post">
        <div class="modal-header">
          <h4 class="modal-title">Sync with Overtime Data</h4>
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
                    <input type="number" class="form-control"  name="frm_otsyncyear" required>
                  </div>
                  <div class="form-group">
                    <label>Month</label>
                    <select class="form-control" name="frm_otsyncmonth" required>
                      <option value="" disabled selected>Select an option</option>
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
          <button type="submit" class="btn btn-primary">Import</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-export">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{url('/hr/attendance/exportrecord')}}" method="GET">
        <div class="modal-header">
          <h4 class="modal-title">Export Data</h4>
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
                    <input type="number" class="form-control"  name="frml_year" required>
                  </div>
                  <div class="form-group">
                    <label>Month</label>
                    <select class="form-control" name="frml_month" required>
                      <option value="" disabled selected>Select an option</option>
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
          <button type="submit" class="btn btn-primary">Export</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-exportovt">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{url('/hr/attendance/exportrecordovt')}}" method="GET">
        <div class="modal-header">
          <h4 class="modal-title">Export Data with Overtime</h4>
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
                    <label>Employee ID</label>
                    <select class="form-control select2" style="width: 100%;" name="ovt_emplid">
                      <option value="" disabled selected>Select an option</option>
                      @foreach($empllist as $empl)
                        <option value="{{ $empl->emplid }}">
                          {{ $empl->emplid }} - {{ $empl->emplname }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Year</label>
                    <input type="number" class="form-control"  name="ovt_year" required>
                  </div>
                  <div class="form-group">
                    <label>Month</label>
                    <select class="form-control" name="ovt_month" required>
                      <option value="" disabled selected>Select an option</option>
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
          <button type="submit" class="btn btn-primary">Export</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

@endsection