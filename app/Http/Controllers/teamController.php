<?php

namespace App\Http\Controllers;

use App\Models\myTeamModel;
use App\Models\userPlansModel;
use App\Models\usersModel;
use Illuminate\Http\Request;

use function App\Helpers\getLevelTeam;
use function App\Helpers\is_mobile;

class teamController extends Controller
{
    public function my_team(Request $request)
    {
        $type = $request->input('type');
        $user_id = $request->session()->get('user_id');

        $data = myTeamModel::selectRaw('users.*')->join('users', 'users.id', '=', 'my_team.team_id')->where(['user_id' => $user_id])->orderBy('my_team.id', 'desc')->get()->toArray();

        $otherPackageLeft = 0;
        $otherPackageRight = 0;

        foreach ($data as $key => $value) {
            $currentPackage = 0;
            $matchingDistributed = 0;
            $allPackages = '';
            $currentPackageDate = '-';
            $package = userPlansModel::where(['user_id' => $value['id']])->whereRaw("roi > 0 and isSynced != 2")->get()->toArray();

            foreach ($package as $k => $v) {
                if ($v['status'] == 1) {
                    $currentPackage = $v['amount'];
                    $matchingDistributed = $v['isSynced'];
                    $currentPackageDate = $v['created_on'];
                } else {

                    $otherPackageLeft += $v['amount'];
                }

                $allPackages .= $v['amount'] . ",";
            }

            $data[$key]['matchingDistributed'] = $matchingDistributed;
            $data[$key]['currentPackage'] = $currentPackage;
            $data[$key]['otherPackageLeft'] = $otherPackageLeft;
            $data[$key]['otherPackageRight'] = $otherPackageRight;
            $data[$key]['currentPackageDate'] = $currentPackageDate;
            $data[$key]['allPackages'] = rtrim($allPackages, ",");
        }

        $user = usersModel::where(['id' => $user_id])->get()->toArray();

        $res['status_code'] = 1;
        $res['message'] = "My Team";
        $res['data'] = $data;
        $res['user'] = $user['0'];

        return is_mobile($type, "pages.total_team", $res, "view");
    }

    public function my_directs(Request $request)
    {
        $type = $request->input('type');
        $user_id = $request->session()->get('user_id');

        $data = usersModel::where(['sponser_id' => $user_id])->orderBy('id', 'desc')->get()->toArray();

        foreach ($data as $key => $value) {
            $totalPackage = 0;
            $currentPackage = 0;
            $allPackages = '';
            $currentPackageDate = '-';
            $package = userPlansModel::where(['user_id' => $value['id']])->whereRaw('roi > 0 and isSynced != 2')->get()->toArray();

            foreach ($package as $k => $v) {
                if ($v['status'] == 1) {
                    $currentPackage = $v['amount'];
                    $currentPackageDate = $v['created_on'];
                } else {
                    $allPackages .= $v['amount'] . ",";
                }
                $totalPackage += $v['amount'];
            }
            $data[$key]['totalPackage'] = $totalPackage;
            $data[$key]['currentPackage'] = $currentPackage;
            $data[$key]['currentPackageDate'] = $currentPackageDate;
            $data[$key]['allPackages'] = rtrim($allPackages, ",");
        }

        $user = usersModel::where(['id' => $user_id])->get()->toArray();

        $res['status_code'] = 1;
        $res['message'] = "My Team";
        $res['data'] = $data;
        $res['user'] = $user['0'];

        return is_mobile($type, "pages.directs_team", $res, "view");
    }

    public function genealogy_level_team(Request $request)
    {
        $type = $request->input('type');
        if ($type == "API") {
            $refferal_code = $request->input('refferal_code');
            $getUserId = usersModel::where(['refferal_code' => $refferal_code])->get()->toArray();
            $user_id = $getUserId['0']['id'];
        } else {
            $user_id = $request->session()->get('user_id');
        }

        $data = getLevelTeam($user_id);

        foreach ($data as $key => $value) {
            $dataL2 = getLevelTeam($value['id']);

            if (count($dataL2) > 0) {
                $data[$key][$value['refferal_code']] = $dataL2;
            }
        }

        $res['status_code'] = 1;
        $res['message'] = "Fetched Successfully.";
        $res['data'] = $data;

        return is_mobile($type, "pages.genealogy", $res, "view");
    }
}
