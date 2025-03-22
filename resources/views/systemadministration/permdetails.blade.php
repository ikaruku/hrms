@extends('main')

@section('title')
<title>Admin | Dashboard</title>
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
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
            <h1 class="m-0">User Permission of {{ DB::table('users')->where('id',request()->id)->first()->name }}</h1>
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
              <div class="card-body p-0">
                <!-- Form untuk menyimpan perubahan -->
                <form action="{{ route('savePermission') }}" method="POST">
                  @csrf
                  <div class="card-body">
                    <button type="submit" class="btn btn-success">Save Permissions</button>
                  </div>
                  <input type="hidden" name="user_id" value="{{ request()->id }}">
                  <table class="table table-hover">
                    <tbody>
                      @foreach($menulist as $r)
                        @if($r->parentid == 0)
                          <tr aria-expanded="false">
                            <td>
                              <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                              {{ $r->name }} 
                              <!-- Cek apakah menu ini sudah dipilih berdasarkan menuid -->
                              <input type="checkbox" name="menus[]" value="{{ $r->menuid }}"
                                     @if(in_array($r->menuid, $selectedMenus)) checked @endif>
                            </td>
                          </tr>
                          <tr class="expandable-body">
                            <td>
                              <div class="p-0">
                                <table class="table table-hover">
                                  <tbody>
                                    @foreach($menulist as $child)
                                      @if($child->parentid == $r->menuid)
                                        <tr aria-expanded="false">
                                          <td>
                                            {{ $child->name }} 
                                            <!-- Cek apakah child menu ini sudah dipilih berdasarkan menuid -->
                                            <input type="checkbox" name="menus[]" value="{{ $child->menuid }}"
                                                   @if(in_array($child->menuid, $selectedMenus)) checked @endif>
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
                </form>
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
