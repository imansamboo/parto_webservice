<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\Province;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetAddressesController extends Controller
{
    const TARGET = "webview";
    const TARGET_ID = "http://havadaran.org";
    protected $inputs;
    protected $defaultValues;

    /**
     * GetShopDetailsController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->setInputs($request);
        $this->setDefaultValues();
    }
    
    /**
     * @param $request
     */
    public function setInputs($request)
    {
        $this->inputs = $request->only(
            "ID",
            "devficeid",
            "token",
            "action",
            "fullname",
            "mobile",
            "phone",
            "areacode",
            "city",
            "province",
            "postalcode",
            "address",
            "latitude",
            "longitude"
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
    public function index(Request $request)
    {
        $action = $this->getInputs()['action'];
        switch ($action) {
            case "insert":
                return $this->insert($request);
                break;
            case "edit":
                return $this->edit($request);
                break;
            case "delete":
                return $this->delete();
                break;
            case "select":
                return $this->select();
                break;
            case "getList":
                return $this->getList();
                break;
            default:
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert($request)
    {
        $address = Address::create(
            $request->only(
                array(
                    'fullname',
                    'city',
                    'province',
                    'address',
                    'postalcode',
                    'phone',
                    'mobile',
                    'areacode',
                    'selected',
                    'latitude',
                    'longitude'
                )
            )
        );
        return response()->json($address, 201);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($request)
    {
        $address = Address::find($this->getInputs()['ID']);
        $address->update(
            $request->only(
                array(
                    'fullname',
                    'city',
                    'province',
                    'address',
                    'postalcode',
                    'phone',
                    'mobile',
                    'areacode',
                    'selected',
                    'latitude',
                    'longitude'
                )
            )
        );
        return response()->json($address, 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete()
    {
        Address::destroy($this->getInputs()['ID']);
        return response()->json(null, 204);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function select()
    {
        Address::where('selected', '=', 1)->update(['selected' => false]);
        $address = Address::find($this->getInputs()['ID']);
        $address->update(
            array(
                'selected' => true
            )
        );
        return response()->json($address, 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList()
    {
        foreach (Province::all() as $province)
        {
            foreach ($province->cities as $city){
                $arrayCities[] = $city->only(["ID", "title"]);
            }
            $arrayProvuinces[] = array_merge(
                $province->only(["ID", "title"]),
                ['cities' => $arrayCities]
            );
        }
        foreach (Address::all() as $address){
            $arrayAddresses[] = $address->only(
                array(
                    "ID",
                    "fullname",
                    "city",
                    "province",
                    "address",
                    "postalcode",
                    "phone",
                    "mobile",
                    "areacode",
                    "selected",
                    "latitude",
                    "longitude"
                )
            );
        }
        $data["province"]=$arrayProvuinces;
        $data["address"]=$arrayAddresses;
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
