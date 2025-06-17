<?php

namespace App\Http\Controllers;

use App\Models\settingModel;
use App\Models\myTeamModel;
use App\Models\userPlansModel;
use App\Models\earningLogsModel;
use App\Models\levelEarningLogsModel;
use App\Models\usersModel;
use App\Models\transferModel;
use App\Models\withdrawModel;
use Illuminate\Http\Request;

use function App\Helpers\getBalance;
use function App\Helpers\getRP1Balance;
use function App\Helpers\is_mobile;
use Illuminate\Support\Facades\DB;

class withdrawController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input("type");
        $user_id = $request->session()->get("user_id");

        $user = usersModel::where("id", $user_id)->get()->toArray();

        $withdraw = withdrawModel::where(['user_id' => $user_id])->orderBy('id', 'desc')->get()->toArray();

        $lastRequest = withdrawModel::where(['user_id' => $user_id, 'status' => 0])->orderBy('id', 'desc')->get()->toArray();

        $setting = settingModel::get()->toArray();

        $adminUserPlans = userPlansModel::selectRaw("IFNULL(SUM(amount), 0) as activationAmount")->where(['transaction_hash' => 'By Admin', 'user_id' => $user_id])->get()->toArray();

        // if(count($adminUserPlans) > 0)
        // {
        //     if($adminUserPlans['0']['activationAmount'] > 0)
        //     {
        //         $setting['0']['admin_fees'] = 30;
        //     }
        // }

        $queue = 0;

        if (count($lastRequest) > 0) {
            if ($lastRequest['0']['isSynced'] == '-1') {
                $getCountQueue = withdrawModel::whereRaw("id < " . $lastRequest['0']['id'] . " and isSynced = '-1'")->get()->toArray();

                $queue = count($getCountQueue) + 1;
            }
        }

        $pending_withdraw_amount = 0;
        $withdraw_amount = 0;

        foreach ($withdraw as $key => $value) {
            if ($value['status'] == 0) {
                $pending_withdraw_amount += $value['amount'];
            }
            if ($value['status'] == 1) {
                $withdraw_amount += $value['amount'];
            }
        }

        $totalIncome = $user['0']['roi_income'] + $user['0']['level_income'] + $user['0']['reward'] + $user['0']['royalty'] + $user['0']['direct_income'] + $user['0']['leadership_comission'];

        $res['status_code'] = 1;
        $res['message'] = "Success";
        $res['user'] = $user['0'];
        $res['setting'] = $setting['0'];
        $res['totalIncome'] = $totalIncome;
        $res['queue'] = $queue;
        $res['withdraw'] = $withdraw;
        $res['withdraw_amount'] = $withdraw_amount;
        $res['availableBalance'] = $totalIncome - $withdraw_amount;
        $res['pendingWithdraw'] = $pending_withdraw_amount;

        return is_mobile($type, "pages.withdraws", $res, "view");
    }

    public function withdrawprocess(Request $request)
    {
        $type = $request->input('type');
        $withdraw_type = "USDT";
        $amount = $request->input('amount');
        $hashedMessageScript = $request->input('hashedMessageScript');
        $rsvScript = $request->input('rsvScript');
        $rsScript = $request->input('rsScript');
        $rScript = $request->input('rScript');
        $user_id = $request->session()->get('user_id');

        $users = usersModel::where(['id' => $user_id])->get()->toArray();

        $setting = settingModel::get()->toArray();

        if ($setting['0']['withdraw_setting'] == 0) {
            $res['status_code'] = 0;
            $res['message'] = "Withdraw can not be processed right now. Please try again later.";

            return is_mobile($type, "fwithdraw", $res);
        }

        $checkCanWithdraw = usersModel::where(['id' => $user_id])->get()->toArray();

        if ($checkCanWithdraw['0']['canWithdraw'] == 0) {
            $res['status_code'] = 0;
            $res['message'] = "Withdraw can not be processed right now please try again later.";

            return is_mobile($type, "fwithdraw", $res);
        }

        if ($amount <= 0) {
            $res['status_code'] = 0;
            $res['message'] = "Amount must be greater than zero.";

            return is_mobile($type, "fwithdraw", $res);
        }

        if ($amount < 10) {
            $res['status_code'] = 0;
            $res['message'] = "Minimum Amount to process withdraw is $10.";

            return is_mobile($type, "fwithdraw", $res);
        }

        $balance = getBalance($user_id);

        if ($amount > ($balance)) {
            $res['status_code'] = 0;
            $res['message'] = "Insufficent balance to process that request.";

            return is_mobile($type, "fwithdraw", $res);
        }

        $admin_charge = ($setting['0']["admin_fees"] * $amount) / 100;

        $net_amount = $amount - $admin_charge;

        $withdrawAmount = withdrawModel::selectRaw("SUM(amount) as amount")->where(['user_id' => $user_id, 'status' => 1])->get()->toArray();

        if (($withdrawAmount['0']['amount'] + $amount) > ($users['0']['roi_income'] + $users['0']['level_income'] + $users['0']['royalty'] + $users['0']['reward'] + $users['0']['direct_income'] + $users['0']['leadership_comission'])) {
            $res['status_code'] = 0;
            $res['message'] = "Amount entered more your total balance please try again later.";

            return is_mobile($type, "fwithdraw", $res);
        }

        $checkWithdraw = withdrawModel::where(['user_id' => $user_id, 'status' => 0])->get()->toArray();

        if (count($checkWithdraw) > 0) {
            $res['status_code'] = 0;
            $res['message'] = "One of your withdrawal is in process please wait.";

            return is_mobile($type, "fwithdraw", $res);
        }

        $checkPackage = userPlansModel::where(['user_id' => $user_id])->get()->toArray();

        if(count($checkPackage) == 0)
        {
            $res['status_code'] = 0;
            $res['message'] = "Your are not eligible to withdraw.";

            return is_mobile($type, "fwithdraw", $res);
        }

        $withdrawCount = withdrawModel::where(['user_id' => $user_id, 'status' => 1])->get()->toArray();
        $withdraw_balance_earn = 0;
        foreach ($withdrawCount as $key => $value) {
            $withdraw_balance_earn += $value['amount'];
        }

        $countEarningLogs = earningLogsModel::selectRaw("IFNULL(SUM(amount), 0) as earnAmount")->where(['user_id' => $user_id])->get()->toArray();
        $countLevelEarningLogs = levelEarningLogsModel::selectRaw("IFNULL(SUM(amount), 0) as earnAmount")->where(['user_id' => $user_id])->get()->toArray();

        if ($amount > (($countEarningLogs['0']['earnAmount'] + $countLevelEarningLogs['0']['earnAmount']) - $withdraw_balance_earn)) {
            $res['status_code'] = 0;
            $res['message'] = "Insufficent balance to process that request.";

            return is_mobile($type, "fwithdraw", $res);
        }

        $signaturejsonRPC = array();

        $signaturejsonRPC['message'] = $hashedMessageScript;
        $signaturejsonRPC['v'] = $rsvScript;
        $signaturejsonRPC['r'] = $rScript;
        $signaturejsonRPC['s'] = $rsScript;

        $transaction_hash = '-';

        $withdraw = array();
        $withdraw['user_id'] = $user_id;
        $withdraw['withdraw_type'] = $withdraw_type;
        $withdraw['amount'] = $amount;
        $withdraw['coin_price'] = 1;
        $withdraw['admin_charge'] = $admin_charge;
        $withdraw['fees'] = $setting['0']["admin_fees"];
        $withdraw['net_amount'] = $net_amount;
        $withdraw['transaction_hash'] = $transaction_hash;
        $withdraw['status'] = 0;
        $withdraw['json_response'] = '-';
        $withdraw['isSynced'] = '-1';
        $withdraw['isRequestSynced'] = '-1';
        $withdraw['signatureJson'] = json_encode($signaturejsonRPC, true);

        withdrawModel::insert($withdraw);

        $res['status_code'] = 1;
        $res['message'] = "Withdraw Request Submitted Successfully.";

        return is_mobile($type, "fwithdraw", $res);
    }

    public function transferBalance(Request $request)
    {
        $type = $request->input('type');
        $user_id = $request->session()->get('user_id');

        $user = usersModel::where(['id' => $user_id])->get()->toArray();

        $transfer = transferModel::join('users as user1', 'user1.id', '=', 'transfer.user_id')
            ->join('users as user2', 'user2.id', '=', 'transfer.for_user_id')
            ->where('transfer.user_id', $user_id)
            ->orWhere('transfer.for_user_id', $user_id)
            ->select(
                'transfer.*',
                'user1.refferal_code as user_refferal_code',
                'user2.refferal_code as for_user_refferal_code'
            )
            ->orderBy('transfer.id', 'desc')
            ->get()
            ->toArray();

        $res['status_code'] = 1;
        $res['message'] = "Transfer Balance.";
        $res['user'] = $user['0'];
        $res['transfer'] = $transfer;

        return is_mobile($type, "pages.transfer-balance", $res, "view");
    }

    public function transferBalanceProcess(Request $request)
    {
        $type = $request->input('type');
        $amount = $request->input('amount');
        $refferal_code = $request->input('refferal_code');
        $user_id = $request->session()->get('user_id');
        $total_amount = $amount;

        $user = usersModel::where(['id' => $user_id])->get()->toArray();

        $checkRefferalValid = usersModel::where(['refferal_code' => $refferal_code])->get()->toArray();

        if (count($checkRefferalValid) == 0) {
            $res['status_code'] = 0;
            $res['message'] = "Invalid Receiever User Id.";

            return is_mobile($type, "ftransferBalance", $res);
        }

        if ($user['0']['active_direct'] < 5) {
            $res['status_code'] = 0;
            $res['message'] = "You need to have 5 active direct referrals to transfer balance.";

            return is_mobile($type, "ftransferBalance", $res);
        }

        if ($user['0']['direct_business'] < 100) {
            $res['status_code'] = 0;
            $res['message'] = "You need to have 100 direct business to transfer balance.";

            return is_mobile($type, "ftransferBalance", $res);
        }

        if ($amount < 10) {
            $res['status_code'] = 0;
            $res['message'] = "Minimum Amount to process withdraw is $10.";

            return is_mobile($type, "ftransferBalance", $res);
        }

        $checkDownline = myTeamModel::where(['user_id' => $user_id, 'team_id' => $checkRefferalValid['0']['id']])->get()->toArray();

        if (count($checkDownline) == 0) {
            $res['status_code'] = 0;
            $res['message'] = "Receiever User Id is not in your Team.";

            return is_mobile($type, "ftransferBalance", $res);
        }

        if ($amount > $user['0']['registration_bonus']) {
            $res['status_code'] = 0;
            $res['message'] = "Insufficent funds to transfer. Available Balance is $" . $user['0']['registration_bonus'] . " and trying to transfer $" . $amount;

            return is_mobile($type, "ftransferBalance", $res);
        }

        $transferData = array();
        $transferData['user_id'] = $user_id;
        $transferData['for_user_id'] = $checkRefferalValid['0']['id'];
        $transferData['amount'] = $amount;
        $transferData['status'] = 1;
        $transferData['created_on'] = date('Y-m-d H:i:s');

        transferModel::insert($transferData);

        $user = usersModel::where(['id' => $user_id])->get()->toArray();

        DB::statement("UPDATE users set registration_bonus = (registration_bonus - $amount) where id = '" . $user_id . "'");

        DB::statement("UPDATE users set registration_bonus = (registration_bonus + $amount) where id = '" . $checkRefferalValid['0']['id'] . "'");

        $res['status_code'] = 1;
        $res['message'] = "Transfer Balance.";

        return is_mobile($type, "ftransferBalance", $res);
    }
}
