<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarConnect App - Receipt - SaleID : {{$sale->id}}</title>
    <link type="text/css" rel="stylesheet" href="{{asset('/css/receipt.css')}}" media="all" >
    <link type="text/css" rel="stylesheet" href="{{asset('/css/no-print.css')}}" media="print" >
</head>
<div id="wrapper">
    <div id="receipt-header">
      <h3 id="car-dealer-name">CarConnect - Zurich</h3>
      <p>Address: Rotelstrasse 86, 8057 Zurich, Switzerland</p>
      <p>Tel: +41 44 363 52 10</p>
      <p>Reference Receipt: <strong>{{$sale->id}}</strong></p>
    </div>
    <div id="receipt-body">
      <table class="tb-sale-detail">
        <thead>
          <tr>
            <th>#</th>
            <th>Car Brand/Model</th>
            <th>Pick-Up Date</th>
            <th>Drop-off Date</th>
            <th>Daily Price</th>
            <th>Days</th>
            <th>Quantity</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($saleDetails as $saleDetail)
            <tr>
                <td width="30">{{$saleDetail->id}}</td>
                <td width="30">{{$saleDetail->car->brand}} {{$saleDetail->car->model}}</td>
                <td width="180">{{$saleDetail->pick_up_date}}</td>
                <td width="180">{{$saleDetail->drop_off_date}}</td>
                <td width="30">{{$saleDetail->car->price}}</td>
                <td width="30">{{
                    (strtotime($saleDetail->drop_off_date) - strtotime($saleDetail->pick_up_date))/ 86400 + 1
                }}</td>
                <td  width="30">X {{$saleDetail->quantity}}</td>
                <td width="30">${{
                    ((strtotime($saleDetail->drop_off_date) - strtotime($saleDetail->pick_up_date))/ 86400 + 1) * ($saleDetail->car->price) * ($saleDetail->quantity)
                }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <table class="tb-sale-total">
        <tbody>
          <tr>
            <td>Total Quantity</td>
            <td>{{$saleDetails->count()}}</td>
            <td>Total</td>
            <td>${{number_format($sale->total_price, 2)}}</td>
          </tr>
          <tr>
            <td colspan="2">Payment Type</td>
            <td colspan="2">{{$sale->payment_type}}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div id="receipt-footer">
      <p>Thank You!</p>
    </div>
    <div id="buttons">
      <a href="/cash">
        <button class="btn btn-back">
          Back to Cash
        </button>
      </a>
      <button class="btn btn-print" type="button"  onclick="window.print(); return false;">
        Print
      </button>
    </div>
  </div>
</body>
</html>