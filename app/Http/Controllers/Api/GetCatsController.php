<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetCatsController extends Controller
{
    const TARGET = "webview";
    const TARGET_ID = "http://havadaran.org";
    public $inputs;
    public $defaultValues;
    public $mainPageSpecification;
    public $slide;
    public $cats;
    public $sections;


    /**
     * GetShopDetailsController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->setInputs($request);
        $this->setDefaultValues();
        $this->setSlide();
        $this->setCats();
        $this->setSections();
    }

    /**
     * @param $request
     */
    public function setInputs($request)
    {
        $this->inputs = $request->only(
            "token",
            "ID"
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

    public function setSlide()
    {
        $arrayBanners = array();
        $slide = \App\Slide::where('title', '=', 'get_cat_page')->first();
        foreach ($slide->banners as $slide){
            $arrayBanners[] = $slide->only(['image', 'target', 'targetID']);
        }
        $this->slide = $arrayBanners;
    }

    public function setCats()
    {
        $arrayCats = array();
        foreach (\App\ParentCategory::find($this->getInputs()['ID'])->categories as $cat){
            $arrayCats[] = $cat->only(['image', 'target', 'targetID']);
        }
        $this->cats = $arrayCats;
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
                case "پیشنهاد شگفت انگیز":
                    unset($arraySections[$j]);
                    break;
                case "پرفروش ترین ها":
                    $i = 0;
                    foreach(\App\Product::where('visibility', '=', 1)->orderBy('total_cell_count', 'desc')->get() as $product){
                        $arraySections[$j]['list'][$i]["image"] = $product->image;
                        $arraySections[$j]['list'][$i]["title"] = $product->title;
                        $arraySections[$j]['list'][$i]["oldprice"] = $product->prices[0]->oldpricetxt;
                        $arraySections[$j]['list'][$i]["price"] = $product->prices[0]->pricetxt;
                        $arraySections[$j]['list'][$i]["target"] = "webview";
                        $arraySections[$j]['list'][$i]["targetID"] = $product->ID;
                        $i++;
                    }

                    break;
                case "جدید ترین محصولات":
                    $i = 0;
                    foreach(\App\Product::where('visibility', '=', 1)->orderBy('updated_at', 'desc')->get() as $product){
                        $arraySections[$j]['list'][$i]["image"] = $product->image;
                        $arraySections[$j]['list'][$i]["title"] = $product->title;
                        $arraySections[$j]['list'][$i]["oldprice"] = $product->prices[0]->oldpricetxt;
                        $arraySections[$j]['list'][$i]["price"] = $product->prices[0]->pricetxt;
                        $arraySections[$j]['list'][$i]["target"] = "webview";
                        $arraySections[$j]['list'][$i]["targetID"] = $product->ID;
                        $i++;
                    }
                    break;
                default:
                    $i = 0;
                    if($section->type == 'fullbanner'){
                        $arraySections[$j]['list'] = null;
                    }elseif ($section->type == 'gridbanner'){
                        $arraySections[$j]['list']= array(
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
    public function getSlide()
    {
        return $this->slide;
    }

    /**
     * @return mixed
     */
    public function getCats()
    {
        return $this->cats;
    }

    /**
     * @return mixed
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param $string
     * @return mixed
     */
    public function persian($string) {
        $persian_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $latin_num = range(0, 9);

        $string = str_replace($latin_num, $persian_num, $string);

        return $string;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data["slides"]= $this->getSlide();
        $data["cats"]= $this->getCats();
        $data["section"]= $this->getSections();
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
