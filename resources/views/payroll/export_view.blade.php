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
        <li class="breadcrumb-item active">Management</li> 
      </ol>
    </div>
  </div>
@stop


@section('content')
  
   <div class="card bg-light mt-3">
        <div class="card-header">
            Import Biometric Data From Excel (.CSV)
        </div>
        <div class="card-body">
            <form action="{{ route('admin.payroll.import') }}" method="POST" enctype="multipart/form-data">
                @csrf


                  
                  <div class="col-xs-12 form-group">
                  {!! Form::label('month','Month', ['class' => 'control-label']) !!}
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control float-right" name="month" id="monthPicker" autocomplete="off">
                      <em class="invalid-feedback" id="month-error"></em>
                  </div>
                </div>


    
                  <div class="form-group">
                    <label for="file">File input</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="file" id="file">
                        <label class="custom-file-label" for="file">Choose file</label>
                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text" id=""><i class="fas fa-file-upload"></i></span>
                      </div>
                    </div>
                        <em class="invalid-feedback" id="file-error"></em>
                  </div>


              

                <br>
                <input type="hidden" name="payroll" value="1">
                <a class="btn btn-success" href="{{ route('admin.payroll.export') }}"><i class="fas fa-file-download"></i> Download Template</a>
                <button class="btn btn-info"><i class="fas fa-database"></i> Import Data</button>
            </form>
        </div>
    </div>
                              

@stop

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/month-picker/css/smoothness-jquery-ui.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/month-picker/css/MonthPicker.css') }}" />

@stop
@section('js')


<script src="{{ asset('vendor/month-picker/js/jquery-migrate-3.3.0.min.js') }}"></script>
<script src="{{ asset('vendor/month-picker/js/montly-jquery-ui.min.js') }}"></script>
<script src="{{ asset('vendor/month-picker/js/montly-jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('vendor/month-picker/js/MonthPicker.min.js') }}"></script>
<script src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

<script src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

<script type="text/javascript">
   $(document).ready(function(){
      

      $(document).ready(function () {
        bsCustomFileInput.init();
      });

    });

   $(document).ready(function() {
  $('#monthPicker').MonthPicker({ MaxMonth: 0 ,Button: false });
});
    
</script>
@stop