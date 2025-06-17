<?php

namespace App\Http\Controllers;

use App\Models\earningLogsModel;
use App\Models\levelEarningLogsModel;
use App\Models\levelRoiModel;
use App\Models\myTeamModel;
use App\Models\packageModel;
use App\Models\withdrawModel;
use App\Models\userPlansModel;
use App\Models\usersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function App\Helpers\getRefferer;
use function App\Helpers\rankwiseRoiIncome;
use function App\Helpers\updateActiveTeam;
use function App\Helpers\updateReverseBusiness;
use function App\Helpers\updateReverseWeeklyBusiness;
use function App\Helpers\isUserCompound;

class scriptController extends Controller
{
    public function checkLevel(Request $request)
    {
        $investment = userPlansModel::where(['isCount' => 0])->whereRaw("roi > 0")->orderBy('id', 'asc')->get()->toArray();

        foreach ($investment as $key => $value) {
            updateReverseBusiness($value['user_id'], $value['amount']);

            $checkIfFirstPackage = userPlansModel::where('user_id', $value['user_id'])->get()->toArray();

            if (count($checkIfFirstPackage) == 1) {
                updateActiveTeam($value['user_id']);
            }

            userPlansModel::where(['id' => $value['id']])->update(['isCount' => 1]);
        }

        $users = usersModel::where(['status' => 1])->orderBy('id', 'asc')->get()->toArray();

        foreach ($users as $key => $value) {
            $activeDirectCount = usersModel::join('user_plans', 'user_plans.user_id', '=', 'users.id')
                ->where(['users.sponser_id' => $value['id']])
                ->selectRaw('COUNT(DISTINCT users.id) as count')
                ->first()
                ->count;

            $levelsOpen = levelRoiModel::select('id')->where(['direct' => $activeDirectCount])->orderBy('id', 'desc')->get()->toArray();

            if (count($levelsOpen) > 0) {
                usersModel::where(['id' => $value['id']])->update(['level' => $levelsOpen['0']['id']]);
            }
        }
    }

    public function activeTeamCalculate(Request $request)
    {
        usersModel::where(['status' => 1])->update(['active_team' => 0]);

        $userPlans = userPlansModel::select('user_id')->groupBy('user_id')->get()->toArray();

        foreach ($userPlans as $key => $value) {
            updateActiveTeam($value['user_id']);
        }
    }

