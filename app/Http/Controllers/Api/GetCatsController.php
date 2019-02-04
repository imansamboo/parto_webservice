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
            "errorMessage" => "",
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
        $slide = App\Slide::where('title', '=', 'get_cat_page')->first();
        foreach ($slide->banners as $slide){
            $arrayBanners[] = $slide->only(['image', 'target', 'targetID']);
        }
        $this->slide = $arrayBanners;
    }

    public function setCats()
    {
        $arrayCats = array();
        foreach (App\ParentCategory::all() as $cat){
            $arrayCats[] = $cat->only(['image', 'target', 'targetID']);
        }
        $this->cats = $arrayCats;
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
                case "پیشنهاد شگفت انگیز":
                    break;
                case "پرفروش ترین ها":
                    foreach(App\Product::where('visibility', '=', 1)->orderBy('total_cell_count', 'desc') as $product){
                        $arraySections[$i]['list']["image"] = $product->image;
                        $arraySections[$i]['list']["title"] = $product->title;
                        $arraySections[$i]['list']["oldprice"] = $product->prices[0]->oldpricetxt;
                        $arraySections[$i]['list']["price"] = $product->prices[0]->pricetxt;
                        $arraySections[$i]['list']["target"] = "webview";
                        $arraySections[$i]['list']["targetID"] = $product->ID;
                    }
                    break;
                case "جدید ترین محصولات":
                    foreach(App\Product::where('visibility', '=', 1)->orderBy('updated_at', 'desc') as $product){
                        $arraySections[$i]['list']["image"] = $product->image;
                        $arraySections[$i]['list']["title"] = $product->title;
                        $arraySections[$i]['list']["oldprice"] = $product->prices[0]->oldpricetxt;
                        $arraySections[$i]['list']["price"] = $product->prices[0]->pricetxt;
                        $arraySections[$i]['list']["target"] = "webview";
                        $arraySections[$i]['list']["targetID"] = $product->ID;
                    }
                    break;
                default:
                    if($section->type == 'fullbanner'){
                        $arraySections[$i]['list'] = null;
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
