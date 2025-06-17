@extends('layouts.app')

@section('title', 'Activate Package (USDT)')

@section('content')
<section class="w-full p-3 md:p-8 mx-auto max-w-[1400px]">
    <div class="grid grid-cols-1 gap-5 relative z-10">
        <!-- <div class="grid grid-cols-1 md:my-4"></div> -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mt-4">
            <div class="grid-cols-1 grid gap-5">
                <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] relative">
                    <h3 class="font-bold text-xl md:text-2xl mb-2">Activate Package By TOPUP Balance</h3>
                    <div class="bg-white bg-opacity-20 p-4 leading-none rounded flex items-center justify-between mb-4">
                        <p class="mr-2">Topup Balance</p>
                        <span>${{$data['user']['topup_balance']}}</span>
                    </div>
                    <form class="relative" method="post" action="{{route('process.package')}}">
                        @method('POST')
                        @csrf
                        <!-- Calculator -->
                        <div class="relative mb-8 flex items-center justify-between border border-white border-opacity-30 p-3 rounded gap-3 bg-black bg-opacity-50">
                            <svg class="w-7 h-7 min-w-7 min-h-7" viewBox="0 0 24 24" fill="none">
                                <path d="M21 12C21 16.714 21 19.0711 19.682 20.5355C18.364 22 16.2426 22 12 22C7.75736 22 5.63604 22 4.31802 20.5355C3 19.0711 3 16.714 3 12C3 7.28595 3 4.92893 4.31802 3.46447C5.63604 2 7.75736 2 12 2C16.2426 2 18.364 2 19.682 3.46447C20.5583 4.43821 20.852 5.80655 20.9504 8" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M7 8C7 7.53501 7 7.30252 7.05111 7.11177C7.18981 6.59413 7.59413 6.18981 8.11177 6.05111C8.30252 6 8.53501 6 9 6H15C15.465 6 15.6975 6 15.8882 6.05111C16.4059 6.18981 16.8102 6.59413 16.9489 7.11177C17 7.30252 17 7.53501 17 8C17 8.46499 17 8.69748 16.9489 8.88823C16.8102 9.40587 16.4059 9.81019 15.8882 9.94889C15.6975 10 15.465 10 15 10H9C8.53501 10 8.30252 10 8.11177 9.94889C7.59413 9.81019 7.18981 9.40587 7.05111 8.88823C7 8.69748 7 8.46499 7 8Z" stroke="#ffffff" stroke-width="1.5" />
                                <circle cx="8" cy="13" r="1" fill="#ffffff" />
                                <circle cx="8" cy="17" r="1" fill="#ffffff" />
                                <circle cx="12" cy="13" r="1" fill="#ffffff" />
                                <circle cx="12" cy="17" r="1" fill="#ffffff" />
                                <circle cx="16" cy="13" r="1" fill="#ffffff" />
                                <circle cx="16" cy="17" r="1" fill="#ffffff" />
                            </svg>
                            <input type="text" name="amount" id="amount" value="{{$data['user']['topup_balance']}}" placeholder="0.0" required="required" class="border-l pl-4 border-white border-opacity-15 outline-none shadow-none bg-transparent w-full block text-base">
                            <input type="hidden" name="unique_th" value="{{$data['form_code']}}">
                            <input type="hidden" name="transaction_hash" value="By Topup">
                        </div>

                        <!-- Enter Wallet -->
                        <div class="relative mb-4 flex items-center justify-between border border-white border-opacity-30 p-3 rounded gap-3 bg-black bg-opacity-50">
                            <svg class="w-6 h-6 min-w-6 min-h-6" viewBox="0 0 24 24" fill="none">
                                <path d="M21 6V3.50519C21 2.92196 20.3109 2.61251 19.875 2.99999C19.2334 3.57029 18.2666 3.57029 17.625 2.99999C16.9834 2.42969 16.0166 2.42969 15.375 2.99999C14.7334 3.57029 13.7666 3.57029 13.125 2.99999C12.4834 2.42969 11.5166 2.42969 10.875 2.99999C10.2334 3.57029 9.26659 3.57029 8.625 2.99999C7.98341 2.42969 7.01659 2.42969 6.375 2.99999C5.73341 3.57029 4.76659 3.57029 4.125 2.99999C3.68909 2.61251 3 2.92196 3 3.50519V14M21 10V20.495C21 21.0782 20.3109 21.3876 19.875 21.0002C19.2334 20.4299 18.2666 20.4299 17.625 21.0002C16.9834 21.5705 16.0166 21.5705 15.375 21.0002C14.7334 20.4299 13.7666 20.4299 13.125 21.0002C12.4834 21.5705 11.5166 21.5705 10.875 21.0002C10.2334 20.4299 9.26659 20.4299 8.625 21.0002C7.98341 21.5705 7.01659 21.5705 6.375 21.0002C5.73341 20.4299 4.76659 20.4299 4.125 21.0002C3.68909 21.3876 3 21.0782 3 20.495V18" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M7.5 15.5H11.5M16.5 15.5H14.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M16.5 12H12.5M7.5 12H9.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M7.5 8.5H16.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <div class="flex flex-wrap items-center gap-3 w-full">
                                <div>
                                    <label>
                                        <input type="radio" name="wallet" value="USDT" checked> USDT
                                    </label>
                                </div>

                                @if($data['registration_bonus'] >= 10 && $data['canUseActivationBonus'] == 1)
                                <div>
                                    <label>
                                        <input type="radio" name="wallet" value="registration_bonus"> Registration Bonus ({{$data['registration_bonus']}})
                                    </label>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- button Process start -->
                        <div class="flex items-center justify-center my-4 relative group">
                            <button class="w-full relative inline-block p-px font-semibold leading-none text-white cursor-pointer rounded-sm">
                                <span class="absolute inset-0 rounded-full bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 p-[2px]"></span>
                                <span class="relative z-10 block px-6 py-3 rounded-sm">
                                    <div class="relative z-10 flex items-center space-x-2 justify-center">
                                        <span class="transition-all duration-500 group-hover:translate-x-1">Process</span>
                                        <svg class="w-6 h-6 transition-transform duration-500 group-hover:translate-x-1" data-slot="icon" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                            <path clip-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" fill-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </span>
                            </button>
                        </div>
                        <!-- button Process end -->
                        <!-- button Topup Balance start -->
                        <div class="flex items-center justify-center my-4 relative group">
                            <a href="{{ url('/topup-9pay') }}" class="w-full relative inline-block p-px font-semibold leading-none text-white cursor-pointer rounded-sm">
                                <span class="absolute inset-0 rounded-full bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 p-[2px]"></span>
                                <span class="relative z-10 block px-6 py-3 rounded-sm">
                                    <div class="relative z-10 flex items-center space-x-2 justify-center">
                                        <span class="transition-all duration-500 group-hover:translate-x-1">Topup Balance</span>
                                        <svg class="w-6 h-6 transition-transform duration-500 group-hover:translate-x-1" data-slot="icon" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                            <path clip-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" fill-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </span>
                            </a>
                        </div>
                        <!-- button Topup Balance end -->
                    </form>
                </div>
            </div>
            <div class="cols-span-1 grid grid-cols-1">
                <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] relative w-full h-full">
                    <div class="p-4 md:p-5 rounded-xl w-full mx-auto border border-[#1d2753] bg-[#000] rankinginfo relative">
                        <h3 class="font-bold text-xl md:text-2xl mb-2">Packages History</h3>
                        <p class="font-normal text-lg my-1">Your referrer :</p>
                        <div class="bg-black bg-opacity-20 px-2 py-0.5 leading-none rounded flex items-center justify-between">
                            <span id="copyYourReferral" class="text-lg truncate text-ellipsis overflow-hidden">{{$data['user']['sponser_code']}}</span>
                            <button onclick="copyYourReferral(); showToast('success', 'Copied to clipboard!')" class="ml-2 p-1 border-l border-white border-opacity-20">
                                <svg class="w-6 h-6 min-w-6 min-h-6 ml-2" viewBox="0 0 1024 1024">
                                    <path fill="#ffffff" d="M768 832a128 128 0 0 1-128 128H192A128 128 0 0 1 64 832V384a128 128 0 0 1 128-128v64a64 64 0 0 0-64 64v448a64 64 0 0 0 64 64h448a64 64 0 0 0 64-64h64z" />
                                    <path fill="#ffffff" d="M384 128a64 64 0 0 0-64 64v448a64 64 0 0 0 64 64h448a64 64 0 0 0 64-64V192a64 64 0 0 0-64-64H384zm0-64h448a128 128 0 0 1 128 128v448a128 128 0 0 1-128 128H384a128 128 0 0 1-128-128V192A128 128 0 0 1 384 64z" />
                                </svg>
                            </button>
                        </div>
                        <script>
                            function copyYourReferral() {
                                const linkElement = document.getElementById("copyYourReferral");
                                if (!linkElement) {
                                    console.error("Referral code element not found!");
                                    return;
                                }
                                const link = linkElement.innerText;
                                navigator.clipboard.writeText(link).catch(() => {
                                    console.error("Failed to copy text!");
                                });
                            }
                        </script>
                    </div>
                    <div class="w-full flex-1 mt-4 text-center mx-auto">
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-2 gap-5">
                            @foreach($data['packages'] as $key => $value)
                            <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
                                <div class="flex items-center space-x-3 w-full gap-1 bg-[#101735] rounded-2xl p-4 h-full border border-white border-opacity-15">
                                    <div class="text-2xl flex items-center justify-center w-12 h-12 min-w-12 min-h-12 rounded-full border border-white border-opacity-15 bg-white bg-opacity-10 text-center">{{$key + 1}}</div>
                                    <div class="flex flex-wrap justify-between items-center w-full gap-1">
                                        <div>
                                            <h3 class="text-base mb-2 opacity-75 leading-none">#{{$key + 1}}</h3>
                                            <span class="text-base font-semibold block min-w-[90px]">${{$value['amount']}} {{$value['compound_amount'] > 0 ? " + $" . number_format($value['compound_amount'],2) : ""}}</span>
                                        </div>
                                        @if($value['status'] == 1 && $value['compound'] == 0 && $value['roi'] == 1)
                                            <form method="POST" action="{{route('fpackageCompound')}}">
                                                @csrf
                                                @method('POST')
                                                <input type="hidden" name="package_id" value="{{$value['id']}}">
                                                <button type="submit" class="rounded-full bg-gradient-to-br mt-1 from-yellow-500 via-pink-500 to-blue-500 px-2 py-1">Compound</button>
                                            </form>
                                        @endif
                                    </div>
                                    @if($value['compound'] == 1)
                                        <div class="relative w-14 h-14 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 rounded-full flex items-center justify-center ">
                                        <span class="relative z-10 font-bold">C</span>
                                        <span class="absolute top-1/2 left-1/2 transform translate-x-[-50%] translate-y-[-50%] bg-gradient-to-t from-[#222A40] via-[#101735] to-[#222A40] rounded-full z-0 block w-[calc(100%-3px)] h-[calc(100%-3px)]"></span>
                                    </div>
                                    @endif
                                    @if($value['status'] == 2)
                                        <div class="flex flex-wrap justify-between items-center w-full gap-1">
                                            <div>
                                                <h3 class="text-base mb-2 opacity-75 leading-none">Completed</h3>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-5 mt-4">
            <div class="grid-cols-1 grid gap-5">
                <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] relative space-y-4">
                    <h3 class="font-semibold text-xl md:text-2xl mb-4">Disclaimer</h3>
                    <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
                        <div class="flex items-center space-x-3 w-full gap-1 bg-[#101735] rounded-2xl p-4 h-full">
                            <div class="text-2xl flex items-center justify-center w-12 h-12 min-w-12 min-h-12 rounded-full border border-white border-opacity-15 bg-white bg-opacity-10 text-center">1</div>
                            <div class="w-full">
                                <h3 class="text-base mb-2 opacity-75 leading-none">Activate Package Usage : Only the top-up balance can be used for activating packages.</h3>
                            </div>
                        </div>
                    </div>
                    <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
                        <div class="flex items-center space-x-3 w-full gap-1 bg-[#101735] rounded-2xl p-4 h-full">
                            <div class="text-2xl flex items-center justify-center w-12 h-12 min-w-12 min-h-12 rounded-full border border-white border-opacity-15 bg-white bg-opacity-10 text-center">2</div>
                            <div class="w-full">
                                <h3 class="text-base mb-2 opacity-75 leading-none">Exclusive Use of Top-Up Balance : The top-up balance is not applicable for any other purposes.</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection