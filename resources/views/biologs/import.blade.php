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
            <form action="{{ route('admin.biologs.import') }}" method="POST" enctype="multipart/form-data">
                @csrf


                   <div class="col-xs-12 form-group {{ $errors->has('employee_id') ? 'has-error' : '' }}">
                    {!! Form::label('employee_id','Employee', ['class' => 'control-label']) !!}
                    {!! Form::select('employee_id', $employee, old('employee_id'), ['name' => 'employee_id','class' => 'form-control select2']) !!}
                      <em class="invalid-feedback" id="employee_id-error"></em>
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
                <input type="hidden" name="biologs" value="1">
                <a class="btn btn-success" href="{{ route('admin.biologs.export') }}"><i class="fas fa-file-download"></i> Download Template</a>
                <button class="btn btn-info"><i class="fas fa-database"></i> Import Data</button>
            </form>
        </div>
    </div>
                              

@stop

@section('css')

@stop
@section('js')

<script src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

<script type="text/javascript">
   $(document).ready(function(){
      
      $("#employee_id").select2();

      $(document).ready(function () {
        bsCustomFileInput.init();
      });

    });
</script>
@stop