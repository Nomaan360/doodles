@extends('layouts.app')

@section('title', 'Withdraw')

@section('style')
<style type="text/css">
    .swal-button {
        background-color: #a855f7 !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
</style>
@endsection
@section('content')
<section class="w-full p-3 md:p-8 mx-auto max-w-[1400px]">
    <h2 class="bg-gradient-to-r from-indigo-300 to-cyan-300 relative text-black rounded-sm p-3 text-lg font-normal leading-none mb-5 flex items-center gap-2">
        <svg class="w-7 h-7 min-w-7 min-h-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 17V11" stroke="#000000" stroke-width="1.5" stroke-linecap="round" />
            <circle cx="1" cy="1" r="1" transform="matrix(1 0 0 -1 11 9)" fill="#000000" />
            <path d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7" stroke="#000000" stroke-width="1.5" stroke-linecap="round" />
        </svg>
        Due to polygon congestion, withdrawals are slowly processed.
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
            <div class="flex items-center space-x-3 w-full gap-1 bg-[#101735] rounded-2xl p-4 h-full">
                <img src={{ asset('assets/images/icons/direct-investment.webp') }} width="64" height="48" alt="Logo" class="w-12 h-auto">
                <div class="w-full">
                    <h3 class="text-base mb-1 opacity-75 leading-none flex items-start justify-between">Direct Referral</h3>
                    <span class="text-xl font-bold">${{$data['user']['direct_income']}}</span>
                </div>
            </div>
        </div>
        <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
            <div class="flex items-center space-x-3 w-full gap-1 bg-[#101735] rounded-2xl p-4 h-full">
                <img src={{ asset('assets/images/income-icons/profit-sharing.webp') }} width="64" height="48" alt="Logo" class="w-12 h-auto">
                <div class="w-full">
                    <h3 class="text-base mb-1 opacity-75 leading-none flex items-start justify-between">Roi Income</h3>
                    <span class="text-xl font-bold">${{$data['user']['roi_income']}}</span>
                </div>
            </div>
        </div>
        <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
            <div class="flex items-center space-x-3 w-full gap-1 bg-[#101735] rounded-2xl p-4 h-full">
                <img src={{ asset('assets/images/income-icons/level-income.webp') }} width="64" height="48" alt="Logo" class="w-12 h-auto">
                <div class="w-full">
                    <h3 class="text-base mb-1 opacity-75 leading-none flex items-start justify-between">Level Income</h3>
                    <span class="text-xl font-bold">${{$data['user']['level_income']}}</span>
                </div>
            </div>
        </div>
        <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
            <div class="flex items-center space-x-3 w-full gap-1 bg-[#101735] rounded-2xl p-4 h-full">
                <img src={{ asset('assets/images/income-icons/rank-income.webp') }} width="64" height="48" alt="Logo" class="w-12 h-auto">
                <div class="w-full">
                    <h3 class="text-base mb-1 opacity-75 leading-none flex items-start justify-between">Rank Bonus</h3>
                    <span class="text-xl font-bold">${{$data['user']['reward']}}</span>
                </div>
            </div>
        </div>
        <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
            <div class="flex items-center space-x-3 w-full gap-1 bg-[#101735] rounded-2xl p-4 h-full">
                <img src={{ asset('assets/images/income-icons/leadership.webp') }} width="64" height="48" alt="Logo" class="w-12 h-auto">
                <div class="w-full">
                    <h3 class="text-base mb-1 opacity-75 leading-none flex items-start justify-between">Royalty</h3>
                    <span class="text-xl font-bold">${{$data['user']['royalty']}}</span>
                </div>
            </div>
        </div>
        <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
            <div class="flex items-center space-x-3 w-full gap-1 bg-[#101735] rounded-2xl p-4 h-full">
                <img src={{ asset('assets/images/income-icons/leadership.webp') }} width="64" height="48" alt="Logo" class="w-12 h-auto">
                <div class="w-full">
                    <h3 class="text-base mb-1 opacity-75 leading-none flex items-start justify-between">Leadership Comission</h3>
                    <span class="text-xl font-bold">${{$data['user']['leadership_comission']}}</span>
                </div>
            </div>
        </div>
        <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
            <div class="flex items-center space-x-3 w-full gap-1 bg-[#101735] rounded-2xl p-4 h-full">
                <img src={{ asset('assets/images/income-icons/total-invest.webp') }} width="64" height="48" alt="Logo" class="w-12 h-auto">
                <div class="w-full">
                    <h3 class="text-base mb-1 opacity-75 leading-none flex items-start justify-between">Total Income</h3>
                    <span class="text-xl font-bold">${{$data['user']['roi_income'] + $data['user']['level_income'] + $data['user']['reward'] + $data['user']['royalty'] + $data['user']['direct_income'] + $data['user']['leadership_comission']}}</span>
                </div>
            </div>
        </div>
        <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
            <div class="flex items-center space-x-3 w-full gap-1 bg-[#101735] rounded-2xl p-4 h-full">
                <img src={{ asset('assets/images/icons/total-withdraw-icon.webp') }} width="64" height="48" alt="Logo" class="w-12 h-auto">
                <div class="w-full">
                    <h3 class="text-base mb-1 opacity-75 leading-none flex items-start justify-between">Total Withdraw</h3>
                    <span class="text-xl font-bold">${{$data['withdraw_amount']}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="flex items-center justify-center max-w-fit mx-auto my-10 gap-2">
        <div class="flex flex-wrap items-center justify-center relative group max-w-fit">
            <button data-dialog-target="dialog" class="text-sm sm:text-base w-full relative inline-block p-px font-semibold leading-none text-white cursor-pointer rounded-sm" type="button">
                <span class="absolute inset-0 rounded-full bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 p-[2px]"></span>
                <span class="relative z-10 block px-2 sm:px-6 py-3 rounded-sm">
                    <div class="relative z-10 flex items-center space-x-2 justify-center">
                        <span class="transition-all duration-500 group-hover:translate-x-1">Withdraw </span>
                        <svg id="svg1-icon" class="w-6 h-6 transition-transform duration-500 group-hover:translate-x-1" data-slot="icon" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path clip-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" fill-rule="evenodd"></path>
                        </svg>
                    </div>
                </span>
            </button>
        </div>
         <!-- button Topup Balance start -->
        <div class="flex items-center justify-center max-w-fit mx-auto my-10 gap-2">
        <a href="{{ route('ftransferBalance') }}" class="w-full relative inline-block p-px font-semibold leading-none text-white cursor-pointer rounded-sm">
            <span class="absolute inset-0 rounded-full bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 p-[2px]"></span>
            <span class="relative z-10 block px-6 py-3 rounded-sm">
                <div class="relative z-10 flex items-center space-x-2 justify-center">
                    <span class="transition-all duration-500 group-hover:translate-x-1">Transfer</span>
                    <svg class="w-6 h-6 transition-transform duration-500 group-hover:translate-x-1" data-slot="icon" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path clip-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" fill-rule="evenodd"></path>
                    </svg>
                </div>
            </span>
        </a>
    </div>
    <!-- button Topup Balance end -->
    </div>

   

    <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] relative w-full h-full mt-10">
        <div class="overflow-x-auto">
            <table id="withdrawalsTable" class="w-full text-left border-collapse" style="padding-top: 15px;">
                <thead>
                    <tr class="bg-white bg-opacity-10 text-white">
                        <th class="px-4 py-2">Sr.</th>
                        <th class="px-4 py-2">Amount</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Transaction ID</th>
                        <th class="px-4 py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['withdraw'] as $key => $value)
                    <tr>
                        <td class="text-nowrap mr-3 px-4 py-2 flex items-center">
                            <span>{{ $key + 1 }}</span>
                        </td>
                        <td class="text-nowrap px-4 py-2">{{ $value['amount'] }}</td>
                        <td class="text-nowrap px-4 py-2 {{ $value['status'] == 1 ? 'text-green-400' : ($value['status'] == 2 ? 'text-red-300' : 'text-yellow-400') }}">{{ $value['status'] == 1 ? "Complete" : ($value['status'] == 2 ? "Reject" : "Pending (Queue ". $data['queue'].")") }}</td>
                        @if($value['status'] == 1)
                        @if($value['transaction_hash'] == "BY COMPOUND")
                        <td class="text-nowrap px-4 py-2 text-yellow-400">BY COMPOUND</td>
                        @else
                        <td class="text-nowrap px-4 py-2"><a href="https://polygonscan.com/tx/{{ $value['transaction_hash'] }}" class="text-blue-600 flex items-center gap-2" target="_blank">View <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="Interface / External_Link">
                                        <path id="Vector" d="M10.0002 5H8.2002C7.08009 5 6.51962 5 6.0918 5.21799C5.71547 5.40973 5.40973 5.71547 5.21799 6.0918C5 6.51962 5 7.08009 5 8.2002V15.8002C5 16.9203 5 17.4801 5.21799 17.9079C5.40973 18.2842 5.71547 18.5905 6.0918 18.7822C6.5192 19 7.07899 19 8.19691 19H15.8031C16.921 19 17.48 19 17.9074 18.7822C18.2837 18.5905 18.5905 18.2839 18.7822 17.9076C19 17.4802 19 16.921 19 15.8031V14M20 9V4M20 4H15M20 4L13 11" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                </svg></a></td>
                        @endif
                        @else
                        <td class="text-nowrap px-4 py-2 text-yellow-400">No Transaction Hash</td>
                        @endif
                        <td class="text-nowrap px-4 py-2 text-[#30b8f5]">{{ date('d-m-Y H:i', strtotime($value['created_on'])) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
<div data-dialog-backdrop="dialog" data-dialog-backdrop-close="true" class="pointer-events-none fixed inset-0 z-[999] grid h-screen w-screen place-items-center bg-black bg-opacity-60 opacity-0 backdrop-blur-sm transition-opacity duration-300 overflow-auto p-2">
    <div data-dialog="dialog" class="text-white relative text-white m-4 p-4 w-full max-w-xl shadow-sm bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 rounded-md p-px" style="max-height: calc(100% - 0px);">
        <div class="bg-[#101735] rounded-md p-5">
            <div class="flex items-start justify-between">
                <h2 class="flex shrink-0 items-center pb-4 text-xl font-semibold text-gradient">
                    Withdraw
                </h2>
                <button data-ripple-dark="true" data-dialog-close="true" class="relative h-8 w-8 bg-white bg-opacity-10 flex items-center justify-center select-none rounded-lg text-center" type="button">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="relative border-t border-[#1d2753] pt-4 leading-normal font-light">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-2 gap-5 mb-5">
                    <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
                        <div class="flex items-center space-x-3 w-full gap-1 bg-[#131c45] rounded-2xl p-4 h-full">
                            <div class="text-2xl flex items-center justify-center w-12 h-12 min-w-12 min-h-12 rounded-full border border-white border-opacity-15 bg-white bg-opacity-10 text-center">1</div>
                            <div class="w-full">
                                <h3 class="text-base mb-2 opacity-75 leading-none">Available balance</h3>
                                <span class="text-base font-semibold">${{$data['availableBalance']}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
                        <div class="flex items-center space-x-3 w-full gap-1 bg-[#131c45] rounded-2xl p-4 h-full">
                            <div class="text-2xl flex items-center justify-center w-12 h-12 min-w-12 min-h-12 rounded-full border border-white border-opacity-15 bg-white bg-opacity-10 text-center">2</div>
                            <div class="w-full">
                                <h3 class="text-base mb-2 opacity-75 leading-none">Pending Balance</h3>
                                <span class="text-base font-semibold">${{$data['pendingWithdraw']}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <form class="relative" method="post" action="{{route('fwithdrawProcess')}}" id="withdraw-process-form">
                    @method('POST')
                    @csrf
                    <!-- usdt -->
                    <!-- <div class="relative">
                    <label for="usdt" class="block text-xs text-white text-opacity-70 font-medium mb-2">Withdraw In</label>
                    <div class="relative mb-4 flex items-center justify-between border border-white border-opacity-5 p-3 rounded gap-3 bg-[#131c45] bg-opacity-50">
                        <div class="inline-flex items-center">
                            <label class="relative flex items-center cursor-pointer" for="usdt">
                                <input id="usdt" name="usdt" type="radio" class="peer h-5 w-5 cursor-pointer appearance-none rounded-full border border-slate-300 checked:border-slate-400 transition-all">
                                <span class="absolute bg-white w-3 h-3 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity duration-200 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                </span>
                            </label>
                            <label class="ml-2 text-white cursor-pointer text-sm uppercase" for="usdt">USDT</label>
                        </div>
                    </div>
                </div> -->
                    <!-- amount -->
                    <div class="relative">
                        <label for="amount" class="block text-xs text-white text-opacity-70 font-medium mb-2">Enter Amount</label>
                        <div class="relative mb-4 flex items-center justify-between border border-white border-opacity-5 p-3 rounded gap-3 bg-[#131c45] bg-opacity-50">
                            <svg class="w-7 h-7 min-w-7 min-h-7" viewBox="0 0 24 24" fill="none">
                                <path d="M21 6V3.50519C21 2.92196 20.3109 2.61251 19.875 2.99999C19.2334 3.57029 18.2666 3.57029 17.625 2.99999C16.9834 2.42969 16.0166 2.42969 15.375 2.99999C14.7334 3.57029 13.7666 3.57029 13.125 2.99999C12.4834 2.42969 11.5166 2.42969 10.875 2.99999C10.2334 3.57029 9.26659 3.57029 8.625 2.99999C7.98341 2.42969 7.01659 2.42969 6.375 2.99999C5.73341 3.57029 4.76659 3.57029 4.125 2.99999C3.68909 2.61251 3 2.92196 3 3.50519V14M21 10V20.495C21 21.0782 20.3109 21.3876 19.875 21.0002C19.2334 20.4299 18.2666 20.4299 17.625 21.0002C16.9834 21.5705 16.0166 21.5705 15.375 21.0002C14.7334 20.4299 13.7666 20.4299 13.125 21.0002C12.4834 21.5705 11.5166 21.5705 10.875 21.0002C10.2334 20.4299 9.26659 20.4299 8.625 21.0002C7.98341 21.5705 7.01659 21.5705 6.375 21.0002C5.73341 20.4299 4.76659 20.4299 4.125 21.0002C3.68909 21.3876 3 21.0782 3 20.495V18" stroke="#b3b3b4" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M7.5 15.5H11.5M16.5 15.5H14.5" stroke="#b3b3b4" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M16.5 12H12.5M7.5 12H9.5" stroke="#b3b3b4" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M7.5 8.5H16.5" stroke="#b3b3b4" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <input type="text" name="amount" id="amount" autocomplete="off" placeholder="Enter Amount  (min withdraw ${{$data['setting']['min_withdraw']}})" required="required" class="border-l pl-4 border-white border-opacity-15 outline-none shadow-none bg-transparent w-full block text-base" onkeyup="setAdminFees(this.value);">
                        </div>
                    </div>
                    <!-- Admin Fees -->
                    <div class="relative">
                        <label for="adminfees" class="block text-xs text-white text-opacity-70 font-medium mb-2">Admin Fees {{$data['setting']['admin_fees'] - 0.5}}% | Withdrawal Fees - 0.5% (Minimum $0.5)</label>
                        <div class="relative mb-4 flex items-center justify-between border border-white border-opacity-5 p-3 rounded gap-3 bg-[#131c45] bg-opacity-50">
                            <svg class="w-7 h-7 min-w-7 min-h-7" viewBox="0 0 24 24" fill="none">
                                <path d="M21 6V3.50519C21 2.92196 20.3109 2.61251 19.875 2.99999C19.2334 3.57029 18.2666 3.57029 17.625 2.99999C16.9834 2.42969 16.0166 2.42969 15.375 2.99999C14.7334 3.57029 13.7666 3.57029 13.125 2.99999C12.4834 2.42969 11.5166 2.42969 10.875 2.99999C10.2334 3.57029 9.26659 3.57029 8.625 2.99999C7.98341 2.42969 7.01659 2.42969 6.375 2.99999C5.73341 3.57029 4.76659 3.57029 4.125 2.99999C3.68909 2.61251 3 2.92196 3 3.50519V14M21 10V20.495C21 21.0782 20.3109 21.3876 19.875 21.0002C19.2334 20.4299 18.2666 20.4299 17.625 21.0002C16.9834 21.5705 16.0166 21.5705 15.375 21.0002C14.7334 20.4299 13.7666 20.4299 13.125 21.0002C12.4834 21.5705 11.5166 21.5705 10.875 21.0002C10.2334 20.4299 9.26659 20.4299 8.625 21.0002C7.98341 21.5705 7.01659 21.5705 6.375 21.0002C5.73341 20.4299 4.76659 20.4299 4.125 21.0002C3.68909 21.3876 3 21.0782 3 20.495V18" stroke="#b3b3b4" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M7.5 15.5H11.5M16.5 15.5H14.5" stroke="#b3b3b4" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M16.5 12H12.5M7.5 12H9.5" stroke="#b3b3b4" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M7.5 8.5H16.5" stroke="#b3b3b4" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <input type="text" name="admin_charge" readonly id="adminFees" placeholder="0" value="0" required="required" class="border-l pl-4 border-white border-opacity-15 outline-none shadow-none bg-transparent w-full block text-base">
                        </div>
                    </div>
                    <!-- Your final Amount -->
                    <div class="relative">
                        <label for="yourfinalamount" class="block text-xs text-white text-opacity-70 font-medium mb-2">Your final Amount</label>
                        <div class="relative mb-4 flex items-center justify-between border border-white border-opacity-5 p-3 rounded gap-3 bg-[#131c45] bg-opacity-50">
                            <svg class="w-7 h-7 min-w-7 min-h-7" viewBox="0 0 24 24" fill="none">
                                <path d="M21 6V3.50519C21 2.92196 20.3109 2.61251 19.875 2.99999C19.2334 3.57029 18.2666 3.57029 17.625 2.99999C16.9834 2.42969 16.0166 2.42969 15.375 2.99999C14.7334 3.57029 13.7666 3.57029 13.125 2.99999C12.4834 2.42969 11.5166 2.42969 10.875 2.99999C10.2334 3.57029 9.26659 3.57029 8.625 2.99999C7.98341 2.42969 7.01659 2.42969 6.375 2.99999C5.73341 3.57029 4.76659 3.57029 4.125 2.99999C3.68909 2.61251 3 2.92196 3 3.50519V14M21 10V20.495C21 21.0782 20.3109 21.3876 19.875 21.0002C19.2334 20.4299 18.2666 20.4299 17.625 21.0002C16.9834 21.5705 16.0166 21.5705 15.375 21.0002C14.7334 20.4299 13.7666 20.4299 13.125 21.0002C12.4834 21.5705 11.5166 21.5705 10.875 21.0002C10.2334 20.4299 9.26659 20.4299 8.625 21.0002C7.98341 21.5705 7.01659 21.5705 6.375 21.0002C5.73341 20.4299 4.76659 20.4299 4.125 21.0002C3.68909 21.3876 3 21.0782 3 20.495V18" stroke="#b3b3b4" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M7.5 15.5H11.5M16.5 15.5H14.5" stroke="#b3b3b4" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M16.5 12H12.5M7.5 12H9.5" stroke="#b3b3b4" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M7.5 8.5H16.5" stroke="#b3b3b4" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <input type="text" readonly id="yourfinalamount" placeholder="Your final Amount" value="0" required="required" class="border-l pl-4 border-white border-opacity-15 outline-none shadow-none bg-transparent w-full block text-base">
                        </div>
                    </div>


                    <!-- button start -->
                    <input type="hidden" id="rScript" name="rScript">
                    <input type="hidden" id="rsScript" name="rsScript">
                    <input type="hidden" id="rsvScript" name="rsvScript">
                    <input type="hidden" id="hashedMessageScript" name="hashedMessageScript">
                    <input type="hidden" id="walletAddressScript" name="walletAddressScript">
                    <div class="flex items-center justify-center mt-6 relative group max-w-fit mx-auto">
                        <button class="w-full relative inline-block p-px font-semibold leading-none text-white cursor-pointer rounded-sm" type="button" onclick="processWithdraw(this);">
                            <span class="absolute inset-0 rounded-full bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 p-[2px]"></span>
                            <span class="relative z-10 block px-6 py-3 rounded-sm">
                                <div class="relative z-10 flex items-center space-x-2 justify-center">
                                    <span class="transition-all duration-500 group-hover:translate-x-1">Withdraw </span>
                                    <!-- First SVG (will be hidden on click) -->
                                    <svg id="svg1-icon" class="w-6 h-6 transition-transform duration-500 group-hover:translate-x-1" data-slot="icon" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                        <path clip-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" fill-rule="evenodd"></path>
                                    </svg>
                                    <!-- Second SVG (initially hidden) -->
                                    <svg id="svg2-icon" class="w-6 h-6 transition-transform duration-500 group-hover:translate-x-1 hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                                        <circle fill="#ffffff" stroke="#ffffff" stroke-width="15" r="15" cx="40" cy="65">
                                            <animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.4"></animate>
                                        </circle>
                                        <circle fill="#ffffff" stroke="#ffffff" stroke-width="15" r="15" cx="100" cy="65">
                                            <animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.2"></animate>
                                        </circle>
                                        <circle fill="#ffffff" stroke="#ffffff" stroke-width="15" r="15" cx="160" cy="65">
                                            <animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="0"></animate>
                                        </circle>
                                    </svg>
                                </div>
                            </span>
                        </button>
                    </div>
                    <!-- button end -->
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="{{asset('web3/ethers.umd.min.js')}}"></script>

<script src="{{asset('web3/web3.min.js')}}"></script>

<script src="{{asset('web3/web3.js')}}"></script>
<script type="text/javascript">
    let ogadminFees = {{$data['setting']['admin_fees']}};

    function setAdminFees(amount) {
        let adminFees = (amount * ogadminFees) / 100;

        document.getElementById('adminFees').value = adminFees;

        document.getElementById('yourfinalamount').value = (amount - adminFees);

    }
</script>
<script type="text/javascript">
    async function processWithdraw(btn) {

        try {
            event.preventDefault();
            btn.disabled = true;
            // Show loader
            document.getElementById('svg1-icon').classList.add('hidden');

            document.getElementById('svg2-icon').classList.remove('hidden');

            var walletAddress = await doConnect();

            var storedWalletAddress = "{{$data['user']['wallet_address']}}";

            if (walletAddress.toLowerCase() !== storedWalletAddress.toLowerCase()) {
                alert("Wallet Address Not Matched.")
                btn.disabled = false;
                // Show loader
                document.getElementById('svg1-icon').classList.remove('hidden');

                document.getElementById('svg2-icon').classList.add('hidden');
                return;
            }

            let finalAmount = document.getElementById('yourfinalamount').value;

            if (finalAmount <= 0) {
                showToast("error", "Please enter valid amount");
                btn.disabled = false;
                // Show loader
                document.getElementById('svg1-icon').classList.remove('hidden');

                document.getElementById('svg2-icon').classList.add('hidden');
                return false;
            }

            // message to sign
            const message = `withdraw-${walletAddress}-amount-${ethers.utils.parseEther(finalAmount)}`;
            console.log({
                message
            });

            // hash message
            const hashedMessage = Web3.utils.sha3(message);
            console.log({
                hashedMessage
            });

            // sign hashed message

            swal({
                    text: 'Confirm Request For Withdrawal.\n\nThe request transaction will take 5-10 minutes to update status on Polygon Chain, if you get any errors, do try after 10 mins from your request.\nClick on request to proceed.\n\nRegards,\nTeam Doodles',
                    button: {
                        text: "Request",
                        closeModal: false,
                    },
                })
                .then(async (confirmed) => {
                    if (confirmed) {
                        return await ethereum.request({
                            method: "personal_sign",
                            params: [hashedMessage, walletAddress],
                        });
                    } else {
                        return null
                    }
                }).then((signature) => {
                    if (!signature) {
                        btn.disabled = false;
                        // Show loader
                        document.getElementById('svg1-icon').classList.remove('hidden');

                        document.getElementById('svg2-icon').classList.add('hidden');
                        swal("Request declined!", "The signature was declined by the user", "error")
                        return;
                    }
                    swal("Request added successfully", "Withdraw request was added successfully!", "success")

                    const r = signature.slice(0, 66);
                    const s = "0x" + signature.slice(66, 130);
                    const v = parseInt(signature.slice(130, 132), 16);
                    console.log({
                        r,
                        s,
                        v
                    });

                    document.getElementById('rScript').value = r;
                    document.getElementById('rsScript').value = s;
                    document.getElementById('rsvScript').value = v;
                    document.getElementById('hashedMessageScript').value = hashedMessage;
                    document.getElementById('walletAddressScript').value = walletAddress;

                    document.getElementById("withdraw-process-form").submit();
                }).catch((err) => {
                    btn.disabled = false;
                    // Show loader
                    document.getElementById('svg1-icon').classList.remove('hidden');

                    document.getElementById('svg2-icon').classList.add('hidden');
                    swal(`Error while requesting`, `${err['data'] ? err['data']['message']: err['message']}`, "error")
                })

        } catch (err) {
            btn.disabled = false;
            // Show loader
            document.getElementById('svg1-icon').classList.remove('hidden');

            document.getElementById('svg2-icon').classList.add('hidden');
            showToast("warning", err);
        }

    }
</script>
@endsection