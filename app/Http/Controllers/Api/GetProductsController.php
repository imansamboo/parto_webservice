<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Product;
use App\Tag;
use Illuminate\Http\Request;

class GetProductsController extends Controller
{
    const TARGET = "webview";
    const TARGET_ID = "http://havadaran.org";
    protected $inputs;
    protected $defaultValues;
    protected $sections;
    protected $slides;
    protected $prices;
    protected $features;
    protected $menus;

    /**
     * GetShopDetailsController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->setInputs($request);
        $this->setDefaultValues();
        $this->setSections();
        $this->setSlides();
        $this->setFeatures();
        $this->setPrices();
        $this->setMenus();
    }


    /**
     * @param $request
     */
    public function setInputs($request)
    {
        $this->inputs = $request->only(
            "ID",
            "deviceid",
            "action",
            "token"
        );
    }

    public function setDefaultValues()
    {
        $this->defaultValues = array(
            "status" => 200,
            "message" => "",
            "showDialog" => false,
            "positiveBtn" => "باشه",
            "positiveBtnUrl" => "",
            "negativeBtn" => "",
            "canDismiss" => true,
            "dialogImage" => "http://havadaran.org/images/dialog.png"
        );
    }

    public function setSections()
    {
        $arraySections = array();
        $j = -1;
        foreach (\App\Section::all() as $section){
            $j++;
            $arraySections[] = $section->only([
                "title",
                "type",
                "more_button_text",
                "image",
                "expire_date",
                "target",
                "targetID"
            ]);
            switch ($section->title) {
                case "محصولات مرتبط":
                    $i = 0;
                    foreach(Product::find($this->getInputs()["ID"])->tags as $tag){
                        foreach ($tag->products as $product){
                            if($product->visibility == 1){
                                $arraySections[$j]['list'][$i]["image"] = $product->image;
                                $arraySections[$j]['list'][$i]["title"] = $product->title;
                                $arraySections[$j]['list'][$i]["oldprice"] = $product->prices[0]->oldpricetxt;
                                $arraySections[$j]['list'][$i]["price"] = $product->prices[0]->pricetxt;
                                $arraySections[$j]['list'][$i]["target"] = "webview";
                                $arraySections[$j]['list'][$i]["targetID"] = $product->ID;
                            }
                            $i++;
                        }
                    }
                    break;
                default:
                    if( in_array($section->title, ['پیشنهاد شگفت انگیز', 'جدید ترین محصولات', 'پرفروش ترین ها']) ){
                        unset($arraySections[$j]);
                    }elseif($section->type == 'fullbanner'){
                        $arraySections[$j]['list'] = null;
                    }elseif ($section->type == 'gridbanner'){
                        $arraySections[$j]['list'] = array(
                            array(

                                "image" => "https://api.backino.net/red-apple/gridbanner_left.png",
                                "title" => "",
                                "oldprice" => "",
                                "price" => "",
                                "target" => "viewproduct",
                                "targetID" => "153"

                            ),
                            array(

                                "image" => "https://api.backino.net/red-apple/gridbanner_right.png",
                                "title" => "",
                                "oldprice" => "",
                                "price" => "",
                                "target" => "viewproduct",
                                "targetID" => "153"

                            )
                        );
                    }
            }
        }
        $this->sections = $arraySections;
    }

    public function setSlides()
    {
        $arraySlides = array();
        foreach (Product::find($this->getInputs()["ID"])->slides as $slide){
            $arraySlides[] = $slide->only(
                array(
                    "image",
                    "large_image"
                )
            );
        }
        $this->slides = $arraySlides;
    }

    public function setPrices()
    {
        $arrayPrices = array();
        foreach (Product::find($this->getInputs()["ID"])->prices as $price){
            $arrayPrices[] = $price->only(
                array(
                    "colorID",
                    "txtcolorcode",
                    "colortitle",
                    "colorcode",
                    "garrantytitle",
                    "pricetxt",
                    "price",
                    "oldpricetxt"
                )
            );
        }
        $this->prices = $arrayPrices;
    }

    public function setFeatures()
    {
        $arrayFeatures = array();
        foreach (Product::find($this->getInputs()["ID"])->features as $feature){
            $arrayFeatures[] = $feature->only(
                array(
                    "key",
                    "value",
                )
            );
        }
        $this->features = $arrayFeatures;
    }

    public function setMenus()
    {
        $arrayMenus = array();
        foreach (Product::find($this->getInputs()["ID"])->menus as $menu){
            $arrayMenus[] = $menu->only(
                array(
                    "title",
                    "target",
                    "targetID",
                    "image"
                )
            );
        }
        $this->menus = $arrayMenus;
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
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @return mixed
     */
    public function getSlides()
    {
        return $this->slides;
    }

    /**
     * @return mixed
     */
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * @return mixed
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * @return mixed
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data["slides"]=$this->getSlides();
        $data["price"]=$this->getPrices();
        $data["section"]=$this->getSections();
        $data["features"]=$this->getFeatures();
        $data["menus"]=$this->getMenus();
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
