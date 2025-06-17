<?php

namespace App\Http\Controllers;

use App\Models\adsModel;
use App\Models\earningLogsModel;
use App\Models\levelRoiModel;
use App\Models\rankingModel;
use App\Models\userPlansModel;
use App\Models\usersModel;
use App\Models\withdrawModel;
use App\Models\loginLogsModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function App\Helpers\is_mobile;
use function App\Helpers\getBalance;
use function App\Helpers\verifyRSVP;

class loginController extends Controller
{
    public function userValidate(Request $request)
    {
        $type = $request->input('type');
        $wallet_address = $request->input('wallet_address');

        $users = usersModel::where(['wallet_address' => $wallet_address])->get()->toArray();

        if (count($users) == 0) {
            $res['status_code'] = 1;
            $res['message'] = "Wallet Address is eligeble for user.";
        } else {
            $res['status_code'] = 0;
            $res['message'] = "User already exist make login.";
        }

        return is_mobile($type, "pages.index", $res, "view");
    }

    public function login(Request $request)
    {
        $type = $request->input('type');
        $wallet_address = $request->input('wallet_address');

        $data = usersModel::where(['wallet_address' => $wallet_address])->get()->toArray();

        $loginLogs = array();
        if (count($data) == 1) {
            $loginLogs['user_id'] = $data['0']['id'];
        } else {
            $loginLogs['user_id'] = "FAILED";
        }
        $loginLogs['login_type'] = "USER";
        $loginLogs['email'] = $wallet_address;
        $loginLogs['password'] = $wallet_address;
        $loginLogs['ip_address'] = $request->ip();
        $loginLogs['ip_address_2'] = $request->header('x-forwarded-for');
        $loginLogs['device'] = $request->header('User-Agent');
        $loginLogs['created_on'] = date('Y-m-d H:i:s');

        loginLogsModel::insert($loginLogs);

        if(!$request->session()->has('admin_user_id'))
        {
            $walletAddressScript = $request->input('walletAddressScript');
            $hashedMessageScript = $request->input('hashedMessageScript');
            $rsvScript = $request->input('rsvScript');
            $rsScript = $request->input('rsScript');
            $rScript = $request->input('rScript');


            $verifySignData = json_encode(array(
                "wallet" => $wallet_address,
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

                    return is_mobile($type, "flogin", $res);
                }
            } else {
                $res['status_code'] = 0;
                $res['message'] = "Invalid Signature. Please try again later.";

                return is_mobile($type, "flogin", $res);
            }
        }

        if (count($data) == 1) {
            if ($data['0']['status'] == 1) {
                $request->session()->put('user_id', $data['0']['id']);
                $request->session()->put('email', $data['0']['email']);
                $request->session()->put('name', $data['0']['name']);
                $request->session()->put('refferal_code', $data['0']['refferal_code']);
                $request->session()->put('wallet_address', $data['0']['wallet_address']);
                $request->session()->put('rank', $data['0']['rank']);

                $res['status_code'] = 1;
                $res['message'] = "Login Successfully.";

                return is_mobile($type, "fdashboard", $res);
            } else {
                $res['status_code'] = 0;
                $res['message'] = "Your account is suspended by admin.";

                return is_mobile($type, "flogin", $res);
            }
        } else {
            $res['status_code'] = 0;
            $res['message'] = "User Id and Password Does Not Match.";

            return is_mobile($type, "flogin", $res);
        }
    }

    public function logout(Request $request)
    {
        $type = $request->input('type');

        $request->session()->flush();

        $res['status_code'] = 1;
        $res['message'] = "Logout Successfully.";

        return is_mobile($type, "flogin", $res);
    }

    public function dashboard(Request $request)
    {
        $type = $request->input('type');
        $user_id = $request->session()->get('user_id');

        $user = usersModel::where(['id' => $user_id])->get()->toArray();

        if (count($user) == 0) {
            $res['status_code'] = 0;
            $res['message'] = "User not found.";

            return is_mobile($type, "flogin", $res);
        }

        $ranks = rankingModel::orderBy('id', 'desc')->get()->toArray();

        $levels = levelRoiModel::get()->toArray();

        $directs = usersModel::selectRaw("count(id) as directs, DATE_FORMAT(created_on, '%Y-%m-%d') as dates")
            ->where('created_on', '>=', Carbon::now()->subDays(7))
            ->groupBy(DB::raw('DATE_FORMAT(created_on, "%Y-%m-%d")'))
            ->get()
            ->keyBy('dates') // Key by the date for easy lookup
            ->toArray();

        $chartDirect = [];
        $last7Days = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $last7Days[] = $date; // Array of last 7 days (dates)

            // If the date exists in the query result, use its directs, otherwise set to 0
            $chartDirect[$date] = isset($directs[$date]) ? $directs[$date]['directs'] : 0;
        }

