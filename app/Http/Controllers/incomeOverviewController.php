<?php

namespace App\Http\Controllers;

use App\Models\earningLogsModel;
use App\Models\levelEarningLogsModel;
use App\Models\usersModel;
use Illuminate\Http\Request;

use function App\Helpers\is_mobile;

class incomeOverviewController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input("type");
        $og_start_date = $start_date = $request->input("start_date");
        $og_end_date = $end_date = $request->input("end_date");
        $user_id = $request->session()->get("user_id");

        $user = usersModel::where("id", $user_id)->get()->toArray();

        // Filter earningLogs if start_date and end_date are provided
        $earningLogsQuery = earningLogsModel::where("user_id", $user_id);
        if (!empty($start_date) && !empty($end_date)) {
            $start_date = date("Y-m-d", strtotime($request->input("start_date")));
            $end_date = date("Y-m-d", strtotime($request->input("end_date")));
            $earningLogsQuery->whereRaw("DATE_FORMAT(created_on, '%Y-%m-%d') BETWEEN ? AND ?", [$start_date, $end_date]);
        }
        $earningLogs = $earningLogsQuery->orderBy('id', 'desc')->get()->toArray();

        // Filter levelEarningLogs if start_date and end_date are provided
        $levelEarningLogsQuery = levelEarningLogsModel::where("user_id", $user_id);
        if (!empty($start_date) && !empty($end_date)) {
            $start_date = date("Y-m-d", strtotime($request->input("start_date")));
            $end_date = date("Y-m-d", strtotime($request->input("end_date")));
            $levelEarningLogsQuery->whereRaw("DATE_FORMAT(created_on, '%Y-%m-%d') BETWEEN ? AND ?", [$start_date, $end_date]);
        }
        $levelEarningLogs = $levelEarningLogsQuery->orderBy('id', 'desc')->get()->toArray();

        $directReferral = 0;
        $roiIncome = 0;
        $levelIncome = 0;
        $rewardIncome = 0;
        $royaltyIncome = 0;

        foreach($earningLogs as $key => $value)
        {
            if($value['tag'] == "REFERRAL")
            {
                $directReferral += $value['amount'];
            }

            if($value['tag'] == "ROI")
            {
                $roiIncome += $value['amount'];
            }

            if($value['tag'] == "RANK-BONUS")
            {
                $rewardIncome += $value['amount'];
            }

            if($value['tag'] == "ROYALTY")
            {
                $royaltyIncome += $value['amount'];
            }
        }

        foreach($levelEarningLogs as $key => $value)
        {
            $levelIncome += $value['amount'];
        }

        $res['status_code'] = 1;
        $res['message'] = "Success";
        $res['user'] = $user['0'];
        $res['earningLogs'] = $earningLogs;
        $res['levelEarningLogs'] = $levelEarningLogs;
        $res['start_date'] = $og_start_date;
        $res['end_date'] = $og_end_date;
        $res['roiIncome'] = $roiIncome;
        $res['levelIncome'] = $levelIncome;
        $res['directReferral'] = $directReferral;
        $res['rewardIncome'] = $rewardIncome;
        $res['royaltyIncome'] = $royaltyIncome;

        return is_mobile($type, "pages.income_overview", $res, "view");
    }
}
