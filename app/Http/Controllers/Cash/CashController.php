<?php

namespace App\Http\Controllers\Cash;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customer;
use App\Category;
use App\Car;
use App\Sale;
use App\SaleDetail;

class CashController extends Controller
{
    
    public function index(){
        $categories = Category::all();
        return view('cash.index')->with('categories', $categories);
    }

    public function GetCustomers(){
        $customers = Customer::all();
        //$customers = Customer::paginate(3);
        $html = '';
        foreach($customers as $customer){
            $sale = $sale = Sale::where('customer_id', $customer->id)->first();
            $status = "";
            if($sale)
                $status = $sale->sale_status;
            $html .= '<div class="col-sm offset-md-1">';
            $html .= 
            '<button class="btn btn-primary btn-customer" data-id="'.$customer->id.'"  data-name="'.$customer->name." ".$customer->surname.'">
                <img width="30px" src="'.url('/images/driver.svg').'"/>
                <br>';
            if($status == "unpaid"){
                $html .= '<span class="badge badge-danger">'.$customer->name." ".$customer->surname.'</span>';
            }else{ // a table is not available
                $html .= '<span class="badge badge-success">'.$customer->name." ".$customer->surname.'</span>';
            }
            $html .='</button>';
            $html .= '</div>';
            $html .= '<br>';
        }
        return $html;
    }

