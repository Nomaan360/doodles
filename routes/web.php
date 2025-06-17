<?php

use App\Http\Controllers\incomeOverviewController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\packagesController;
use App\Http\Controllers\profileController;
use App\Http\Controllers\registerController;
use App\Http\Controllers\supportTicketController;
use App\Http\Controllers\teamController;
use App\Http\Controllers\withdrawController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\admin\loginController as BackendLoginController;
use App\Http\Controllers\admin\withdrawController as BackendwithdrawController;
use App\Http\Controllers\admin\packageController as BackendpackageController;
use App\Http\Controllers\admin\usersController as BackendusersController;
use App\Http\Controllers\admin\levelRoiController as BackendlevelRoiController;
use App\Http\Controllers\admin\settingController as BackendsettingController;
use App\Http\Controllers\admin\rankBonusController as BackendrankBonusController;
use App\Http\Controllers\adsController as BackendAdsController;
use App\Http\Controllers\newsController as BackendNewsController;
use App\Http\Controllers\roiDistributionController as BackendroiDistributionController;

Route::any('/', [loginController::class, 'dashboard'])->middleware('session');

// Login Page Route
Route::get('/login', function () {
    return view('pages.login');
})->name('flogin');

Route::any('/register', [registerController::class, 'index'])->name('fregister');

Route::post('/register-process', [registerController::class, 'store'])->name('fregisterProcess');

Route::post('/user-validate', [loginController::class, 'userValidate'])->name('fuserValidate');

Route::post('/login-process', [loginController::class, 'login'])->name('floginProcess');

Route::any('/dashboard', [loginController::class, 'dashboard'])->name('fdashboard')->middleware('session');

Route::any('/logout', [loginController::class, 'logout'])->name('flogout');

Route::any('/profile', [profileController::class, 'index'])->name('fprofile')->middleware('session');

Route::any('/profile-update', [profileController::class, 'profile_update'])->name('fprofileUpdate')->middleware('session');

Route::any('/password-update', [profileController::class, 'password_update'])->name('fpasswordUpdate')->middleware('session');

Route::any('/packages', [packagesController::class, 'index'])->name('packageDeposit')->middleware('session');

Route::any('/package-deposit', [packagesController::class, 'packageDeposit'])->name('package')->middleware('session');

Route::any('/process-package', [packagesController::class, 'processpackage'])->name('process.package')->middleware('session');

Route::any('/topup-9pay', [packagesController::class, 'topup9pay'])->name('topup9pay')->middleware('session');

Route::any('/topup-9pay-process-activation', [packagesController::class, 'topup9PayActivation'])->name('ftopup9PayActivation')->middleware('session');

Route::any('/api-handle-package-transaction-9pay', [packagesController::class, 'apiHandlePackageTransaction9Pay'])->name('fapiHandlePackageTransaction9Pay')->middleware('session');

Route::any('/api-handle-package-transaction', [packagesController::class, 'handlePackageTransaction'])->name('fhandlePackageTransaction')->middleware('session');

Route::any('/check-package-transaction', [packagesController::class, 'checkPackageTransaction'])->name('check_package_transaction')->middleware('session');

Route::any('/ajax-activate-package', [packagesController::class, 'ajaxActivatePackage'])->name('fajaxActivatePackage')->middleware('session');

Route::post('/ajax-store-package', [packagesController::class, 'ajaxStorePackage'])->name('fajaxStorePackage')->middleware('session');

Route::any('/cancel-pay-transaction', [packagesController::class, 'cancelPayTransaction'])->name('fcancelPayTransaction')->middleware('session');

Route::any('/my-directs', [teamController::class, 'my_directs'])->name('fmy_directs')->middleware('session');

Route::any('/my-team', [teamController::class, 'my_team'])->name('fmy_team')->middleware('session');

Route::any('/genealogy', [teamController::class, 'genealogy_level_team'])->name('fgenealogy')->middleware('session');

Route::any('/support-tickets', [supportTicketController::class, 'index'])->name('supportTicket')->middleware('session');

Route::any('/process-support-tickets', [supportTicketController::class, 'support_ticket_process'])->name('supportTicketProcess')->middleware('session');

Route::any('/package-compound', [packagesController::class, 'packageCompound'])->name('fpackageCompound')->middleware('session');

Route::any('/my-nfts', [packagesController::class, 'myNfts'])->name('fmyNfts')->middleware('session');

Route::get('/404', function () {
    return view('pages.404');
});

Route::get('/500', function () {
    return view('pages.500');
});
// Activate Package (FUND) Page Route
Route::get('/package-activation-pin', function () {
    return view('pages.packages');
});

// Activate Package (Multichain USDT) Page Route
Route::get('/package-topup-9pay', function () {
    return view('pages.package_topup_9pay');
});

// Income Overview Page Route
Route::any('/income-overview', [incomeOverviewController::class, 'index'])->name('fincomeOverview')->middleware('session');

Route::any('/withdraw', [withdrawController::class, 'index'])->name('fwithdraw')->middleware('session');

Route::any('/withdraw-process', [withdrawController::class, 'withdrawProcess'])->name('fwithdrawProcess')->middleware('session');

Route::any('/transfer-balance', [withdrawController::class, 'transferBalance'])->name('ftransferBalance')->middleware('session');

Route::any('/transfer-balance-process', [withdrawController::class, 'transferBalanceProcess'])->name('ftransferBalanceProcess')->middleware('session');

Route::any('/referral-code-details', [loginController::class, 'referralCodeDetails'])->name('freferralCodeDetails')->middleware('session');

