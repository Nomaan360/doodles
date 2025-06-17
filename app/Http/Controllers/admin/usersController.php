<?php

namespace App\Http\Controllers\admin;

use App\Exports\CustomQueryExport;
use App\Models\usersModel;
use App\Models\supportTicketModel;
use App\Models\transferModel;
use App\Models\packageTransaction;
use App\Models\earningLogsModel;
use App\Models\marketExcelModel;
use App\Models\profitSharingModel;
use App\Models\userPlansModel;
use App\Models\withdrawModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use function App\Helpers\findUplineRank;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

use function App\Helpers\is_mobile;

class usersController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type');
        $search = $request->input('search');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $rank = $request->input('rank');
        $isExport = $request->input('export') === 'yes';

        $whereStartDate = '';
        $whereEndDate = '';
        $whereRank = '';

        if($start_date != '')
        {
            $whereStartDate = " AND date_format(users.created_on, '%Y-%m-%d') >= '".date('Y-m-d', strtotime($start_date))."'";
        }

        if($end_date != '')
        {
            $whereEndDate = " AND date_format(users.created_on, '%Y-%m-%d') <= '".date('Y-m-d', strtotime($end_date))."'";
        }

        $whereRawSearch = '';

        if($search != '')
        {
            $whereRawSearch = " AND (refferal_code = '".$search."' or wallet_address like '".$search."' or name like '%".$search."%' or email like '%".$search."%') ";
            $res['search'] = $search;
        }

        if($rank != '')
        {
            $whereRank = " AND rank_id = '".$rank."'";   
        }

        $data = usersModel::selectRaw("users.*, IFNULL(SUM(user_plans.amount), 0) as amount,user_plans.roi, (users.roi_income + users.level_income + users.royalty + users.reward + users.direct_income) as tincome, (select IFNULL(sum(amount), 0) from withdraw where user_id = users.id and status = 1) as twithdraw ")->leftjoin('user_plans', 'user_plans.user_id', '=', 'users.id')->whereRaw(" 1 = 1 ". $whereRawSearch.$whereStartDate.$whereEndDate.$whereRank)->groupBy('users.id')->paginate(20)->toArray();

        $data = array_map(function ($value) {
            return (array) $value;
        }, $data);

        foreach($data['data'] as $key => $value)
        {
            $getUnsettledIncome = earningLogsModel::selectRaw("IFNULL(SUM(amount), 0) as unsettledAmount")->whereRaw("status = 0")->where(['user_id' => $value['id']])->get()->toArray();

            $data['data'][$key]['available_balance'] = ($value['tincome'] - $value['twithdraw']);
            $data['data'][$key]['unsettled_amount'] = $getUnsettledIncome['0']['unsettledAmount'];
        }
        if ($isExport) {
            $users = usersModel::selectRaw("users.*, IFNULL(SUM(user_plans.amount), 0) as amount,user_plans.roi, (users.roi_income + users.level_income + users.royalty + users.reward + users.direct_income) as tincome, (select IFNULL(sum(amount), 0) from withdraw where user_id = users.id and status = 1) as twithdraw ")->leftjoin('user_plans', 'user_plans.user_id', '=', 'users.id')->whereRaw(" 1 = 1 ". $whereRawSearch.$whereStartDate.$whereEndDate.$whereRank)->groupBy('users.id')->get()->toArray();

            $users = array_map(function ($value) {
                return (array) $value;
            }, $users);

            foreach($users as $key => $value)
            {
                $getUnsettledIncome = earningLogsModel::selectRaw("IFNULL(SUM(amount), 0) as unsettledAmount")->whereRaw("status = 0")->where(['user_id' => $value['id']])->get()->toArray();

                $users[$key]['available_balance'] = ($value['tincome'] - $value['twithdraw']);
                $users[$key]['unsettled_amount'] = $getUnsettledIncome['0']['unsettledAmount'];
            }

            $list = [
                ['Name','Email','Wallet Address', 'User Id','Rank','Package','Topup Balance','Total Income','Total Withdraw','Available Balance']
            ];
            $filePath='/var/www/html/exports/users.csv';
            $fp = fopen($filePath, 'w');
            foreach ($list as $fields) {
                fputcsv($fp, $fields);
            }
            foreach ($users as $value) {
                $dataRows = [
                    $value['name'],
                    $value['email'],
                    $value['wallet_address'],
                    $value['refferal_code'],
                    $value['rank'],
                    $value['roi'] > 0 ? $value['amount'] : 0,
                    number_format($value['topup_balance'],2),
                    number_format($value['tincome'],2),
                    number_format($value['twithdraw'],2),
                    number_format($value['available_balance'],2),
                ];
                fputcsv($fp, $dataRows);
            }
            
            fclose($fp);
            return response()->download($filePath)->deleteFileAfterSend(true);
        }

        $res['status_code'] = 1;
        $res['message'] = "Success";
        $res['data'] = $data;
        $res['start_date'] = $start_date;
        $res['end_date'] = $end_date;
        $res['rank'] = $rank;

        return is_mobile($type, "users", $res, 'view');
    }

    public function userExport(Request $request)
    {
        $type = $request->input('type');
        $search = $request->input('search');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $whereStartDate = '';
        $whereEndDate = '';

        if($start_date != '')
        {
            $whereStartDate = " AND date_format(users.created_on, '%Y-%m-%d') >= '".date('Y-m-d', strtotime($start_date))."'";
        }

        if($end_date != '')
        {
            $whereEndDate = " AND date_format(users.created_on, '%Y-%m-%d') <= '".date('Y-m-d', strtotime($end_date))."'";
        }

        $whereRawSearch = '';

        if($search != '')
        {
            $whereRawSearch = " AND (refferal_code = '".$search."' or wallet_address like '".$search."' or mt5 like '".$search."' or mt5_name like '%".$search."%' or name like '%".$search."%' or email like '%".$search."%') ";
            $res['search'] = $search;
        }

        $data = usersModel::selectRaw("users.*, IFNULL(user_plans.amount, 0) as amount, (users.referral_bonus + users.profit_sharing + users.profit_sharing_level + users.rank_bonus + users.brokerage + users.royalty_pool) as tincome, (select IFNULL(sum(amount), 0) from withdraw where user_id = users.id and status = 1) as twithdraw ")->leftjoin('user_plans', 'user_plans.user_id', '=', 'users.id')->whereRaw(" 1 = 1 ". $whereRawSearch.$whereStartDate.$whereEndDate)->groupBy('users.id')->get()->toArray();

        foreach($data as $key => $value)
        {
            $data[$key]['available_balance'] = ($value['tincome'] - $value['twithdraw']);
        }

        $headings = [
            'Name',
            'Email',
            'Wallet Address',
            'User Id',
            'Package',
            'Total Income',
            'Total Withdraw',
            'Available Balance',
            'MT5 Id',
            'MT5 Status',
        ];

        $finalData = array();

        foreach($data as $key => $value)
        {
            $finalData[$key]['name'] = $value['name'];
            $finalData[$key]['email'] = $value['email'];
            $finalData[$key]['wallet_address'] = $value['wallet_address'];
            $finalData[$key]['refferal_code'] = $value['refferal_code'];
            $finalData[$key]['amount'] = $value['amount'];
            $finalData[$key]['tincome'] = number_format($value['tincome'],2);
            $finalData[$key]['twithdraw'] = number_format($value['twithdraw'],2);
            $finalData[$key]['available_balance'] = number_format($value['available_balance'],2);
            $finalData[$key]['mt5'] = $value['mt5'];
            if($value['mt5_verify'] == 0)
            {
                $finalData[$key]['mt5_status'] = "Not Submitted";
            }else if($value['mt5_verify'] == 1)
            {
                $finalData[$key]['mt5_status'] = "Verified";
            }else
            {
                $finalData[$key]['mt5_status'] = "Rejected";
            }
        }

        return Excel::download(new CustomQueryExport($finalData, $headings), 'userExport.xlsx');
    }

    public function mt5VerifyUsers(Request $request)
    {
        $type = $request->input('type');
        $search = $request->input('search');

        $whereRawSearch = '';

        if($search != '')
        {
            $whereRawSearch = " AND (refferal_code = '".$search."' or wallet_address like '".$search."' or mt5 like '".$search."' or mt5_name like '%".$search."%' or name like '%".$search."%' or email like '%".$search."%') ";
            $res['search'] = $search;
        }

        $data = usersModel::selectRaw("users.*, IFNULL(user_plans.amount, 0) as amount, (users.referral_bonus + users.profit_sharing + users.profit_sharing_level + users.rank_bonus + users.brokerage + users.royalty_pool) as tincome, (select IFNULL(sum(amount), 0) from withdraw where user_id = users.id and status = 1) as twithdraw ")->leftjoin('user_plans', 'user_plans.user_id', '=', 'users.id')->whereRaw(" 1 = 1 AND mt5_verify = 0 and mt5 is not null ". $whereRawSearch)->groupBy('users.id')->orderBy('users.id','desc')->paginate(20)->toArray();

        $data = array_map(function ($value) {
            return (array) $value;
        }, $data);

        foreach($data['data'] as $key => $value)
        {
            $data['data'][$key]['available_balance'] = ($value['tincome'] - $value['twithdraw']);
        }

        $res['status_code'] = 1;
        $res['message'] = "Success";
        $res['data'] = $data;

        return is_mobile($type, "users", $res, 'view');
    }

    public function updateTopupBalance(Request $request)
    {
        $type = $request->input('type');
        $user_id = $request->input('user_id');        
        $topup_amount = $request->input('topup_amount');


        if($user_id > 0)
        {
            usersModel::where(['id' => $user_id])->update(['topup_balance' => DB::raw('topup_balance + ' . $topup_amount)]);

            DB::table('topup_logs')->insert([
                'user_id' => $user_id,
                'amount' => $topup_amount,
                'ip_address' => $request->ip() . " - " . $request->header('x-forwarded-for') . " - " . $request->header('User-Agent'),
                'created_on' => date('Y-m-d H:i:s'),
            ]);

            $res['status_code'] = 1;
            $res['message'] = "Topup Balance Worth $".$topup_amount." Added Successfully";
        }else
        {
            $res['status_code'] = 1;
            $res['message'] = "Something Went Wrong.";
        }


        return redirect()->back()->with('data', $res);
    }

    public function updateUserDetails(Request $request)
    {
        $type = $request->input('type');
        $user_id = $request->input('user_id');        
        $name = $request->input('name');
        $email = $request->input('email');        
        $wallet_address = $request->input('wallet_address');     
        $mt5 = $request->input('mt5');   
        $mt5_verify = $request->input('mt5_verify');
        $mt5_name = $request->input('mt5_name');
        $status = $request->input('status');
        $canWithdraw = $request->input('canWithdraw');
        $password = $request->input('password');

        $updateUser = array();
        if($email != '')
        {
            $updateUser['email'] = $email;
        }

        if($mt5_name != '')
        {
            $updateUser['mt5_name'] = $mt5_name;
        }

        if($name != '')
        {
            $updateUser['name'] = $name;
        }

        if($mt5_verify != '')
        {
            $updateUser['mt5_verify'] = $mt5_verify;
        }

        if($mt5 != '')
        {
            $updateUser['mt5'] = $mt5;
            $updateUser['mt5_verify'] = 1;
        }

        if($wallet_address != '')
        {
            $updateUser['wallet_address'] = $wallet_address;
        }

        if($status != '')
        {
            $updateUser['status'] = $status;
        }

        if($canWithdraw != '')
        {
            $updateUser['canWithdraw'] = $canWithdraw;
        }

        if($password != '')
        {
            $updateUser['password'] = md5($password);
        }

        if(count($updateUser) > 0)
        {
            usersModel::where(['id' => $user_id])->update($updateUser);
        }

        $res['status_code'] = 1;
        $res['message'] = "Member updated successfully";

        // return is_mobile($type, "searchMember", $res);
        return redirect()->back()->with('data', $res);

    }

    public function awardIncomeProcess(Request $request)
    {
        $type = $request->input('type');
        $user_id = $request->input('user_id');
        $award_income = $request->input('award_income');

        $updateUser = array();
        $updateUser['award_income'] = $award_income;

        usersModel::where(['id' => $user_id])->update($updateUser);

        $roi = array();
        $roi['user_id'] = $user_id;
        $roi['amount'] = $award_income;
        $roi['tag'] = "award_income";
        $roi['status'] = 1;
        $roi['refrence'] = "1";
        $roi['refrence_id'] = $award_income;
        $roi['flush_amount'] = 0;
        $roi['created_on'] = date('Y-m-d H:i:s');
        earningLogsModel::insert($roi);

        $res['status_code'] = 1;
        $res['message'] = "Award Income Updated Successfully";

        return redirect()->back()->with('data', $res);

    }

    public function replySupportTickets(Request $request)
    {
        $type = $request->input('type');
        $ticket_id = $request->input('ticket_id');
        $reply = $request->input('reply');

        if($ticket_id != '')
        {
            supportTicketModel::where(['id' => $ticket_id])->update(['reply' => $reply, 'status' => 2, 'reply_on' => date('Y-m-d H:i:s')]);
        }

        $res['status_code'] = 1;
        $res['message'] = "Support ticket updated successfully";

        return is_mobile($type, "memberSupportTickets", $res);
    }

    public function memberSupportTickets(Request $request)
    {
        $type = $request->input('type');

        $data = supportTicketModel::where(['status' => 0])->get()->toArray();
        $closed = supportTicketModel::where(['status' => 2])->get()->toArray();

        $res['status_code'] = 1;
        $res['message'] = "Member updated successfully";
        $res['data'] = $data;
        $res['closed'] = $closed;

        return is_mobile($type, "support_tickets", $res, 'view');
    }

    public function membersReport(Request $request)
    {
        $type = $request->input('type');
        $report_type = $request->input('report_type');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $refferal_code = $request->input('refferal_code');

        if($report_type == 1)
        {
            $where = " tag = 'ROI'";
        }
        if($report_type == 2)
        {
            $where = " tag = 'REFERRAL'";
        }
        if($report_type == 3)
        {
            $where = " tag IN ('LEVEL1-ROI', 'LEVEL2-ROI', 'LEVEL3-ROI', 'LEVEL4-ROI','LEVEL5-ROI','LEVEL6-ROI','LEVEL7-ROI','LEVEL8-ROI','LEVEL9-ROI','LEVEL10-ROI')";
        }
        if($report_type == 4)
        {
            $where = " tag = 'DIRECT-MATCHING'";
        }
        if($report_type == 5)
        {
            $where = " tag IN ('LEVEL-1-MATCHING','LEVEL-2-MATCHING','LEVEL-3-MATCHING','LEVEL-4-MATCHING','LEVEL-5-MATCHING','LEVEL-6-MATCHING','LEVEL-7-MATCHING','LEVEL-8-MATCHING','LEVEL-9-MATCHING','LEVEL-10-MATCHING')";
        }

        $whereStartDate = '';
        $whereEndDate = '';
        $whereRC = '';

        if($start_date != '')
        {
            $whereStartDate = " AND date_format(earning_logs.created_on, '%Y-%m-%d') >= '".date('Y-m-d', strtotime($start_date))."'";
        }

        if($end_date != '')
        {
            $whereEndDate = " AND date_format(earning_logs.created_on, '%Y-%m-%d') <= '".date('Y-m-d', strtotime($end_date))."'";
        }

        if($refferal_code != '')
        {
            $whereRC = "  AND users.refferal_code = '".$refferal_code."' ";
        }

        $data = DB::select("SELECT users.name, users.refferal_code, users.sponser_code, earning_logs.amount, earning_logs.flush_amount, earning_logs.created_on as dateofearning, users.created_on FROM earning_logs  INNER JOIN users on earning_logs.user_id = users.id WHERE ".$where.$whereStartDate.$whereEndDate.$whereRC);

        $data = array_map(function ($value) {
            return (array) $value;
        }, $data);

        $list = array(
            ['Member Name', 'Member Code', 'Sponsor Code', 'Amount', 'Flush Amount', 'Date', 'Joining Date']
        );

        // Open a file in write mode ('w')
        $fp = fopen('/var/www/ai/exports/export.csv', 'w');
          
        // Loop through file pointer and a line
        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }

        foreach($data as $key => $value)
        {
            fputcsv($fp, $value);   
            // fputcsv($fp, $value['refferal_code']);   
            // fputcsv($fp, $value['sponser_code']);   
            // fputcsv($fp, $value['amount']);   
            // fputcsv($fp, $value['flush_amount']);   
            // fputcsv($fp, date('d-m-Y', strtotime($value['dateofearning'])));   
            // fputcsv($fp, date('d-m-Y', strtotime($value['created_on'])));   
        }
          
        fclose($fp);

        $data = earningLogsModel::selectRaw("users.*,earning_logs.amount,earning_logs.flush_amount,earning_logs.created_on as dateofearning")->join('users','users.id','=','earning_logs.user_id')->whereRaw($where.$whereStartDate.$whereEndDate.$whereRC)->paginate(20)->toArray();

        // dd($data);

        $res['status_code'] = 1;
        $res['message'] = "Report generated successfully";
        $res['data'] = $data;
        $res['report_type'] = $report_type;
        $res['start_date'] = $start_date;
        $res['end_date'] = $end_date;
        $res['refferal_code'] = $refferal_code;

        return is_mobile($type, "report", $res, 'view');
    }

    public function investmentReport(Request $request)
    {
        $type = $request->input('type');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $refferal_code = $request->input('refferal_code');
        $isExport = $request->input('export') === 'yes';

        $whereStartDate = '';
        $whereEndDate = '';
        $whereRC = '';

        if($start_date != '')
        {
            $whereStartDate = " AND date_format(user_plans.created_on, '%Y-%m-%d') >= '".date('Y-m-d', strtotime($start_date))."'";
        }

        if($end_date != '')
        {
            $whereEndDate = " AND date_format(user_plans.created_on, '%Y-%m-%d') <= '".date('Y-m-d', strtotime($end_date))."'";
        }

        if($refferal_code != '')
        {
            $whereRC = "  AND users.refferal_code = '".$refferal_code."' ";
        }

        $data = userPlansModel::selectRaw("users.*,user_plans.amount,user_plans.created_on as dateofearning, user_plans.transaction_hash, user_plans.isSynced, user_plans.created_on, user_plans.compound, user_plans.compound_amount")->join('users','users.id','=','user_plans.user_id')->whereRaw("1 = 1  ".$whereStartDate.$whereEndDate.$whereRC)->paginate(20)->toArray();

        if ($isExport) {

            $investment = userPlansModel::selectRaw("users.*,user_plans.amount,user_plans.created_on as dateofearning, user_plans.transaction_hash, user_plans.isSynced, user_plans.created_on, user_plans.compound, user_plans.compound_amount")->join('users','users.id','=','user_plans.user_id')->whereRaw("1 = 1  ".$whereStartDate.$whereEndDate.$whereRC)->get()->toArray();

            $investment = array_map(function ($value) {
                return (array) $value;
            }, $investment);


            $list = [
                ['Member Name','Member Code', 'Sponsor Code', 'Transaction Hash','Amount','Compound','Date','Joining Date']
            ];
            $filePath='/var/www/html/exports/investment.csv';
            $fp = fopen($filePath, 'w');
            foreach ($list as $fields) {
                fputcsv($fp, $fields);
            }
            foreach ($investment as $value) {
                $txn_hash=$value['transaction_hash'];
                if ($value['transaction_hash']=='By Topup') {
                    $package_date= date('d-m-Y', strtotime($value['dateofearning']));
                    $package_txn= packageTransaction::where('user_id',$value['id'])->where('amount',$value['amount'])->whereDate('created_on',$package_date)->first();
                    $txn_hash=$package_txn->transaction_hash;
                }
                $dataRows = [
                    $value['name'],
                    $value['refferal_code'],
                    $value['sponser_code'],
                    $txn_hash,
                    $value['amount'],
                    $value['compound'] == 1 ? "Active - ". $value['compound_amount'] : "-",
                    date('d-m-Y', strtotime($value['dateofearning'])),
                    date('d-m-Y', strtotime($value['created_on'])),
                ];
                fputcsv($fp, $dataRows);
            }
            
            fclose($fp);
            return response()->download($filePath)->deleteFileAfterSend(true);
        }
        foreach ($data as $key => $value){
            $txn_hash=$value['transaction_hash'];
            if ($value['transaction_hash']=='By Topup') {
                $package_date= date('d-m-Y', strtotime($value['dateofearning']));
                $package_txn= packageTransaction::where('user_id',$value['id'])->where('amount',$value['amount'])->whereDate('created_on',$package_date)->first();
                $txn_hash=$package_txn->transaction_hash;
            }
            $data['data'][$key]['transaction_hash'] = $txn_hash;
        }

        $res['status_code'] = 1;
        $res['message'] = "Report generated successfully";
        $res['data'] = $data;
        $res['start_date'] = $start_date;
        $res['end_date'] = $end_date;
        $res['refferal_code'] = $refferal_code;

        return is_mobile($type, "investment_report", $res, 'view');
    }

    public function withdrawReport(Request $request)
    {
        $type = $request->input('type');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $refferal_code = $request->input('refferal_code');
        $isExport = $request->input('export') === 'yes';

        $whereStartDate = '';
        $whereEndDate = '';
        $whereRC = '';

        if($start_date != '')
        {
            $whereStartDate = " AND date_format(withdraw.created_on, '%Y-%m-%d') >= '".date('Y-m-d', strtotime($start_date))."'";
        }

        if($end_date != '')
        {
            $whereEndDate = " AND date_format(withdraw.created_on, '%Y-%m-%d') <= '".date('Y-m-d', strtotime($end_date))."'";
        }

        if($refferal_code != '')
        {
            $whereRC = "  AND users.refferal_code = '".$refferal_code."' ";
        }

    //     $data = DB::select("SELECT users.name, users.refferal_code, users.sponser_code, withdraw.amount, withdraw.created_on as dateofearning, users.created_on,withdraw.withdraw_type, withdraw.transaction_hash, CASE
    //     WHEN withdraw.status = 2 THEN 'Failed'
    //     WHEN withdraw.status = 1 THEN 'Success'
    //     ELSE 'Pending'
    // END AS status_text FROM withdraw INNER JOIN users on withdraw.user_id = users.id WHERE 1 = 1 ".$whereStartDate.$whereEndDate.$whereRC);

    //     $data = array_map(function ($value) {
    //         return (array) $value;
    //     }, $data);

    //     $list = array(
    //         ['Member Name', 'Member Code', 'MT5', 'Sponsor Code', 'Amount', 'Date', 'Joining Date', 'Type', 'Wallet', 'Transaction Hash', 'Status']
    //     );

    //     $fp = fopen('/var/www/ai/exports/withdraw.csv', 'w');
          
    //     foreach ($list as $fields) {
    //         fputcsv($fp, $fields);
    //     }

    //     foreach($data as $key => $value)
    //     {
    //         fputcsv($fp, $value);  
    //     }
          
    //     fclose($fp);

        $data = withdrawModel::selectRaw("users.*,withdraw.amount,withdraw.created_on as dateofearning,withdraw.claim_hash,withdraw.withdraw_type, withdraw.status")->join('users','users.id','=','withdraw.user_id')->whereRaw("1 = 1 and withdraw_type != 'COMPOUND'  ".$whereStartDate.$whereEndDate.$whereRC)->paginate(20)->toArray();

        if ($isExport) {
            $withdraw = withdrawModel::selectRaw("users.*,withdraw.amount,withdraw.created_on as dateofearning,withdraw.claim_hash,withdraw.withdraw_type, withdraw.status")->join('users','users.id','=','withdraw.user_id')->whereRaw("1 = 1 and withdraw_type != 'COMPOUND'  ".$whereStartDate.$whereEndDate.$whereRC)->get()->toArray();

            $withdraw = array_map(function ($value) {
                return (array) $value;
            }, $withdraw);

            $list = [
                ['Member Name','Member Code', 'Sponsor Code', 'Transaction Hash','Amount','Date','Joining Date','Status']
            ];
            $filePath='/var/www/html/exports/withdraw.csv';
            $fp = fopen($filePath, 'w');
            foreach ($list as $fields) {
                fputcsv($fp, $fields);
            }
            foreach ($withdraw as $value) {
                if($value['status'] == 1){
                    $status='Complete';
                }elseif($value['status'] == 2){
                    $status='Failed';
                    
                }elseif($value['status'] == 0){
                    $status='Pending';

                }

                $dataRows = [
                    $value['name'],
                    $value['refferal_code'],
                    $value['sponser_code'],
                    $value['claim_hash'],
                    $value['amount'],
                    date('d-m-Y', strtotime($value['dateofearning'])),
                    date('d-m-Y', strtotime($value['created_on'])),
                    $status,
                ];
                fputcsv($fp, $dataRows);
            }
            
            fclose($fp);
            return response()->download($filePath)->deleteFileAfterSend(true);
        }


        $res['status_code'] = 1;
        $res['message'] = "Report generated successfully";
        $res['data'] = $data;
        $res['start_date'] = $start_date;
        $res['end_date'] = $end_date;
        $res['refferal_code'] = $refferal_code;

        return is_mobile($type, "withdraw_report", $res, 'view');
    }

    public function userDetails(Request $request)
    {
        $type = $request->input('type');
        $refferal_code = $request->input('member_code');

        if($refferal_code != null)
        {
            $data = usersModel::where(['refferal_code' => $refferal_code])->get()->toArray();

            $withdraw = withdrawModel::selectRaw('IFNULL(SUM(amount),0) as amount')->where(['user_id' => $data['0']['id']])->where(['status' => 1])->get()->toArray();

            $pendingWithdraw = withdrawModel::selectRaw('IFNULL(SUM(amount),0) as amount')->where(['user_id' => $data['0']['id']])->where(['status' => 0])->get()->toArray();

            $packages = userPlansModel::where(['user_id' => $data['0']['id']])->get()->toArray();

            $res['status_code'] = 1;
            $res['message'] = "User details successfully";
            $res['data'] = $data['0'];
            $res['withdraw'] = $withdraw['0']['amount'];
            $res['pending_withdraw'] = $pendingWithdraw['0']['amount'];
            $res['packages'] = $packages;
            $res['member_code'] = $refferal_code;

        }else
        {
            $res['status_code'] = 1;
            $res['message'] = "User details successfully";
        }


        return is_mobile($type, "user_details", $res, 'view');
    }

    public function userExportReport(Request $request)
    {
        $type = $request->input('type');
        $refferal_code = $request->input('refferal_code');
        $whereRC = '';

        if($refferal_code != '')
        {
            $whereRC = "  AND users.refferal_code = '".$refferal_code."' ";
        }

        $data = DB::select("SELECT users.name, users.refferal_code, SUM(user_plans.amount) as amount, users.roi_income, users.binary_income, users.bonus_income, users.matching_income, (users.roi_income + users.binary_income + users.bonus_income + users.matching_income) as total_income,(select sum(amount) from withdraw where user_id = users.id and status = 1) as total_withdraw, users.created_on as dateofearning FROM `users` LEFT JOIN user_plans on users.id = user_plans.user_id WHERE 1 = 1  ".$whereRC." GROUP by users.id");

        $data = array_map(function ($value) {
            return (array) $value;
        }, $data);

        $list = array(
            ['Member Name', 'Member Code', 'Invested Amount', 'Roi Income', 'Binary Income', 'Refferal Income', 'Matching Income', 'Total Income', 'Total Withdraw', 'Joining Date']
        );

        $fp = fopen('/var/www/ai/exports/userdataExport.csv', 'w');
          
        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }

        foreach($data as $key => $value)
        {
            fputcsv($fp, $value);  
        }
          
        fclose($fp);

        $data = usersModel::selectRaw('users.name, users.refferal_code, SUM(user_plans.amount) as amount, users.roi_income, users.binary_income, users.bonus_income, users.matching_income, (users.roi_income + users.binary_income + users.bonus_income + users.matching_income) as total_income,(select sum(amount) from withdraw where user_id = users.id and status = 1) as total_withdraw, users.created_on as dateofearning')->leftjoin('user_plans','user_plans.user_id','=','users.id')->whereRaw("1 = 1  ".$whereRC)->groupBy('users.id')->paginate(20)->toArray();

        $res['status_code'] = 1;
        $res['message'] = "Report generated successfully";
        $res['data'] = $data;
        $res['refferal_code'] = $refferal_code;

        return is_mobile($type, "user_export_report", $res, 'view');
    }

    public function verifyIncomeAdmin(Request $request)
    {
        $type = $request->input('type');
        $incometype = $request->input('incometype');
        $responseIncomeType = '';

        $data = earningLogsModel::selectRaw("earning_logs.*, users.refferal_code as user_code, users.rank, users.sponser_code, '' as refferal_code")->join('users','users.id','=','earning_logs.user_id')->where(['earning_logs.status' => 0])->orderBy('earning_logs.id', 'desc')->get()->toArray();

        foreach($data as $key => $value)
        {
            if($incometype == "PB")
            {
                $responseIncomeType = "Profit Bonus";
                if($value['tag'] == "L-1-PB" || $value['tag'] == "L-2-PB" || $value['tag'] == "L-3-PB" || $value['tag'] == "L-4-PB" || $value['tag'] == "L-5-PB" || $value['tag'] == "L-6-PB" || $value['tag'] == "L-7-PB" || $value['tag'] == "L-8-PB" || $value['tag'] == "L-9-PB" || $value['tag'] == "L-10-PB" || $value['tag'] == "L-11-PB" || $value['tag'] == "L-12-PB" || $value['tag'] == "L-13-PB" || $value['tag'] == "L-14-PB" || $value['tag'] == "L-15-PB" || $value['tag'] == "L-16-PB" || $value['tag'] == "L-17-PB" || $value['tag'] == "L-18-PB" || $value['tag'] == "L-19-PB" || $value['tag'] == "L-20-PB")
                {
                    $getRef = usersModel::where(['id' => $value['refrence_id']])->get()->toArray();
                    if(count($getRef) > 0)
                    {
                        $data[$key]['refferal_code'] = $getRef['0']['refferal_code'];
                    }else
                    {
                        $data[$key]['refferal_code'] = '';
                    }
                }else
                {
                    unset($data[$key]);
                }
            }

            if($incometype == "RP")
            {
                $responseIncomeType = "Royalty Pool";
                if($value['tag'] == "pool")
                {
                    
                }else
                {
                    unset($data[$key]);
                }
            }

            if($incometype == "rankbonus")
            {
                $responseIncomeType = "Rank Bonus";
                if($value['tag'] == "rank_bonus")
                {

                    $getRef = usersModel::where(['id' => $value['refrence_id']])->get()->toArray();
                    if(count($getRef) > 0)
                    {
                        $data[$key]['refferal_code'] = $getRef['0']['refferal_code'];
                    }else
                    {
                        $data[$key]['refferal_code'] = '';
                    }
                }else
                {
                    unset($data[$key]);
                }
            }

            if($incometype == "profitsharing")
            {
                $responseIncomeType = "Profit Sharing";
                if($value['tag'] == "profit_sharing")
                {
                    $getRef = usersModel::where(['id' => $value['refrence_id']])->get()->toArray();
                    if(count($getRef) > 0)
                    {
                        $data[$key]['refferal_code'] = $getRef['0']['refferal_code'];
                    }else
                    {
                        $data[$key]['refferal_code'] = '';
                    }
                }else
                {
                    unset($data[$key]);
                }
            }

            if($incometype == "brokerage")
            {
                $responseIncomeType = "Brokerage";
                if($value['tag'] == "brokerage")
                {
                    $getRef = usersModel::where(['id' => $value['refrence_id']])->get()->toArray();
                    if(count($getRef) > 0)
                    {
                        $data[$key]['refferal_code'] = $getRef['0']['refferal_code'];
                    }else
                    {
                        $data[$key]['refferal_code'] = '';
                    }
                }else
                {
                    unset($data[$key]);
                }
            }
        }

        $res['status_code'] = 1;
        $res['message'] = "Incomes fetched successfully";
        $res['data'] = $data;
        $res['responseIncomeType'] = $responseIncomeType;
        $res['incometype'] = $incometype;

        return is_mobile($type, "verify_income", $res, 'view');
    }

    public function verifyIncomeAdminExcel(Request $request)
    {
        $type = $request->input('type');
        $incometype = $request->input('incometype');
        $responseIncomeType = '';

        $data = earningLogsModel::selectRaw("users.refferal_code as user_code, earning_logs.tag, earning_logs.amount, earning_logs.flush_amount,users.sponser_code,  '' as refferal_code, users.rank,earning_logs.refrence_id")->join('users','users.id','=','earning_logs.user_id')->where(['earning_logs.status' => 0])->orderBy('earning_logs.id', 'desc')->get()->toArray();

        foreach($data as $key => $value)
        {
            if($incometype == "PB")
            {
                $responseIncomeType = "Profit Bonus";
                if($value['tag'] == "L-1-PB" || $value['tag'] == "L-2-PB" || $value['tag'] == "L-3-PB" || $value['tag'] == "L-4-PB" || $value['tag'] == "L-5-PB" || $value['tag'] == "L-6-PB" || $value['tag'] == "L-7-PB" || $value['tag'] == "L-8-PB" || $value['tag'] == "L-9-PB" || $value['tag'] == "L-10-PB" || $value['tag'] == "L-11-PB" || $value['tag'] == "L-12-PB" || $value['tag'] == "L-13-PB" || $value['tag'] == "L-14-PB" || $value['tag'] == "L-15-PB" || $value['tag'] == "L-16-PB" || $value['tag'] == "L-17-PB" || $value['tag'] == "L-18-PB" || $value['tag'] == "L-19-PB" || $value['tag'] == "L-20-PB")
                {
                    $getRef = usersModel::where(['id' => $value['refrence_id']])->get()->toArray();
                    if(count($getRef) > 0)
                    {
                        $data[$key]['refferal_code'] = $getRef['0']['refferal_code'];
                    }else
                    {
                        $data[$key]['refferal_code'] = '';
                    }
                }else
                {
                    unset($data[$key]);
                }
            }

            if($incometype == "RP")
            {
                $responseIncomeType = "Royalty Pool";
                if($value['tag'] == "pool")
                {
                    
                }else
                {
                    unset($data[$key]);
                }
            }

            if($incometype == "rankbonus")
            {
                $responseIncomeType = "Rank Bonus";
                if($value['tag'] == "rank_bonus")
                {

                    $getRef = usersModel::where(['id' => $value['refrence_id']])->get()->toArray();
                    if(count($getRef) > 0)
                    {
                        $data[$key]['refferal_code'] = $getRef['0']['refferal_code'];
                    }else
                    {
                        $data[$key]['refferal_code'] = '';
                    }
                }else
                {
                    unset($data[$key]);
                }
            }

            if($incometype == "profitsharing")
            {
                $responseIncomeType = "Profit Sharing";
                if($value['tag'] == "profit_sharing")
                {
                    $getRef = usersModel::where(['id' => $value['refrence_id']])->get()->toArray();
                    if(count($getRef) > 0)
                    {
                        $data[$key]['refferal_code'] = $getRef['0']['refferal_code'];
                    }else
                    {
                        $data[$key]['refferal_code'] = '';
                    }
                }else
                {
                    unset($data[$key]);
                }
            }

            if($incometype == "brokerage")
            {
                $responseIncomeType = "Brokerage";
                if($value['tag'] == "brokerage")
                {
                    $getRef = usersModel::where(['id' => $value['refrence_id']])->get()->toArray();
                    if(count($getRef) > 0)
                    {
                        $data[$key]['refferal_code'] = $getRef['0']['refferal_code'];
                    }else
                    {
                        $data[$key]['refferal_code'] = '';
                    }
                }else
                {
                    unset($data[$key]);
                }
            }

            unset($data[$key]['refrence_id']);
        }

        $headings = [
            'User Id',
            'Tag',
            'Amount',
            'Flush Amount',
            'Sponser Code',
            'Refrence',
            'Rank',
        ];

        // return Excel::create('VerifyIncomes', function($excel) use ($data, $headings) {
        //     $excel->sheet('VerifyIncomes', function($sheet) use ($data, $headings) {
        //         // Set the headings
        //         $sheet->row(1, $headings);

        //         // Add data to the sheet
        //         $j = 1;
        //         foreach ($data as $row) {
        //             $sheet->appendRow([$j, $row['user_code'], $row['tag'], $row['amount'], $row['flush_amount'], $row['sponser_code'], $row['refferal_code'], $row['rank']]);
        //         }
        //     });
        // })->download('xlsx');

        return Excel::download(new CustomQueryExport($data, $headings), $incometype.'.xlsx');
    }

    public function mismatchedMt5(Request $request)
    {
        $type = $request->input('type');
        $excelType = "Balance Excel";

        $data = marketExcelModel::where(['found' => 0])->orderBy('date','desc')->get()->toArray();

        $res['status_code'] = 1;
        $res['message'] = "Incomes fetched successfully";
        $res['data'] = $data;
        $res['responseExcel'] = $excelType;

        return is_mobile($type, "mismatched_excel", $res, 'view');
    }

    public function psMismatchedMt5(Request $request)
    {
        $type = $request->input('type');
        $excelType = "Profit Sharing Excel";

        $data = profitSharingModel::where(['found' => 0])->orderBy('date','desc')->get()->toArray();

        $res['status_code'] = 1;
        $res['message'] = "Incomes fetched successfully";
        $res['data'] = $data;
        $res['responseExcel'] = $excelType;

        return is_mobile($type, "mismatched_excel", $res, 'view');
    }

    public function inactiveMT5UserBalance(Request $request)
    {
        $type = $request->input('type');

        $data = usersModel::whereRaw('mt5_verify != 1 and balance > 0')->get()->toArray();

        $res['status_code'] = 1;
        $res['message'] = "Incomes fetched successfully";
        $res['data'] = $data;

        return is_mobile($type, "inactive_mt5", $res, 'view');
    }

    public function verifiedNoBalanceMt5(Request $request)
    {
        $type = $request->input('type');

        $data = marketExcelModel::where(['found' => 0])->orderBy('date','desc')->limit(1)->get()->toArray();

        $users = usersModel::whereRaw("mt5_verify = 1")->get()->toArray();

        foreach($users as $key => $value)
        {
            $excel = marketExcelModel::where(['date' => $data['0']['date'], 'mt_acc' => $value['mt5']])->get()->toArray();

            if(count($excel) > 0)
            {
                unset($users[$key]);
            }
        }


        $res['status_code'] = 1;
        $res['message'] = "Incomes fetched successfully";
        $res['data'] = $users;

        return is_mobile($type, "inactive_mt5", $res, 'view');
    }

    public function findUplineRankView(Request $request)
    {
        $type = $request->input('type');
        $member_code = $request->input('member_code');
        
        $res['status_code'] = 1;
        $res['message'] = "Search member here";    

        return is_mobile($type, 'upline_rank', $res, "view");
    }

    public function findUplineRankViewResult(Request $request)
    {
        $type = $request->input('type');

        $member_code = $request->input('member_code');

        $data = usersModel::where(['refferal_code' => $member_code])->get()->toArray();

        $lastRank = 0;
        $lastRankArray = array();
        foreach($data as $k => $v)
        {
            $rankDetails = findUplineRank($v['sponser_id'], 0);

            if($rankDetails['rank_id'] > 0)
            {
                $lastRankArray[$rankDetails['rank_id']] = $rankDetails;
                $lastRank = $rankDetails['rank_id'];
                
                if($rankDetails['rank_id'] < 8)
                {
                    $rankDetails = findUplineRank($rankDetails['user_id'], $lastRank);
                    if($rankDetails['rank_id'] > 0)
                    {
                        $lastRankArray[$rankDetails['rank_id']] = $rankDetails;
                        $lastRank = $rankDetails['rank_id'];
                        
                        if($rankDetails['rank_id'] < 8)
                        {
                            $rankDetails = findUplineRank($rankDetails['user_id'], $lastRank);
                            if($rankDetails['rank_id'] > 0)
                            {
                                $lastRankArray[$rankDetails['rank_id']] = $rankDetails;
                                $lastRank = $rankDetails['rank_id'];
                                
                                if($rankDetails['rank_id'] < 8)
                                {
                                    $rankDetails = findUplineRank($rankDetails['user_id'], $lastRank);
                                    if($rankDetails['rank_id'] > 0)
                                    {
                                        $lastRankArray[$rankDetails['rank_id']] = $rankDetails;
                                        $lastRank = $rankDetails['rank_id'];
                                        
                                        if($rankDetails['rank_id'] < 8)
                                        {
                                            $rankDetails = findUplineRank($rankDetails['user_id'], $lastRank);
                                            if($rankDetails['rank_id'] > 0)
                                            {
                                                $lastRankArray[$rankDetails['rank_id']] = $rankDetails;
                                                $lastRank = $rankDetails['rank_id'];
                                                
                                                if($rankDetails['rank_id'] < 8)
                                                {
                                                    $rankDetails = findUplineRank($rankDetails['user_id'], $lastRank);
                                                    if($rankDetails['rank_id'] > 0)
                                                    {
                                                        $lastRankArray[$rankDetails['rank_id']] = $rankDetails;
                                                        $lastRank = $rankDetails['rank_id'];
                                                        
                                                        if($rankDetails['rank_id'] < 8)
                                                        {
                                                            $rankDetails = findUplineRank($rankDetails['user_id'], $lastRank);
                                                            if($rankDetails['rank_id'] > 0)
                                                            {
                                                                $lastRankArray[$rankDetails['rank_id']] = $rankDetails;
                                                                $lastRank = $rankDetails['rank_id'];
                                                                
                                                                if($rankDetails['rank_id'] < 8)
                                                                {
                                                                    $rankDetails = findUplineRank($rankDetails['user_id'], $lastRank);
                                                                    $lastRankArray[$rankDetails['rank_id']] = $rankDetails;
                                                                }    
                                                            }
                                                        }    
                                                    }
                                                }    
                                            }
                                        }    
                                    }
                                }    
                            }
                        }    
                    }
                }    
            }
        }

        foreach ($lastRankArray as $key => $value) {
            $data = usersModel::where(['id' => $value['user_id']])->get()->toArray();
            $lastRankArray[$key]['refferal_code'] = $data['0']['refferal_code'];
            $lastRankArray[$key]['name'] = $data['0']['name'];
            $lastRankArray[$key]['mt5_name'] = $data['0']['mt5_name'];
            $lastRankArray[$key]['wallet_address'] = $data['0']['wallet_address'];
            $lastRankArray[$key]['my_team'] = $data['0']['my_team'];
            $lastRankArray[$key]['my_business'] = $data['0']['my_business'];
            $lastRankArray[$key]['sponser_code'] = $data['0']['sponser_code'];
        }

        $res['status_code'] = 1;
        $res['message'] = "Incomes fetched successfully";
        $res['data'] = $lastRankArray;
        $res['member_code'] = $member_code;

        return is_mobile($type, "upline_rank", $res, 'view');
    }

    public function witdhrawBankRequest(Request $request)
    {
        $type = $request->input('type');

        $data = DB::select("SELECT users.name, users.refferal_code, users.mt5, users.sponser_code, withdraw.amount, withdraw.created_on as dateofearning, users.created_on,withdraw.withdraw_type, users.account_holder_name, users.bank_name, users.account_number, users.ifsc_code, withdraw.id FROM withdraw INNER JOIN users on withdraw.user_id = users.id WHERE withdraw.status = 0 and withdraw_type = 'BANKWIRE' ");

        $data = array_map(function ($value) {
            return (array) $value;
        }, $data);

        $res['status_code'] = 1;
        $res['message'] = "Successfully";
        $res['data'] = $data;

        return is_mobile($type, "withdraw_request", $res, 'view');
    }

    public function withdrawBankProcess(Request $request)
    {
        $type = $request->input('type');
        $wid = $request->input('wid');
        $decision = $request->input('decision');

        $data = withdrawModel::where(['id' => $wid])->get()->toArray();

        if(count($data) > 0)
        {
            if($decision == 0)
            {
                withdrawModel::where(['id' => $wid])->update(['status' => 2]);
            }else
            {
                withdrawModel::where(['id' => $wid])->update(['status' => 1]);
            }

            if($data['0']['wallet'] == "PROFIT-SHARING")
            {
                if($decision != 0)
                {
                    $user = usersModel::where(['id' => $data['0']['user_id']])->get()->toArray();

                    $mt5 = $user['0']['mt5'];

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                      CURLOPT_URL => 'https://mt5apis.strelasoft.com/smartbulls/api/accountwithdraw',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS => array('mt5id' => $mt5,'amount' => number_format($data['0']['amount'], 2),'comment' => 'Made withdraw of '.$data['0']['amount']),
                      CURLOPT_HTTPHEADER => array(
                        'Authorization: Sf5u9pxS7iho3AoC6CaDklbkmTwRCOyYhzRPWiz37UBODjIE7Zx9OxK2PMRIc'
                      ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);

                    withdrawModel::where(['id' => $wid])->update(['mt5Response' => $response]);
                }
            }
        }


        $res['status_code'] = 1;
        $res['message'] = "Withdraw Processed Successfully";

        return is_mobile($type, "witdhrawBankRequest", $res);
    }

    public function awardIncomeRequest(Request $request)
    {
        $type = $request->input('type');

        $data = DB::select("SELECT u.name, u.mobile_number, u.sponser_code, u.email, u.refferal_code, u.mt5, a.* FROM award_income_form a inner join users u on a.user_id = u.id WHERE a.status = 0");

        $data = array_map(function ($value) {
            return (array) $value;
        }, $data);

        $res['status_code'] = 1;
        $res['message'] = "Successfully";
        $res['data'] = $data;

        return is_mobile($type, "award_withdraw_request", $res, 'view');
    }

    public function awardIncomeProcessAdmin(Request $request)
    {
        $type = $request->input('type');
        $wid = $request->input('wid');
        $decision = $request->input('decision');

        if($decision == 0)
        {
            DB::table("award_income_form")->where(['id' => $wid])->update(['status' => 2]);
        }else
        {
            DB::table("award_income_form")->where(['id' => $wid])->update(['status' => 1]);
        }

        $res['status_code'] = 1;
        $res['message'] = "Withdraw Processed Successfully";

        return is_mobile($type, "awardIncomeRequest", $res);
    }

    public function rankSortDate(Request $request)
    {
        $type = $request->input('type');

        $data = DB::select("SELECT * FROM users WHERE rank_id > 0 order by rank_date asc");

        $data = array_map(function ($value) {
            return (array) $value;
        }, $data);

        $res['status_code'] = 1;
        $res['message'] = "Successfully";
        $res['data'] = $data;

        return is_mobile($type, "rank_sort_date", $res, 'view');
    }

    public function fundTransferReport(Request $request)
    {
        $type = $request->input('type');

        $data = DB::select("SELECT p.pin, p.amount, p.status, u.name, u.refferal_code, u.mt5, uf.name as for_user_name, uf.refferal_code as for_user_refferal_code, uf.mt5 as for_user_mt5, p.for_user_id, p.for_created_on FROM `pins` p inner join users u on p.user_id = u.id inner join users as uf on uf.id = p.for_user_id");

        $data = array_map(function ($value) {
            return (array) $value;
        }, $data);

        foreach($data as $key => $value)
        {
            if($value['status'] == 1)
            {
                if(empty($value['created_on']))
                {
                    $packageTransaction = packageTransaction::whereRaw("amount = '".$value['amount']."' and user_id = '".$value['for_user_id']."'")->get()->toArray();

                    if(count($packageTransaction) > 0)
                    {
                        $data[$key]['for_created_on'] = date('d-m-Y', strtotime($packageTransaction['0']['created_on']));
                    }else
                    {
                        $data[$key]['for_created_on'] = '-';
                    }
                }else
                {
                    $data[$key]['for_created_on'] = date('d-m-Y', strtotime($value['for_created_on']));
                }
            }else
            {
                $data[$key]['for_created_on'] = '-';
            }
        }

        $res['status_code'] = 1;
        $res['message'] = "Successfully";
        $res['data'] = $data;

        return is_mobile($type, "fund_transfer_report", $res, 'view');


    }

    public function verifyReferralIncome(Request $request)
    {
        $type = $request->input('type');

        $data = DB::select("SELECT e.*, u.refferal_code, u.name, u.wallet_address, u.mt5, ur.mt5 as imt5, ur.name as iname, ur.wallet_address as iwallet_address, ur.refferal_code as irefferal_code FROM earning_logs e inner join users u on e.user_id = u.id inner join users ur on e.refrence_id = ur.id WHERE e.status = 0 and e.tag = 'REFERRAL' order by e.id desc");

        $data = array_map(function ($value) {
            return (array) $value;
        }, $data);

        $res['status_code'] = 1;
        $res['message'] = "Successfully";
        $res['data'] = $data;

        return is_mobile($type, "verify_referral_income", $res, 'view');
    }

    public function activeReferralIncome(Request $request)
    {
        $type = $request->input('type');

        $data = earningLogsModel::where(['status' => 0, 'tag' => "REFERRAL"])->get()->toArray();

        foreach($data as $key => $value)
        {
            if($value['tag'] == "REFERRAL")
            {
                DB::statement("UPDATE users set referral_bonus = (IFNULL(referral_bonus,0) + (".$value['amount'].")) where id = '".$value['user_id']."'");
            }

            earningLogsModel::where(['id' => $value['id']])->update(['status' => 1]);
        }

        $res['status_code'] = 1;
        $res['message'] = "Successfully";
        $res['data'] = $data;

        return is_mobile($type, "verifyReferralIncome", $res);
    }

    public function transferBalanceReport(Request $request)
    {
        $type = $request->input('type');

        $data = transferModel::join('users as user1', 'user1.id', '=', 'transfer.user_id')
        ->join('users as user2', 'user2.id', '=', 'transfer.for_user_id')
        ->select(
            'transfer.*',
            'user1.refferal_code as user_refferal_code',
            'user2.refferal_code as for_user_refferal_code'
        )
        ->orderBy('transfer.id', 'desc')
        ->get()
        ->toArray();

        $res['status_code'] = 1;
        $res['message'] = "Successfully";
        $res['data'] = $data;

        return is_mobile($type, "transfer_balance_report", $res, "view");
    }

    public function updateLeadershipComission(Request $request)
    {
        $type = $request->input('type');
        $user_id = $request->input('user_id');        
        $leader_comission = $request->input('leader_comission');
        if ($leader_comission>500) {
            $res['status_code'] = 0;
            $res['message'] = "Minimum 500 can be entered.";

            return redirect()->back()->with('data', $res);
        }


        if($user_id > 0)
        {
            usersModel::where(['id' => $user_id])->update(['leadership_comission' => DB::raw('leadership_comission + ' . $leader_comission)]);

            $roi = array();
            $roi['user_id'] = $user_id;
            $roi['amount'] = $leader_comission;
            $roi['tag'] = "leader-comission";
            $roi['refrence'] = 'By Admin';
            $roi['created_on'] = date('Y-m-d H:i:s');

            earningLogsModel::insert($roi);

            $res['status_code'] = 1;
            $res['message'] = "Leadership Comission Worth $".$leader_comission." Added Successfully";
        }else
        {
            $res['status_code'] = 1;
            $res['message'] = "Something Went Wrong.";
        }


        return redirect()->back()->with('data', $res);
    }

}
