<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetShopDetailsController extends Controller
{
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
        $this->setMainPageSpecification();
        $this->setSlide();
        $this->setCats();
    }


    /**
     * @param $request
     */
    public function setInputs($request)
    {
        $this->inputs = $request->only(
            "version",
            "os",
            "osversion",
            "model",
            "deviceid",
            "token",
            "unique_id"
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

    public function setMainPageSpecification()
    {
        $this->mainPageSpecification = App\MainPageSpecification::all()[0];
    }

    public function setSlide()
    {
        $arrayBanners = array();
        $slide = App\Slide::where('title', '=', 'main_page_banner')->first();
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
        $arraySections = array();
        foreach (App\Section::all() as $section){
            $arraySections[] = $section->only(['image', 'target', 'targetID']);
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
    public function getMainPageSpecification()
    {
        return $this->mainPageSpecification;
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



}
