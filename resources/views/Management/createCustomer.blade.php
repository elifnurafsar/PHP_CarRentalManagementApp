@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('Management.inc.sidebar')
        <div class="col-md-8">
            <img width="30px" src="{{asset('images/driver.svg')}}"/>Customer (Driver)
            <hr>
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="/management/customer" method="POST">
                @csrf
                <div class="form-group">
                    <label for="customerName">Name</label>
                    <input type="text" name="name" class="form-control">
                </div>
                <div class="form-group">
                    <label for="customerSurname">Surname</label>
                    <input type="text" name="surname" class="form-control">
                </div>
                <div class="form-group">
                    <label for="customerPhone">Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="form-group">
                    <label for="customerLicenseNo">License No:</label>
                    <input type="text" name="license_no" class="form-control">
                </div>
                <div class="form-group">
                    <label for="customerDateOfBirth">Date Of Birth</label>
                    <input type="date" name="date_of_birth" id="date" class="form-control" style="width: 100%; display: inline;">
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection