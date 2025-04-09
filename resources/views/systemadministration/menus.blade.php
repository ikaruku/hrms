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
        <h1 class="m-0">Menu Manager</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Menu Manager</a></li>
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
          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-add">
          Add
          </button>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <table class="table table-hover">
          <tbody>
            @foreach($menulist as $r)
        @if($r->parentid == 0)
      <tr data-widget="expandable-table" aria-expanded="false">
      <td>
        <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
        {{ $r->name }}
        <button type="button" class="btn btn-success btn-xs" data-toggle="modal"
        data-target="#modal-edit{{$r->menuid}}"><i class="fas fa-edit"></i></button>
        <button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
        data-target="#modal-delete"
        onclick="setDeleteUrl('{{url('/sysadmin/menumanager/delete')}}/{{ $r->menuid }}')"><i
        class="far fa-trash-alt"></i></button>
      </td>
      </tr>
      <tr class="expandable-body">
      <td>
        <div class="p-0">
        <table class="table table-hover">
        <tbody>
        @foreach($menulist as $child)
      @if($child->parentid == $r->menuid)
      <tr data-widget="expandable-table" aria-expanded="false">
      <td>
      {{ $child->name }}
      <button type="button" class="btn btn-success btn-xs" data-toggle="modal"
      data-target="#modal-edit{{$child->menuid}}"><i class="fas fa-edit"></i></button>
      <button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
      data-target="#modal-delete"
      onclick="setDeleteUrl('{{url('/sysadmin/menumanager/delete')}}/{{ $r->menuid }}')"><i
      class="far fa-trash-alt"></i></button>
      </td>
      </tr>
    @endif
    @endforeach
        </tbody>
        </table>
        </div>
      </td>
      </tr>
    @endif
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
  <div class="modal fade" id="modal-add">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{url('/sysadmin/menumanager/add')}}" method="post">
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
            <label>Name</label>
            <input type="text" class="form-control" placeholder="Name" name="frm_name">
          </div>
          <div class="form-group">
            <label>URL</label>
            <input type="text" class="form-control" placeholder="URL" name="frm_url">
          </div>
          <div class="form-group">
            <label>Icon</label>
            <input type="text" class="form-control" placeholder="fas-copy" name="frm_icon">
          </div>
          <div class="form-group">
            <label>Parent</label>
            <select class="form-control select2" style="width: 100%;" name="frm_parent">
            <option value="">None</option>
            @foreach($parentlist as $p)
        <option value="{{$p->menuid}}">{{$p->menuid}} - {{$p->name}}</option>
      @endforeach
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
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  @foreach($menulist as $r)
    <div class="modal fade" id="modal-edit{{$r->menuid}}">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{url('/sysadmin/menumanager/update')}}" method="post">
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
      <input type="hidden" name="menuid" value="{{ $r->menuid }}">
      <div class="card-body">
        <div class="card-header">
        <h3 class="card-title">
        General
        </h3>
        </div>
        <div class="card-body">
        <div class="form-group">
        <label>Name</label>
        <input type="text" class="form-control" placeholder="Name" name="frme_name" value="{{$r->name}}">
        </div>
        <div class="form-group">
        <label>URL</label>
        <input type="text" class="form-control" placeholder="URL" name="frme_url" value="{{$r->url}}">
        </div>
        <div class="form-group">
        <label>Icon</label>
        <input type="text" class="form-control" placeholder="fas-copy" name="frme_icon" value="{{$r->icon}}">
        </div>
        <div class="form-group">
        <label>Parent</label>
        <select class="form-control select2" style="width: 100%;" name="frme_parent">
        <option value="">None</option>
        @foreach($parentlistedit as $p)
      <option value="{{ $p->menuid }}" @if($p->menuid == $r->parentid) selected @endif>
        {{ $p->menuid }} - {{ $p->name }}
      </option>
    @endforeach
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

  <div class="modal fade" id="modal-delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Confirmation Delete Data</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      </div>
      <div class="modal-body">
      Are you sure you want to delete this data??
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      <!-- Formulir penghapusan yang akan dikirim -->
      <form id="delete-form" method="get" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
      </form>
      </div>
    </div>
    </div>
  </div>

  <script>
    // Menyimpan URL delete dalam variabel global
    let deleteUrl = '';

    // Menetapkan URL delete pada tombol yang diklik
    function setDeleteUrl(url) {
    // Menetapkan URL penghapusan ke action formulir delete
    document.getElementById('delete-form').action = url;
    }
  </script>
@endsection