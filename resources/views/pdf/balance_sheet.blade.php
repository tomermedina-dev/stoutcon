<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Stoutcon Balance Sheet</title>
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
      margin-bottom: 30px;
    }

    #logo {
      text-align: center;
      margin-bottom: 10px;
    }

    #logo img {
      width: 90px;
    }

    
    h3 {
      color: #000;
      font-size: 2em;
      line-height: 1em;
      font-weight: normal;
      text-align: center;
      margin: 0 0 10px 0;
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
      width: 100%;
      border-collapse: collapse;
      border-spacing: 0;
      margin-bottom: 20px;
    }
/*
    table tr:nth-child(2n-1) td {
      background: #F5F5F5;
    }*/

    table th,
    table td {
      text-align: left;
    }

    table th {
      padding: 5px 20px;
      color: #5D6975;
      border-bottom: 1px solid #C1CED9;
      white-space: nowrap;
      font-weight: normal;
    }

    table .service,
    table .desc {
      text-align: left;
    }

    table td {
      padding: 5px;

      font-size: 16px;

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
      background: #fff9a3 !important;
      padding: 5px;
      font-size: 15px;
      line-height: 20px;
    }
    .total {

      font-size: 15px;
      line-height: 20px;
      margin: 0;
    }

    .section-holder{margin-bottom: 20px;}

    .h1 {

      color: #5D6975;
      font-size: 20px !important;
      line-height: 1em;
      font-weight: bold;
      text-align: left;
      margin: 0 0 10px 0;
    }

    </style>
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="logo.jpg">
      </div>
      <div class="" style="text-align:center;">
        <h3>Stoutcon Emergency Response Services</h3>
        <h4>BALANCE SHEET - {{ $period }}</h4>
      </div>


    </header>
    <main>
      <!-- Repeatable Section-->
      <div class="section-holder">

          <table>
            <tbody>
               @foreach($data as $row => $value)
               @if($value->total != '-')
                   @if($value->total == '--')
                   <tr>
                   @else
                   <tr  class="{{ $value->total  ? 'total'  : ''}} {{ $value->title  ? 'h1'  : ''}}">
                   @endif
                       <td>{{ $value->title }}</td>
                       <td>{{ $value->category }}</td>
                       <td>{{ $value->account }}</td>
                       <td>{{ $value->amount }}</td>
                       <td>{{ $value->total }}</td>
                   </tr>
                 
               @endif
               @endforeach

         </tbody>
        </table>
        
      </div>
      <!-- End of Repeatable Section-->

    </main>
    <footer>

    </footer>
  </body>
</html>
