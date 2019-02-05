<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceItem;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetShoppingCartController extends Controller
{
    const TARGET = "webview";
    const TARGET_ID = "http://havadaran.org";
    protected $inputs;
    protected $defaultValues;
    protected $shops;


    /**
     * GetShopDetailsController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->setInputs($request);
        $this->setDefaultValues();
        $this->findRouting();

    }


    /**
     * @param $request
     */
    public function setInputs($request)
    {
        $this->inputs = $request->only(
            "ID",
            "deviceid",
            "token",
            "action",
            "quantity",
            "colorID"
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
            case "getList":
                $this->setShops();
                break;
            case "updateList":
                $quantity = $this->getInputs()['quantity'];
                switch ($quantity) {
                    case "0":
                        $this->deleteItem();
                        break;
                    default:
                        $this->createOrUpdate();
                }
                break;
            default:
        }

    }

    public function setShops()
    {
        $invoice = Invoice::where('user_id', '=', Auth::user()->id)->where('status', '=', 'درانتظار پرداخت')->firstOrFail();
        $arrayShops = array();
        foreach ($invoice->items() as $invoice_item){
            $arrayShops[] = array_merge(
                $invoice_item->product->only(["ID", "title", "desc", "maxquantity", "image", "ID"]),
                $invoice_item->product->prices[0]->only(["colorID", "colortitle", "colorcode", "price", "garrantytitle"]),
                $invoice_item->product->menus[0]->only(["target", "targetID"]),
                $invoice_item->only(["quantity"])
            );
        }
    }


    public function deleteItem()
    {
        $invoiceItem = InvoiceItem::where('user_id', '=', Auth::user()->id)
            ->where('product_ID', '=', $this->getInputs()["ID"])->firstOrFail();
        if($invoiceItem->is_discounted == 0){
            $itemPrice = $invoiceItem->product->prices[0]->price;
        }else{
            $itemPrice = (100 - $invoiceItem->product->prices[0]->discount)/100*$invoiceItem->product->prices[0]->price;
        }
        InvoiceItem::destroy($invoiceItem->ID);
        $invoice = Invoice::find($invoiceItem->invoice->ID);
        $invoice->price = $invoice->price - $invoiceItem->quantity*$itemPrice;
        $invoice->save();
        $this->setShops();
    }

    public function createOrUpdate()
    {
        $count = InvoiceItem::where('user_id', '=', Auth::user()->id)
            ->where('product_ID', '=', $this->getInputs()["ID"])
            ->count();
        if ($count == 0){
            $invoiceItem = InvoiceItem::create(
                array(
                    'user_id' => Auth::user()->id,
                    'product_ID' => $this->getInputs()["ID"],
                    'quantity' => $this->getInputs()["quantity"],
                    'invoice_ID' =>  Invoice::where('user_id', '=', Auth::user()->id)->where('status', '=', 'درانتظار پرداخت')->firstOrFail()->ID
                )
            );
            $itemPrice = Product::find($this->getInputs()["ID"])->prices[0]->price;
            $invoice = Invoice::find($invoiceItem->invoice->ID);
            $invoice->price = $invoice->price + $invoiceItem->quantity*$itemPrice;
            $invoice->save();
            $this->setShops();
        }elseif($count > 0){
            $invoiceItem = InvoiceItem::where('user_id', '=', Auth::user()->id)
                ->where('product_ID', '=', $this->getInputs()["ID"])->firstOrFail();
            $oldQuantity = $invoiceItem->quantity;
            $invoiceItem->quantity = $this->getInputs()["quantity"];
            $invoiceItem->save();
            $itemPrice = Product::find($this->getInputs()["ID"])->prices[0]->price;
            $invoice = Invoice::find($invoiceItem->invoice->ID);
            $invoice->price = $invoice->price + ($invoiceItem->quantity - $oldQuantity)*$itemPrice;
            $invoice->save();
            $this->setShops();
        }
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
                    foreach(App\Product::where('visibility', '=', 1)->get() as $product){
                        $arraySections[$i]['list']["image"] = $product->image;
                        $arraySections[$i]['list']["title"] = $product->title;
                        $arraySections[$i]['list']["oldprice"] = $product->prices[0]->oldpricetxt;
                        $arraySections[$i]['list']["price"] = $product->prices[0]->pricetxt;
                        $arraySections[$i]['list']["target"] = "webview";
                        $arraySections[$i]['list']["targetID"] = $product->ID;
                    }
                    $i++;
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
                    $i++;
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
                    $i++;
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
     * @return mixed
     */
    public function getShops()
    {
        return $this->shops;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data["shops"] = $this->setShops();
        $data["amount"] = $this->persian('تومان' . $invoice = Invoice::where('user_id', '=', Auth::user()->id)->where('status', '=', 'درانتظار پرداخت')->firstOrFail()->price);
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
