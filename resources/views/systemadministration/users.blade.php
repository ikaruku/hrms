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
        <h1 class="m-0">User Manager</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">User Manager</a></li>
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
        <div class="card-header">
          <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-add">
          Add
          </button>
        </div>
        <!-- /.card-header -->
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
          <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
          data-target="#modal-edit{{$r->id}}"><i class="fas fa-edit"></i></button>
          <a href="{{url('/sysadmin/usermanager/delete\/')}}{{ $r->id }}">
          <button type="button" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></button>
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
      <form action="{{url('/sysadmin/usermanager/add')}}" method="post">
      <div class="modal-header">
        <h4 class="modal-title">Add User</h4>
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
            <label for="name">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Name"
            name="name" value="{{ old('name') }}" required>
            @error('name')
        <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
        </span>
      @enderror
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email"
            name="email" value="{{ old('email') }}" required>
            @error('email')
        <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
        </span>
      @enderror
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
            placeholder="Password" name="password" required>
            @error('password')
        <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
        </span>
      @enderror
          </div>
          <div class="form-group">
            <label for="password-confirm">Confirm Password</label>
            <input type="password" class="form-control" placeholder="Confirm Password"
            name="password_confirmation" required>
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

  @foreach($userlist as $r)
    <div class="modal fade" id="modal-edit{{$r->id}}">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{url('/sysadmin/usermanager/update')}}" method="post">
      <div class="modal-header">
      <h4 class="modal-title">Edit Data</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
      </div>
      <div class="modal-body">
      <!-- general form elements -->
      <div class="card card-primary">
      <!-- form start -->
      {{ csrf_field() }}
      <input type="hidden" name="id" value="{{ $r->id }}">
      <div class="card-body">
        <div class="card-header">
        <h3 class="card-title">
        General
        </h3>
        </div>
        <div class="card-body">
        <div class="form-group">
        <label>Name</label>
        <input type="text" class="form-control" placeholder="Name" name="frme_name" value="{{$r->name}}"
        readonly>
        </div>
        <div class="form-group">
        <label>Email</label>
        <input type="text" class="form-control" placeholder="URL" name="frme_email" value="{{$r->email}}"
        readonly>
        </div>
        <div class="form-group">
        <label>Change Password</label>
        <input type="password" class="form-control" name="frme_password">
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
  @endforeach

@endsection