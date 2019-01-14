<?php

namespace App\Http\Controllers;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Category::all();
      return view('admin/category/list',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/category/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       $inputs = $request->all();
       $validator = Validator::make($inputs,[
           'title' => 'required'
       ]);
       if($validator->fails()){
           return redirect('category/create')->withErrors($validator)->withInput();
       }
       $category = new Category();
       $category->title = $inputs['title'];
       $category->status = '1';
       $category->save();
       return redirect('category');
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
        //echo'ok';die;
       $data = Category::find($id);
       return view('admin/category/update',compact('data'));
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
        $inputs = $request->all();
        $validator = Validator::make($inputs,[
            'title' => 'required'
        ]);
        if($validator->fails()){
            return redirect('admin/category/update')->withErrors($validator)->withInput();
        }
        $data = Category::find($id);
        $data->title = $inputs['title'];
        $data->save();

        return redirect('category');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $data = Category::find($id);
       $data->delete();
        return redirect('category');
    }

}
