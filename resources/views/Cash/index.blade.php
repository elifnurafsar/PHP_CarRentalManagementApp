@extends('layouts.app')

@section('content')
<head>
    <link href='https://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.4/themes/flick/jquery-ui.css' rel='stylesheet'>
    <style>
        .metro-skin.ui-widget {
          font-family: 'Open Sans', sans-serif;
          background: #000000; 
          border-radius: 0;
          -webkit-border-radius: 0;
          -moz-border-radius: 0;
          box-shadow: 0 2px 10px 0 rgba(0, 0, 0, 0.16);
        }
        .datePickerUI{
            margin: 1% 1%;
        }
    </style>
</head>
<div class="container">
    <div id="dialog-form" class="datePickerUI" title="Select Dates">
      <form>
        <div class="form-group">
            <label for="pickUpDate">Pick-up Date</label>
            <input class="form-control input-sm metro-skin" type="text" name="pick_up_date" id="pick_up_date" style="width: 100%; display: inline;">
        </div>
        <div class="form-group">
            <label for="dropOffDate">Drop-off Date</label>
            <input class="form-control input-sm metro-skin" type="text" name="drop_off_date" id="drop_off_date" style="width: 100%; display: inline;">
        </div>
        <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
      </form>
    </div>
    <button class="btn btn-danger btn-block" style="margin-bottom: 25px;" id="btn-hide-dates">Hide Dates</button>

    <div class="row p-3" id="customer-detail"></div>
    <div class="row justify-content-center py5">
      <div class="col">
        <button class="btn btn-primary btn-block" id="btn-show-customers">View All Customers (Drivers)</button>
        <div id="selected-customer"></div>
        <div id="renting-detail"></div>
      </div>
    </div>
    <div class="row justify-content-center py5">
      <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
          @foreach($categories as $category)
            <a class="nav-item nav-link" data-id=" {{$category->id}}" data-toggle="tab">
              {{$category->name}}
            </a>
          @endforeach
        </div>
      </nav>
      <div id="list-of-cars" class="row mt-2"></div>
      <button class="btn btn-primary btn-block" id="btn-hide-cars"  data-id="999999">Hide Cars</button>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h3 class="totalAmount"></h3>
        <h3 class="changeAmount"></h3>
        <div class="input-group mb-3">
           <div class="input-group-prepend">
            <span class="input-group-text">$</span>
           </div> 
           <input type="number" id="recieved-amount" class="form-control">
        </div>
        <div class="form-group">
          <label for="payment">Payment Type</label>
          <select class="form-control" id="payment-type">
            <option value="cash">Cash</option>
            <option value="credit card">Credit Card</option>
          </select>
        </div>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-save-payment" disabled>Save Payment</button>
      </div>
    </div>
  </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
