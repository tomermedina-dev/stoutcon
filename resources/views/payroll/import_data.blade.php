@section('plugins.Datatables', true)
@section('plugins.Select2', true)
@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('content_header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>{{ strtoupper( $request->segment(2)) }}</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
       <li class="breadcrumb-item"><a href="{{ route('admin.biometrics') }}">Biometrics</a></li>
        <li class="breadcrumb-item active">Import Data</li> 
      </ol>
    </div>
  </div>
@stop


@section('content')

<div class="row">

    <div class="col-6">
      <div class="callout callout-info">
        <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Import Biometric Data</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form">
                <div class="card-body">
                          <!-- Date range -->
                <div class="form-group">
                  <label>Date range:</label>

                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control float-right" id="reservation">
                  </div>
                  <!-- /.input group -->
                </div>

                  <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text" id="">Upload</span>
                      </div>
                    </div>
                  </div>
              
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                 <!--  <button type="submit" class="btn btn-primary">Submit</button> -->
                </div>
              </form>
            </div>
          
      </div>
    </div>


</div>


@stop

@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@stop
@section('js') 
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script type="text/javascript">
  $('#reservation').daterangepicker();
</script>

@stop