Route::any('/ad-viewed', [BackendAdsController::class, 'adViewed'])->name('fadViewed')->middleware('session');

Route::any('/news', [BackendNewsController::class, 'newsIndex'])->name('fnews')->middleware('session');


// BACKEND ROUTES

Route::get('ZG9vZGxlcw/', function (Request $request) {
    $user_id = $request->session()->get('admin_user_id');
    if (!empty($user_id)) {
        return redirect()->route('dashboard');
    } else {
        return view('login');
    }
})->name('index');

Route::post('ZG9vZGxlcw/login', [BackendLoginController::class, 'index'])->name('login');

Route::any('ZG9vZGxlcw/login-otp', [BackendLoginController::class, 'loginviewotp'])->name('aloginviewotp');
Route::post('ZG9vZGxlcw/login-otp-process', [BackendLoginController::class, 'otpProcess'])->name('aotpProcess');

Route::any('ZG9vZGxlcw/logout', [BackendLoginController::class, 'logout'])->name('logout');

Route::any('ZG9vZGxlcw/dashboard', [BackendLoginController::class, 'dashboard'])->name('dashboard')->middleware('adminsession');

Route::any('ZG9vZGxlcw/with-drawprocess', [BackendwithdrawController::class, 'withdrawProcess'])->name('withdrawProcess')->middleware('adminsession');
Route::any('ZG9vZGxlcw/withdraw-save', [BackendwithdrawController::class, 'withdrawSave'])->name('withdrawSave')->middleware('adminsession');
Route::any('ZG9vZGxlcw/search-member', [BackendpackageController::class, 'searchMember'])->name('searchMember')->middleware('adminsession');
Route::any('ZG9vZGxlcw/processpackage-member', [BackendpackageController::class, 'processpackage'])->name('processpackagemember')->middleware('adminsession');
Route::any('ZG9vZGxlcw/update-member', [BackendusersController::class, 'updateUserDetails'])->name('updateUserDetails')->middleware('adminsession');
Route::any('ZG9vZGxlcw/update-topup-balance', [BackendusersController::class, 'updateTopupBalance'])->name('updateTopupBalance')->middleware('adminsession');
Route::any('ZG9vZGxlcw/process-award-income', [BackendusersController::class, 'awardIncomeProcess'])->name('awardIncomeProcess')->middleware('adminsession');
Route::any('ZG9vZGxlcw/member-support-tickets', [BackendusersController::class, 'memberSupportTickets'])->name('memberSupportTickets')->middleware('adminsession');
Route::any('ZG9vZGxlcw/reply-support-tickets', [BackendusersController::class, 'replySupportTickets'])->name('replySupportTickets')->middleware('adminsession');
Route::any('ZG9vZGxlcw/members-report', [BackendusersController::class, 'membersReport'])->name('membersReport')->middleware('adminsession');
Route::any('ZG9vZGxlcw/investment-process-report', [BackendusersController::class, 'investmentReport'])->name('investmentReport')->middleware('adminsession');
Route::any('ZG9vZGxlcw/withdraw-process-report', [BackendusersController::class, 'withdrawReport'])->name('withdrawReport')->middleware('adminsession');
Route::any('ZG9vZGxlcw/income-geneated-report-process', [loginController::class, 'incomeOverviewFilter'])->name('incomeOverviewFilter')->middleware('adminsession');
Route::any('ZG9vZGxlcw/income-geneated-report-process-excel', [loginController::class, 'incomeOverviewFilterExcel'])->name('incomeOverviewFilterExcel')->middleware('adminsession');
Route::any('ZG9vZGxlcw/user-details', [BackendusersController::class, 'userDetails'])->name('userDetails')->middleware('adminsession');
Route::any('ZG9vZGxlcw/user-export-report', [BackendusersController::class, 'userExportReport'])->name('userExportReport')->middleware('adminsession');
Route::any('ZG9vZGxlcw/transfer-balance-report', [BackendusersController::class, 'transferBalanceReport'])->name('transferBalanceReport')->middleware('adminsession');
Route::any('ZG9vZGxlcw/update-leadership-comission', [BackendusersController::class, 'updateLeadershipComission'])->name('updateLeadershipComission')->middleware('adminsession');

Route::get('ZG9vZGxlcw/withdraw-report', function (Request $request) {
    return view('withdraw_report');
})->name('withdraw_report');

Route::get('ZG9vZGxlcw/investment-report', function (Request $request) {
    return view('investment_report');
})->name('investment_report');

Route::get('ZG9vZGxlcw/report', function (Request $request) {
    return view('report');
})->name('report');

Route::get('ZG9vZGxlcw/income-geneated-report', function (Request $request) {
    return view('income_generated_report');
})->name('incomeGReport');

Route::get('ZG9vZGxlcw/report-users', function (Request $request) {
    return view('user_export_report');
})->name('report_users');

Route::group(['prefix' => 'ZG9vZGxlcw', 'middleware' => ['adminsession']], function () {
    Route::resource('level-roi', BackendlevelRoiController::class);
    Route::resource('package', BackendpackageController::class);
    Route::resource('setting', BackendsettingController::class);
    Route::resource('users', BackendusersController::class);
    Route::resource('withdraw', BackendwithdrawController::class);
    Route::resource('rank-bonus', BackendrankBonusController::class);
    Route::resource('ads', BackendAdsController::class);
    Route::resource('news', BackendNewsController::class);
    Route::resource('roi-distribution-import', BackendroiDistributionController::class);
});
