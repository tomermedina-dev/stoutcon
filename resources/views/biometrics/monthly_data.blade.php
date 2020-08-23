@section('plugins.Datatables', true)
@section('plugins.Select2', true)
@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('content_header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>{{ strtoupper( $request->segment(2)) }} : {{ $user->name }} | {{ $month_year }}</h1>
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
  
  <div class="row">

   <div class="col-12">
      <div class="callout callout-info h-100">
        &nbsp;
        <button type="button" class="btn btn-success float-right" name="tial_balance" id="add" data-action="{{ route('admin.biometric.store.data') }}">
          <i class="fas fa-plus"></i> Add Biometric Time
        </button>
        &nbsp;

      </div>
    </div>
  
  

    <div class="col-md-12">



           <div class="card card-secondary">
            <div class="card-header">
              <!-- <h3 class="card-title">&nbsp;</h3> -->
                &nbsp;
                <button type="button" class="btn btn-danger float-right" name="tial_balance" id="add-leaved" data-action="{{ route('admin.biometric.store.leaved.data') }}">
                  <i class="fas fa-plus"></i> Add Leave Day(s)
                </button>
                &nbsp;
            </div>

           
                 <div class="card-body table-responsive">

                    
                   <table id="table-biometrics" class="table table-bordered table-striped compact" width="100%"> 
                      <thead class="thead-dark">
                          <tr>
                              <th>In Out Mode</th>
                              <th>Date Only Record</th>
                              <th>Time Only Record</th>
                              <th>Remarks</th>
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

                <div class="col-md-12">

                   <div class="col-xs-12 form-group {{ $errors->has('DwInOutMode') ? 'has-error' : '' }}">
                        {!! Form::label('DwInOutMode','Time In/Out', ['class' => 'control-label']) !!} 
                        {!!Form::select('DwInOutMode', array('0' => 'Time In', '1' => 'Time Out'), '', ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']); !!}
                        <em class="invalid-feedback" id="DwInOutMode-error">
                        </em>
                    </div>


                   <div class="form-group">
                      <label>Date Only Record:</label>

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                          </span>
                        </div>
                        <input type="text" class="form-control float-right"  autocomplete="off" name="DateOnlyRecord" id="DateOnlyRecord">
                      </div>
                      <em class="invalid-feedback" id="DateOnlyRecord-error">
                            </em>
                      <!-- /.input group -->
                    </div>
                    <!-- /.form group -->
                       

                     <div class="bootstrap-timepicker">
                      <div class="form-group">
                        <label>Time Only Record:</label>

                        <div class="input-group date" id="TimeOnlyRecord" data-target-input="nearest">
                          <input type="text" name="TimeOnlyRecord" autocomplete="off" class="form-control dateTimeOnlyRecord-input" data-target="#TimeOnlyRecord"/>
                           
                          <div class="input-group-append" data-target="#TimeOnlyRecord" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="far fa-clock"></i></div>
                          </div>
                          </div>
                        <!-- /.input group -->
                        <em class="invalid-feedback" id="TimeOnlyRecord-error"> </em>
                      </div>
                      <!-- /.form group -->
                    </div>



                 </div>

            
              </div>
               
              

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               {!! Form::hidden('month_year', $month_year) !!}
               {!! Form::hidden('user_id', $user->id) !!}
               {!! Form::submit('Submit',['class' => 'btn btn-primary' ,'id' => 'store-submit']) !!}
               {!! Form::close() !!}
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->
                              






    <div class="modal fade" id="modal-leave" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">CREATE LEAVE</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

             {!! Form::open(['method' => 'POST']) !!}
             {{ csrf_field() }}


            <div class="modal-body">

              <div class="row">

                <div class="col-md-12">




                   <div class="col-xs-12 form-group {{ $errors->has('leaved') ? 'has-error' : '' }}">
                        {!! Form::label('leaved','Leaved', ['class' => 'control-label']) !!} 
                        {!!Form::select('leaved', array( '1' => 'Leaved w/ Pay', '2' => 'Leaved w/out Pay'), '0', ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']); !!}
                        <em class="invalid-feedback" id="leaved-error">
                        </em>
                    </div>

                    <div class="col-xs-12 form-group {{ $errors->has('Absent') ? 'has-error' : '' }}">
                        {!! Form::label('shift','If Absent', ['class' => 'control-label']) !!} 
                        {!!Form::select('shift', array('1' => 'Morning Shift (6AM-2PM)', '2' => 'Mid Shift (2PM-10PM)', '3' => 'Night Shift (10PM-6AM)'), '0', ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']); !!}
                        <em class="invalid-feedback" id="shift-error">
                        </em>
                    </div>

                   <div class="col-xs-12 form-group {{ $errors->has('date_range') ? 'has-error' : '' }}">
                        {!! Form::label('date_range','Date Range', ['class' => 'control-label']) !!} 
                        {!! Form::text('date_range', old('date_range'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                        <em class="invalid-feedback" id="date_range-error">
                        </em>
                    </div>



                 </div>

            
              </div>
               
              

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               {!! Form::hidden('month_year', $month_year) !!}
               {!! Form::hidden('user_id', $user->id) !!}
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css"/>
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.dataTables.min.css"/>
  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />


  <style type="text/css">
        th { font-size: 16px; }
        td { font-size: 13px; }
  </style>

@stop 
@section('js') 

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script> 


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> 
<script>
    $( document ).ready(function() {
        console.log( "document loaded" );
    });
 
    $( window ).on( "load", function() {
        console.log( "window loaded" );
    });
    </script>

<script type="text/javascript">

    //Timepicker
    $('#TimeOnlyRecord').datetimepicker({
      format: 'LT'
    });

     $('#DateOnlyRecord').daterangepicker({
      timePicker: false,
      singleDatePicker: true,
      locale: {
        format: 'YYYY-MM-DD'
      }
    });
  

  $(document).ready(function(){


    table = $('#table-biometrics').DataTable({
        // aSorting: [[1, 'desc']],
        bSort: false,
        ordering: false,
        iDisplayLength: 100,
        bPaginate: true,
        bLengthChange: true,
        processing:true,
        serverSide:true,
        button:false,
        searching:false,
        scrollY:500,
        scrollX:true,
        scrollCollapse:true,
       ajax:{
          url: "{{  url('admin/biometrics/generate/'.$user->id.'/'.$month_year) }}",
       },
       columns:[
  
         {
            data: 'DwInOutMode',
            name: 'DwInOutMode',
         },
         {
            data: 'DateOnlyRecord',
            name: 'DateOnlyRecord',
         },
         {
            data: 'TimeOnlyRecord',
            name: 'TimeOnlyRecord',
         },
          {
            data: 'status',
            name: 'status',
         },
         {
            data: 'action',
            name: 'action',
            class:'project-actions text-right'
         }
         ],dom: 'Bfrtip',
           buttons: [
            {
              extend: 'print',
             className: 'btn btn-success float-right',
              exportOptions: {
                columns: ':visible'
              },
              customize: function(win) {
                $(win.document.body).find( 'table' ).find('td:last-child, th:last-child').remove();
              }
            },
    
        ]
     });

    table.ajax.reload();
    
  ;

  });



   $(document).on('click','#add-leaved',function(){
      $('form').trigger("reset");
      $('.modal-title').text('Add new!');
      $('#modal-leave').modal({backdrop: 'static', keyboard: false}).show();
      $('form').attr('action',$(this).data('action'));
      $('form').removeClass('has-error');
      $('form').find('.form-control').removeClass('is-invalid'); 
      $('form').addClass('has-success'); 
      $('form').find('em').text('');
   });


   $('#date_range').datepicker({
      multidate: true,
      format: 'yyyy-mm-dd'
    });

        

</script>


@stop