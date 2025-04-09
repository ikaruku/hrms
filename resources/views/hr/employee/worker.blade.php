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
    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
    class="nav-link dropdown-toggle">Configuration</a>
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
        <h1 class="m-0">Worker</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Worker</a></li>
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
          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-add">Add</button>
          <!-- <a href="{{url('/hr/employee/worker/exportexcel')}}"><button type="button" class="btn btn-success">Excel</button></a>-->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Join Date</th>
            <th>Permanent Date</th>
            <th>Department</th>
            <th>Level</th>
            <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($empllist as $r)
        <tr>
        <td>{{ $r->emplid }}</td>
        <td>{{ $r->emplname }}</td>
        <td>{{ $r->joindate }}</td>
        <td>{{ $r->permanentdate }}</td>
        <td>{{ $r->deptname }}</td>
        <td>{{ $r->levelname }}</td>
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

  <!-- /.POP UP CONTENT -->
  <div class="modal fade" id="modal-add">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{url('/hr/employee/worker/add')}}" method="post">
      <div class="modal-header">
        <h4 class="modal-title">Add Data</h4>
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
            <input type="text" class="form-control" placeholder="EmplID" name="frm_emplid" required>
          </div>
          <div class="form-group">
            <label>Employee Name</label>
            <input type="text" class="form-control" placeholder="EmplName" name="frm_emplname" required>
          </div>
          <div class="form-group">
            <label>Address</label>
            <input type="text" class="form-control" placeholder="Address" name="frm_address" required>
          </div>
          <div class="form-group">
            <label>Join Date</label>
            <input type="date" class="form-control" placeholder="JoinDate" name="frm_joindate" required>
          </div>
          </div>
        </div>
        <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

@endsection