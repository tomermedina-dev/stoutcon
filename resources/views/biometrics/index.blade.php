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
           <form action="{{ route('admin.biometrics.generate') }}" method="POST" enctype="multipart/form-data">
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

                

               <div class="col-xs-12 form-group {{ $errors->has('employee_id') ? 'has-error' : '' }}">
                {!! Form::label('employee_id','Employee', ['class' => 'control-label']) !!}
                {!! Form::select('employee_id', $employee, old('employee_id'), ['name' => 'employee_id','class' => 'form-control select2']) !!}
                  <em class="invalid-feedback" id="employee_id-error"></em>
              </div>
             

                <br>
                <button class="btn btn-info" id="modal-button"><i class="fas fa-table"></i> Generate Data</button>
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

<script type="text/javascript">
   $(document).ready(function(){
      
      $("#employee_id").select2();

      $(document).ready(function () {
        bsCustomFileInput.init();
      });

    });



$(document).ready(function() {
  $('#monthPicker').MonthPicker({ MaxMonth: 0 ,Button: false });
});
    


</script>



@stop