    public function roiRelease(Request $request)
    {
        $packages = userPlansModel::where(['status' => 1])->whereRaw("roi > 0")->orderBy('id', 'desc')->get()->toArray();

        $roiIncomeRank = rankwiseRoiIncome();

        foreach ($packages as $key => $value) {
            $getUserRank = usersModel::select('topup_balance', 'sponser_id', 'rank_id', 'refferal_code','ad_viewed')->where('id', $value['user_id'])->get()->toArray();

            if($getUserRank['0']['rank_id'] == 0)
            {
                continue;
            }

            //roi calculation start
            $roi = $roiIncomeRank[$getUserRank['0']['rank_id']];
            $amount = ($value['amount'] + $value['compound_amount']);
            $user_id = $value['user_id'];
            $investment_id = $value['id'];

            if($getUserRank['0']['ad_viewed'] == 0)
            {
                continue;
            }

            $levelRoi = levelRoiModel::where(['status' => 1])->get()->toArray();

            $roiUser = usersModel::where(['id' => $user_id])->get()->toArray();

            $roiLevel = array();

            foreach ($levelRoi as $key => $value) {
                $roiLevel[$value['level']]['percentage_1'] = $value['percentage_1'];
                $roiLevel[$value['level']]['percentage_2'] = $value['percentage_2'];
                $roiLevel[$value['level']]['percentage_3'] = $value['percentage_3'];
                $roiLevel[$value['level']]['percentage_4'] = $value['percentage_4'];
                $roiLevel[$value['level']]['percentage_5'] = $value['percentage_5'];
            }

            $roi_amount = ($amount * $roi) / 100;

            $roi = array();
            $roi['user_id'] = $user_id;
            $roi['amount'] = $roi_amount;
            $roi['tag'] = "ROI";
            $roi['refrence_id'] = $investment_id;
            $roi['created_on'] = date('Y-m-d H:i:s');

            earningLogsModel::insert($roi);

            $checkCompoundId = isUserCompound($user_id);
                
            if($checkCompoundId > 0)
            {
                userPlansModel::where('id', $checkCompoundId)->update(['compound_amount' => DB::raw('compound_amount + ' . $roi_amount)]);

                $withdraw = array();
                $withdraw['user_id'] = $user_id;
                $withdraw['withdraw_type'] = "COMPOUND";
                $withdraw['amount'] = $roi_amount;
                $withdraw['net_amount'] = $roi_amount;
                $withdraw['admin_charge'] = 0;
                $withdraw['fees'] = 0;
                $withdraw['status'] = 1;
                $withdraw['transaction_hash'] = "BY COMPOUND";
                $withdraw['coin_price'] = 1;
                $withdraw['isSynced'] = 0;
                $withdraw['isRequestSynced'] = 0;
                $withdraw['remarks'] = "ROI COMPOUND WITHDRAW";

                withdrawModel::insert($withdraw);
            }

            DB::statement("UPDATE users set roi_income = (IFNULL(roi_income,0) + ($roi_amount)) where id = '" . $user_id . "'");
            DB::statement("UPDATE user_plans set `return` = (IFNULL(`return`,0) + (" . $roi_amount . ")) where user_id = '" . $user_id . "' and status = 1");


            userPlansModel::where(['id' => $investment_id])->update(['return' => DB::raw('`return` + ' . $roi_amount)]);
            //roi calculation end

            //level roi distribution
            $level1 = getRefferer($user_id);
            if (isset($level1['sponser_id']) && $level1['sponser_id'] > 0) {
                if ($level1['rank_id'] > 1) {
                    $level1_amount = ($roi_amount * $roiLevel['1']['percentage_'. $level1['rank_id']]) / 100;

                    $level1_roi = array();
                    $level1_roi['user_id'] = $level1['sponser_id'];
                    $level1_roi['amount'] = $level1_amount;
                    $level1_roi['tag'] = "LEVEL1-ROI";
                    $level1_roi['refrence'] = $roiUser['0']['refferal_code'];
                    $level1_roi['refrence_id'] = $investment_id;
                    $level1_roi['created_on'] = date('Y-m-d H:i:s');

                    levelEarningLogsModel::insert($level1_roi);

                    $checkCompoundId = isUserCompound($level1['sponser_id']);
                
                    if($checkCompoundId > 0)
                    {
                        userPlansModel::where('id', $checkCompoundId)->update(['compound_amount' => DB::raw('compound_amount + ' . $level1_amount)]);

                        $withdraw = array();
                        $withdraw['user_id'] = $level1['sponser_id'];
                        $withdraw['withdraw_type'] = "COMPOUND";
                        $withdraw['amount'] = $level1_amount;
                        $withdraw['net_amount'] = $level1_amount;
                        $withdraw['admin_charge'] = 0;
                        $withdraw['fees'] = 0;
                        $withdraw['status'] = 1;
                        $withdraw['transaction_hash'] = "BY COMPOUND";
                        $withdraw['coin_price'] = 1;
                        $withdraw['isSynced'] = 0;
                        $withdraw['isRequestSynced'] = 0;
                        $withdraw['remarks'] = "LEVEL 1 COMPOUND WITHDRAW";

                        withdrawModel::insert($withdraw);
                    }

                    DB::statement("UPDATE users set level_income = (IFNULL(level_income,0) + ($level1_amount)) where id = '" . $level1['sponser_id'] . "'");
                    DB::statement("UPDATE user_plans set `return` = (IFNULL(`return`,0) + (" . $level1_amount . ")) where user_id = '" . $level1['sponser_id'] . "' and status = 1");
                    
                }

                $level2 = getRefferer($level1['sponser_id']);
                if (isset($level2['sponser_id']) && $level2['sponser_id'] > 0) {
                    if ($level2['rank_id'] > 1) {
                        $level2_amount = ($roi_amount * $roiLevel['2']['percentage_'. $level2['rank_id']]) / 100;

                        $level2_roi = array();
                        $level2_roi['user_id'] = $level2['sponser_id'];
                        $level2_roi['amount'] = $level2_amount;
                        $level2_roi['tag'] = "LEVEL2-ROI";
                        $level2_roi['refrence'] = $roiUser['0']['refferal_code'];
                        $level2_roi['refrence_id'] = $investment_id;
                        $level2_roi['created_on'] = date('Y-m-d H:i:s');

                        levelEarningLogsModel::insert($level2_roi);

                        $checkCompoundId = isUserCompound($level2['sponser_id']);
                        if($checkCompoundId > 0)
                        {
                            userPlansModel::where('id', $checkCompoundId)->update(['compound_amount' => DB::raw('compound_amount + ' . $level2_amount)]);

                            $withdraw = array();
                            $withdraw['user_id'] = $level2['sponser_id'];
                            $withdraw['withdraw_type'] = "COMPOUND";
                            $withdraw['amount'] = $level2_amount;
                            $withdraw['net_amount'] = $level2_amount;
                            $withdraw['admin_charge'] = 0;
                            $withdraw['fees'] = 0;
                            $withdraw['status'] = 1;
                            $withdraw['transaction_hash'] = "BY COMPOUND";
                            $withdraw['coin_price'] = 1;
                            $withdraw['isSynced'] = 0;
                            $withdraw['isRequestSynced'] = 0;
                            $withdraw['remarks'] = "LEVEL 2 COMPOUND WITHDRAW";

                            withdrawModel::insert($withdraw);
                        }

                        DB::statement("UPDATE users set level_income = (IFNULL(level_income,0) + ($level2_amount)) where id = '" . $level2['sponser_id'] . "'");
                        DB::statement("UPDATE user_plans set `return` = (IFNULL(`return`,0) + (" . $level2_amount . ")) where user_id = '" . $level2['sponser_id'] . "' and status = 1");
                        
                    }

                    $level3 = getRefferer($level2['sponser_id']);
                    if (isset($level3['sponser_id']) && $level3['sponser_id'] > 0) {
                        if ($level3['rank_id'] > 1) {
                            $level3_amount = ($roi_amount * $roiLevel['3']['percentage_'. $level3['rank_id']]) / 100;

                            $level3_roi = array();
                            $level3_roi['user_id'] = $level3['sponser_id'];
                            $level3_roi['amount'] = $level3_amount;
                            $level3_roi['tag'] = "LEVEL3-ROI";
                            $level3_roi['refrence'] = $roiUser['0']['refferal_code'];
                            $level3_roi['refrence_id'] = $investment_id;
                            $level3_roi['created_on'] = date('Y-m-d H:i:s');

                            levelEarningLogsModel::insert($level3_roi);
                            
                            $checkCompoundId = isUserCompound($level3['sponser_id']);
                            if($checkCompoundId > 0)
                            {
                                userPlansModel::where('id', $checkCompoundId)->update(['compound_amount' => DB::raw('compound_amount + ' . $level3_amount)]);

                                $withdraw = array();
                                $withdraw['user_id'] = $level3['sponser_id'];
                                $withdraw['withdraw_type'] = "COMPOUND";
                                $withdraw['amount'] = $level3_amount;
                                $withdraw['net_amount'] = $level3_amount;
                                $withdraw['admin_charge'] = 0;
                                $withdraw['fees'] = 0;
                                $withdraw['status'] = 1;
                                $withdraw['transaction_hash'] = "BY COMPOUND";
                                $withdraw['coin_price'] = 1;
                                $withdraw['isSynced'] = 0;
                                $withdraw['isRequestSynced'] = 0;
                                $withdraw['remarks'] = "LEVEL 3 COMPOUND WITHDRAW";

                                withdrawModel::insert($withdraw);
                            }
                            
                            DB::statement("UPDATE users set level_income = (IFNULL(level_income,0) + ($level3_amount)) where id = '" . $level3['sponser_id'] . "'");
                            DB::statement("UPDATE user_plans set `return` = (IFNULL(`return`,0) + (" . $level3_amount . ")) where user_id = '" . $level3['sponser_id'] . "' and status = 1");
                        }
                    }
                }
            }
        }
        
        userPlansModel::whereRaw("1 = 1")->update(['compound' => 0]);

        usersModel::whereRaw("1 = 1")->update(['ad_viewed' => 0]);
    }

