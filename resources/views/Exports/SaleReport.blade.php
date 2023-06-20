<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Sale ID</th>
            <th>Date Time</th>
            <th>Customer</th>
            <th>Staff</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $countSale = 1;
        @endphp 
        @foreach($Sales as $sale)
            <tr >
                <td>{{$countSale++}}</td>
                <td>{{$sale->id}}</td>
                <td>{{date("m/d/Y H:i:s", strtotime($sale->updated_at))}}</td>
                <td>{{$sale->customer->name}} {{$sale->customer->surname}}</td>
                <td>{{$sale->user_id}}</td>
                <td>{{$sale->total_price}}</td>
            </tr>
            <tr >
                <th>Car Brand</th>
                <th>Car Model</th>
                <th>Num. of Days</th>
                <th>Quantity</th>
                <th>Daily Price</th>
                <th>Total Price</th>
            </tr>
            @foreach($sale->saleDetails as $saleDetail)
                <tr>
                    <td>{{$saleDetail->car->brand}}</td>
                    <td>{{$saleDetail->car->model}}</td>
                    <td>{{
                        ((strtotime($saleDetail->drop_off_date) - strtotime($saleDetail->pick_up_date))/ 86400 + 1)
                    }}</td>
                    <td>{{$saleDetail->quantity}}</td>
                    <td>{{$saleDetail->car->price}}</td>
                    <td>{{
                        ((strtotime($saleDetail->drop_off_date) - strtotime($saleDetail->pick_up_date))/ 86400 + 1)*$saleDetail->car->price
                    }}</td>
                </tr>
            @endforeach
        @endforeach   
        <tr>
            <td>Total Amount from {{$dateStart}} to {{$dateEnd}}</td>
            <td>{{number_format($totalSale, 2)}}</td>
        </tr>
    </tbody>
</table>