<?php

namespace App\Http\Controllers;

use App\Models\userPlansModel;
use App\Models\usersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function App\Helpers\checkReferralCode;
use function App\Helpers\verifyRSVP;
use function App\Helpers\is_mobile;
use function App\Helpers\updateReverseSize;

class registerController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type');
        $sponser_code = $request->input('sponser_code');
        $user_id = $request->session()->get('user_id');


        if (empty($sponser_code)) {
            $res['status_code'] = 0;
            $res['message'] = "Please use referral link for register.";

            return is_mobile($type, "flogin", $res);
        }

        if (!empty($user_id)) {
            $res['status_code'] = 1;
            $res['message'] = "Already loggedin.";

            return is_mobile($type, "fdashboard", $res);
        }

        $res['status_code'] = 1;
        $res['message'] = "Fetched Successfully.";

        if (!empty($sponser_code)) {
            $res['sponser_code'] = $sponser_code;
        }

        return is_mobile($type, "pages.register", $res, "view");
    }

    public function store(Request $request)
    {
        $type = $request->input('type');
        $updateData = $request->except('_method', '_token', 'submit', 'image', 'cover', 'value_cover', 'confirm_password', 'type', 'rScript','rsScript','rsvScript','hashedMessageScript','walletAddressScript');

        if (empty($updateData['wallet_address'])) {
            $res['status_code'] = 0;
            $res['message'] = "Invalid wallet address Address.";
            return is_mobile($type, "fregister", $res);
        }

        if ($updateData['wallet_address'] == 'undefined') {
            $res['status_code'] = 0;
            $res['message'] = "Invalid wallet address Address.";
            return is_mobile($type, "fregister", $res);
        }

        $walletAddressScript = $request->input('walletAddressScript');
        $hashedMessageScript = $request->input('hashedMessageScript');
        $rsvScript = $request->input('rsvScript');
        $rsScript = $request->input('rsScript');
        $rScript = $request->input('rScript');

        $verifySignData = json_encode(array(
            "wallet" => $updateData['wallet_address'],
            "message" => $hashedMessageScript,
            "v" => $rsvScript,
            "r" => $rScript,
            "s" => $rsScript,
        ));

        $v = verifyRSVP($verifySignData);
        
        if (isset($v['result'])) {
            if ($v['result'] != true) {
                // dd($v['result']);
                $res['status_code'] = 0;
                $res['message'] = "Invalid Signature. Please try again later..";

                return is_mobile($type, "fregister", $res);
            }
        } else {
            $res['status_code'] = 0;
            $res['message'] = "Invalid Signature. Please try again later.";

            return is_mobile($type, "fregister", $res);
        }

        $checkEmailMobile = usersModel::whereRaw("wallet_address = '" . $updateData['wallet_address'] . "'")->get()->toArray();

        if (count($checkEmailMobile) == 0) {
            $refferal_code = substr($updateData['wallet_address'], -6);

            $length = 10;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            if (!empty($updateData['refferal'])) {
                $getRefUserId = usersModel::where(['refferal_code' => $updateData['refferal']])->get()->toArray();

                if (count($getRefUserId) == 0) {
                    $res['status_code'] = 0;
                    $res['message'] = "Invalid reffer code used please check again.";
                    return is_mobile($type, "flogin", $res);
                } else {
                    $checkReferalActivation = userPlansModel::where(['user_id' => $getRefUserId['0']['id']])->get()->toArray();

                    // if (count($checkReferalActivation) == 0) {
                    //     $res['status_code'] = 0;
                    //     $res['message'] = "Referrer is not active please check again.";
                    //     return is_mobile($type, "flogin", $res);
                    // }

                    $updateData['sponser_id'] = $getRefUserId['0']['id'];
                    $updateData['sponser_code'] = $updateData['refferal'];
                }
            }

            if (empty($updateData['refferal'])) {
                $res['status_code'] = 0;
                $res['message'] = "Please use referral link to register.";

                return is_mobile($type, "flogin", $res);
            }

            $updateData['password'] = md5($updateData['wallet_address']);
            $updateData['refferal_code'] = $refferal_code;
            $updateData['status'] = 1;
            $updateData['code'] = $randomString;
            $updateData['verified'] = 1;
            $updateData['created_on'] = date('Y-m-d H:i:s');

            unset($updateData['refferal']);

            usersModel::insert($updateData);

            $user_id = DB::getPdo()->lastInsertId();

            DB::statement("UPDATE users set my_direct = (my_direct + 1) where id = '" . $updateData['sponser_id'] . "'");

            updateReverseSize($user_id, $user_id);

            usersModel::where(['id' => $user_id])->update(['isCount' => 1]);

            $res['status_code'] = 1;
            $res['message'] = "Registration Successfully.";
            $res['email_verification'] = 1;
            $res['refferal_code'] = $refferal_code;
            $res['user_id'] = $user_id;

            return is_mobile($type, "flogin", $res);
        } else {
            $res['status_code'] = 0;
            $res['message'] = "Wallet Address already registered please try to login.";
            return is_mobile($type, "flogin", $res);
        }
    }

    public function verifyEmail(Request $request, $code)
    {
        $type = $request->input('type');

        $checkCode = usersModel::where(['code' => $code])->get()->toArray();

        if (count($checkCode) > 0) {
            usersModel::where(['id' => $checkCode['0']['id']])->update(['code' => '', 'verified' => '1']);

            $res['status_code'] = 1;
            $res['message'] = "Your Email Is Successfully Verified.";
        } else {
            $res['status_code'] = 0;
            $res['message'] = "Something went wrong please try again later.";
        }

        return is_mobile($type, "flogin", $res);
    }
}
