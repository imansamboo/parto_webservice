<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\Discount;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceItem;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Exception;

class GetFacorsController extends Controller
{
    const TARGET = "webview";
    const TARGET_ID = "http://havadaran.org";
    const ONLINE_PAY = true;
    const CACHE_PAY = true;
    const PAY_URL = "https://www.partodesign.com/domainregister";
    const SUCCESS_IMG = "https://vignette.wikia.nocookie.net/tibia/images/2/29/Tick.png/revision/latest?cb=20140104123244&path-prefix=en";
    const SUCCESS_TXT = "کاربر گرامی از خرید شما سپاسگزاریم\nتشکر از انتخاب ما";
    protected $inputs;
    protected $defaultValues;
    protected $products;
    protected $address;
    protected $price;
    protected $discount;


    /**
     * GetShopDetailsController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->setInputs($request);
        $this->setDefaultValues();
        $this->findRouting();
        $this->setPrice();
        $this->setDiscount();

    }


    /**
     * @param $request
     */
    public function setInputs($request)
    {
        $this->inputs = $request->only(
            "deviceid",
            "action",
            "token",
            "discountcode",
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

    public function findRouting()
    {
        $action = $this->getInputs()['action'];
        switch ($action) {
            case "getdata":
                $this->setProducts();
                $this->setAddress();
                break;
            case "discount":
                return $this->checkAndSetDiscount();
                break;
            default:
        }

    }

    public function setProducts()
    {
        $invoiceCount = Invoice::where('user_id', '=', Auth::user()->id)->where('ID', '=', $this->getInputs()["ID"])->count();
        if($invoiceCount == 0)
            throw new Exception("can't access", 404);
        $invoice = Invoice::find($this->getInputs()["ID"]);
        $arrayProducts = array();
        foreach ($invoice->items() as $invoice_item){
            $arrayProducts[] = array_merge(
                $invoice_item->product->only(["title", "image"]),
                ['price' => $this->persian("تومان". $invoice_item->product->prices[0]->price)],
                $invoice_item->product->menus[0]->only(["target", "targetID"])
            );
        }
        $this->products = $arrayProducts;
    }

    public function setPrice()
    {
        $invoice = Invoice::findOrFail($this->getInputs()["ID"]);
        $price = 0;
        foreach ($invoice->items() as $invoiceItem){
            $invoiceItem->is_dicounted = true;
            $invoiceItem->save();
            $price += $invoiceItem->product->prices[0]->price*$invoiceItem->quantity;
            $this->price = $price;
        }
    }

    public function setDiscount()
    {
        $invoice = Invoice::findOrFail($this->getInputs()["ID"]);
        $discount = 0;
        foreach ($invoice->items() as $invoiceItem){
            $invoiceItem->is_dicounted = true;
            $invoiceItem->save();
            $discount += ($invoiceItem->product->prices[0]->discount)/100*$invoiceItem->product->prices[0]->price*$invoiceItem->quantity;
        }
        $this->discount = $discount;
    }

    public function setAddress()
    {
        /*
         * this process shoid be clarified
         * */
        $this->address = ["ID" => "1",
            "fullname" => "خاطره قاسمی ",
            "province" => "نهران",
            "address" => "تقاطع سهروردی و عباس آباد خ داریوش پ ۱۲ واحد ۸",
            "postalcode" => "1234567890",
            "phone" => "22336655",
            "mobile" => "09122769683",
            "areacode" =>"021",
        ];
    }

    public function checkAndSetDiscount()
    {
        $discountValid = Discount::where('discount_code', '=', $this->getInputs()["discountcode"])->count();
        if($discountValid == 0)
        {
            return response()->json(["response" => ["showDialog" => true, "message" => 'کد تخفیف نامعتبر', "status" => 400]], 400);
        }else{
            $invoice = Invoice::findOrFail($this->getInputs()["ID"]);
            $price = 0;
            foreach ($invoice->items() as $invoiceItem){
                $invoiceItem->is_dicounted = true;
                $invoiceItem->save();
                $price += (100 - $invoiceItem->product->prices[0]->discount)/100*$invoiceItem->product->prices[0]->price*$invoiceItem->quantity;
            }
            $invoice->price = $price;
            $invoice->save();
            return response()->json($invoice, 200);
        }
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

        $data["product"] = $this->getProducts();
        $data["address"] = $this->getAddress();
        $data["price"] = $this->persian("تومان" . $this->getPrice() - $this->getDiscount());
        $data["discount"] = $this->persian("تومان" . $this->getDiscount());
        $data["delivery"] = "رایگان";
        $data["totalprice"] = $this->persian("تومان" . $this->getPrice());
        $data["onlinepay"] = self::ONLINE_PAY;
        $data["cachepay"] = self::CACHE_PAY;
        $data["payurl"] = self::PAY_URL;
        $data["successimg"] = self::SUCCESS_IMG;
        $data["successtxt"] = self::SUCCESS_TXT;
        $dtp["status"] =  $this->getDefaultValues()["status"];
        $dtp["message"] =  $this->getDefaultValues()["message"];
        $dtp["showDialog"] = $this->getDefaultValues()["showDialog"];
        $dtp["positiveBtn"] = $this->getDefaultValues()["positiveBtn"];
        $dtp["positiveBtnUrl"] = $this->getDefaultValues()["positiveBtnUrl"];
        $dtp["negativeBtn"] = $this->getDefaultValues()["negativeBtn"];
        $dtp["canDismiss"] = $this->getDefaultValues()["canDismiss"];
        $dtp["dialogImage"] = $this->getDefaultValues()["dialogImage"];
        $dtp["target"] =  self::TARGET;
        $dtp["targetID"] = self::TARGET_ID;
        $data["response"] = $dtp;
        return response()->json($data, 200);

    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }
}
