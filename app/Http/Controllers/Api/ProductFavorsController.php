<?php

namespace App\Http\Controllers\Api;

use App\ProductFavor;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductFavorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $proFavs = ProductFavor::paginate(12);

        return response()->json($proFavs, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    /*public function getProvince($id)
    {
        $proFav = ProductFavor::find($id);
        $province = $proFav->province;
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
        
        $proFav = ProductFavor::create($request->all());

        return response()->json($proFav, 201);
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
        $proFav = ProductFavor::findOrFail($id);

        return $proFav;
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
        
        $proFav = ProductFavor::findOrFail($id);
        $proFav->update($request->all());

        return response()->json($proFav, 200);
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
        ProductFavor::destroy($id);

        return response()->json(null, 204);
    }
}
