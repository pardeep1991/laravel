<?php

namespace App\Http\Controllers;

use App\Tags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Tags::all();
        return view('admin/tags/list',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('admin/tags/create');
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
            return redirect('tag')->withErrors($validator)->withInput();
        }
        $tag = new Tags();
        $tag->title = $inputs['title'];
        $tag->save();
        return redirect('tag');
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
        $data = Tags::find($id);
        return view('admin/tags/update',compact('data'));
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
            return redirect('admin/tags/update')->withErrors($validator)->withInput();
        }
        $data = Tags::find($id);
        $data->title = $inputs['title'];
        $data->save();

        return redirect('tag');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Tags::find($id);
        $data->delete();
        return redirect('tag');
    }
}
