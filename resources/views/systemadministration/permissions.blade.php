@extends('main')

@section('title')
  <title>Admin | Dashboard</title>
  <link rel="stylesheet" href="{{url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{url('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection

@section('navi')
  <!-- Isi -->
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">User Permission</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">User Permission</a></li>
        <li class="breadcrumb-item active">SysAdmin</li>
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
        <div class="card-body">
          <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($userlist as $r)
        <tr>
        <td>{{ $r->id }}</td>
        <td>{{ $r->name }}</td>
        <td>{{ $r->email }}</td>
        <td>
          <a href="{{url('/sysadmin/userpermission\/')}}{{ $r->id }}">
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