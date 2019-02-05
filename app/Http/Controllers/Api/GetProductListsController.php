<?php

namespace App\Http\Controllers\Api;

use App\Tag;
use App\Category;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetProductListsController extends Controller
{
    const TARGET = "webview";
    const TARGET_ID = "http://havadaran.org";
    protected $inputs;
    protected $defaultValues;
    protected $tags;
    protected $products;


    /**
     * GetShopDetailsController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->setInputs($request);
        $this->setDefaultValues();
        $this->setDefaultValues();
        $this->setTags();
        if(isset($this->getInputs()['ID']) && $this->getInputs()['ID'] != "" || $this->getInputs()['ID'] != null){
            $this->setProductByCat();
        }else{
            $this->setProductByTag();
        }
    }


    /**
     * @param $request
     */
    public function setInputs($request)
    {
        $this->inputs = $request->only(
            "ID",
            "deviceid",
            "page",
            "token",
            "q",
            "tagID"
        );
    }

    public function setDefaultValues()
    {
        $this->defaultValues = array(
            "status" => 200,
            "hasnextpage" => true,
            "errorMessage" => "",
            "showDialog" => false,
            "positiveBtn" => "باشه",
            "positiveBtnUrl" => "",
            "negativeBtn" => "",
            "canDismiss" => true,
            "dialogImage" => "http://havadaran.org/images/dialog.png"
        );
    }

    public function setTags()
    {
        $arrayTags = array();
        foreach(Tag::all()as $tag){
            $arrayTags[] = $tag->only(['title', 'ID']);
        }
        $this->tags = $arrayTags;
    }

    public function setProductByTag()
    {
        $arrayProducts = array();
        foreach (Tag::where('title', 'LIKE', '%'.$this->getInputs()["q"].'%')->get() as $tag){
            foreach ($tag->products as $product){
                $arrayProducts[] = array_merge(
                    $product->only(
                        [
                            "image",
                            "title",
                            "desc"
                        ]
                    ),
                    $product->prices()[0]->only(
                        [
                            "oldpricetxt",
                            "pricetxt"
                        ]
                    ),
                    [
                        "target" => "viewproduct",
                        "targetID" => $product->ID
                    ]
                );
            }
        }
        $this->products = $arrayProducts;
    }

    public function setProductByCat()
    {
        $arrayProducts = array();
        foreach (Category::find($this->getInputs()["ID"])->products as $product){
            $arrayProducts[] = array_merge(
                $product->only(
                    [
                        "image",
                        "title",
                        "desc"
                    ]
                ),
                $product->prices()[0]->only(
                    [
                        "oldpricetxt",
                        "pricetxt"
                    ]
                ),
                [
                    "target" => "viewproduct",
                    "targetID" => $product->ID
                ]
            );
        }
        $this->products = $arrayProducts;
    }

    /**
     * @return mixed
     */
    public function getInputs()
    {
        return $this->inputs;
    }

    /**
     * @return mixed
     */
    public function getDefaultValues()
    {
        return $this->defaultValues;
    }

        /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data["cats"]=$this->getTags();
        $data["list"]=$this->getProducts();
        $data["hasnextpage"] = $this->getDefaultValues()['hasnextpage'];
        $dtp["status"]= $this->getDefaultValues()["status"];
        $dtp["message"]= $this->getDefaultValues()["message"];
        $dtp["showDialog"]= $this->getDefaultValues()["showDialog"];
        $dtp["positiveBtn"]= $this->getDefaultValues()["positiveBtn"];
        $dtp["positiveBtnUrl"]= $this->getDefaultValues()["positiveBtnUrl"];
        $dtp["negativeBtn"]= $this->getDefaultValues()["negativeBtn"];
        $dtp["canDismiss"]= $this->getDefaultValues()["canDismiss"];
        $dtp["dialogImage"]= $this->getDefaultValues()["dialogImage"];
        $dtp["target"]= self::TARGET;
        $dtp["targetID"]=self::TARGET_ID;
        $data["response"]=$dtp;
        return response()->json($data, 200);
    }

}
