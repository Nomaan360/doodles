<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Redirect;
use App\Models\myTeamModel;
use App\Models\userPlansModel;
use App\Models\usersModel;
use App\Models\withdrawModel;

if (!function_exists('is_mobile')) {

    function is_mobile($type, $url = null, $data = null, $redirect_type = "redirect")
    {
        if ($type == "API") {
            return json_encode($data);
        } else {
            if ($redirect_type == 'redirect') {
                //                return redirect($url)->with(['data' => $data]);
                return redirect()->route($url)->with(['data' => $data]);
                //                return redirect()->route( 'clients.show' )->with( [ 'id' => $id ] );
            } else if ($redirect_type == 'view') {
                return view($url, ['data' => $data]);
            }
        }
    }
}

if (!function_exists('checkReferralCode')) {
    function checkReferralCode($refferal_code)
    {
        $checkRefCode = usersModel::where(['refferal_code' => $refferal_code])->get()->toArray();

        if (count($checkRefCode) == 0) {
            return 1;
        } else {
            return 0;
        }
    }
}

if (!function_exists('updateReverseSize')) {
    function updateReverseSize($user_id, $og_user_id)
    {
        $data = usersModel::where(['id' => $user_id])->get()->toArray();
        $ogdata = usersModel::where(['id' => $og_user_id])->get()->toArray();

        if (count($data) > 0) {
            if ($data['0']['sponser_id'] > 0) {
                $myTeam = array();
                $myTeam['user_id'] = $data['0']['sponser_id'];
                $myTeam['team_id'] = $og_user_id;
                $myTeam['sponser_id'] = $ogdata['0']['sponser_id'];

                myTeamModel::insert($myTeam);

                DB::statement("UPDATE users set my_team = (my_team + 1) where id = '" . $data['0']['sponser_id'] . "'");

                updateReverseSize($data['0']['sponser_id'], $og_user_id);
            }
        }
    }
}

if (!function_exists('getBalance')) {
    function getBalance($user_id)
    {
        $investments = usersModel::selectRaw("(roi_income + level_income + royalty + reward + direct_income + leadership_comission) as balance")->where(['id' => $user_id])->get()->toArray();

        $available_withdraw_balance = 0;
        $withdraw_balance = 0;

        foreach ($investments as $key => $value) {
            $available_withdraw_balance += $value['balance'];
        }

        $withdraw = withdrawModel::where(['user_id' => $user_id, 'status' => 1])->get()->toArray();

        foreach ($withdraw as $key => $value) {
            $withdraw_balance += $value['amount'];
        }

        return ($available_withdraw_balance - $withdraw_balance);
    }
}

if (!function_exists('getLevelTeam')) {
    function getLevelTeam($user_id)
    {
        $users = usersModel::where(['sponser_id' => $user_id])->get()->toArray();

        foreach ($users as $key => $value) {
            $currentPackage = 0;
            $matchingDistributed = 0;
            $allPackages = '';
            $currentPackageDate = '-';
            $package = userPlansModel::where(['user_id' => $value['id']])->whereRaw('roi > 0 and isSynced != 2')->get()->toArray();
            $otherPackageLeft = 0;
            $otherPackageRight = 0;
            $totalInvestment = 0;

            foreach ($package as $k => $v) {
                $totalInvestment += $v['amount'];

                if ($v['status'] == 1) {
                    $currentPackage = $v['amount'];
                    $matchingDistributed = $v['isSynced'];
                    $currentPackageDate = $v['created_on'];
                } else {
                    $allPackages .= $v['amount'] . ",";

                    $otherPackageLeft += $v['amount'];
                }
            }

            $users[$key]['matchingDistributed'] = $matchingDistributed;
            $users[$key]['currentPackage'] = $currentPackage;
            $users[$key]['otherPackageLeft'] = $otherPackageLeft;
            $users[$key]['otherPackageRight'] = $otherPackageRight;
            $users[$key]['currentPackageDate'] = $currentPackageDate;
            $users[$key]['totalInvestment'] = $totalInvestment;
            $users[$key]['allPackages'] = rtrim($allPackages, ",");

            $users[$key]['team_investment'] = $value['my_business']; //$finalTeamActiveAmount;
            $users[$key]['direct_investment'] = $value['direct_business']; //$finalDirectActiveAmount;
            $users[$key]['team_active'] = $value['active_team']; //count($my_team_active);
            $users[$key]['direct_active'] = $value['active_direct'];
        }

        return $users;
    }
}

