@section('plugins.Datatables', true)
@section('plugins.Select2', true)
@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('content_header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>PERIOD</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
       <li class="breadcrumb-item"><a href="{{ route('admin.balance.sheet') }}">Period</a></li>
        <li class="breadcrumb-item active">Management</li> 
      </ol>
    </div>
  </div>
@stop


@section('content')
  
  <div class="row">

    <div class="col-12">
      <div class="callout callout-info h-100">
        &nbsp;
        <button type="button" class="btn btn-success float-right" name="period" id="add" data-action="{{ route('admin.balance.sheet.period.store') }}">
          <i class="fas fa-plus"></i> Add Period
        </button>
        &nbsp;

      </div>
    </div>
  

    <div class="col-md-12">



           <div class="card card-secondary">
            <div class="card-header">
              <h3 class="card-title">&nbsp;</h3>
            </div>

           
                 <div class="card-body table-responsive">

                    
                   <table id="table-balance-sheet" class="table table-bordered table-striped compact" width="100%"> 
                      <thead class="thead-dark">
                          <tr>
                              <th>Period</th>
                              <th width="160px;">&nbsp;</th>

                          </tr>
                      </thead>
                       <tbody>
                    
                                  
                      </tbody>
                    </table>

                  
                 </div>


                 <div class="card-footer">

                 
                 </div>

          </div>
        </div>
    </div>




    <div class="modal fade" id="modal-xl" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">CREATE NEW</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

             {!! Form::open(['method' => 'POST']) !!} 
             {{ csrf_field() }}


              <div class="modal-body">

                <div class="row">
                  <div class="col-md-6">

                      <div class="col-xs-12 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
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


                   </div>
                </div>
                 
                

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               {!!  Form::hidden('id','',['id'=>'id']) !!}
               {!! Form::submit('Submit',['class' => 'btn btn-primary' ,'id' => 'store-submit']) !!}
               {!! Form::close() !!}
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
                              


                              
                              

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
    


  $(document).ready(function(){


     table = $('#table-balance-sheet').DataTable({
        ordering: true,
        iDisplayLength: 100,
        bPaginate: true,
        bLengthChange: true,
        processing:true,
        serverSide:true,
        button:false,
        searching:true,
        scrollY:500,
        scrollX:true,
        scrollCollapse:true,
       ajax:{
          url: "{{ route('admin.balance.sheet') }}",
       },
       columns:[
       {
          data: 'period',
          name: 'period',
       },
       {
          data: 'action',
          name: 'action',
          class:'project-actions text-right',
          orderable: false,
       }
       ]
     });

  
      table.ajax.reload();

  });
  


</script>

@stop