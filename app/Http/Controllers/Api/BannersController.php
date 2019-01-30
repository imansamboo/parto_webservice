<?php

namespace App\Http\Controllers\Api;

use App\Banner;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BannersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $banners = Banner::paginate(12);

        return response()->json($banners, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    /*public function getProvince($id)
    {
        $banner = Banner::find($id);
        $province = $banner->province;
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
        
        $banner = Banner::create($request->all());

        return response()->json($banner, 201);
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
        $banner = Banner::findOrFail($id);

        return $banner;
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
        
        $banner = Banner::findOrFail($id);
        $banner->update($request->all());

        return response()->json($banner, 200);
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
        Banner::destroy($id);

        return response()->json(null, 204);
    }
}
