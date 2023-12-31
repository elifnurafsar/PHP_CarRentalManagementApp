@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('Management.inc.sidebar')
        <div class="col-md-8">
            <img width="30px" src="{{asset('images/car.svg')}}"/>Car
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
            <form action="/management/car" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="carBrand">Brand</label>
                    <input type="text" name="brand" class="form-control"  placeholder="For example: Mercedes Benz...">
                </div>
                <div class="form-group">
                    <label for="carModel">Model</label>
                    <input type="text" name="model" class="form-control"  placeholder="For example: E250 Elite">
                </div>
                <div class="form-group">
                    <label for="carDescription">Description</label>
                    <input type="text" name="description" class="form-control"  placeholder="...">
                </div>
                
                <label for="carPrice">Price</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><img width="20px" src="{{asset('images/TL.svg')}}"/></span>
                    </div>
                    <input type="text" name="price" class="form-control" aria-label="Amount (to the nearest TL)">
                    <div class="input-group-append">
                        <span class="input-group-text">.00</span>
                    </div>
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
                    <label for="carCategory">Category</label>
                    <select class="form-control" name="category_id">
                    @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection