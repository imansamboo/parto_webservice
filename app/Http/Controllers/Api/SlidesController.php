<?php

namespace App\Http\Controllers\Api;

use App\Slide;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SlidesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $slides = Slide::paginate(12);

        return response()->json($slides, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    /*public function getProvince($id)
    {
        $slide = Slide::find($id);
        $province = $slide->province;
        return response()->json($province, 200);
    }*/

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $slide = Slide::create($request->all());

        return response()->json($slide, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $slide = Slide::findOrFail($id);

        return $slide;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $slide = Slide::findOrFail($id);
        $slide->update($request->all());

        return response()->json($slide, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Slide::destroy($id);

        return response()->json(null, 204);
    }
}
