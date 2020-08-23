<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Payslip - [{{ $user->name }}]</title>
    <link rel="stylesheet" href="style.css" media="all" />
    <style media="screen">
    .clearfix:after {
      content: "";
      display: table;
      clear: both;
    }

    a {
      color: #5D6975;
      text-decoration: underline;
    }

    body {
      position: relative;
      width: 21cm;
      height: 29.7cm;
      margin: 0 auto;
      color: #001028;
      background: #FFFFFF;
      font-family: Arial, sans-serif;
      font-size: 12px;
      font-family: Arial;
    }

    header {
      padding: 10px 0;
      margin-bottom: 10px;
    }

    #logo {
      text-align: center;
      margin-bottom: 10px;
    }

    #logo img {
      width: 90px;
    }

    h1 {

      color: #5D6975;
      font-size: 2em;
      line-height: 1em;
      font-weight: normal;
      text-align: left;
      margin: 0 0 10px 0;
    }
    h3 {
      color: #000;
      font-size: 2em;
      line-height: 1em;
      font-weight: normal;
      text-align: center;
      margin: 0 0 8px 0;
    }
    h4 {
      color: #000;
      font-size: 1.8em;
      line-height: 1em;
      font-weight: normal;
      text-align: center;
      margin: 0 0 10px 0;
    }

    #project {
      float: left;
    }

    #project span {
      color: #5D6975;
      text-align: right;
      width: 52px;
      margin-right: 10px;
      display: inline-block;
      font-size: 0.8em;
    }

    #company {
      float: right;
      text-align: right;
    }

    #project div,
    #company div {
      white-space: nowrap;
    }

    table {
      width: 90%;
      border-collapse: collapse;
      border-spacing: 0;
      margin-bottom: 20px;
    }

    table tr:nth-child(2n-1) td {
      background: #F5F5F5;
    }

    table th,
    table td {
      text-align: center;
    }

    table th {
      padding: 5px 10px;
      color: #5D6975;
      border-bottom: 1px solid #C1CED9;
      white-space: nowrap;
      font-weight: normal;
      font-size: 16px;
    }
    table tfoot td {
      padding: 5px 10px;
      color: #5D6975;
      border-top: 1px solid #C1CED9;
      white-space: nowrap;
      font-weight: normal;
      font-size: 16px;
    }
    table tfoot td {
      padding: 5px 5px;
      color: #000;
      border-top: 1px solid #C1CED9;
      white-space: nowrap;
      font-weight: normal;
      font-size: 14px;
    }
    table tfoot td {
      background: #fff9a3 !important;
    }

    table .service,
    table .desc {
      text-align: left;
    }

    table td {
      padding: 5px;

      font-size: 14px;

    }

    table td.service,
    table td.desc {
      vertical-align: top;
    }

    table td.unit,
    table td.qty,
    table td.total {
      font-size: 1.2em;
    }

    table td.grand {
      border-top: 1px solid #5D6975;;
    }

    .total{
      text-align: right;
      background: #fff9a3;
      padding: 15px;
      font-size: 18px;
      line-height: 20px;
    }
    .total p{

      font-size: 18px;
      line-height: 20px;
      margin: 0;
    }

    .section-holder{margin-bottom: 20px;}
    .payslip-breakdown tr td:first-child{width: 45%; text-align: left;}
    .payslip-breakdown tr td:last-child{text-align: right;}
    .payslip-deduction tr td:last-child{color:red;}
    .payslip-breakdown tr th:first-child{width: 45%; text-align: left;}
    </style>
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="logo.jpg">
      </div>
      <div class="" style="text-align:center;">
        <h3>Stoutcon Emergency Response Services</h3>
        <h4>PAYSLIP</h4>
      </div>
    </header>
    <div class="employee-details" style="font-size:14px;">
      <div class="employee-info" style="width:45%; display:inline-block;">
        <p><strong>Employee Name:</strong> {{ $user->name }}</p>
        <p><strong>Employee Position:</strong> {{ $user->position }}</p>
        <p><strong>Employee Status:</strong> {{  $user->work_status }}</p>
      </div>
      <div class="payslip-info" style="width:50%; display:inline-block;">
        <p><strong>Project:</strong> {{ $user->project }} <strong></p>
        <p><strong>Location:</strong> {{ $user->location }} </p>
        <p><strong>Period:</strong> {{ $month_year }}]</p>
      </div>
    </div>

    <div class="employee-details" style="font-size:14px;">
      <div class="employee-info" style="width:45%; display:inline-block;">
        <p><strong>Rate/Hr.:</strong> {{ number_format($user->rate_per_hour,2) }}</p>
      </div>
      <div class="payslip-info" style="width:50%; display:inline-block;">
        <p><strong>Night Diff/hr.:</strong> {{ number_format($user->night_differencial,2) }} <strong></p>
      </div>
    </div>

    
     
    <main>
      <!-- Repeatable Section-->
      <div class="section-holder">
        <h4 style="text-align:left; margin-top:10px; margin-bottom:0;">EARNINGS</h4>
        <table class="payslip-breakdown">
          <thead>
            <tr>
                <td>No. of Days </td>
                <td>Rate/8 hrs.</td>
                <td>Night Diff/8Hrs </td>
                <td>Basic Salary</td>
               
            </tr>
         </thead>  

             
          <tbody>
            <tr>
                <td>{{ $payslip->days }} </td>
                <td>{{ $payslip->basic_salary ? number_format(($payslip->basic_salary) / $payslip->days,2) : '0.00' }}</td>
                <td></td>
                <td>{{ number_format($payslip->basic_salary,2) }}</td>
      
            </tr>
            <tr>
                <td>{{ $payslip->nights }}< </td>
                <td></td>
                <td>{{ $payslip->night_differencial ? number_format(($payslip->night_differencial) / $payslip->nights,2) : '0.00'}} </td>
                <td style="border-bottom: 1px solid black">{{ number_format($payslip->night_differencial,2) }}</td>
               
            </tr>
            <tr>
                <td>Total Basic Salary </td>
                <td></td>
                <td></td>
                <td>{{ number_format($payslip->total_basic_salary,2) }}</td>
               
            </tr>
            <tr>
                <td>Plus: Overtime </td>
                <td ></td>
                <td></td>
                <td>{{ number_format($payslip->overtime,2) }}</td>
             
            </tr>
            <tr>
                <td>Benefits</td>
                <td ></td>
                <td></td>
                <td>{{ number_format($payslip->benefits,2) }}</td>
              
            </tr>
            <tr>
                <td>Other Benefits </td>
                <td ></td>
                <td></td>
                <td style="border-bottom: 1px solid black">{{ number_format($payslip->other_benefits,2) }}</td>
               
            </tr>
           
          </tbody>
          <tfoot>
            <tr>
              <td>GROSS PAY:</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td><b>{{ number_format($payslip->gross_pay,2) }}</b></td>
            </tr>
          </tfoot>
        </table>
      </div>



   <div class="section-holder">
        <h4 style="text-align:left; margin-top:10px; margin-bottom:0;">DEDUCTION</h4>
        <table class="payslip-breakdown">
          <tbody>
        
            <tr>
             
                <td> &nbsp; SSS:</td>
                <td>{{ number_format($user->sss_contribution,2) }}</td>
            </tr>
            <tr>
             
                <td>&nbsp;Philhealth:</td>
                <td>{{ number_format($payslip->philhealth,2) }}</td>
            </tr>
            <tr>
                
                <td>&nbsp;HDMF:</td>
                <td>{{ number_format($payslip->pag_ibig,2) }}</td>
            </tr>
            <tr>
               
                <td>&nbsp;Tax Withheld:</td>
                <td> {{ number_format($payslip->tax,2) }}</td>
            </tr>
            <tr>
  
               
                <td>&nbsp;Tardiness/Late:</td>
                <td>{{ number_format($payslip->tardiness,2) }}</td>
            </tr>
            <tr>
             
                <td>&nbsp;Total Deductions:</td>
                <td style="border-bottom: 1px solid black"> {{ number_format($payslip->total_deduction,2) }}</td>
            </tr>

        </tbody>
        
        </table>
      </div>



      <!-- End of Repeatable Section-->
      <div class="net-pay" style="padding:10px; ">
        <h3 style="color:green;">NET PAY: <span style="text-align:right;"> <b>{{ number_format($payslip->net_pay,2) }}</b></span></h3>
      </div>

    </main>
    <footer>

    </footer>
  </body>
</html>
