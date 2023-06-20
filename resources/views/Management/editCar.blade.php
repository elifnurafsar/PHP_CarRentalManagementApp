@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      @include('management.inc.sidebar')
      <div class="col-md-8">
        <img width="30px" src="{{asset('images/car.svg')}}"/>Edit The Car
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
        <form action="/management/car/{{$car->id}}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="form-group">
            <label for="carBrand">Brand</label>
            <input type="text" name="brand" value="{{$car->brand}}" class="form-control" placeholder="For example: Mercedes Benz...">
          </div>
          <div class="form-group">
            <label for="carModel">Model</label>
            <input type="text" name="model" value="{{$car->model}}" class="form-control" placeholder="For example: E250 Elite...">
          </div>
          <label for="carPrice">Price</label>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><img width="20px" src="{{asset('images/TL.svg')}}"/></span>
            </div>
            <input type="text" name="price" value="{{$car->price}}" class="form-control" aria-label="Amount (to the nearest TL)">
          </div>
          <label for="CarImage">Image</label>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">Upload</span>
            </div>
            <div class="custom-file">
              <input type="file" name="image" class="custom-file-input" id="inputGroupFile01">
              <label class="custom-file-label" for="inputGroupFile01">Choose File</label>            
            </div>
          </div>

          <div class="form-group">
            <label for="Description">Description</label>
            <input type="text" name="description" value="{{$car->description}}" class="form-control" placeholder="Description...">
          </div>

          <div class="form-group">
            <label for="Category">Category</label>
            <select class="form-control" name="category_id">
              @foreach ($categories as $category)
                <option value="{{$category->id}}" {{$car->category_id === $category->id ? 'selected': ''}}>{{$category->name}}</option>
              @endforeach
            </select>
          </div>

          <button type="submit" class="btn btn-warning">Edit</button>
        </form>
      </div>
    </div>
  </div>
@endsection