    public function calculateAndAssignRanks()
    {
        $users = usersModel::selectRaw("*, (SELECT SUM(amount) FROM user_plans WHERE user_id = users.id and roi > 0) as investment")->where(['users.status' => 1])->get(); // Retrieve all users

        foreach ($users as $user) {
            $this->assignRank($user);
        }
    }

    // Function to assign rank to a user
    public function assignRank(usersModel $user)
    {
        $sales = $user->my_business; // The total sales of the user
        $direct_sales = $user->direct_business;
        $investment = $user->investment; // The total investment of the user
        $sponser_id = $user->id; // The user's sponsor ID

        // Find all users who belong to the same sponsor leg
        $legs = usersModel::where('sponser_id', $sponser_id)->get();

        // Applying rank rules based on the sales and legs
        if ($sales >= 30000 && $direct_sales >= 5000 && $investment >= 5000 && $this->hasMultipleLegs($legs, 4, 2)) {
            $user->rank_id = 5; // Rank 5: 2 person Rank 4 achiever in 2 different legs
            $user->rank = "AdDoodle Galaxy";
            // Add Reward: $10K Cash
        } elseif ($sales >= 9000 && $direct_sales >= 3000 && $investment >= 1000 && $this->hasMultipleLegs($legs, 3, 2)) {
            $user->rank_id = 4; // Rank 4: Rank 3 achiever in 2 different legs
            $user->rank = "AdDoodle Blaze";
            // Add Reward: MacBook
        } elseif ($sales >= 900 && $direct_sales >= 300 && $investment >= 100 && $this->hasMultipleLegs($legs, 2, 2)) {
            $user->rank_id = 3; // Rank 3: Rank 2 achiever in 3 different legs
            $user->rank = "AdDoodle Prism";
            // Add Reward: iPhone 15
        } elseif ($sales >= 300 && $direct_sales >= 100 && $investment >= 50 && $this->hasMultipleLegs($legs, 1, 2)) {
            $user->rank_id = 2; // Rank 2: Rank 1 achiever in 3 different legs
            $user->rank = "AdDoodle Glow";
            // Add Reward: Rank 1 achiever in 3 different legs
        } elseif ($investment >= 20) {
            $user->rank_id = 1; // Rank 1: $500
            $user->rank = "AdDoodle Spark";
        }

        $user->save(); // Save the rank to the database
    }

