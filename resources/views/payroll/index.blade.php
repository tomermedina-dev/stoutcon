@section('plugins.Datatables', true)
@section('plugins.Select2', true)
@inject('request', 'Illuminate\Http\Request')
@extends('adminlte::page')

@section('content_header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>{{ $month_year }}</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
       <li class="breadcrumb-item"><a href="{{ route('admin.payroll.generate') }}">Payslip</a></li>
        <li class="breadcrumb-item active">List</li> 
      </ol>
    </div>
  </div>
@stop


@section('content')
  
  <div class="row">

    <div class="col-12">
      <div class="callout callout-info h-100">
        &nbsp;
        <a class="btn btn-success float-right text-decoration-none" name="accounts" target="blank" href="{{ route('admin.payslip.month',[$month,$year]) }}">
          <i class="fas fa-envelope"></i> Send All Slips to Employee Email
        </a>
        &nbsp;

      </div>
    </div>
  

    <div class="col-md-12">



           <div class="card card-secondary">
            <div class="card-header">
              <h3 class="card-title">&nbsp;</h3>
            </div>

           
                 <div class="card-body table-responsive">

                    
                   <table id="table-payslip" class="table table-bordered table-striped compact" width="100%"> 
                      <thead class="thead-dark">
                          <tr>
                          
                              <th>Employee Name</th>
                              <th>Gross</th>
                              <th>Total Deduction</th>
                              <th>Net Amount</th>
                              <th width="160px;">&nbsp;</th>

                          </tr>
                      </thead>
                       <tbody>
                    
                                  
                      </tbody>

                         <tfoot>
                            <tr>
                                <th>TOTAL</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>&nbsp;</th>
                            </tr>
                        </tfoot>

                    </table>

                  
                 </div>


                 <div class="card-footer">

                 
                 </div>

          </div>
        </div>
    </div>


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



    table = $('#table-payslip').DataTable({
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
          url: "{{ route('admin.payroll.month',['month' => $month,'year' => $year]) }}",
       },
       columns:[
  
       {
          data: 'name',
          name: 'name',
       },
       {
          data: 'gross_pay',
          name: 'gross_pay',
       },
      
       {
          data: 'total_deduction',
          name: 'total_deduction',
       },
       {
          data: 'net_pay',
          name: 'net_pay',
       },
       {
          data: 'action',
          name: 'action',
          class:'project-actions text-right',
          orderable: false,
       },

       ],
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            // pageTotal = api
            //     .column( 2, { page: 'current'} )
            //     .data()
            //     .reduce( function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0 );
 
            // Update footer
            $( api.column( 2 ).footer() ).html(
                //'$'+pageTotal +' ( $'+ total +' total)'
                 addCommas((total).toFixed(2))
            );


 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 1 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            // pageTotal = api
            //     .column( 2, { page: 'current'} )
            //     .data()
            //     .reduce( function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0 );
 
            // Update footer
            $( api.column( 1 ).footer() ).html(
                //'$'+pageTotal +' ( $'+ total +' total)'
                 addCommas((total).toFixed(2))
            );



            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            // pageTotal = api
            //     .column( 2, { page: 'current'} )
            //     .data()
            //     .reduce( function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0 );
 
            // Update footer
            $( api.column( 3 ).footer() ).html(
                //'$'+pageTotal +' ( $'+ total +' total)'
                 addCommas((total).toFixed(2))
            );


        }
     });


table.ajax.reload();


  
    

  });
  
</script>

@stop