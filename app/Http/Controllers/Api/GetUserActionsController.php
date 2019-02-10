<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\Province;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class GetUserActionsController extends Controller
{
    const TARGET = "webview";
    const TARGET_ID = "http://havadaran.org";
    protected $inputs;
    protected $defaultValues;
    protected $token;

    /**
     * GetShopDetailsController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->setInputs($request);
        $this->setDefaultValues($request);
        $this->setRouting();
    }
    
    /**
     * @param $request
     */
    public function setInputs($request)
    {
        $this->inputs = $request->only(
            "deviceid",
            "versioncode",
            "osversion",
            "model",
            "os",
            "token",
            "mobile",
            "password",
            "fullname",
            "gender",
            "passcode",
            "activecode",
            "action",
            "unique_id"
            );
    }

    public function setDefaultValues()
    {
        $this->defaultValues = array(
            "status" => 403,
            "errorMessage" => "",
            "showDialog" => false,
            "positiveBtn" => "باشه",
            "positiveBtnUrl" => "",
            "negativeBtn" => "",
            "canDismiss" => true,
            "dialogImage" => "http://havadaran.org/images/dialog.png",
            "target" => "",
            "targetID" => ""
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
    public function setRouting($request)
    {
        $action = $this->getInputs()['action'];
        switch ($action) {
            case 1:
                return $this->register($request);
                break;
            case 2:
                return $this->login($request);
                break;
            case 3:
                return $this->forgetPass($request);
                break;
            case 4:
                return $this->resetPass($request);
                break;
            case 5:
                return $this->activateUser($request);
                break;
            case 6:
                return $this->resendSMS($request);
                break;
            case 7:
                return $this->Logout($request);
                break;
            default:
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register($request)
    {
        $validatedData = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'digits:11', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'gender' => ['required'],
        ]);
        $user = User::create([
            'fullname' => $this->getInputs()['fullname'],
            'mobile' => $this->getInputs()['mobile'],
            'password' => Hash::make($this->getInputs()['password']),
            'gender' => $this->getInputs()['gender'],
        ]);
        $dtp["status"] = 200;
        $dtp["message"] = "";
        $dtp["showDialog"] = false;
        $dtp["positiveBtn"] = $this->getDefaultValues()["positiveBtn"];;
        $dtp["positiveBtnUrl"]= $this->getDefaultValues()["positiveBtnUrl"];
        $dtp["negativeBtn"]= $this->getDefaultValues()["negativeBtn"];
        $dtp["canDismiss"]= $this->getDefaultValues()["canDismiss"];
        $dtp["dialogImage"]= $this->getDefaultValues()["dialogImage"];
        $dtp["target"]= self::TARGET;
        $dtp["targetID"]=self::TARGET_ID;
        $data['user'] = $user;
        $data["response"]=$dtp;
        return response()->json($data, 200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function login($request)
    {
        $request->validate(
            [
                'mobile' => ['required', 'digits:11', 'unique:users'],
            ]
        );
        $user = User::where('mobile', $request->get('mobile'))->first();
        if($request->get('mobile') != $user->mobile || !$user->is_sms_verified) {
            \Session::put('errors', 'Your mobile number not match in our system..!!');
            return back();
        }
        if(\Auth::guard('api')->attempt(['mobile'=> $request->mobile, 'password'=> $request->password], $request->remember)){
            //return redirect()->intended(route('admin.dashboard'));
            $token = md5(mt_rand(1,99999));
            $user->token = $token;
            $user->lastactivity = time();
            $user->is_logged_out = 0;
            $user->save();

            $data["user"] = $user;
            $data["token"] = $token;
            $dtp["status"]= $this->getDefaultValues()["status"];
            $dtp["message"]= $this->getDefaultValues()["message"];
            $dtp["showDialog"]= $this->getDefaultValues()["showDialog"];
            $dtp["positiveBtn"]= $this->getDefaultValues()["positiveBtn"];
            $dtp["positiveBtnUrl"]= $this->getDefaultValues()["positiveBtnUrl"];
            $dtp["negativeBtn"]= $this->getDefaultValues()["negativeBtn"];
            $dtp["canDismiss"]= $this->getDefaultValues()["canDismiss"];
            $dtp["dialogImage"]= $this->getDefaultValues()["dialogImage"];
            $dtp["target"]= self::TARGET;
            $dtp["targetID"]= self::TARGET_ID;
            $data["response"]= $dtp;
            \Auth::login($user);
            return response()->json($data, 200);
       }
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgetPass($request)
    {
        $request->validate(
            [
                'mobile' => ['required', 'digits:11'],
            ]
        );
        $user = User::where('mobile', '=', $this->getInputs()['mobile'])->firstOrFail();
        $user->last_fpass = 123456;
        $user->is_fpass_enabled = true;
        $token = md5(mt_rand(1,99999));
        $user->token = $token;
        $user->save();
        $data["user"] = $user;
        $data["token"] = $token;
        $dtp["status"]= 200;
        $dtp["message"]= $this->getDefaultValues()["message"];
        $dtp["showDialog"]= $this->getDefaultValues()["showDialog"];
        $dtp["positiveBtn"]= $this->getDefaultValues()["positiveBtn"];
        $dtp["positiveBtnUrl"]= $this->getDefaultValues()["positiveBtnUrl"];
        $dtp["negativeBtn"]= $this->getDefaultValues()["negativeBtn"];
        $dtp["canDismiss"]= $this->getDefaultValues()["canDismiss"];
        $dtp["dialogImage"]= $this->getDefaultValues()["dialogImage"];
        $dtp["target"]= self::TARGET;
        $dtp["targetID"]= self::TARGET_ID;
        $data["response"]= $dtp;
        return response()->json($data, 200);

    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPass($request)
    {
        $request->validate(
            [
                'last_fpass' => ['required', 'digits:6'],
                'token' => ['required'],
                'password' => ['required', 'string', 'min:6'],
            ]
        );
        $user = User::where('token', '=', $this->getInputs()['token'])->firstOrFail();
        if($user->is_fpass_enabled && $user->last_fpass == $request->last_fpass){
            $user->password = Hash::make($this->getInputs()['password']);
            $user->is_fpass_enabled = false;
            $user->token = 0;
            $user->save();
            $data["user"] = $user;
            $dtp["status"]= 200;
            $dtp["message"]= $this->getDefaultValues()["message"];
            $dtp["showDialog"]= $this->getDefaultValues()["showDialog"];
            $dtp["positiveBtn"]= $this->getDefaultValues()["positiveBtn"];
            $dtp["positiveBtnUrl"]= $this->getDefaultValues()["positiveBtnUrl"];
            $dtp["negativeBtn"]= $this->getDefaultValues()["negativeBtn"];
            $dtp["canDismiss"]= $this->getDefaultValues()["canDismiss"];
            $dtp["dialogImage"]= $this->getDefaultValues()["dialogImage"];
            $dtp["target"]= self::TARGET;
            $dtp["targetID"]= self::TARGET_ID;
            $data["response"]= $dtp;
            return response()->json($data, 200);
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function activateUser($request)
    {
        $request->validate(
            [
                'last_sent_code' => ['required', 'digits:6'],
                'token' => ['required'],
            ]
        );
        $user = User::where('token', '=', $this->getInputs()['token'])->firstOrFail();
        $user->token = 0;
        $user->is_sms_verified = true;
        $user->is_confirm_sms_enabled = false;
        $user->save();
        $data["user"] = $user;
        $dtp["status"]= 200;
        $dtp["message"]= $this->getDefaultValues()["message"];
        $dtp["showDialog"]= $this->getDefaultValues()["showDialog"];
        $dtp["positiveBtn"]= $this->getDefaultValues()["positiveBtn"];
        $dtp["positiveBtnUrl"]= $this->getDefaultValues()["positiveBtnUrl"];
        $dtp["negativeBtn"]= $this->getDefaultValues()["negativeBtn"];
        $dtp["canDismiss"]= $this->getDefaultValues()["canDismiss"];
        $dtp["dialogImage"]= $this->getDefaultValues()["dialogImage"];
        $dtp["target"]= self::TARGET;
        $dtp["targetID"]= self::TARGET_ID;
        $data["response"]= $dtp;
        return response()->json($data, 200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendSMS($request)
    {
        $request->validate(
            [
                'mobile' => ['required', 'digits:11'],
            ]
        );
        $user = User::where('mobile', '=', $this->getInputs()['mobile'])->firstOrFail();
        $user->last_sent_code = 234567;
        $user->is_confirm_sms_enabled = true;
        $user->save();
        $data["user"] = $user;
        $dtp["status"]= 200;
        $dtp["message"]= $this->getDefaultValues()["message"];
        $dtp["showDialog"]= $this->getDefaultValues()["showDialog"];
        $dtp["positiveBtn"]= $this->getDefaultValues()["positiveBtn"];
        $dtp["positiveBtnUrl"]= $this->getDefaultValues()["positiveBtnUrl"];
        $dtp["negativeBtn"]= $this->getDefaultValues()["negativeBtn"];
        $dtp["canDismiss"]= $this->getDefaultValues()["canDismiss"];
        $dtp["dialogImage"]= $this->getDefaultValues()["dialogImage"];
        $dtp["target"]= self::TARGET;
        $dtp["targetID"]= self::TARGET_ID;
        $data["response"]= $dtp;
        return response()->json($data, 200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function Logout($request)
    {
        $request->validate(
            [
                'token' => ['required'],
            ]
        );
        $user = User::where('token', '=', $this->getInputs()['token'])->firstOrFail();
        $user->token = 0;
        $user->is_logged_out = true;
        $user->save();
        $data["user"] = $user;
        $dtp["status"]= 200;
        $dtp["message"]= $this->getDefaultValues()["message"];
        $dtp["showDialog"]= $this->getDefaultValues()["showDialog"];
        $dtp["positiveBtn"]= $this->getDefaultValues()["positiveBtn"];
        $dtp["positiveBtnUrl"]= $this->getDefaultValues()["positiveBtnUrl"];
        $dtp["negativeBtn"]= $this->getDefaultValues()["negativeBtn"];
        $dtp["canDismiss"]= $this->getDefaultValues()["canDismiss"];
        $dtp["dialogImage"]= $this->getDefaultValues()["dialogImage"];
        $dtp["target"]= self::TARGET;
        $dtp["targetID"]= self::TARGET_ID;
        $data["response"]= $dtp;
        return response()->json($data, 200);
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

}