    // Helper function to check if the user has multiple legs achieving a rank
    public function hasMultipleLegs($legs, $requiredRank, $countDifferentLegs)
    {
        $legCount = 0;
        $legFoundArray = array();

        // Loop through each leg to check if it meets the required rank
        foreach ($legs as $leg) {
            if ($leg->rank_id >= $requiredRank) {
                $legCount++;
                array_push($legFoundArray, $leg->id); // Store the leg's id that qualifies
            }
        }

        // If there aren't enough legs with the required rank, check within teams under each leg
        if ($legCount < $countDifferentLegs) {
            // Loop through the remaining legs to find the teams
            foreach ($legs as $leg) {
                // If leg is already counted, skip it
                if (in_array($leg->id, $legFoundArray)) {
                    continue;
                }

                // Find the users in the leg's team (downline) using their sponsor_id
                $legTeam = myTeamModel::join('users', 'users.id', '=', 'my_team.team_id')
                                      ->where(['my_team.user_id' => $leg->id])
                                      ->get();

                foreach ($legTeam as $legMember) {
                    // Check if the team member qualifies based on the rank
                    if ($legMember->rank_id >= $requiredRank) {
                        $legCount++;
                        array_push($legFoundArray, $leg->id); // Add the leg id if the team member qualifies
                        break; // Stop as soon as we find a qualifying team member
                    }
                }
            }
        }

        // Return whether the number of qualifying legs meets the required count
        return $legCount >= $countDifferentLegs;
    }

    // function to calculate business weekly
    public function calculateWeeklyBusiness(Request $request)
    {
        $type = $request->input('type');

        $rewardIncome = array();
        $rewardIncome['2'] = 0.50;
        $rewardIncome['3'] = 1;
        $rewardIncome['4'] = 1.50;
        $rewardIncome['5'] = 2;

        // Reset weekly_business to 0
        usersModel::whereRaw("1 = 1")->update(['weekly_business' => 0]);

        // Calculate date range: last 6 days + today
        $startDate = now()->subDays(6)->startOfDay()->format('Y-m-d');
        $endDate = now()->endOfDay()->format('Y-m-d');

        // Fetch investments within the date range
        $investment = userPlansModel::whereRaw("roi > 0 and date_format(created_on, '%Y-%m-%d') between '$startDate' and '$endDate'")
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();

        // Update business for each investment
        foreach ($investment as $key => $value) {
            updateReverseWeeklyBusiness($value['user_id'], $value['amount']);
        }

        // Fetch users with updated weekly_business
        $users = usersModel::whereRaw("weekly_business > 0")->get()->toArray();

        foreach($users as $key => $value)
        {
            $rewardAmount = 0;
            $getDirects = usersModel::where(['sponser_id' => $value['id']])->get()->toArray();

            foreach($getDirects as $gk => $gv)
            {
                if($gv['weekly_business'] > 0)
                {
                    if($gv['rank_id'] < $value['rank_id'])
                    {
                        $diff = $rewardIncome[$value['rank_id']] - $rewardIncome[$gv['rank_id']];

                        $rewardAmount += ($gv['weekly_business'] * $diff) / 100;
                    }
                }
            }
        }
    }
}