    public function GetCarsByCategory($category_id){
        $cars = Car::where('category_id', $category_id)->get();
        //$html = '';
        $html='
        <style>
        .containerD{
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .card{
            display: flex;
            align-items: center;
            justify-content: center;
            width: 500px;
            maxWidth: "max-content";
            overflow: "auto";
            background: inherit;
            border-style: hidden;
            margin: 1% 1%;
        }
        </style>
        <div class="containerD">';
        foreach($cars as $car){
            $html .='
            <div class="card">
                <a class="btn btn-outline-secondary btn-car" data-id="'.$car->id.'">
                    <img class="img-fluid" src="'.url('car_images/'.$car->image).'">
                    
                    <div class="container">
                        '.$car->brand." ".$car->model.'
                        <br>
                        '.$car->price.'<img width="20px" src="'.url('images/TL.svg').'"/>
                    </div>
                </a>
            </div>
            ';
        }
        $html .='</div>';
        return $html;
    }

    public function AddToCard(Request $request){
        //return $request->car_id;
        $car = Car::find($request->car_id);
        $customer_id = $request->customer_id;
        $quantity = $request->quantity;
        $pick_up_date = $request->pick_up_date;
        $drop_off_date = $request->drop_off_date;
        $date1 = substr( $pick_up_date, 0, -14);
        $date2 = substr( $drop_off_date, 0, -14);
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        $secs = $date2 - $date1;
        $days = $secs / 86400 + 1;
        $sale = Sale::where('customer_id', $customer_id)->where('sale_status','unpaid')->first();
       
        // if there is no sale for the selected customer, create a new sale record
        if(!$sale){
            $user = Auth::user();
            $sale = new Sale();
            $sale->customer_id = $customer_id;
            $sale->user_id = $user->id;
            $sale->total_price = $sale->total_price + ($quantity * $car->price * $days);
            $sale->payment_type = "cash";
            $sale->save();
            $sale_id = $sale->id;
        }else{ // if there is a sale on the selected customer
            $sale_id = $sale->id;
            $total_price_ = $sale->total_price + ($quantity * $car->price * $days);
            Sale::where('id', $sale_id)->update(['total_price'=>$total_price_]);
            //echo($quantity * $car->price * $days);
        }
        // add ordered car to the sale_details table
        $saleDetail = new SaleDetail();
        $saleDetail->sale_id = $sale_id;
        $saleDetail->car_id = $car->id;
        $saleDetail->quantity = $quantity;
        $p_date = date('Y-m-d', strtotime(str_replace('-', '/', $pick_up_date)));
        $d_date = date('Y-m-d', strtotime(str_replace('-', '/', $drop_off_date)));
        $saleDetail->pick_up_date = $p_date;
        $saleDetail->drop_off_date = $d_date;
        $saleDetail->save();
        //return "Sale id:".$saleDetail->sale_id." Car id:".$saleDetail->car_id." Quantity:".$saleDetail->quantity."\nPick-up:".$saleDetail->pick_up_date."\nDrp-off".$saleDetail->drop_off_date;
        
        $html = $this->GetSaleDetails($sale_id);
        return $html;
    }

    public function GetSaleDetailsByCustomer($customer_id){
        $sale = Sale::where('customer_id', $customer_id)->where('sale_status','unpaid')->first();
        $html = '';
        if($sale){
            $sale_id = $sale->id;
            $html .= $this->GetSaleDetails($sale_id);
        }else{
            $html .= "Not Found Any Sale Details for the Selected Customer.";
        }
        return $html;
    }

    private function GetSaleDetails($sale_id){
        $html = '<p>Sale ID: '.$sale_id.'</p>';
        $saleDetails = SaleDetail::where('sale_id', $sale_id)->get();
        $html .= '<div class="table-responsive-md" style="overflow-y:scroll; height: 400px; border: 1px solid #343A40">
        <table class="table table-stripped table-dark">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Car</th>
                    <th scope="col">Daily Price</th>
                    <th scope="col">  Quantity  </th>
                    <th scope="col">Pick-up Date</th>
                    <th scope="col">Drop-off Date</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>';
        $showBtnPayment = true;
        foreach($saleDetails as $saleDetail){
          
            $decrease_button = '';
            if($saleDetail->quantity > 1){
                $decrease_button = '<button data-id="'.$saleDetail->id.'" class="btn btn-danger btn-sm btn-decrease-quantity">-</button>';
            }
            
            $html .= '
            <tr>
                <td>'.$saleDetail->id.'</td>
                <td>'.$saleDetail->car->brand." ".$saleDetail->car->model.'</td>
                <td>'.$saleDetail->car->price.'</td>
                <td style="width: 15%">'.$decrease_button.$saleDetail->quantity.'<button data-id="'.$saleDetail->id.'" class="btn btn-primary btn-sm btn-increase-quantity">+</button></td>
                <td>'.$saleDetail->pick_up_date.'</td>
                <td>'.$saleDetail->drop_off_date.'</td>
                <td>'.$saleDetail->status.'</td>';

                if($saleDetail->status == "nonConfirmed"){
                    $showBtnPayment = false;
                    $html .= '<td><a data-id="'.$saleDetail->id.'" class="btn btn-danger btn-delete-sale_detail"><i class="far fa-trash-alt"></a></td>';
                }else{ 
                    $html .= '<td><i class="fas fa-check-circle"></i></td>';
                }
            $html .= '</tr>';
        }
        $html .='</tbody></table></div>';

        $sale = Sale::find($sale_id);
        $html .= '<hr>';
        $html .= '<h3>Total Amount: $'.number_format($sale->total_price).'</h3>';

        if($showBtnPayment){
            $html .= '<button data-id="'.$sale_id.'" data-totalAmount="'.$sale->total_price.'" class="btn btn-success btn-block btn-payment" data-toggle="modal" data-target="#paymentModal">Payment</button>';
        }else{
            $html .= '<button data-id="'.$sale_id.'" class="btn btn-warning btn-block btn-confirm-order">Confirm Order</button>';
        }

        return $html;
    }

    public function ConfirmOrderStatus(Request $request){
        $sale_id = $request->sale_id;
        $saleDetails = SaleDetail::where('sale_id', $sale_id)->update(['status'=>'confirmed']);
        $html = $this->GetSaleDetails($sale_id);
        return $html;
    }

    public function DeleteSaleDetail(Request $request){
        $sale_detail_id = $request->sale_detail_id;
        $sale_detail = SaleDetail::find($sale_detail_id);
        $sale_id = $sale_detail->sale_id;
        $car = Car::find($sale_detail->car_id);
       
        //get car's daily price
        $car_price = $car->price;
        //get the number of days they planned to rent
        $pick_up_date = $sale_detail->pick_up_date;
        $drop_off_date = $sale_detail->drop_off_date;
        $date1 = strtotime($pick_up_date);
        $date2 = strtotime($drop_off_date);
        $secs = $date2 - $date1;
        $days = $secs / 86400 + 1;
        $price_temp = ($car_price * $days * $sale_detail->quantity);
        $sale_detail->delete();
        //update total price
        $sale = Sale::find($sale_id);
        $sale->total_price = $sale->total_price - $price_temp;
        $sale->save();
        // check if there are any other sale details having the same sale_id or else display message that expresses the sale is empty
        $saleDetails = SaleDetail::where('sale_id', $sale_id)->first();
        if($saleDetails){
            $html = $this->GetSaleDetails($sale_id);
        }else{
            //delete the sale
            $sale->delete();
            $html = "Not Found Any Sale Details for the Selected Customer";
        }
        return $html;
    }

    public function SavePayment(Request $request){
        $sale_id = $request->sale_id;
        $recieved_amount = $request->recieved_amount;
        $payment_type = $request->payment_type;
        // update sale information in the sales table by using sale model
        $sale = Sale::find($sale_id);
        $sale->payment_type = $payment_type;
        $sale->sale_status = "paid";
        $sale->save();
        return  $sale_id;
    }

    public function ShowReceipt($sale_id){
        $sale = Sale::find($sale_id);
        $saleDetails = SaleDetail::where('sale_id', $sale_id)->get();
        return view('cash.showReceipt')->with('sale', $sale)->with('saleDetails', $saleDetails);
    }

    public function IncreaseQuantity(Request $request){
        $sale_detail_id = $request->sale_detail_id;
        $sale_detail = SaleDetail::find($sale_detail_id);
        $sale_detail->quantity = $sale_detail->quantity + 1;
        $sale_detail->save();

        $sale_id = $sale_detail->sale_id;
        $car_id = $sale_detail->car_id;
        $sale = Sale::find($sale_id);
        $car = Car::find($car_id);
       
        $pick_up_date = $sale_detail->pick_up_date;
        $drop_off_date = $sale_detail->drop_off_date;
        $date1 = strtotime($pick_up_date);
        $date2 = strtotime($drop_off_date);
        $secs = $date2 - $date1;
        $days = $secs / 86400 + 1;
        
        $sale->total_price =  $sale->total_price + ($car->price * $days);
        $sale->save();
        
        $html = $this->GetSaleDetails($sale_id);
        return $html;
    }

    public function DecreaseQuantity(Request $request){
        $sale_detail_id = $request->sale_detail_id;
        $sale_detail = SaleDetail::find($sale_detail_id);
        $sale_detail->quantity = $sale_detail->quantity - 1;
        $sale_detail->save();

        $sale_id = $sale_detail->sale_id;
        $car_id = $sale_detail->car_id;
        $sale = Sale::find($sale_id);
        $car = Car::find($car_id);
       
        $pick_up_date = $sale_detail->pick_up_date;
        $drop_off_date = $sale_detail->drop_off_date;
        $date1 = strtotime($pick_up_date);
        $date2 = strtotime($drop_off_date);
        $secs = $date2 - $date1;
        $days = $secs / 86400 + 1;
        
        $sale->total_price =  $sale->total_price - ($car->price * $days);
        $sale->save();
        
        $html = $this->GetSaleDetails($sale_id);
        return $html;
    }

}
