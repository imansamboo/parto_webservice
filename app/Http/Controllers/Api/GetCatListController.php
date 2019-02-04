<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetShopDetailsController extends Controller
{
    const TARGET = "webview";
    const TARGET_ID = "http://havadaran.org";
    const SHOW_TAB = true;
    public $inputs;
    public $defaultValues;
    public $tabs;


    /**
     * GetShopDetailsController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->setInputs($request);
        $this->setDefaultValues();
        $this->setTabs();
    }


    /**
     * @param $request
     */
    public function setInputs($request)
    {
        $this->inputs = $request->only(
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

    public function setTabs()
    {
        $arrayTabs = array();
        foreach (App\Tab::all() as $tab){
            foreach ($tab->categories as $category){
                $arrayCategories[] = $category->only(['title', 'image', 'target', 'targetID']);
            }
            $arrayTabs[] = array_merge($tab->only(['ID', 'title']), ['list' => $arrayCategories]);
        }
        $this->tabs = $arrayTabs;
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

    /**
     * @return mixed
     */
    public function getTabs()
    {
        return $this->tabs;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data["section"] = $this->tabs;
        $data["showTab"] = self::SHOW_TAB;
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
        $data["response"]=$dtp;
        return response()->json($data, 200);
    }


}
