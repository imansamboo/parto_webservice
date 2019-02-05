<?php

namespace App\Http\Controllers\Api;

use App\ProductFavor;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function isFavored()
    {
        $product = ProductFavor::where('product_ID', '=', $_POST["ID"])->where('user_ID', '=', Auth::user()->id)->firstOrFail();
        $data = ['is_favor' => $product->is_favor];
        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     */
    public function examineFavor(Request $request)
    {
        $productCount = ProductFavor::where('product_ID', '=', $_POST["ID"])->where('user_ID', '=', Auth::user()->id)->count();
        if($productCount == 0){
            return $this->createFavor($request);
        }elseif($productCount > 0){
            return $this->updateFavor($request);
        }
    }
    
    public function updateFavor($request)
    {
        $product = ProductFavor::where('product_ID', '=', $request->only(['ID'])['ID'])->where('user_ID', '=', Auth::user()->id)->firstOrFail();
        $product->isFavor = true;
        $product->save();
        return response()->json($product, 200);
        
    }
    
    public function createFavor($request)
    {
        $product = ProductFavor::create(
            array(
                'user_id' => Auth::user()->id,
                'product_ID' => $request->only(['ID'])['ID'],
                'is_favor' => true
            )
        );
        return response()->json($product, 201);
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