$(document).ready(function(){
    var pick_up_date = "";
    var drop_off_date = "";
    var selected_customer_id = "";
    var selected_customer_name = "";
    var saleID = "";
    $("#pick_up_date").datepicker({dateFormat: 'yy/mm/dd',
      inline: true,
      altField: '#datepicker_value'
    });
    $("#drop_off_date").datepicker({dateFormat: 'yy/mm/dd',
      inline: true,
      altField: '#datepicker_value'
    });
    $("#pick_up_date").addClass("metro-skin");
    $("#customer-detail").hide();
    $("#btn-hide-cars").hide();
    $("#btn-show-customers").click(function(){
        if( $("#customer-detail").is(":hidden")){
            $.get("/cash/getCustomers", function(data){
                $("#customer-detail").html(data);
                $("#customer-detail").slideDown('fast');
                $("#btn-show-customers").html("Hide Customers").removeClass('btn-primary').addClass('btn-danger');
            })
        }
        else{
            $("#renting-detail").hide();
            $("#selected-customer").hide();
            $("#btn-show-customers").html("View All Customers (Drivers)").removeClass('btn-danger').addClass('btn-primary');
            $("#customer-detail").slideUp('fast');
        }
    });

    $("#btn-hide-dates").click(function(){
      if( $("#dialog-form").is(":hidden")){
        $("#dialog-form").show();
        $("#btn-hide-dates").html("Hide Dates").removeClass('btn-primary').addClass('btn-danger');
      }
      else{
        $("#dialog-form").hide();
        $("#btn-hide-dates").html("Show Dates").removeClass('btn-danger').addClass('btn-primary');
      }
    });

    $("#btn-hide-cars").click(function(){
      $("#list-of-cars").hide();
      $("#btn-hide-cars").hide();
    });

    //cars by category
    $("#customer-detail").on("click", ".btn-customer", function(){
        selected_customer_id = $(this).data("id");
        selected_customer_name = $(this).data("name");
        $("#selected-customer").html('<br><h3>Customer: ' + selected_customer_name + '</h3>');
        //$("#selected-customer").show();
        $.get("/cash/getSaleDetailsByCustomer/"+selected_customer_id, function(data){
          $("#renting-detail").html(data);
        });
    });

    //pick-up and drop-off date
    $('#pick_up_date').change(function() {
        pick_up_date = $(this).datepicker('getDate')
        $("#drop_off_date").datepicker("option", "minDate", pick_up_date)
        var date = new Date(pick_up_date)
        pick_up_date = new Date(date.getTime() - date.getTimezoneOffset() * 60000)
    });
    $('#drop_off_date').change(function() {
        drop_off_date = $(this).datepicker('getDate')
        var date = new Date(drop_off_date)
        drop_off_date = new Date(date.getTime() - date.getTimezoneOffset() * 60000)
    });
    
    //customers on click
    $(".nav-link").click(function(){
      var temp_id = $(this).data("id");
      $.get("cash/getCarsByCategory/" + $(this).data("id"), function(data){
        $("#list-of-cars").hide();
        $("#list-of-cars").html(data);
        $("#list-of-cars").fadeIn('fast');
        console.log(">>>", temp_id)
        if(temp_id != null){
          $("#btn-hide-cars").show();
        }
          
      });
    })

    $("#list-of-cars").on("click", ".btn-car", function(){
        if(selected_customer_id == "" || pick_up_date == "" || drop_off_date == ""){
          alert("Warning! Please select a customer and dates.");
        }
        else{
          var car_id = $(this).data("id");
          $.ajax({
            type: "POST",
            data: {
              "_token" : $('meta[name="csrf-token"]').attr('content'),
              "car_id": car_id,
              "customer_id": selected_customer_id,
              "customer_name": selected_customer_name,
              "pick_up_date": pick_up_date.toISOString(),
              "drop_off_date": drop_off_date.toISOString(),
              "quantity" : 1
            },
            url: "/cash/AddToCard" ,
            success: function(data){
              $("#renting-detail").html(data);
              $("#renting-detail").show();
            }
          });
        }
    });

    $("#renting-detail").on('click', ".btn-confirm-order", function(){
      var sale_id = $(this).data("id");
      $.ajax({
        type: "POST",
        data: {
          "_token" : $('meta[name="csrf-token"]').attr('content'),
          "sale_id" : sale_id
        },
        url: "/cash/confirmOrderStatus",
        success: function(data){
          $("#renting-detail").html(data);
        }
      });
    });


  //delete
  $("#renting-detail").on('click', ".btn-delete-sale_detail", function(){
    var sale_detail_id = $(this).data("id");
    $.ajax({
      type: "POST",
        data: {
          "_token" : $('meta[name="csrf-token"]').attr('content'),
          "sale_detail_id": sale_detail_id
        },
      url: "/cash/deleteSaleDetail",
      success: function(data){
        $("#renting-detail").html(data);
      }
    });
  });

  // when an user clicks on the payment button
  $("#renting-detail").on('click', ".btn-payment", function(){
    var totalAmout = $(this).attr('data-totalamount');
    $(".totalAmount").html("Total Amount $" + totalAmout);
    $("#recieved-amount").val('');
    $(".changeAmount").html('');
    saleID = $(this).data('id');
  });

  // calcuate change
  $("#recieved-amount").keyup(function(){
    var totalAmount = $(".btn-payment").attr('data-totalamount');
    var recievedAmount = $(this).val();
    var changeAmount = recievedAmount - totalAmount;
    $(".changeAmount").html("Total Change: $" + changeAmount);

    //check if there are enough money to proceed to payment, then enable or disable save payment button
    if(changeAmount >= 0){
      $('.btn-save-payment').prop('disabled', false);
    }else{
      $('.btn-save-payment').prop('disabled', true);
    }
  });

   // save payment
   $(".btn-save-payment").click(function(){
    var recieved_amount = $("#recieved-amount").val();
    var payment_type =$("#payment-type").val();
    var sale_id = saleID;
    $.ajax({
      type: "POST",
      data: {
        "_token" : $('meta[name="csrf-token"]').attr('content'),
        "sale_id" : sale_id,
        "recieved_amount" : recieved_amount,
        "payment_type" : payment_type
      },
      url: "/cash/savePayment",
      success: function(data){
        window.location.href = "/cash/showReceipt/"+sale_id;
      }
    });
  });

  //increase quantity
  $("#renting-detail").on('click', ".btn-increase-quantity", function(){
    var sale_detail_id = $(this).data("id");
    $.ajax({
      type: "POST",
        data: {
          "_token" : $('meta[name="csrf-token"]').attr('content'),
          "sale_detail_id": sale_detail_id
        },
      url: "/cash/increaseQuantity",
      success: function(data){
        $("#renting-detail").html(data);
      }
    });
  });

  
  //decrease quantity
  $("#renting-detail").on('click', ".btn-decrease-quantity", function(){
    var sale_detail_id = $(this).data("id");
    $.ajax({
      type: "POST",
        data: {
          "_token" : $('meta[name="csrf-token"]').attr('content'),
          "sale_detail_id": sale_detail_id
        },
      url: "/cash/decreaseQuantity",
      success: function(data){
        $("#renting-detail").html(data);
      }
    });
  });

});
</script>
@endsection