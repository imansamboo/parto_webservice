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
            "errorMessage" => "",
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
        $i = 0;
        $arraySections = array();
        foreach (App\Section::all() as $section){
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
                    foreach(Product::find($this->getInputs()["ID"])->tags as $tag){
                        foreach ($tag->products as $product){
                            if($product->visibility == 1){
                                $arraySections[$i]['list']["image"] = $product->image;
                                $arraySections[$i]['list']["title"] = $product->title;
                                $arraySections[$i]['list']["oldprice"] = $product->prices[0]->oldpricetxt;
                                $arraySections[$i]['list']["price"] = $product->prices[0]->pricetxt;
                                $arraySections[$i]['list']["target"] = "webview";
                                $arraySections[$i]['list']["targetID"] = $product->ID;
                            }
                        }
                    }
                    $section[]=array(
                        "title" => "محصولات مرتبط",
                        "type" => "section",  //See readme file
                        "more_button_text" => "",
                        "image" => "",
                        "expire_date" => 0,
                        "target" => "viewcat",
                        "targetID" => "10",
                        "list" => array());

                    $i++;
                    break;
                case "پرفروش ترین ها":
                    break;
                case "جدید ترین محصولات":
                    break;
                default:
                    if($section->type == 'fullbanner'){
                        $arraySections[$i]['list'] = null;
                        $i++;
                    }elseif ($section->type == 'gridbanner'){
                        $arraySections[$i]['list'] = array(
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
                        $i++;
                    }
            }
        }
        $this->sections = $arraySections;
    }

    public function setSlides()
    {
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
        return $this->price;
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
