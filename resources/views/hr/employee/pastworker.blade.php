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
  <a href="{{url('/hr/employee')}}" class="nav-link"><i class="nav-icon fas fa-home"></i></a>
</li>
<li class="nav-item d-none d-sm-inline-block">
  <a href="{{url('/hr/employee/worker')}}" class="nav-link">Worker</a>
</li>
<li class="nav-item d-none d-sm-inline-block">
  <a href="{{url('/hr/employee/pastworker')}}" class="nav-link">Past Worker</a>
</li>
<li class="nav-item dropdown">
  <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Configuration</a>
  <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
    <li><a href="{{url('/hr/employee/department')}}" class="dropdown-item">Department </a></li>
    <li><a href="{{url('/hr/employee/level')}}" class="dropdown-item">Level</a></li>
    <li><a href="{{url('/hr/employee/position')}}" class="dropdown-item">Position</a></li>
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
            <h1 class="m-0">Past Worker</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Past Worker</a></li>
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
                <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Join Date</th>
                    <th>Leave Date</th>
                    <th>Permanent Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($empllist as $r)
                  <tr>
                    <td>{{ $r->emplid }}</td>
                    <td>{{ $r->emplname }}</td>
                    <td>{{ $r->joindate }}</td>
                    <td>{{ $r->leavedate }}</td>
                    <td>{{ $r->status }}</td>
                    <td>
                      <a href="{{url('/hr/employee/worker/detail\/')}}{{ $r->emplid }}">
                        <button type="button" class="btn btn-info btn-sm">Detail</button>
                      </a>
                    </td>
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
 
@endsection