@section('plugins.Datatables', true)
@section('plugins.Select2', true)
@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('content_header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>{{ strtoupper( $request->segment(1)) }}</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
       <li class="breadcrumb-item"><a href="{{ route('admin.accounts.index') }}">Accounts</a></li>
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
        <button type="button" class="btn btn-success float-right" name="accounts" id="add" data-action="{{ route('admin.accounts.store') }}">
          <i class="fas fa-plus"></i> Add Employee
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

                    
                   <table id="table-accounts" class="table table-bordered table-striped compact" width="100%"> 
                      <thead class="thead-dark">
                          <tr>
                              <th>Employee ID</th>
                              <th>Biometric ID</th>
                              <th>Name</th>
                              <th>Position</th>
                              <th>Project</th>
                              <th>Location</th>
                              <th>Contact Number</th>
                              <th>Salary</th>
                              <th>Status</th>
                             
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
                        {!! Form::label('name','Name', ['class' => 'control-label']) !!} 
                        {!! Form::text('name', old('name'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                        <em class="invalid-feedback" id="name-error">
                        </em>
                    </div>

                    <div class="col-xs-12 form-group {{ $errors->has('employee_identification') ? 'has-error' : '' }}">
                        {!! Form::label('employee_identification','Employee Identification', ['class' => 'control-label']) !!} 
                        {!! Form::text('employee_identification', old('employee_identification'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                        <em class="invalid-feedback" id="employee_identification-error">
                        </em>
                    </div>


                     <div class="col-xs-12 form-group {{ $errors->has('date_of_birth') ? 'has-error' : '' }}">
                        {!! Form::label('date_of_birth','Date of Birth', ['class' => 'control-label']) !!} 
                        {!! Form::date('date_of_birth', old('date_of_birth'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                        <em class="invalid-feedback" id="date_of_birth-error">
                        </em>
                    </div>


                    <div class="col-xs-12 form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                        {!! Form::label('address','Address', ['class' => 'control-label']) !!} 
                        {!! Form::text('address', old('address'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                        <em class="invalid-feedback" id="address-error">
                        </em>
                    </div>



                     <div class="col-xs-12 form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        {!! Form::label('email','Email', ['class' => 'control-label']) !!} 
                        {!! Form::text('email', old('email'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                        <em class="invalid-feedback" id="email-error">
                        </em>
                    </div>

                    <div class="col-xs-12 form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                        {!! Form::label('username','Username', ['class' => 'control-label']) !!} 
                        {!! Form::text('username', old('username'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                        <em class="invalid-feedback" id="username-error">
                        </em>
                    </div>

                    <div class="col-xs-12 form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        {!! Form::label('password','Password', ['class' => 'control-label']) !!} 
                        {!! Form::password('password', ['class' => 'form-control', 'placeholder' => '']) !!}
                        <em class="invalid-feedback" id="password-error">
                        </em>
                    </div>

                  

                     <div class="col-xs-12 form-group {{ $errors->has('work_status') ? 'has-error' : '' }}">
                        {!! Form::label('work_status','Work Status', ['class' => 'control-label']) !!} 
                        {!! Form::text('work_status', old('work_status'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                        <em class="invalid-feedback" id="work_status-error">
                        </em>
                     </div>


                     <div class="col-xs-12 form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                        {!! Form::label('username','Username', ['class' => 'control-label']) !!} 
                        {!! Form::text('username', old('username'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                        <em class="invalid-feedback" id="username-error">
                        </em>
                    </div>



                   <div class="col-xs-12 form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                        {!! Form::label('status','Account Status', ['class' => 'control-label']) !!} 
                        {!!Form::select('status', array('1' => 'Active', '0' => 'Inactive'), '1', ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']); !!}
                        <em class="invalid-feedback" id="status-error">
                        </em>
                    </div>


                    <div class="col-xs-12 form-group {{ $errors->has('role') ? 'has-error' : '' }}">
                        {!! Form::label('role','Role', ['class' => 'control-label']) !!} 
                        {!!Form::select('role', array('1' => 'Administrator', '0' => 'Employee'), '0', ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']); !!}
                        <em class="invalid-feedback" id="role-error">
                        </em>
                    </div>

                 </div>

                 <div class="col-md-6">

                  <div class="col-xs-12 form-group {{ $errors->has('position') ? 'has-error' : '' }}">
                      {!! Form::label('position','Position', ['class' => 'control-label']) !!} 
                      {!! Form::text('position', old('position'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                      <em class="invalid-feedback" id="position-error">
                      </em>
                  </div>


                  <div class="col-xs-12 form-group {{ $errors->has('department') ? 'has-error' : '' }}">
                      {!! Form::label('department','Department', ['class' => 'control-label']) !!} 
                      {!! Form::text('department', old('department'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                      <em class="invalid-feedback" id="department-error">
                      </em>
                  </div>

                  <div class="col-xs-12 form-group {{ $errors->has('project') ? 'has-error' : '' }}">
                      {!! Form::label('project','Project', ['class' => 'control-label']) !!} 
                      {!! Form::text('project', old('project'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                      <em class="invalid-feedback" id="project-error">
                      </em>
                  </div>

                  <div class="col-xs-12 form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                      {!! Form::label('location','Location', ['class' => 'control-label']) !!} 
                      {!! Form::text('location', old('location'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                      <em class="invalid-feedback" id="location-error">
                      </em>
                  </div>

                  <!-- <div class="col-xs-12 form-group {{ $errors->has('period') ? 'has-error' : '' }}">
                      {!! Form::label('period','Period', ['class' => 'control-label']) !!} 
                      {!! Form::date('period', old('period'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                      <em class="invalid-feedback" id="period-error">
                      </em>
                  </div> -->

<!-- 
                  <div class="col-xs-12 form-group {{ $errors->has('start_date') ? 'has-error' : '' }}">
                      {!! Form::label('start_date','Start Date', ['class' => 'control-label']) !!} 
                      {!! Form::date('start_date', old('start_date'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                      <em class="invalid-feedback" id="start_date-error">
                      </em>
                  </div>


                  <div class="col-xs-12 form-group {{ $errors->has('end_date') ? 'has-error' : '' }}">
                      {!! Form::label('end_date','End Date', ['class' => 'control-label']) !!} 
                      {!! Form::date('end_date', old('end_date'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                      <em class="invalid-feedback" id="end_date-error">
                      </em>
                  </div>
 -->



                  <div class="col-xs-12 form-group {{ $errors->has('salary_amount') ? 'has-error' : '' }}">
                      {!! Form::label('salary_amount','BASIC SALARY', ['class' => 'control-label']) !!} 
                      {!! Form::number('salary_amount', old('salary_amount'), ['autocomplete' => 'off','class' => 'form-control money is-warning','step'=>'any', 'placeholder' => '']) !!}
                      <em class="invalid-feedback" id="salary_amount-error">
                      </em>
                  </div>

                  <div class="col-xs-12 form-group {{ $errors->has('rate_per_hour') ? 'has-error' : '' }}">
                      {!! Form::label('rate_per_hour','Rate / Hr.', ['class' => 'control-label']) !!} 
                      {!! Form::number('rate_per_hour', old('rate_per_hour'), ['autocomplete' => 'off','class' => 'form-control money','step'=>'any', 'placeholder' => '']) !!}
                      <em class="invalid-feedback" id="rate_per_hour-error">
                      </em>
                  </div>

                  <div class="col-xs-12 form-group {{ $errors->has('night_differencial') ? 'has-error' : '' }}">
                      {!! Form::label('night_differencial','Night Diff/ Hr.', ['class' => 'control-label']) !!} 
                      {!! Form::number('night_differencial', old('night_differencial'), ['autocomplete' => 'off','class' => 'form-control money','step'=>'any', 'placeholder' => '']) !!}
                      <em class="invalid-feedback" id="night_differencial-error">
                      </em>
                  </div>


                  <div class="col-xs-12 form-group {{ $errors->has('sss_contribution') ? 'has-error' : '' }}">
                      {!! Form::label('sss_contribution','SSS contribution', ['class' => 'control-label']) !!} 
                      {!! Form::number('sss_contribution', old('sss_contribution'), ['autocomplete' => 'off','class' => 'form-control money', 'step'=>'any', 'placeholder' => '']) !!}
                      <em class="invalid-feedback" id="sss_contribution-error">
                      </em>
                  </div>


                  <div class="col-xs-12 form-group {{ $errors->has('philhealth') ? 'has-error' : '' }}">
                      {!! Form::label('philhealth','PhilHealth', ['class' => 'control-label']) !!} 
                      {!! Form::number('philhealth', old('philhealth'), ['autocomplete' => 'off','class' => 'form-control money','step'=>'any', 'placeholder' => '']) !!}
                      <em class="invalid-feedback" id="philhealth-error">
                      </em>
                  </div>


                  <div class="col-xs-12 form-group {{ $errors->has('pag_ibig') ? 'has-error' : '' }}">
                      {!! Form::label('pag_ibig','Pag-ibig', ['class' => 'control-label']) !!} 
                      {!! Form::number('pag_ibig', old('pag_ibig'), ['autocomplete' => 'off','class' => 'form-control money','step'=>'any', 'placeholder' => '']) !!}
                      <em class="invalid-feedback" id="pag_ibig-error">
                      </em>
                  </div>



                  <div class="col-xs-12 form-group {{ $errors->has('benefits') ? 'has-error' : '' }}">
                      {!! Form::label('benefits','Benefits', ['class' => 'control-label']) !!} 
                      {!! Form::number('benefits', old('benefits'), ['autocomplete' => 'off','class' => 'form-control money', 'step'=>'any','placeholder' => '']) !!}
                      <em class="invalid-feedback" id="benefits-error">
                      </em>
                  </div>

                 <div class="col-xs-12 form-group {{ $errors->has('other_benefits') ? 'has-error' : '' }}">
                      {!! Form::label('other_benefits','Other Benefits', ['class' => 'control-label']) !!} 
                      {!! Form::number('other_benefits', old('other_benefits'), ['autocomplete' => 'off','class' => 'form-control money', 'step'=>'any','placeholder' => '']) !!}
                      <em class="invalid-feedback" id="other_benefits-error">
                      </em>
                  </div>


                
                 <div class="col-xs-12 form-group {{ $errors->has('tax_withheld') ? 'has-error' : '' }}">
                      {!! Form::label('tax_withheld','Tax Withheld', ['class' => 'control-label']) !!} 
                      {!! Form::number('tax_withheld', old('tax_withheld'), ['autocomplete' => 'off','class' => 'form-control money', 'step'=>'any','placeholder' => '']) !!}
                      <em class="invalid-feedback" id="tax_withheld-error">
                      </em>
                  </div>


               <!--    <div class="col-xs-12 form-group {{ $errors->has('biometric_id') ? 'has-error' : '' }}">
                      {!! Form::label('biometric_id','Biometric ID', ['class' => 'control-label']) !!} 
                      {!! Form::number('biometric_id', old('biometric_id'), ['autocomplete' => 'off','class' => 'form-control money','step'=>'any', 'placeholder' => '']) !!}
                      <em class="invalid-feedback" id="biometric_id-error">
                      </em>
                  </div> -->

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
                              


     <div class="modal fade" id="modal-l" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">CREATE NEW</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">

              <div class="card-body">
                <dl class="row" id="_information">
                 <!--  <dt class="col-sm-4">Description lists</dt>
                  <dd class="col-sm-8">A description list is perfect for defining terms.</dd> -->
                <!--   <dt class="col-sm-4">Euismod</dt>
                  <dd class="col-sm-8">Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
                  <dd class="col-sm-8 offset-sm-4">Donec id elit non mi porta gravida at eget metus.</dd>
                  <dt class="col-sm-4">Malesuada porta</dt>
                  <dd class="col-sm-8">Etiam porta sem malesuada magna mollis euismod.</dd>
                  <dt class="col-sm-4">Felis euismod semper eget lacinia</dt>
                  <dd class="col-sm-8">Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo
                    sit amet risus.
                  </dd> -->
                </dl>
              </div>


            </div>
            <div class="modal-footer justify-content-between">
             
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
                              

@stop

@section('css')


 <link href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css" rel="stylesheet">
@stop
@section('js') 

<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>

<script type="text/javascript">

  $(document).ready(function(){


     table = $('#table-accounts').DataTable({
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
          url: "{{ route('admin.accounts.index') }}",
       },
       columns:[
        {
          data: 'employee_identification',
          name: 'employee_identification',
       },
        {
          data: 'id',
          name: 'id',
       },
       {
          data: 'name',
          name: 'name',
       },
       {
          data: 'position',
          name: 'position',
       },
      
       {
          data: 'project',
          name: 'project',
       },
       {
          data: 'location',
          name: 'location',
       },
       {
          data: 'mobile_number',
          name: 'mobile_number',
       },
       {
          data: 'salary_amount',
          name: 'salary_amount',
       },
        {
          data: 'status',
          name: 'status',
       },
   
       {
          data: 'action',
          name: 'action',
          class:'project-actions text-right',
          orderable: false,
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
    
        ],
        //   columnDefs: [ {
        //     targets: -1,
        //     visible: false
        // } ]
     });

    
    table.ajax.reload();
    

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


</script>

@stop