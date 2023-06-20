@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      @include('management.inc.sidebar')
      <div class="col-md-8">
        <img width="30px" src="{{asset('images/driver.svg')}}"/></i>Drivers (Customers)
        <a href="/management/customer/create " class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i> Add New Customer</a>
        <hr>
        @if(Session()->has('status'))
          <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">X</button>
            {{Session()->get('status')}}
          </div>
        @endif
        <table class="table table-bordered">
          <thead>
            <tr>
              <th scope="col">Name Surname</th>
              <th scope="col">Phone</th>
              <th scope="col">Date of Birth</th>
              <th scope="col">License No</th>
              <th scope="col">Edit</th>
              <th scope="col">Delete</th>
            </tr>
          </thead>
          <tbody>
            @foreach($customers as $customer)
              <tr>
                <td>{{$customer->name}} {{$customer->surname}}</td>
                <td>{{$customer->phone}}</td>
                <td>{{$customer->date_of_birth}}</td>
                <td>{{$customer->license_no}}</td>
                <td>
                  <a href="/management/customer/{{$customer->id}}/edit" class="btn btn-warning">Edit</a>
                </td>
                <td>
                  <form action="/management/customer/{{$customer->id}}" method="post">
                  @csrf 
                  @method('DELETE')
                  <input type="submit" onclick="return confirm('Are you sure to delete this customer?')" value="Delete" class="btn btn-danger">
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{$customers->links()}}
      </div>
    </div>
  </div>
@endsection