@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('Management.inc.sidebar')
        <div class="col-md-8">
            <img width="30px" src="{{asset('images/car.svg')}}"/>Car
            <a href="/management/car/create " class="btn btn-success btn-sm float-right"><i class="fas fa-plus"></i>Create a Car</a>
            <hr>
            @if(Session()->has('status'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"><img width="30px" src="{{asset('images/cancel_btn.svg')}}"/></button>
                {{Session()->get('status')}}
            </div>
            @endif
            <table class="table table-bordered">
            <thead>
                    <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Brand</th>
                    <th scope="col">Model</th>
                    <th scope="col">Price</th>
                    <th scope="col">Category</th>
                    <th scope="col">Picture</th>
                    <th scope="col">Description</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cars as $car)
                    <tr>
                        <th scope="row">{{$car->id}}</th>
                        <td>{{$car->brand}}</td>
                        <td>{{$car->model}}</td>
                        <td>{{$car->price}}<img width="20px" src="{{asset('images/TL.svg')}}"/></td>
                        <td>{{$car->category->name}}</td>
                        <td>
                            <img src="{{asset('car_images')}}/{{$car->image}}" alt="{{$car->model}}" width="120px" height="120px" class="img-thumbnail">
                        </td>
                        <td>{{$car->description}}</td>
                        <td>
                            <a href="/management/car/{{$car->id}}/edit" class="btn btn-warning">Edit</a>
                        </td>
                        <td>
                            <form action="/management/car/{{$car->id}}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="submit" onclick="return confirm('Want to delete this car?')" value="Delete" class="btn btn-danger">
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{$cars->links()}}
        </div>
    </div>
</div>
@endsection