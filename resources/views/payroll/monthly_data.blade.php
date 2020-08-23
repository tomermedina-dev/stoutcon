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
       <li class="breadcrumb-item"><a href="{{ route('admin.payroll.create') }}">Payroll</a></li>
        <li class="breadcrumb-item active">Create</li> 
      </ol>
    </div>
  </div>
@stop


@section('content')
  
   <div class="card bg-light mt-3">
        <div class="card-header">
            Search Payslip By Month
        </div>
        <div class="card-body">
            <form action="{{ route('admin.payroll.load.data') }}" method="POST" enctype="multipart/form-data">
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
              

                <br>
                <input type="hidden" name="payroll" value="1">
                <button class="btn btn-info"><i class="fas fa-file-invoice"></i> Generate Payslip</button>
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