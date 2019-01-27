<?php

namespace App\Http\Controllers\Api;

use App\ProductMenu;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductMenusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $productMenus = ProductMenu::paginate(12);

        return response()->json($productMenus, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    /*public function getProvince($id)
    {
        $productMenu = ProductMenu::find($id);
        $province = $productMenu->province;
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
        
        $productMenu = ProductMenu::create($request->all());

        return response()->json($productMenu, 201);
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
        $productMenu = ProductMenu::findOrFail($id);

        return $productMenu;
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
        
        $productMenu = ProductMenu::findOrFail($id);
        $productMenu->update($request->all());

        return response()->json($productMenu, 200);
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
        ProductMenu::destroy($id);

        return response()->json(null, 204);
    }
}