        $directChart = array();

        foreach ($chartDirect as $key => $value) {
            array_push($directChart, $value);
        }

        $packages = userPlansModel::where(['user_id' => $user_id])->orderBy('id', 'desc')->get()->toArray();

        $selfInvestment = 0;

        foreach ($packages as $key => $value) {
            $selfInvestment += ($value['amount'] + $value['compound_amount']);
        }

        $withdraw = withdrawModel::selectRaw("IFNULL(SUM(amount),0) as total_withdraw")->where(['user_id' => $user_id, 'status' => 1])->whereRaw('transaction_hash != "RP2"')->get()->toArray();

        $checkRegistarationBonus = earningLogsModel::where(['user_id' => $user_id, 'tag' => 'REGISTRATION-BONUS'])->get()->toArray();
        $checkRegistarationBonus = count($checkRegistarationBonus) > 0 ? count($checkRegistarationBonus) : 0;

        if ($checkRegistarationBonus == 0) {
            $registrationDate = $user['0']['created_on'];
            $activationDate = date('Y-m-d H:i:s');
            $diff = strtotime($activationDate) - strtotime($registrationDate);
            $hours = floor($diff / (60 * 60));
            if ($hours < 72) {
                $res['registration_bonus'] = 0;
            } else {
                $res['registration_bonus'] = 2;
            }
        } else {
            $res['registration_bonus'] = 1;
        }

        // Step 1: Prepare date range (last 7 days, formatted)
        // $startDate = Carbon::now()->subDays(7)->format('Y-m-d'); // 7 days ago (string)
        // $endDate = Carbon::now()->subDays(1)->format('Y-m-d');   // yesterday (string)

        // Step 1: Default range (last 7 days to yesterday)
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now()->subDays(1);

        // Get user's registration date
        $registrationDate = Carbon::parse($user['0']['created_on']); // assuming format 'Y-m-d H:i:s'

        // Step 2: If registration is after the default startDate, update it
        if ($registrationDate->greaterThan($startDate)) {
            $startDate = $registrationDate;
        }

        // Step 3: Format both dates as strings (Y-m-d)
        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        // Step 2: Get earnings grouped by date
        $earnings = DB::table('earning_logs')
            ->selectRaw("DATE_FORMAT(created_on, '%Y-%m-%d') as log_date, SUM(amount) as roi_amount")
            ->where('tag', 'ROI')
            ->where('user_id', $user_id)
            ->whereBetween(DB::raw("DATE_FORMAT(created_on, '%Y-%m-%d')"), [$startDate, $endDate])
            ->groupBy(DB::raw("DATE_FORMAT(created_on, '%Y-%m-%d')"))
            ->pluck('roi_amount', 'log_date');

        // Step 3: Fill missing dates
        $results = collect();
        $currentDate = Carbon::createFromFormat('Y-m-d', $startDate);
        $lastDate = Carbon::createFromFormat('Y-m-d', $endDate);

        for (; $currentDate->lte($lastDate); $currentDate->addDay()) {
            $results->push([
                'date' => $currentDate->toDateString(),
                'roi_amount' => $earnings->get($currentDate->toDateString(), 0),
            ]);
        }

        $results = $results->reverse()->values();

        $adCampaign = adsModel::where(['status' => 1])->orderBy('id', 'desc')->get()->toArray();

        $user['0']['rank_id'] == 0 ? $user['0']['rank'] = "No Rank" : $user['0']['rank'];
        $res['status_code'] = 1;
        $res['message'] = "Dashboard Page.";
        $res['user'] = $user['0'];
        $res['ranks'] = $ranks;
        $res['levels'] = $levels;
        $res['chartDirect'] = $directChart;
        $res['my_packages'] = $packages;
        $res['total_withdraw'] = $withdraw['0']['total_withdraw'];
        $res['self_investment'] = $selfInvestment;
        $res['available_balance'] = getBalance($user_id);
        $res['ads'] = $results->toArray();
        if (count($adCampaign) > 0) {
            $res['adCampaign'] = $adCampaign['0'];
        }

        return is_mobile($type, "pages.index", $res, "view");
    }

    public function referralCodeDetails(Request $request)
    {
        $type = "API";
        $refferal_code = $request->input('refferal_code');

        if (!empty($refferal_code)) {
            $data = usersModel::select('wallet_address')->where(['refferal_code' => $refferal_code])->get()->toArray();

            if (count($data) > 0) {
                $res['status_code'] = 1;
                $res['message'] = "Successfully.";
                $res['data'] = $data['0']['wallet_address'];
            } else {
                $res['status_code'] = 0;
                $res['message'] = "Invalid user.";
            }
        } else {
            $res['status_code'] = 0;
            $res['message'] = "Parameter missing.";
        }

        return is_mobile($type, "pages.index", $res, "view");
    }
}
