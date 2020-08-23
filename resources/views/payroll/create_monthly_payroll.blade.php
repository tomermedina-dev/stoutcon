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
       <li class="breadcrumb-item"><a href="{{ route('admin.payroll.create') }}">Payroll</a></li>
        <li class="breadcrumb-item active">Create</li> 
      </ol>
    </div>
  </div>
@stop


@section('content')
  
  <div class="row">

   <div class="col-12">
      <div class="callout callout-info h-100">
        &nbsp;
       <!--  <button type="button" class="btn btn-success float-right" name="tial_balance" id="add" data-action="{{ route('admin.payroll.biometric.store.data') }}">
          <i class="fas fa-plus"></i> Add Biometric Time
        </button> -->
        &nbsp;

      </div>
    </div>
  
  

    <div class="col-md-8">



           <div class="card card-secondary">
            <div class="card-header">
              <h3 class="card-title">&nbsp;</h3>
            </div>

           
                 <div class="card-body table-responsive">

                    
                   <table id="table-biometrics" class="table table-bordered table-striped compact" width="100%"> 
                      <thead class="thead-dark">
                          <tr>
                              <th>Day</th>
                              <th>Time In</th>
                              <th>Time Out</th>
                              <th>Hrs<br><small>Required</small></th>
                              <th>Hrs<br><small>Break Time</small></th>
                              <th class="bg-info">Hrs<br><small>Day</small></th>
                              <th class="bg-warning">Hrs<br><small>Night</small></th>
                              <th>Hrs<br><small>Over Time</small></th>
                              <th>Hrs<br><small>Over Undertime</small></th>

                          </tr>
                      </thead>
                       <tbody>
                        <?php
                          $days = 0;
                          $total_required = 0.00;
                          $total_break_time = 0.00;
                          $total_days = 0.00;
                          $total_night = 0.00;
                          $total_overtime = 0.00;
                          $total_undertime = 0.00;
                        ?>
                         @foreach($data as $key => $value)
                            <tr>
                                <td>{{ $value->day }}</td>
                                <td>{{ date("g:i a",strtotime($value->timein)) }}</td>
                                <td>{{ date("g:i a",strtotime($value->timeout)) }}</td>
                                <td>8.00</td>
                                <td>1.00</td>
                                <td class="bg-info"><b>{{ date("a",strtotime($value->timein)) == "am" ? $value->duration : "0.00" }}</b></td>
                                <td class="bg-warning"><b>{{ date("a",strtotime($value->timein)) == "pm" ? $value->duration : "0.00" }}</b></td>
                                <td>{{ abs(str_replace('.00.000000','',str_replace(':','.',$value->overtime)))  }}</td>
                                <td>{{ abs(str_replace('.00.000000','',str_replace(':','.',$value->undertime)))  }}</td>
                            </tr>
                            <?php
                                $days += 1;
                                $total_required += (float) 8.00;
                                $total_break_time += (float) 1.00;
                                $total_days += date("a",strtotime($value->timein)) == "am" ? $value->duration : "0.00";
                                $total_night += date("a",strtotime($value->timein)) == "pm" ? $value->duration : "0.00";
                                $total_overtime += (float) abs(str_replace('.00.000000','',str_replace(':','.',$value->overtime)));
                                $total_undertime += (float) abs(str_replace('.00.000000','',str_replace(':','.',$value->undertime)));
                            ?>
                         @endforeach
                      </tbody>
                      <tfoot class="thead-dark">
                           
                            <tr>
                              <th>Days Present : {{ $days }}</th>
                              <th></th>
                              <th></th>
                              <th>{{ $total_required }}</th>
                              <th>{{ $total_break_time }}</th>
                              <th class="bg-info">{{ $total_days }}</th>
                              <th class="bg-warning">{{ $total_night }}</th>
                              <th>{{ $total_overtime }}</th>
                              <th>{{ $total_undertime }}</th>
                            
                            </tr>
                      </tfoot>
                    </table>

                  
                 </div>


                 <div class="card-footer">

                 
                 </div>

          </div>
        </div>


        <div class="col-md-4">

           <div class="card card-secondary">
            <div class="card-header">
              <h3 class="card-title">&nbsp;</h3>
            </div>

                {!! Form::open(['method' => 'POST', 'route' => ['admin.payroll.store.data']]) !!}
              {{ csrf_field() }}

           
                 <div class="card-body">

                    
                         <div class="col-xs-12 form-group {{ $errors->has('salary_amount') ? 'has-error' : '' }}">
                             {!! Form::label('salary_amount','BASIC SALARY', ['class' => 'control-label']) !!} 
                             {!! Form::number('salary_amount', $user->salary_amount, ['autocomplete' => 'off','class' => 'form-control money is-warning','step'=>'any', 'placeholder' => '']) !!}
                             <em class="invalid-feedback" id="salary_amount-error">
                             </em>
                         </div>

                         <div class="col-xs-12 form-group {{ $errors->has('rate_per_hour') ? 'has-error' : '' }}">
                             {!! Form::label('rate_per_hour','Rate / Hr.', ['class' => 'control-label']) !!} 
                             {!! Form::number('rate_per_hour', $user->rate_per_hour, ['autocomplete' => 'off','class' => 'form-control money','step'=>'any', 'placeholder' => '']) !!}
                             <em class="invalid-feedback" id="rate_per_hour-error">
                             </em>
                         </div>

                         <div class="col-xs-12 form-group {{ $errors->has('night_differencial') ? 'has-error' : '' }}">
                             {!! Form::label('night_differencial','Night Diff/ Hr.', ['class' => 'control-label']) !!} 
                             {!! Form::number('night_differencial', $user->night_differencial, ['autocomplete' => 'off','class' => 'form-control money','step'=>'any', 'placeholder' => '']) !!}
                             <em class="invalid-feedback" id="night_differencial-error">
                             </em>
                         </div>


                         <div class="col-xs-12 form-group {{ $errors->has('sss_contribution') ? 'has-error' : '' }}">
                             {!! Form::label('sss_contribution','SSS contribution', ['class' => 'control-label']) !!} 
                             {!! Form::number('sss_contribution', $user->sss_contribution, ['autocomplete' => 'off','class' => 'form-control money', 'step'=>'any', 'placeholder' => '']) !!}
                             <em class="invalid-feedback" id="sss_contribution-error">
                             </em>
                         </div>


                         <div class="col-xs-12 form-group {{ $errors->has('philhealth') ? 'has-error' : '' }}">
                             {!! Form::label('philhealth','PhilHealth', ['class' => 'control-label']) !!} 
                             {!! Form::number('philhealth', $user->philhealth, ['autocomplete' => 'off','class' => 'form-control money','step'=>'any', 'placeholder' => '']) !!}
                             <em class="invalid-feedback" id="philhealth-error">
                             </em>
                         </div>


                         <div class="col-xs-12 form-group {{ $errors->has('pag_ibig') ? 'has-error' : '' }}">
                             {!! Form::label('pag_ibig','Pag-ibig', ['class' => 'control-label']) !!} 
                             {!! Form::number('pag_ibig', $user->pag_ibig, ['autocomplete' => 'off','class' => 'form-control money','step'=>'any', 'placeholder' => '']) !!}
                             <em class="invalid-feedback" id="pag_ibig-error">
                             </em>
                         </div>



                         <div class="col-xs-12 form-group {{ $errors->has('benefits') ? 'has-error' : '' }}">
                             {!! Form::label('benefits','Benefits', ['class' => 'control-label']) !!} 
                             {!! Form::number('benefits', $user->benefits, ['autocomplete' => 'off','class' => 'form-control money', 'step'=>'any','placeholder' => '']) !!}
                             <em class="invalid-feedback" id="benefits-error">
                             </em>
                         </div>

                        <div class="col-xs-12 form-group {{ $errors->has('other_benefits') ? 'has-error' : '' }}">
                             {!! Form::label('other_benefits','Other Benefits', ['class' => 'control-label']) !!} 
                             {!! Form::number('other_benefits', $user->other_benefits, ['autocomplete' => 'off','class' => 'form-control money', 'step'=>'any','placeholder' => '']) !!}
                             <em class="invalid-feedback" id="other_benefits-error">
                             </em>
                         </div>


                        
                        <div class="col-xs-12 form-group {{ $errors->has('tax_withheld') ? 'has-error' : '' }}">
                             {!! Form::label('tax_withheld','Tax Withheld', ['class' => 'control-label']) !!} 
                             {!! Form::number('tax_withheld', $user->tax_withheld, ['autocomplete' => 'off','class' => 'form-control money', 'step'=>'any','placeholder' => '']) !!}
                             <em class="invalid-feedback" id="tax_withheld-error">
                             </em>
                         </div>

                         <hr>

                  

                          <div class="col-xs-6 form-group {{ $errors->has('days_hours') ? 'has-error' : '' }}">
                             {!! Form::label('days_hours','Day Shift (Hours)', ['class' => 'control-label']) !!} 
                             {!! Form::number('days_hours', $total_days, ['autocomplete' => 'off','class' => 'form-control money', 'step'=>'any','placeholder' => '']) !!}
                             <em class="invalid-feedback" id="days_hours-error">
                             </em>
                         </div>

                          <div class="col-xs-6 form-group {{ $errors->has('night_hours') ? 'has-error' : '' }}">
                             {!! Form::label('night_hours','Night Shift (Hours)', ['class' => 'control-label']) !!} 
                             {!! Form::number('night_hours', $total_night, ['autocomplete' => 'off','class' => 'form-control money', 'step'=>'any','placeholder' => '']) !!}
                             <em class="invalid-feedback" id="night_hours-error">
                             </em>
                         </div>

                           <div class="col-xs-6 form-group {{ $errors->has('overtime_pay') ? 'has-error' : '' }}">
                             {!! Form::label('overtime_pay','Overtime Pay', ['class' => 'control-label']) !!} 
                             {!! Form::number('overtime_pay',$find->overtime, ['autocomplete' => 'off','class' => 'form-control money', 'step'=>'any','placeholder' => '']) !!}
                             <em class="invalid-feedback" id="overtime_pay-error">
                             </em>
                         </div>


                  <?php
                  $total_basic_salary = ($user->rate_per_hour * $total_days) + ( $user->rate_per_hour * $total_night) + ( $user->night_differencial * $total_night);

                   $gross_pay =  $total_basic_salary + $user->benefits + $user->other_benefits;

                   $total_deduction = $user->sss_contribution + $user->philhealth + $user->pag_ibig + $user->tax_withheld;

                   $net_pay = $gross_pay - $total_deduction;

                   $hidden_night_differencial = ( $user->rate_per_hour * $total_night) + ( $user->night_differencial * $total_night);

                    $hidden_basic_salary = ($user->rate_per_hour * $total_days);
                  ?>  

                         <div class="col-xs-6 form-group {{ $errors->has('total_basic_salary') ? 'has-error' : '' }}">
                             {!! Form::label('total_basic_salary','Total Basic Pay', ['class' => 'control-label']) !!} 
                             {!! Form::number('total_basic_salary', round($total_basic_salary,2), ['autocomplete' => 'off','class' => 'form-control money', 'step'=>'any','placeholder' => '']) !!}
                             <em class="invalid-feedback" id="total_basic_salary-error">
                             </em>
                         </div>

                          <div class="col-xs-6 form-group {{ $errors->has('tardiness') ? 'has-error' : '' }}">
                             {!! Form::label('tardiness','Tardiness', ['class' => 'control-label']) !!} 
                             {!! Form::number('tardiness', round($find->tardiness,2), ['autocomplete' => 'off','class' => 'form-control money', 'step'=>'any','placeholder' => '']) !!}
                             <em class="invalid-feedback" id="tardiness-error">
                             </em>
                         </div>


                          <div class="col-xs-6 form-group {{ $errors->has('gross_pay') ? 'has-error' : '' }}">
                             {!! Form::label('gross_pay','Gross Pay', ['class' => 'control-label']) !!} 
                             {!! Form::number('gross_pay', round($gross_pay,2), ['autocomplete' => 'off','class' => 'form-control money', 'step'=>'any','placeholder' => '']) !!}
                             <em class="invalid-feedback" id="gross_pay-error">
                             </em>
                         </div>

                            <div class="col-xs-6 form-group {{ $errors->has('net_pay') ? 'has-error' : '' }}">
                             {!! Form::label('net_pay','Net Pay', ['class' => 'control-label']) !!} 
                             {!! Form::number('net_pay', round($net_pay,2), ['autocomplete' => 'off','class' => 'form-control money', 'step'=>'any','placeholder' => '']) !!}
                             <em class="invalid-feedback" id="net_pay-error">
                             </em>
                         </div>



                  
                 </div>


                 <div class="card-footer">

                       {!! Form::hidden('hidden_night_differencial',$hidden_night_differencial) !!}
                       {!! Form::hidden('hidden_basic_salary',$hidden_basic_salary) !!}
                       {!! Form::hidden('total_deduction', $total_deduction) !!}
                       {!! Form::hidden('month_year', $month_year) !!}
                       {!! Form::hidden('user_id', $user->id) !!}
                       {!! Form::submit('Submit',['class' => 'btn btn-primary' ,'id' => 'store-submit']) !!}
                       {!! Form::close() !!}
                
                         
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
                        {!! Form::label('DwInOutMode','DwInOutMode', ['class' => 'control-label']) !!} 
                        {!!Form::select('DwInOutMode', array('0' => 'Time In', '1' => 'Time Out'), '0', ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']); !!}
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
                              


                              
  
                              

@stop


@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css"/>
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.dataTables.min.css"/>
  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
  <style type="text/css">
        th { font-size: 16px; }
        td { font-size: 13px; }
  </style>

@stop 
@section('js') 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.4-beta.33/jquery.inputmask.min.js"></script>

<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>
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

    // $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yy/mm/dd' })

   var table = $('#table-biometrics').dataTable({
      "searching": false,
      "ordering": false,
      "iDisplayLength": 100,
      "bPaginate": false,
      "bLengthChange": false });
   new $.fn.dataTable.FixedHeader( table );

   $(document).ready(function(){

     $('.ovetimepay,.night_differencial').change(function() {
      if(this.checked) {
          $(this).parent().parent().next().prop( "disabled", false );
   
      }else{
          $(this).parent().parent().next().prop( "disabled", true );
   
      }

     });


      $('.salary').each(function() {
          if(this.checked) {
              $(this).parent().parent().next().prop( "disabled", false );
       
          }else{
              $(this).parent().parent().next().prop( "disabled", true );
       
          }

       });

      $('.salary').change(function(){
          if(this.checked) {
              $(this).parent().parent().next().prop( "disabled", false );
              $(this).parent().parent().next().val("{{ $user->rate_per_hour ? $user->rate_per_hour * 8 : 0 }}");
 
          }else{
              $(this).parent().parent().next().prop( "disabled", true );
              $(this).parent().parent().next().val(0);
           
          }
      });




    $('#salary_amount').on('keyup',function(){
      ///Rate per HR = basic / 22 days / 8 hours;
      basic_salary = $(this).val();
      rate_per_hour = basic_salary / 22;
      rate_per_hour = rate_per_hour / 8;
      $('#rate_per_hour').val(rate_per_hour.toFixed(2));

      //Night Differencial = Rate per HR  * 0.10 * 8; 
      night_differencial = rate_per_hour * 0.10;
      $('#night_differencial').val(night_differencial.toFixed(2));

      //PhilHealth = basic * 0.015;
      philhealth =  basic_salary * 0.015;
      $('#philhealth').val(philhealth.toFixed(2));

      // PAG-IBIG = basic * 0.02
      pag_ibig = 100;//basic_salary * 0.02;
      $('#pag_ibig').val(pag_ibig.toFixed(2));

     // 11% - employee 3.36% - employer 7.37
     // SSS = basic * 0.0336 
      sss_contribution = basic_salary * 0.0336;
     // $('#sss_contribution').val(sss_contribution.toFixed(2));
      });

      $('#rate_per_hour,#days_hours,#night_hours,#night_differencial,#overtime_pay,#benefits,#other_benefits,#sss_contribution,#philhealth,#pag_ibig,#tax_withheld,#tardiness').on('keyup',function(){

          rate_per_hour = $('#rate_per_hour').val();
          days_hours = $('#days_hours').val();
          night_hours = $('#night_hours').val();
          night_differencial =  $('#night_differencial').val();
          overtime_pay = $('#overtime_pay').val();
          benefits = $('#benefits').val();
          other_benefits = $('#other_benefits').val();

          sss_contribution = $('#sss_contribution').val();
          philhealth = $('#philhealth').val();
          pag_ibig = $('#pag_ibig').val();
          tax_withheld = $('#tax_withheld').val();
          tardiness = $('#tardiness').val();

          total_deduction = (parseFloat(sss_contribution) + parseFloat(philhealth) + parseFloat(pag_ibig) + parseFloat(tax_withheld) + parseFloat(tardiness));

          $('#total_deduction').val(total_deduction.toFixed(2));

        
          total_basic_salary = (parseFloat(rate_per_hour) * parseFloat(days_hours)) + ( parseFloat(rate_per_hour) * parseFloat(night_hours)) + ( parseFloat(night_differencial) * parseFloat(night_hours));

          total_basic_salary =  parseFloat(total_basic_salary) + parseFloat(overtime_pay);
           gross_pay =  parseFloat(total_basic_salary) + parseFloat(benefits) + parseFloat(other_benefits);

           net_pay = parseFloat(gross_pay) - parseFloat(total_deduction);

          $('#total_basic_salary').val(total_basic_salary.toFixed(2));
          $('#gross_pay').val(gross_pay.toFixed(2));
          $('#net_pay').val(net_pay.toFixed(2));


          // rate_per_hour = $('#rate_per_hour').val();
          // night_hours = $('#night_hours').val();
          // night_differencial =  $('#night_differencial').val();

          hidden_night_differencial  = (parseFloat(rate_per_hour) * parseFloat(night_hours)) + ( parseFloat(night_differencial) * parseFloat(night_hours));
          hidden_basic_salary  = (parseFloat(rate_per_hour) * parseFloat(days_hours));

          console.log(hidden_night_differencial);

          $('input[name="hidden_night_differencial"]').val(hidden_night_differencial.toFixed(2));
          $('input[name="hidden_basic_salary"]').val(hidden_basic_salary.toFixed(2));

         
      });
        

   });
</script>


@stop