@section('plugins.Datatables', true)
@section('plugins.Select2', true)
@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('content_header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>INCOME STATEMENT {{ $month_year }}</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
       <li class="breadcrumb-item"><a href="{{ route('admin.income.statement') }}">Period</a></li>
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
        <button type="button" class="btn btn-success float-right" name="tial_balance" id="add" data-action="{{ route('admin.income.statement.store') }}">
          <i class="fas fa-plus"></i> Add Income Statement Data
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

                    
                   <table id="table-income-statement" class="table table-bordered compact" width="100%"> 
                      <thead class="thead-dark">
                          <tr>
                              <th>&nbsp;</th>
                              <th>&nbsp;</th>
                              <th>&nbsp;</th>
                              <th>&nbsp;</th>
                              
                              <th width="160px;">&nbsp;</th>

                          </tr>
                      </thead>
                       <tbody>
                    
                                  
                      </tbody>

                        
                    </table>

                  
                 </div>


                 <div class="card-footer">

                    &nbsp;
                    <a type="button" class="btn btn-primary float-right" name="tial_balance"  href="{{   route('admin.pdf.income.statement',[ 'month' => $month, 'year' => $year] ) }}" target="_blank">
                      <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                 
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

                    <div class="col-xs-12 form-group {{ $errors->has('account') ? 'has-error' : '' }}">
                        {!! Form::label('account','Account', ['class' => 'control-label']) !!} 
                        {!! Form::text('account', old('account'), ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']) !!}
                        <em class="invalid-feedback" id="account-error">
                        </em>
                    </div>





                    <div class="col-xs-12 form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                        {!! Form::label('type','Type', ['class' => 'control-label']) !!} 
                        {!!Form::select('type', array('4' => 'Revenue or Income', '5' => 'Expenses'), '0', ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']); !!}
                        <em class="invalid-feedback" id="type-error">
                        </em>
                    </div>



                    <div class="col-xs-12 form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                        {!! Form::label('status','Status', ['class' => 'control-label']) !!} 
                        {!!Form::select('status', array('1' => 'Permanent', '2' => 'Temporary'), '0', ['autocomplete' => 'off','class' => 'form-control', 'placeholder' => '']); !!}
                        <em class="invalid-feedback" id="status-error">
                        </em>
                    </div>



                  <div class="col-xs-12 form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                      {!! Form::label('amount','Amount', ['class' => 'control-label']) !!} 
                      {!! Form::number('amount', old('amount'), ['autocomplete' => 'off','class' => 'form-control money ','step'=>'any', 'placeholder' => '']) !!}
                      <em class="invalid-feedback" id="amount-error">
                      </em>
                  </div>




                 </div>

            
              </div>
               
              

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               {!!  Form::hidden('period',$period,['id'=>'period']) !!}
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



@stop
@section('js') 

<!-- <script src="{{ asset('vendor/masking/jquery.mask.min.js') }}"></script> -->

<script type="text/javascript">

  $(document).ready(function(){



       addCommas = function(input){
        // If the regex doesn't match, `replace` returns the string unmodified
        return (input.toString()).replace(
          // Each parentheses group (or 'capture') in this regex becomes an argument 
          // to the function; in this case, every argument after 'match'
          /^([-+]?)(0?)(\d+)(.?)(\d+)$/g, function(match, sign, zeros, before, decimal, after) {

            // Less obtrusive than adding 'reverse' method on all strings
            var reverseString = function(string) { return string.split('').reverse().join(''); };

            // Insert commas every three characters from the right
            var insertCommas  = function(string) { 

              // Reverse, because it's easier to do things from the left
              var reversed           = reverseString(string);

              // Add commas every three characters
              var reversedWithCommas = reversed.match(/.{1,3}/g).join(',');

              // Reverse again (back to normal)
              return reverseString(reversedWithCommas);
            };

            // If there was no decimal, the last capture grabs the final digit, so
            // we have to put it back together with the 'before' substring
            return sign + (decimal ? insertCommas(before) + decimal + after : insertCommas(before + after));
          }
        );
      }


    table = $('#table-income-statement').DataTable({
        ordering: false,
        iDisplayLength: 100,
        bPaginate: false,
        bLengthChange: false,
        processing:true,
        serverSide:true,
        button:false,
        searching:false,
        paging:false,
        info:false,
        // scrollY:500,
        // scrollX:true,
        // scrollCollapse:true,
         ajax:{
            url: "{{ route('admin.income.statement.view',[$month,$year]) }}",
         },
         columns:[
         {
            data: 'title',
            name: 'title',
         },
         {
            data: 'account',
            name: 'account',
         },
         {
            data: 'col1',
            name: 'col1',
         },
        
         {
            data: 'col2',
            name: 'col2',
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