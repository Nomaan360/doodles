<?php

namespace App\Http\Controllers;

use App\Models\earningLogsModel;
use App\Models\packageModel;
use App\Models\packageTransaction;
use App\Models\pay9Model;
use App\Models\settingModel;
use App\Models\userPlansModel;
use App\Models\usersModel;
use App\Models\withdrawModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use function App\Helpers\getIncome;
use function App\Helpers\getUserMaxReturn;
use function App\Helpers\is_mobile;
use function App\Helpers\rankwiseDirectIncome;
use function App\Helpers\isUserCompound;

class packagesController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type');
        $user_id = $request->session()->get('user_id');
        $canUseActivationBonus = 0;

        $user = usersModel::where(['id' => $user_id])->get()->toArray();
        $packages = userPlansModel::where(['user_id' => $user_id])->orderBy('id', 'desc')->get()->toArray();

        if($user['0']['active_direct'] >= 5 && $user['0']['direct_business'] >= 100)
        {
            $canUseActivationBonus = 1;
        }

        $res['status_code'] = 1;
        $res['message'] = "Activate package by TOPUP 9 Pay.";
        $res['topup_balance'] = $user['0']['topup_balance'];
        $res['registration_bonus'] = $user['0']['registration_bonus'];
        $res['packages'] = $packages;
        $res['user'] = $user['0'];
        $res['form_code'] = $user_id . date('YmdHis');
        $res['canUseActivationBonus'] = $canUseActivationBonus;

        // packageTransaction::where(['user_id' => $user_id])->whereRaw("remarks is not NULL")->update(['isSynced' => 1]);

        exec('cd /var/www/doodles/ && /usr/bin/php artisan check:level');

        return is_mobile($type, "pages.package_topup_9pay", $res, "view");
    }

    public function topup9pay(Request $request)
    {
        $type = $request->input("type");
        $user_id = $request->session()->get("user_id");

        $user = usersModel::where(["id" => $user_id])->get()->toArray();

        if (empty($user['0']['pg_evm_json'])) {
            $evm_tss = "https://api.9pay.co/get-eth-wallet/doodles-" . $user_id . "-" . $user['0']['refferal_code'] . "/eth";

            $getTss = file_get_contents($evm_tss);

            $finalTss = json_decode($getTss, true);

            usersModel::where(['id' => $user_id])->update(['pg_evm_json' => $finalTss]);
        }

        if (empty($user['0']['pg_trc_json'])) {
            $trc_tss = "https://api.9pay.co/get-tron-wallet/doodles-" . $user_id . "-" . $user['0']['refferal_code'];

            $getTss = file_get_contents($trc_tss);

            $finalTss = json_decode($getTss, true);

            usersModel::where(['id' => $user_id])->update(['pg_trc_json' => $finalTss]);
        }

        $user = usersModel::where(['id' => $user_id])->get()->toArray();

        $evmAddress = json_decode($user['0']['pg_evm_json'], true);
        $trcAddress = json_decode($user['0']['pg_trc_json'], true);

        $evmqrCode = QrCode::size(240)->generate($evmAddress['address']);
        $trcqrCode = QrCode::size(240)->generate($trcAddress['address']);

        $res['status_code'] = 1;
        $res['message'] = "Package fetched successfully";
        $res['evm_address'] = $evmAddress['address'];
        $res['trc_address'] = $trcAddress['address'];
        $res['evmqrCode'] = $evmqrCode;
        $res['trcqrCode'] = $trcqrCode;
        $res['registration_bonus'] = $user['0']['registration_bonus'];

        $checkPendingTransaction = packageTransaction::where(['user_id' => $user_id, 'isSynced' => 0])->get()->toArray();

        if (count($checkPendingTransaction) > 0) {
            $res['confirmTransactionWindow'] = 1;
            $res['pending_amount'] = $checkPendingTransaction['0']['amount'];
            $res['pending_transaction_hash'] = $checkPendingTransaction['0']['transaction_hash'];
            $res['pending_package_id'] = $checkPendingTransaction['0']['package_id'];
            $res['pending_package_amount'] = $checkPendingTransaction['0']['amount'];
        } else {
            $check9pay = pay9Model::where(['user_id' => $user_id, 'status' => 0])->get()->toArray();

            if (count($check9pay) > 0) {
                $res['amount'] = $check9pay['0']['amount'];
                $res['fees_amount'] = $check9pay['0']['fees_amount'];
                $res['received_amount'] = $check9pay['0']['received_amount'];
                $res['chain'] = $check9pay['0']['chain'];

                return is_mobile($type, "pages.package-9pay", $res, "view");
            }
        }

        return is_mobile($type, "pages.topup_9pay", $res, "view");
    }

    public function ajaxActivatePackage(Request $request)
    {
        $type = "API";
        $user_id = $request->session()->get('user_id');

        $checkPendingTransaction = packageTransaction::where(['user_id' => $user_id, 'isSynced' => 9])->get()->toArray();

        if (count($checkPendingTransaction) > 0) {

            $checkInvestment = userPlansModel::where(['transaction_hash' => $checkPendingTransaction['0']['transaction_hash'], 'user_id' => $user_id])->get()->toArray();

            if (count($checkInvestment) == 0) {

                $res['status_code'] = 1;
                $res['message'] = $checkPendingTransaction['0']['amount'] . " package detected its getting activated please wait.";

                return is_mobile($type, "topup9PayPackage", $res);
            }
        } else {
            $res['status_code'] = 0;
            $res['message'] = "No Package Found.";

            return is_mobile($type, "topup9PayPackage", $res);
        }
    }

    public function ajaxStorePackage(Request $request)
    {
        $type = "API";
        $user_id = $request->session()->get('user_id');
        $amount = $request->input('amount');
        $fees_amount = $request->input('fees_amount');
        $chain = $request->input('chain');

        if ($chain == "eth") {
            $fees_amount = 5;
        }

        if ($chain == "bsc") {
            $fees_amount = 1;
        }

        if ($chain == "polygon") {
            $fees_amount = 0.5;
        }

        if ($chain == "tron") {
            $fees_amount = 3;
        }

        if($amount > 100)
        {
            $fees_amount = ($amount * $fees_amount / 100);
        }

        $checkPendingTransaction = pay9Model::where(['user_id' => $user_id, 'status' => 0])->get()->toArray();

        if (count($checkPendingTransaction) == 0) {
            $pay9 = array();
            $pay9['user_id'] = $user_id;
            $pay9['amount'] = $amount;
            $pay9['fees_amount'] = $fees_amount;
            $pay9['received_amount'] = 0;
            $pay9['chain'] = $chain;
            $pay9['status'] = 0;
            $pay9['created_on'] = date('Y-m-d H:i:s');

            pay9Model::insert($pay9);

            $res['status_code'] = 1;
            $res['message'] = "Logged successfully";
        } else {
            $res['status_code'] = 0;
            $res['message'] = "Already transaction initiated.";

            return is_mobile($type, "topup9pay", $res);
        }
    }

    public function cancelPayTransaction(Request $request)
    {
        $type = $request->input('type');
        $user_id = $request->session()->get('user_id');

        $getPay = pay9Model::where(['user_id' => $user_id])->get()->toArray();

        if (count($getPay) > 0) {
            pay9Model::where(['user_id' => $user_id])->update(['status' => 2]);
        }

        $res['status_code'] = 1;
        $res['message'] = "Transaction canceled";

        return is_mobile($type, "topup9pay", $res);
    }

    public function apiHandlePackageTransaction9Pay(Request $request)
    {
        $type = "API";
        $newRequest = $request->input('auth');
        if (empty($newRequest)) {
            $res['status_code'] = 0;
            $res['message'] = "Parameter Missing.";

            return is_mobile($type, "topup9pay", $res);
        }
        $newRequest = str_replace("806a7c4ac4e0133b5a90af9008738851", "", $newRequest);
        $newRequest = str_replace("203eb5fde9dbf86421903bb84fde4e03", "", $newRequest);
        $decodedString = base64_decode($newRequest);
        $explodeString = explode("+", $decodedString);

        $transaction_hash = trim($explodeString['0']);
        $amount = trim($explodeString['1']);
        $usdt_amount = trim($explodeString['2']);
        $user_id = trim($explodeString['3']);
        $network_type = trim($explodeString['4']);


        $getUser = usersModel::where(['id' => $user_id])->get()->toArray();

        $res = array();

        if (count($getUser) > 0) {
            $user_id = $getUser['0']['id'];

            $check9pay = pay9Model::where(['user_id' => $user_id, 'status' => 0])->get()->toArray();

            if (count($check9pay) > 0) {
                DB::statement("UPDATE pay9_payments set received_amount = (IFNULL(received_amount,0) + ($amount)) where id = '" . $check9pay['0']['id'] . "'");
            }

            if ($user_id > 0) {
                DB::statement("UPDATE users set topup_balance = (IFNULL(topup_balance,0) + ($usdt_amount)) where id = '" . $user_id . "'");
            }


            $checkTransaction = packageTransaction::where(['transaction_hash' => $transaction_hash])->get()->toArray();

            if (count($checkTransaction) == 0) {

                $user_plans = array();
                $user_plans['user_id'] = $user_id;
                $user_plans['transaction_hash'] = $transaction_hash;
                $user_plans['amount'] = $usdt_amount;
                $user_plans['package_id'] = 1;
                $user_plans['isSynced'] = 9;
                $user_plans['isApi'] = 1;
                $user_plans['remarks'] = $network_type;

                packageTransaction::insert($user_plans);

                $res['status_code'] = 1;
                $res['message'] = "Transaction logged successfully.";
            } else {
                $res['status_code'] = 0;
                $res['message'] = "transaction already exist.";

                return is_mobile($type, "packages", $res);
            }
        } else {
            $res['status_code'] = 0;
            $res['message'] = "Invalid wallet address.";

            return is_mobile($type, "packages", $res);
        }

        return is_mobile($type, "packages", $res);
    }

    public function packageDeposit(Request $request)
    {
        $type = $request->input('type');
        $user_id = $request->session()->get('user_id');
        $canUseActivationBonus = 0;

        $user = usersModel::where(['id' => $user_id])->get()->toArray();
        $packages = userPlansModel::where(['user_id' => $user_id])->orderBy('id', 'desc')->get()->toArray();

        if($user['0']['active_direct'] >= 5 && $user['0']['direct_business'] >= 100)
        {
            $canUseActivationBonus = 1;
        }

        $res['status_code'] = 1;
        $res['message'] = "Activate package deposit.";
        $res['packages'] = $packages;
        $res['user'] = $user['0'];
        $res['form_code'] = $user_id . date('YmdHis');
        $res['canUseActivationBonus'] = $canUseActivationBonus;

        $checkPendingTransaction = packageTransaction::where(['user_id' => $user_id, 'isSynced' => 0])->whereRaw("transaction_hash != 'By Other'")->get()->toArray();

        if (count($checkPendingTransaction) > 0) {
            $res['confirmTransactionWindow'] = 1;
            $res['pending_amount'] = $checkPendingTransaction['0']['amount'];
            $res['pending_transaction_hash'] = $checkPendingTransaction['0']['transaction_hash'];
            $res['pending_package_id'] = $checkPendingTransaction['0']['package_id'];
            $res['pending_package_amount'] = $checkPendingTransaction['0']['amount'];
        }

        return is_mobile($type, "pages.packages", $res, "view");
    }

    public function processpackage(Request $request)
    {
        $type = $request->input('type');
        $package = $request->input('package');
        $amount = $request->input('amount');
        $transaction_hash = $request->input('transaction_hash');
        $unique_transaction_hash = $request->input('unique_th');
        $wallet = $request->input('wallet');

        if ($type == "API") {
            $user_id = $request->input('user_id');
        } else {
            $user_id = $request->session()->get('user_id');
        }

        if ($amount < 20) {
            $res['status_code'] = 0;
            $res['message'] = "Minimum transaction amount is $20.";

            return is_mobile($type, "packageDeposit", $res);
        }

        $users = usersModel::select('topup_balance', 'sponser_id', 'rank_id', 'refferal_code', 'created_on', 'registration_bonus')->where('id', $user_id)->get()->toArray();

        if($transaction_hash == "By Topup")
        {
            if($wallet == "registration_bonus")
            {
                if (($amount / 2) > $users['0']['topup_balance']) {
                    $res['status_code'] = 0;
                    $res['message'] = "Insufficient balance please topup.";

                    return is_mobile($type, "topup9pay", $res);
                }

                if (($amount / 2) > $users['0']['registration_bonus']) {
                    $res['status_code'] = 0;
                    $res['message'] = "Insufficient balance in your activation bonus please check and proceed.";

                    return is_mobile($type, "packageDeposit", $res);
                }
            }else
            {
                if ($amount > $users['0']['topup_balance']) {
                    $res['status_code'] = 0;
                    $res['message'] = "Insufficient balance please topup.";

                    return is_mobile($type, "topup9pay", $res);
                }
            }
        }

        if (!empty($transaction_hash) && $transaction_hash != 'By Topup') {
            $checkTransactionExist = userPlansModel::where(['transaction_hash' => $transaction_hash, 'user_id' => $user_id])->get()->toArray();

            if (count($checkTransactionExist) > 0) {
                packageTransaction::where(['transaction_hash' => $transaction_hash, 'user_id' => $user_id])->update(['isSynced' => 1]);

                $res['status_code'] = 0;
                $res['message'] = "Your package is already activated.";

                return is_mobile($type, "packageDeposit", $res);
            }

            $checkTransactionExist = userPlansModel::where(['transaction_hash' => $transaction_hash])->get()->toArray();

            if (count($checkTransactionExist) > 0) {
                packageTransaction::where(['transaction_hash' => $transaction_hash])->update(['isSynced' => 1]);

                $res['status_code'] = 0;
                $res['message'] = "Your package is already activated.";

                return is_mobile($type, "packageDeposit", $res);
            }
        }

        if ($amount >= 20 && $amount <= 199) {
            $package = 1;
        } else if ($amount >= 200 && $amount <= 999) {
            $package = 2;
        } else if ($amount >= 1000 && $amount <= 2999) {
            $package = 3;
        } else if ($amount >= 3000 && $amount <= 9999) {
            $package = 4;
        } else if ($amount >= 10000) {
            $package = 5;
        }

        $packageData = packageModel::where(['status' => 1, 'id' => $package])->get()->toArray();

        $packageData = $packageData['0'];

        $packageData['amount'] = $amount;

        $user_plans = array();
        $user_plans['user_id'] = $user_id;
        $user_plans['package_id'] = $package;
        $user_plans['amount'] = $amount;
        $user_plans['roi'] = $packageData['roi'];
        $user_plans['days'] = $packageData['days'];
        $user_plans['max_return'] = $packageData['max'] * $amount;
        $user_plans['transaction_hash'] = $transaction_hash;
        $user_plans['unique_th'] = $unique_transaction_hash;
        $user_plans['status'] = 1;
        $user_plans['created_on'] = date('Y-m-d H:i:s');

        if($wallet == "registration_bonus")
        {
            $user_plans['remarks'] = "Activation Bonus - ".($amount / 2);
        }

        userPlansModel::insert($user_plans);

        if($transaction_hash == "By Topup")
        {
            if($wallet == "registration_bonus")
            {
                usersModel::where(['id' => $user_id])->update(['topup_balance' => DB::raw('topup_balance - ' . ($amount / 2))]);
                usersModel::where(['id' => $user_id])->update(['registration_bonus' => DB::raw('registration_bonus - ' . ($amount / 2))]);
            }else
            {
                usersModel::where(['id' => $user_id])->update(['topup_balance' => DB::raw('topup_balance - ' . $amount)]);
            }

            pay9Model::where(['status' => 0, 'user_id' => $user_id])->update(['status' => 1]);
        }

        //is elgible for registration bonus

        $checkIfFirstPackage = userPlansModel::where(['user_id' => $user_id])->get()->toArray();
        if (count($checkIfFirstPackage) == 1) {
            $registrationDate = $users['0']['created_on'];
            $activationDate = $user_plans['created_on'];
            $diff = strtotime($activationDate) - strtotime($registrationDate);
            $hours = floor($diff / (60 * 60));
            if ($hours <= 72) {
                $roi = array();
                $roi['user_id'] = $user_id;
                $roi['amount'] = 100;
                $roi['tag'] = "REGISTRATION-BONUS";
                $roi['status'] = 1;
                $roi['refrence'] = $registrationDate;
                $roi['refrence_id'] = 0;
                $roi['created_on'] = date('Y-m-d H:i:s');

                earningLogsModel::insert($roi);

                DB::statement("UPDATE users set registration_bonus = (IFNULL(registration_bonus,0) + (" . ($roi['amount']) . ")) where id = '" . $user_id . "'");
            }
        }

        packageTransaction::where(['transaction_hash' => $transaction_hash, 'user_id' => $user_id])->update(['isSynced' => 1]);

        if ($users['0']['sponser_id'] > 0) {

            $sponser = usersModel::select('topup_balance', 'sponser_id', 'rank_id', 'refferal_code')->where('id', $users['0']['sponser_id'])->get()->toArray();

            $directIncomeRank = rankwiseDirectIncome();

            $checkIfFirstPackage = userPlansModel::where('user_id', $user_id)->get()->toArray();

            if (count($checkIfFirstPackage) == 1) {
                usersModel::where('id', $users['0']['sponser_id'])->update(['active_direct' => DB::raw('active_direct + 1')]);
            }

            usersModel::where('id', $users['0']['sponser_id'])->update(['direct_business' => DB::raw('direct_business + ' . $amount)]);

            if($sponser['0']['rank_id'] > 0)
            {
                $directAmount = $amount * $directIncomeRank[$sponser['0']['rank_id']];

                $totalMaxReturn = getUserMaxReturn($users['0']['sponser_id']);
                $totalIncome = getIncome($users['0']['sponser_id']);

                $roi = array();
                if (($totalIncome + $directAmount) >= $totalMaxReturn) {
                    $directAmount = ($totalMaxReturn - $totalIncome);

                    $roi['flush_amount'] = (($amount * $directIncomeRank[$sponser['0']['rank_id']]) - $directAmount);

                    userPlansModel::where(['user_id' => $users['0']['sponser_id'], 'status' => 1])->update(['status' => 2, 'completed_on' => date('Y-m-d H:i:s')]);
                }

                if ($directAmount > 0) {
                    $roi['user_id'] = $users['0']['sponser_id'];
                    $roi['amount'] = $directAmount;
                    $roi['tag'] = "REFERRAL";
                    $roi['status'] = 1;
                    $roi['refrence'] = $users['0']['refferal_code'];
                    $roi['refrence_id'] = $sponser['0']['rank_id'];
                    $roi['created_on'] = date('Y-m-d H:i:s');

                    earningLogsModel::insert($roi);

                    $checkCompoundId = isUserCompound($users['0']['sponser_id']);
                    
                    if($checkCompoundId > 0)
                    {
                        userPlansModel::where('id', $checkCompoundId)->update(['compound_amount' => DB::raw('compound_amount + ' . $directAmount)]);

                        $withdraw = array();
                        $withdraw['user_id'] = $users['0']['sponser_id'];
                        $withdraw['withdraw_type'] = "COMPOUND";
                        $withdraw['amount'] = $roi['amount'];
                        $withdraw['net_amount'] = $roi['amount'];
                        $withdraw['admin_charge'] = 0;
                        $withdraw['fees'] = 0;
                        $withdraw['status'] = 1;
                        $withdraw['transaction_hash'] = "BY COMPOUND";
                        $withdraw['coin_price'] = 1;
                        $withdraw['isSynced'] = 0;
                        $withdraw['isRequestSynced'] = 0;
                        $withdraw['remarks'] = "REFERRAL COMPOUND WITHDRAW";

                        withdrawModel::insert($withdraw);
                    }
                    DB::statement("UPDATE users set direct_income = (IFNULL(direct_income,0) + (" . ($roi['amount']) . ")) where id = '" . $users['0']['sponser_id'] . "'");
                    DB::statement("UPDATE user_plans set `return` = (IFNULL(`return`,0) + (" . $roi['amount'] . ")) where user_id = '" . $users['0']['sponser_id'] . "' and status = 1");

                }
            }
        }

        $res['status_code'] = 1;
        $res['message'] = "Package activated successfully";

        return is_mobile($type, "packageDeposit", $res);
    }

    public function handlePackageTransaction(Request $request)
    {
        $type = $request->input('type');
        $transaction_hash = $request->input('transaction_hash');
        $amount = $request->input('amount');

        if ($type == "API") {
            $wallet_address = $request->input("wallet_address");
            $wallet = "USDT";
            $user = usersModel::where(['wallet_address' => $wallet_address])->get()->toArray();

            if (count($user) == 0) {
                $res['status_code'] = 0;
                $res['message'] = "Invalid user id.";

                return is_mobile($type, "packageDeposit", $res);
            }
            $user_id = $user['0']['id'];
        } else {
            $user_id = $request->session()->get('user_id');
            $wallet = $request->input('wallet');
        }

        $user = usersModel::where(['id' => $user_id])->get()->toArray();

        if ($amount >= 20 && $amount <= 199) {
            $package = 1;
        } else if ($amount >= 200 && $amount <= 999) {
            $package = 2;
        } else if ($amount >= 1000 && $amount <= 2999) {
            $package = 3;
        } else if ($amount >= 3000 && $amount <= 9999) {
            $package = 4;
        } else if ($amount >= 10000) {
            $package = 5;
        }

        $user_plans = array();
        $user_plans['user_id'] = $user_id;
        $user_plans['transaction_hash'] = $transaction_hash;
        $user_plans['amount'] = $amount;
        $user_plans['package_id'] = $package;
        $user_plans['isSynced'] = 0;
        $user_plans['wallet'] = $wallet;
        $user_plans['remarks'] = "polygon";
        packageTransaction::insert($user_plans);

        $res['status_code'] = 1;
        $res['message'] = "Please check transaction status to proceed.";

        return is_mobile($type, "package", $res);
    }

    public function checkPackageTransaction(Request $request)
    {
        $type = $request->input('type');
        $user_id = $request->session()->get('user_id');
        $isSynced = 0;

        $checkPendingTransaction = packageTransaction::where(['user_id' => $user_id, 'isSynced' => 0])->get()->toArray();


        if (count($checkPendingTransaction) > 0) {

            $tempUser = usersModel::where(['id' => $user_id])->get()->toArray();

            if (count($tempUser) == 0) {
                $res['status_code'] = 0;
                $res['message'] = "Something went wrong please try again later.";

                return is_mobile($type, "package", $res);
            }
            
            $checkAmount = $checkPendingTransaction['0']['amount'];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://69.62.76.140:3154/check-transaction',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'transaction=' . $checkPendingTransaction['0']['transaction_hash'] . '&amount=' . $checkAmount . '&wallet=' . $tempUser['0']['wallet_address'],
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $decodeResponse = json_decode($response, true);

            if (isset($decodeResponse['status'])) {
                if ($decodeResponse['status'] == 1) {
                    if (isset($decodeResponse['result'])) {
                        if ($decodeResponse['result'] == true) {
                            $isSynced = 1;
                            $checkInvestment = userPlansModel::where(['transaction_hash' => $checkPendingTransaction['0']['transaction_hash'], 'user_id' => $user_id])->get()->toArray();

                            if (count($checkInvestment) == 0) {
                                packageTransaction::where(['transaction_hash' => $checkPendingTransaction['0']['transaction_hash']])->update(['json' => $response]);
                                return redirect()->route('process.package', ['transaction_hash' => $checkPendingTransaction['0']['transaction_hash'], 'unique_th' => $checkPendingTransaction['0']['transaction_hash'], 'amount' => $checkPendingTransaction['0']['amount'], 'package' => $checkPendingTransaction['0']['package_id']]);
                            }
                        } else {
                            $isSynced = 2;
                        }
                    } else {
                        $isSynced = 2;
                    }
                } else if ($decodeResponse['status'] == 0) {
                    $isSynced = 0;
                } else {
                    $isSynced = 2;
                }

                packageTransaction::where(['transaction_hash' => $checkPendingTransaction['0']['transaction_hash']])->update(['json' => $response, 'isSynced' => $isSynced]);

                $res['status_code'] = 0;
                if ($isSynced == 0) {
                    $res['message'] = "You transaction is still pending please check again later.";
                } else {
                    $res['message'] = "Your transaction is failed please check and try again later.";
                }

                return is_mobile($type, "package", $res);
            } else {
                $res['status_code'] = 0;
                $res['message'] = "Something went wrong please try again later.";

                return is_mobile($type, "package", $res);
            }
        } else {
            $res['status_code'] = 0;
            $res['message'] = "Package activated successfully.";

            return is_mobile($type, "package", $res);
        }
    }

    public function packageCompound(Request $request)
    {
        $type = $request->input('type');
        $package_id = $request->input('package_id');
        $user_id = $request->session()->get('user_id');

        $checkPackage = userPlansModel::where(['user_id' => $user_id, 'id' => $package_id])->get()->toArray();

        if(count($checkPackage) == 0)
        {
            $res['status_code'] = 0;
            $res['message'] = "Invalid Package To Compound";

            return is_mobile($type, "packageDeposit", $res);
        }

        if($checkPackage['0']['compound'] == 1)
        {
            $res['status_code'] = 0;
            $res['message'] = "This package is already compounded.";

            return is_mobile($type, "packageDeposit", $res);
        }

        $checkUserCompound = userPlansModel::where(['user_id' => $user_id, 'compound' => 1])->get()->toArray();

        if(count($checkUserCompound) > 0)
        {
            $res['status_code'] = 0;
            $res['message'] = "One of your package is already compounding.";

            return is_mobile($type, "packageDeposit", $res);
        }

        userPlansModel::where(['user_id' => $user_id, 'id' => $package_id])->update(['compound' => 1]);

        $res['status_code'] = 1;
        $res['message'] = "Compound successfully processed.";

        return is_mobile($type, "packageDeposit", $res);
    }

    public function myNfts(Request $request)
    {
        $type = $request->input('type');
        $user_id = $request->session()->get('user_id');

        $package = userPlansModel::where(['user_id' => $user_id])->get()->toArray();

        $res['status_code'] = 1;
        $res['message'] = "Package NFT list.";
        $res['nfts'] = $package;

        return is_mobile($type, "pages.mynfts", $res, "view");
    }
}
