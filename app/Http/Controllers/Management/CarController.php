<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Car;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = Car::paginate(10);
        return view('management.car')->with('cars', $cars);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('management.createCar')->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|max:255',
            'model' => 'required|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|numeric'
        ]);
        //if a user does not uploade an image, use noimge.png for the car
        $imageName = "noimage.png";

        //if a user upload image
        if($request->image){
            $request->validate([
                'image' => 'nullable|file|image|mimes:jpeg,png,jpg|max:5000'
            ]);
            $imageName = date('mdYHis').uniqid().'.'.$request->image->extension();
            $request->image->move(public_path('car_images'), $imageName);
        }
        //save information to Cars table
        $car = new Car();
        $car->brand = $request->brand;
        $car->model = $request->model;
        $car->price = $request->price;
        $car->image = $imageName;
        $car->description = $request->description;
        $car->category_id = $request->category_id;
        $car->save();
        $request->session()->flash('status', $request->model. ' has saved successfully!');
        return redirect('/management/car');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $car = Car::find($id);
        $categories = Category::all();
        return view('management.editCar')->with('car',$car)->with('categories', $categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // information validation
        $request->validate([
            'brand' => 'required|max:255',
            'model' => 'required|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|numeric'
        ]);
        $car = Car::find($id);
        // validate if a user upload image
        if($request->image){
            $request->validate([
                'image' => 'nullable|file|image|mimes:jpeg,png,jpg|max:5000'
            ]);
            if($car->image != "noimage.png"){
                $imageName = $car->image;
                unlink(public_path('car_images').'\\'.$imageName);
            }
            $imageName = date('mdYHis').uniqid().'.'.$request->image->extension();
            $request->image->move(public_path('car_images'), $imageName);
        }else{
            $imageName = $car->image;
        }

        $car->brand = $request->brand;
        $car->model = $request->model;
        $carName =  $car->brand." ".$car->model;
        $car->price = $request->price;
        $car->image = $imageName;
        $car->description = $request->description;
        $car->category_id = $request->category_id;
        $car->save();
        $request->session()->flash('status', $carName. ' has updated successfully!');
        return redirect('/management/car');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $car = Car::find($id);
        if($car->image != "noimage.png"){
            unlink(public_path('car_images').'/'.$car->image);
        }
        $carBrand = $car->brand;
        $carModel = $car->model;
        $carName = $carBrand." ".$carModel;
        $car->delete();
        Session()->flash('status', $carName.' has deleted successfully!');
        return redirect('/management/car');
    }
}
