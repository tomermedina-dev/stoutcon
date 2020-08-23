@section('plugins.Datatables', true)
@section('plugins.Chartjs', true)
@extends('adminlte::page')

@section('content')
	

	  <div class="row">

	  	  <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ $employee_count }}</h3>

                <p>Total Employee</p>
              </div>
              <div class="icon">
                <i class="fas fa-fw fa-users"></i>
              </div>
              <a href="{{ route('admin.accounts.index') }}#add" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->


          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>Payroll</h3>

                <p> {{ $current_month }} </p>
              </div>
              <div class="icon">
                <i class="fas fa-fw fa-money-bill-wave"></i>
              </div>
              <a href="{{ url($url_current_month_year) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>Attendance</h3>

                <p>Biometrics</p>
              </div>
              <div class="icon">
                <i class="fas fa-fw fa-fingerprint"></i>
              </div>
              <a href="{{ route('admin.biometrics') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->

   
      
          <!-- ./col -->
        </div>



         <div class="row">



          <!-- Left col -->
          <section class="col-lg-10 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  INCOME AND EXPENSES
                </h3>
                <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                      <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                    </li>
                  </ul>
                </div>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                  <div class="chart tab-pane active" id="revenue-chart"
                       style="position: relative; height: 300px;">
                      <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>                         
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                    <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>                         
                  </div>  
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

          </section>
          <!-- /.Left col -->





          <div class="col-md-2">
            <!-- Info Boxes Style 2 -->
         <!--    <div class="info-box mb-3 bg-warning">
              <span class="info-box-icon"><i class="fas fa-tag"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Inventory</span>
                <span class="info-box-number">5,200</span>
              </div>
         
            </div> -->
            <!-- /.info-box -->
            <a href="{{ route('admin.pdf.trial',[ 'month' => $month, 'year' => $year] ) }}" target="_blank">
            <div class="info-box mb-3 bg-dark">
              <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Trial Balance</span>
                <span class="info-box-number">Download PDF</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            </a>

            <!-- /.info-box -->
            <a href="{{ route('admin.pdf.income.statement',[ 'month' => $month, 'year' => $year] ) }}" target="_blank">
            <div class="info-box mb-3 bg-secondary">
              <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Income Statement</span>
               <span class="info-box-number">Download PDF</span>
              </div>
              <!-- /.info-box-content -->
            </div>
        	</a>
            <!-- /.info-box -->
             <a href="{{ route('admin.pdf.balance.sheet',[ 'month' => $month, 'year' => $year] ) }}" target="_blank">
            <div class="info-box mb-3 bg-light">
              <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Balance Sheet</span>
                <span class="info-box-number">Download PDF</span>
              </div>
              <!-- /.info-box-content -->
            </div>
        	</a>
            <!-- /.info-box -->
            <!-- /.card -->
          </div>
      
        </div>
	
@stop

@section('js')

<script type="text/javascript">
	 /* Chart.js Charts */
  // Sales chart
  var salesChartCanvas = document.getElementById('revenue-chart-canvas').getContext('2d');
  //$('#revenue-chart').get(0).getContext('2d');

  var credit = <?php echo json_encode($credit) ?>;
  var debit = <?php echo json_encode($debit) ?>;
  var pie = <?php echo json_encode($pie) ?>;

  var salesChartData = {
    labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July','August','September','October','November','December'],
    datasets: [
      {
        label               : 'Income',
        backgroundColor     : 'rgba(60,141,188,0.9)',
        borderColor         : 'rgba(60,141,188,0.8)',
        pointRadius          : false,
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(60,141,188,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data                :  debit 
      },
      {
        label               : 'Expenses',
        backgroundColor     : 'rgba(210, 214, 222, 1)',
        borderColor         : 'rgba(210, 214, 222, 1)',
        pointRadius         : false,
        pointColor          : 'rgba(210, 214, 222, 1)',
        pointStrokeColor    : '#c1c7d1',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(220,220,220,1)',
        data                :  credit
      },
    ]
  }

  var salesChartOptions = {
    maintainAspectRatio : false,
    responsive : true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        gridLines : {
          display : false,
        }
      }],
      yAxes: [{
        gridLines : {
          display : false,
        }
      }]
    }
  }



    // This will get the first returned node in the jQuery collection.
  var salesChart = new Chart(salesChartCanvas, { 
      type: 'line', 
      data: salesChartData, 
      options: salesChartOptions
    }
  )

  // Donut Chart
  var pieChartCanvas = $('#sales-chart-canvas').get(0).getContext('2d')
  var pieData        = {
    labels: [
        'Expenses', 
        'Income',
    ],
    datasets: [
      {
        data: pie,
        backgroundColor : ['#f56954', '#00a65a'],
      }
    ]
  }
  var pieOptions = {
    legend: {
      display: false
    },
    maintainAspectRatio : false,
    responsive : true,
  }
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  var pieChart = new Chart(pieChartCanvas, {
    type: 'doughnut',
    data: pieData,
    options: pieOptions      
  });


</script>

@stop