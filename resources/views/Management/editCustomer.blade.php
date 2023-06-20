@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      @include('management.inc.sidebar')
      <div class="col-md-8">
        <img width="30px" src="{{asset('images/driver.svg')}}"/>Edit Driver
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
        <form action="/management/customer/{{$customer->id}}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" value="{{$customer->name}}" class="form-control">
            </div>
            <div class="form-group">
                <label for="surname">Surname</label>
                <input type="text" name="surname" value="{{$customer->surname}}" class="form-control">
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" value="{{$customer->phone}}" class="form-control">
            </div>
            <div class="form-group">
                <label for="licenseNo">License No:</label>
                <input type="text" name="license_no" value="{{$customer->license_no}}" class="form-control">
            </div>
            <div class="form-group">
                <label for="customerDateOfBirth">Date Of Birth</label>
                <input type="date" name="date_of_birth" id="date" value="{{$customer->date_of_birth}}" class="form-control" style="width: 100%; display: inline;">
            </div>
          <button type="submit" class="btn btn-warning">Edit</button>
        </form>
      </div>
    </div>
  </div>
@endsection