if (!function_exists('updateReverseBusiness')) {
    function updateReverseBusiness($user_id, $amount)
    {
        $data = usersModel::where(['id' => $user_id])->get()->toArray();

        if (count($data) > 0) {
            if ($data['0']['sponser_id'] > 0) {
                // , leadership_business = (leadership_business + ".$amount.")
                DB::statement("UPDATE users set my_business = (my_business + " . $amount . ") where id = '" . $data['0']['sponser_id'] . "'");

                updateReverseBusiness($data['0']['sponser_id'], $amount);
            }
        }
    }
}

if (!function_exists('updateActiveTeam')) {
    function updateActiveTeam($user_id)
    {
        $data = myTeamModel::where(['team_id' => $user_id])->get()->toArray();

        foreach ($data as $key => $value) {
            usersModel::where('id', $value['user_id'])->update(['active_team' => DB::raw('active_team + 1')]);
        }
    }
}


if (!function_exists('getRefferer')) {
    function getRefferer($user_id)
    {
        $checkRefferal = usersModel::selectRaw("IFNULL(sponser_id, 0) as sponser_id")->where(['id' => $user_id])->get()->toArray();

        if (isset($checkRefferal['0']['sponser_id'])) {
            if ($checkRefferal['0']['sponser_id'] == 0) {
                return 0;
            } else {
                $getLevel = usersModel::select('level','rank_id')->where(['id' => $checkRefferal['0']['sponser_id']])->get()->toArray();

                $returnArray = array();
                $returnArray['sponser_id'] = $checkRefferal['0']['sponser_id'];
                $returnArray['level'] = $getLevel['0']['level'];
                $returnArray['rank_id'] = $getLevel['0']['rank_id'];

                return $returnArray;
            }
        } else {
            return 0;
        }
    }
}

if (!function_exists('verifyRSVP')) {
    function verifyRSVP($signature)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://147.93.106.204:3154/verify-wallet-using-vrs',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $signature,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $responseData = json_decode($response, true);
    }
}

if (!function_exists('getUserMaxReturn')) {
    function getUserMaxReturn($user_id)
    {
        $investments = userPlansModel::where(['user_id' => $user_id])->get()->toArray();

        $return = 0;

        foreach ($investments as $key => $value) {
            $return += $value['max_return'];
        }

        return $return;
    }
}

if (!function_exists('getIncome')) {
    function getIncome($user_id)
    {
        $users = usersModel::selectRaw("(direct_income + roi_income + level_income + royalty + reward) as balance")->where(['id' => $user_id])->get()->toArray();

        return $users['0']['balance'];
    }
}

if (!function_exists('rankwiseDirectIncome')) {
    function rankwiseDirectIncome()
    {
        $directIncome = array();
        $directIncome['1'] = 0.05;
        $directIncome['2'] = 0.075;
        $directIncome['3'] = 0.10;
        $directIncome['4'] = 0.15;
        $directIncome['5'] = 0.20;

        return $directIncome;
    }
}

if (!function_exists('rankwiseRoiIncome')) {
    function rankwiseRoiIncome()
    {
        $directIncome = array();
        $directIncome['1'] = 1;
        $directIncome['2'] = 1.5;
        $directIncome['3'] = 2;
        $directIncome['4'] = 2.5;
        $directIncome['5'] = 3;

        return $directIncome;
    }
}

if (!function_exists('isUserCompound')) {
    function isUserCompound($user_id)
    {
        $userPlans = userPlansModel::where(['user_id' => $user_id, 'compound' => 1, 'status' => 1])->get()->toArray();

        if(count($userPlans) > 0)
        {
            return $userPlans['0']['id'];
        }else
        {
            return 0;
        }
    }
}

if (!function_exists('findUplineRank')) {
    function findUplineRank($user_id, $findRank)
    {
        if(count($getUser) > 0)
        {
            $data = array();
            $data['user_id'] = $user_id;
            $data['rank'] = $getUser['0']['rank'];
            $data['rank_id'] = $getUser['0']['rank_id'];

            return $data;
        }else
        {
            $getSponser = usersModel::where(['id' => $user_id])->get()->toArray();

            if(count($getSponser) > 0)
            {
                return findUplineRank($getSponser['0']['sponser_id'], $findRank);
            }else
            {
                $data = array();
                $data['user_id'] = $user_id;
                $data['rank'] = 0;
                $data['rank_id'] = 0;

                return $data;
            }
        }
    }
}

if (!function_exists('updateReverseWeeklyBusiness')) {
    function updateReverseWeeklyBusiness($user_id, $amount)
    {
        $data = usersModel::where(['id' => $user_id])->get()->toArray();

        if(count($data) > 0)
        {
            if($data['0']['sponser_id'] > 0)
            {
                DB::statement("UPDATE users set weekly_business = (weekly_business + ".$amount.") where id = '".$data['0']['sponser_id']."'");

                updateReverseWeeklyBusiness($data['0']['sponser_id'], $amount);
            }
        }
    }
}
