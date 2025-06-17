@extends('layouts.app')
@section('title', 'Home')

@section('content')
<section class="w-full p-3 md:p-8 mx-auto max-w-[1400px]">
    <div class="grid grid-cols-1 gap-5 relative z-10">
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
            <div class="cols-span-1 grid gap-5 grid-cols-1">
                <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] relative">
                    <!-- Reward Section -->
                    <div class="w-full relative" id="achievementreward">
                        <div class="flex items-center justify-center lg:justify-between">
                            <div class="w-full space-y-3 flex flex-wrap gap-2 items-center justify-center">
                                <div class="flex flex-wrap flex-auto items-center gap-4">
                                    <img id="addclsspb16" class="object-contain max-h-[120px] max-w-[120px] w-full absolute top-1/2 right-0 transform -translate-y-1/2 -z-1" src={{ asset('assets/images/rankbgbox.svg') }} />
                                    <div class="flex flex-col relative z-10">
                                        <div id="achievedText" class="font-semibold text-xl leading-none font-semibold text-white mb-3 tracking-wide text-gradient">Activation Bonus</div>
                                        @if($data['registration_bonus'] == 0)
                                        <div id="achievedMessage" class="bg-[#282f4a]/95 rounded-md px-4 py-4 hidden text-sm md:text-base leading-none text-white text-opacity-80">
                                            @else
                                            <div id="achievedMessage" class="bg-[#282f4a]/95 rounded-md px-4 py-4 text-sm md:text-base leading-none text-white text-opacity-80">
                                                @endif
                                                @if($data['registration_bonus'] == 1)
                                                Activation Bonus Achieved $100
                                                @elseif($data['registration_bonus'] == 2)
                                                Activation Bonus Not Achieved
                                                @else
                                                Activation Bonus Not Achieved
                                                @endif
                                            </div>
                                            <!-- Countdown Timer Display -->
                                            <div id="reward-timer" class="flex gap-2 sm:gap-3 text-white text-sm font-medium"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Countdown Script -->
                        @if($data['registration_bonus'] == 0)
                        @php
                        $endDate = \Carbon\Carbon::createFromFormat('d M Y h:i A', date('d M Y h:i A', strtotime($data['user']['created_on'])));
                        $endDate->addDays(3);
                        @endphp
                        <script>
                            const rewardEndTime = new Date("{{ $endDate->format('Y-m-d H:i:s') }}").getTime();

                            function updateRewardTimer() {
                                const now = new Date().getTime();
                                const distance = rewardEndTime - now;
                                const timerContainer = document.getElementById("reward-timer");

                                if (distance <= 0) {
                                    document.getElementById("achievedMessage").classList.remove("hidden");
                                    document.getElementById("reward-timer").classList.add("hidden");

                                    document.getElementById("achievedText").innerHTML = "Achieve Registration Bonus";
                                    clearInterval(timerInterval);
                                } else {
                                    document.getElementById("addclsspb16").classList.remove("pb-16");
                                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                    timerContainer.innerHTML = `
                                    <div class="bg-[#282f4a]/95 rounded-md px-3 py-2 text-center min-w-[60px]">
                                        <div class="text-base sm:text-2xl font-bold">${days}</div>
                                        <div class="text-[8px] sm:text-xs uppercase text-gradient tracking-wide">D</div>
                                    </div>
                                    <div class="bg-[#282f4a]/95 rounded-md px-3 py-2 text-center min-w-[60px]">
                                        <div class="text-base sm:text-2xl font-bold">${hours}</div>
                                        <div class="text-[8px] sm:text-xs uppercase text-gradient tracking-wide">H</div>
                                    </div>
                                    <div class="bg-[#282f4a]/95 rounded-md px-3 py-2 text-center min-w-[60px]">
                                        <div class="text-base sm:text-2xl font-bold">${minutes}</div>
                                        <div class="text-[8px] sm:text-xs uppercase text-gradient tracking-wide">M</div>
                                    </div>
                                    <div class="bg-[#282f4a]/95 rounded-md px-3 py-2 text-center min-w-[60px]">
                                        <div class="text-base sm:text-2xl font-bold">${seconds}</div>
                                        <div class="text-[8px] sm:text-xs uppercase text-gradient tracking-wide">S</div>
                                    </div>
                                `;
                                }
                            }

                            const timerInterval = setInterval(updateRewardTimer, 1000); // Update every second
                            updateRewardTimer(); // Initial call
                        </script>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="cols-span-1 grid gap-5 grid-cols-1">
                            <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] overflow-hidden relative z-10">
                                <h3 class="font-bold text-sm md:text-base mb-2">Your Rank -
                                    <span class="font-large">{{$data['user']['rank']}}</span>
                                </h3>
                                <h3 class="font-normal text-sm md:text-base my-1">Team Business - <span class="font-light">{{$data['user']['my_business']}}</span></h3>
                                <img src="{{ asset('assets/images/rankbg.png') }}" width="120" height="120" class="absolute -bottom-5 left-0 w-full h-auto object-contain object-bottom -z-10 mix-blend-lighten opacity-40 [filter:hue-rotate(45deg)] [transform:scaleY(-0.7)]" alt="rankbg">
                            </div>
                        </div>
                        <div class="cols-span-1 grid gap-5 grid-cols-1">
                            <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] overflow-hidden relative z-10">
                                <h3 class="font-bold text-sm md:text-base mb-5">Level - <span class="text-sm sm:text-base">{{$data['user']['level'] == 0 ? "No Level" : $data['user']['level']}}</span></h3>
                                <div class="flex items-center justify-start gap-3 text-xl sm:text-2xl text-white">
                                    <div class="relative w-14 h-14 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 rounded-full flex items-center justify-center ">
                                        <span class="relative z-10">1</span>
                                        <span class="absolute top-1/2 left-1/2 transform translate-x-[-50%] translate-y-[-50%] bg-gradient-to-t from-[#222A40] via-[#101735] to-[#222A40] rounded-full z-0 block w-[calc(100%-3px)] h-[calc(100%-3px)]"></span>
                                    </div>
                                    <div class="relative w-14 h-14 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 rounded-full flex items-center justify-center ">
                                        <span class="relative z-10">2</span>
                                        <span class="absolute top-1/2 left-1/2 transform translate-x-[-50%] translate-y-[-50%] bg-gradient-to-t from-[#222A40] via-[#101735] to-[#222A40] rounded-full z-0 block w-[calc(100%-3px)] h-[calc(100%-3px)]"></span>
                                    </div>
                                    <div class="relative w-14 h-14 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 rounded-full flex items-center justify-center ">
                                        <span class="relative z-10">3</span>
                                        <span class="absolute top-1/2 left-1/2 transform translate-x-[-50%] translate-y-[-50%] bg-gradient-to-t from-[#222A40] via-[#101735] to-[#222A40] rounded-full z-0 block w-[calc(100%-3px)] h-[calc(100%-3px)]"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid-cols-1 grid gap-5">
                    <div class="p-4 md:p-6 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] relative overflow-hidden text-left">
                        <h3 class="font-semibold text-xl -mt-3 text-gradient mb-3">Welcome, {{$data['user']['name']}}!</h3>
                        <div class="rankingboxmn flex flex-col gap-2 w-full relative z-10 pr-10">
                            @foreach($data['ranks'] as $key => $value)
                            @php
                            // Adjust width: Rank 1 => 100%, Rank 5 => 40%
                            $maxWidth = 100;
                            $minWidth = 40;
                            $rankId = $value['id'];
                            $totalRanks = 5;

                            // Calculate step between each rank
                            $step = ($maxWidth - $minWidth) / ($totalRanks - 1);
                            $width = $maxWidth - ($rankId - 1) * $step;
                            $width = round($width); // clean percentage value
                            @endphp
                            <div class="gridbox {{$data['user']['rank_id'] == $value['id'] ? '' : 'opacity-30 pointer-events-none'}} w-[{{$width}}%] ml-auto flex items-center justify-between gap-2 py-1.5 px-3 pl-1.5 relative bg-gradient-to-r from-violet-200 to-pink-200 text-black text-center rounded-full">
                                <img src="{{ asset('assets/images/rank/'.$value['id'].'.webp') }}" width="120" height="120" class="absolute transform -translate-y-1/2 top-1/2 -right-14 w-auto h-14 object-contain p-1.5" alt="{{$value['name']}}">
                                <span class="text-sm sm:text-xl flex items-center justify-center leading-none rounded-full w-8 h-8 min-w-8 min-h-8 bg-black bg-opacity-20 text-white">{{$value['id']}}</span>
                                <span class="text-sm sm:text-base leading-none">{{$value['name']}}</span>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
                <!-- Referral Link Card -->
                <div class="relative bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 rounded-md flex items-center justify-center p-px">
                    <div class="flex items-center space-x-3 w-full bg-[#101735] rounded-md p-4">
                        <img src={{ asset('assets/images/logoface.webp') }} width="64" height="48" alt="Logo" class="w-8 sm:w-12 h-auto max-h-8 sm:max-h-12">
                        <div class="w-full" style="max-width:calc(100% - 60px)">
                            <h3 class="text-base leading-none my-3">AdDoodles Referral Link</h3>
                            <div class="bg-white bg-opacity-5 px-2 py-0.5 leading-none rounded flex items-center justify-between">
                                <span id="referral-link" class="text-xs text-xs truncate text-ellipsis overflow-hidden">https://{{ request()->getHost() }}/register?sponser_code=@if(Session::has('refferal_code')){{ Session::get('refferal_code')}}@endif</span>
                                <button onclick="copyReferral(); showToast('success', 'Copied to clipboard!')" class="ml-2 p-1 border-l border-white border-opacity-20">
                                    <svg class="w-6 h-6 min-w-6 min-h-6 ml-2" viewBox="0 0 1024 1024">
                                        <path fill="#ffffff" d="M768 832a128 128 0 0 1-128 128H192A128 128 0 0 1 64 832V384a128 128 0 0 1 128-128v64a64 64 0 0 0-64 64v448a64 64 0 0 0 64 64h448a64 64 0 0 0 64-64h64z" />
                                        <path fill="#ffffff" d="M384 128a64 64 0 0 0-64 64v448a64 64 0 0 0 64 64h448a64 64 0 0 0 64-64V192a64 64 0 0 0-64-64H384zm0-64h448a128 128 0 0 1 128 128v448a128 128 0 0 1-128 128H384a128 128 0 0 1-128-128V192A128 128 0 0 1 384 64z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    function copyReferral() {
                        const linkElement = document.getElementById("referral-link");

                        if (!linkElement) {
                            console.error("Referral link element not found!");
                            return;
                        }

                        const link = linkElement.innerText;
                        navigator.clipboard.writeText(link).catch(() => {
                            console.error("Failed to copy text!");
                        });
                    }
                </script>
                <!-- Download PDF -->
                <div class="relative bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 rounded-md flex items-center justify-center p-px">
                    <div class="flex items-center space-x-3 w-full bg-[#101735] rounded-md p-4">
                        <img src={{ asset('assets/images/icons/download-pdf-icon.webp') }} width="64" height="48" alt="Logo" class="w-8 sm:w-12 h-auto max-h-8 sm:max-h-12">
                        <div class="w-full">
                            <h3 class="text-base leading-none my-3">Download PDF</h3>
                            <div class="bg-white bg-opacity-5 px-2 py-0.5 leading-none rounded flex items-center justify-between">
                                <span class="text-xs">Download AdDoodles Presentation</span>
                                <a href="{{ asset('assets/presentation/DOODLE-NFT.pdf') }}" download="doodles-ad.pdf" target="_blank" onclick="showToast('success', 'PDF download successfully!')" class="ml-2 p-1 border-l border-white border-opacity-20">
                                    <svg class="w-6 h-6 min-w-6 min-h-6 ml-2" viewBox="0 0 24 24" fill="none">
                                        <path d="M12.5535 16.5061C12.4114 16.6615 12.2106 16.75 12 16.75C11.7894 16.75 11.5886 16.6615 11.4465 16.5061L7.44648 12.1311C7.16698 11.8254 7.18822 11.351 7.49392 11.0715C7.79963 10.792 8.27402 10.8132 8.55352 11.1189L11.25 14.0682V3C11.25 2.58579 11.5858 2.25 12 2.25C12.4142 2.25 12.75 2.58579 12.75 3V14.0682L15.4465 11.1189C15.726 10.8132 16.2004 10.792 16.5061 11.0715C16.8118 11.351 16.833 11.8254 16.5535 12.1311L12.5535 16.5061Z" fill="#ffffff" />
                                        <path d="M3.75 15C3.75 14.5858 3.41422 14.25 3 14.25C2.58579 14.25 2.25 14.5858 2.25 15V15.0549C2.24998 16.4225 2.24996 17.5248 2.36652 18.3918C2.48754 19.2919 2.74643 20.0497 3.34835 20.6516C3.95027 21.2536 4.70814 21.5125 5.60825 21.6335C6.47522 21.75 7.57754 21.75 8.94513 21.75H15.0549C16.4225 21.75 17.5248 21.75 18.3918 21.6335C19.2919 21.5125 20.0497 21.2536 20.6517 20.6516C21.2536 20.0497 21.5125 19.2919 21.6335 18.3918C21.75 17.5248 21.75 16.4225 21.75 15.0549V15C21.75 14.5858 21.4142 14.25 21 14.25C20.5858 14.25 20.25 14.5858 20.25 15C20.25 16.4354 20.2484 17.4365 20.1469 18.1919C20.0482 18.9257 19.8678 19.3142 19.591 19.591C19.3142 19.8678 18.9257 20.0482 18.1919 20.1469C17.4365 20.2484 16.4354 20.25 15 20.25H9C7.56459 20.25 6.56347 20.2484 5.80812 20.1469C5.07435 20.0482 4.68577 19.8678 4.40901 19.591C4.13225 19.3142 3.9518 18.9257 3.85315 18.1919C3.75159 17.4365 3.75 16.4354 3.75 15Z" fill="#ffffff" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
                <div class="grid grid-cols-2 gap-5">
                    <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] overflow-hidden relative">
                        <img src={{ asset('assets/images/icons/total-withdraw-icon.webp') }} width="64" height="48" alt="Logo" class="w-8 sm:w-12 h-auto max-h-8 sm:max-h-12">
                        <div class="w-full">
                            <h3 class="text-sm sm:text-base my-4 opacity-75 leading-none">Total Withdraw</h3>
                            <span class="text-sm sm:text-base">${{$data['total_withdraw']}}</span>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] overflow-hidden relative">
                        <img src={{ asset('assets/images/icons/self-investment.webp') }} width="64" height="48" alt="Logo" class="w-8 sm:w-12 h-auto max-h-8 sm:max-h-12">
                        <div class="w-full">
                            <h3 class="text-sm sm:text-base my-4 opacity-75 leading-none">Self Investment</h3>
                            <span class="text-sm sm:text-base">${{$data['self_investment']}}</span>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] overflow-hidden relative">
                        <img src={{ asset('assets/images/icons/team-investment.webp') }} width="64" height="48" alt="Logo" class="w-8 sm:w-12 h-auto max-h-8 sm:max-h-12">
                        <div class="w-full">
                            <h3 class="text-sm sm:text-base my-4 opacity-75 leading-none">Total Team Investment</h3>
                            <span class="text-sm sm:text-base">${{number_format($data['user']['my_business'], 2)}}</span>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] overflow-hidden relative">
                        <img src={{ asset('assets/images/icons/direct-investment.webp') }} width="64" height="48" alt="Logo" class="w-8 sm:w-12 h-auto max-h-8 sm:max-h-12">
                        <div class="w-full">
                            <h3 class="text-sm sm:text-base my-4 opacity-75 leading-none">Direct Investment</h3>
                            <span class="text-sm sm:text-base">${{number_format($data['user']['direct_business'], 2)}}</span>
                        </div>
                    </div>
                </div>
                <div class="grid-cols-1 grid relative bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 rounded-xs">
                    <div class="w-full relative z-10 p-5 p-4 rounded-2xl mx-auto border border-[#16204a] bg-[#101735] overflow-hidden space-y-4">
                        <div class="grid grid-cols-1 gap-5">
                            <div class="p-4 flex items-center gap-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#16204a] overflow-hidden relative">
                                <img src={{ asset('assets/images/icons/total-team.webp') }} width="64" height="48" alt="Logo" class="w-8 sm:w-12 h-auto max-h-8 sm:max-h-12">
                                <div class="w-full">
                                    <h3 class="text-sm sm:text-base mb-2 opacity-75 leading-none">Total Team</h3>
                                    <span class="text-sm sm:text-base">{{$data['user']['my_team']}}</span>
                                </div>
                            </div>
                            <div class="p-4 flex items-center gap-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#16204a] overflow-hidden relative">
                                <img src={{ asset('assets/images/icons/total-income-icon.webp') }} width="64" height="48" alt="Logo" class="w-8 sm:w-12 h-auto max-h-8 sm:max-h-12">
                                <div class="w-full">
                                    <h3 class="text-sm sm:text-base mb-2 opacity-75 leading-none">Total Income</h3>
                                    <span class="text-sm sm:text-base">{{$data['user']['roi_income'] + $data['user']['level_income'] + $data['user']['royalty'] + $data['user']['reward'] + $data['user']['direct_income']}}</span>
                                </div>
                            </div>
                            <div class="p-4 flex items-center gap-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#16204a] overflow-hidden relative">
                                <img src={{ asset('assets/images/income-icons/total-invest.webp') }} width="64" height="48" alt="Logo" class="w-8 sm:w-12 h-auto max-h-8 sm:max-h-12">
                                <div class="w-full">
                                    <h3 class="text-sm sm:text-base mb-2 opacity-75 leading-none">Available Balance</h3>
                                    <span class="text-sm sm:text-base">{{number_format($data['available_balance'], 2)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] overflow-hidden relative">
                        <img src={{ asset('assets/images/icons/total-directs.webp') }} width="64" height="48" alt="Logo" class="w-8 sm:w-12 h-auto max-h-8 sm:max-h-12">
                        <div class="w-full">
                            <h3 class="text-sm sm:text-base my-4 opacity-75 leading-none">Total Directs</h3>
                            <span class="text-sm sm:text-base">{{$data['user']['my_direct']}}</span>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] overflow-hidden relative">
                        <img src={{ asset('assets/images/icons/sponsor.webp') }} width="64" height="48" alt="Logo" class="w-8 sm:w-12 h-auto max-h-8 sm:max-h-12">
                        <div class="w-full">
                            <h3 class="text-sm sm:text-base my-4 opacity-75 leading-none">Sponsor</h3>
                            <span class="text-sm sm:text-base">{{$data['user']['sponser_code']}}</span>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] overflow-hidden relative">
                        <img src={{ asset('assets/images/icons/date-of-activation.webp') }} width="64" height="48" alt="Logo" class="w-8 sm:w-12 h-auto max-h-8 sm:max-h-12">
                        <div class="w-full">
                            <h3 class="text-sm sm:text-base my-4 opacity-75 leading-none">Date of Activation</h3>
                            <span class="text-sm sm:text-base">{{date('d-m-Y', strtotime($data['user']['created_on']))}}</span>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] overflow-hidden relative">
                        <img src={{ asset('assets/images/icons/total-active-directs.webp') }} width="64" height="48" alt="Logo" class="w-8 sm:w-12 h-auto max-h-8 sm:max-h-12">
                        <div class="w-full">
                            <h3 class="text-sm sm:text-base my-4 opacity-75 leading-none">Total Active Direct</h3>
                            <span class="text-sm sm:text-base">{{$data['user']['active_direct']}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
                <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] relative">
                    @include('components.trading-table')
                </div>
                <div class="relative bg-gradient-to-br from-yellow-800 via-pink-800 to-blue-800 rounded-md flex items-center justify-center p-px">
                    <div class="h-full w-full bg-[#101735] rounded-md p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-base sm:text-2xl leading-none font-semibold text-white mb-2 tracking-wide text-gradient">Your Daily Task</h2>
                                <p class="text-sm sm:text-base mb-2 opacity-75 leading-none">Last 7 Days at doodle</p>
                            </div>
                            @if($data['self_investment'] > 0)
                            <button id="viewAdsBtn" class="px-4 py-2 bg-blue-500 text-white rounded-md bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">View Ads</button>
                            @endif
                        </div>
                        <div class="mt-8 grid sm:grid-cols-2 gap-4">
                            <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] overflow-hidden relative flex items-center gap-4 text-white md:flex-row md:items-center">
                                <div class="w-max rounded-lg p-px text-white relative bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
                                    <img src="{{ $data['user']['ad_viewed'] == 0 ? asset('assets/images/wrong-icon.svg') : asset('assets/images/right-icon.svg') }}" width="64" height="48" alt="Logo" class="w-8 min-w-8 min-h-8 h-auto max-h-8 bg-[#101735] p-1.5 rounded-lg" id="todayAdViewImage">
                                </div>
                                <div>
                                    <h6 class="block font-sans text-base font-semibold leading-relaxed tracking-normal leading-none text-blue-gray-900 mb-2">
                                        Watch Today's Ad
                                    </h6>
                                    <p class="block max-w-sm font-sans text-sm font-normal leading-none opacity-50">
                                        Watch the ad to earn your daily ROI.
                                    </p>
                                </div>
                            </div>
                            @foreach($data['ads'] as $key => $value)
                            <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] overflow-hidden relative flex items-center gap-4 text-white md:flex-row md:items-center">
                                <div class="w-max rounded-lg p-px text-white relative bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
                                    <img src="{{ $value['roi_amount'] == 0 ? asset('assets/images/wrong-icon.svg') : asset('assets/images/right-icon.svg') }}" width="64" height="48" alt="Logo" class="w-8 min-w-8 min-h-8 h-auto max-h-8 bg-[#101735] p-1.5 rounded-lg">
                                </div>
                                <div>
                                    <h6 class="block font-sans text-base font-semibold leading-relaxed tracking-normal leading-none text-blue-gray-900 mb-2">
                                        {{date('d-m-Y', strtotime($value['date']))}}
                                    </h6>
                                    <p class="block max-w-sm font-sans text-sm font-normal leading-none opacity-50">
                                        {{$value['roi_amount'] > 0 ? 'Your ROI was generated' : 'Your ROI was not generated'}}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
                <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] relative">
                    <div class="h-full w-full">
                        @include('components.pie-charts')
                    </div>
                </div>
                <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] relative">
                    <div class="h-full w-full">
                        @include('components.radial-bar-chart')
                    </div>
                </div>
            </div>
        </div>
</section>
<!-- Home page Popup Modal -->
<!-- Home Page Popup Modal -->
<div id="popup" class="hidden fixed inset-0 z-[999] grid h-screen w-screen place-items-center bg-black bg-opacity-60 backdrop-blur-sm transition-opacity duration-300 overflow-auto p-2">
    <!-- <span id="closePopup" class="fixed inset-0 -z-1 h-screen w-screen"></span> -->

    <div class="relative bg-black shadow-lg overflow-auto max-h-[95vh] z-10">
        <!-- Countdown Close Button -->
        <button id="closePopup1" class="absolute top-2 right-2 h-10 w-10 bg-[#ea499a] border-white border-2 border-opacity-50 flex items-center justify-center select-none rounded-full text-white text-sm font-bold pointer-events-none z-10">
            <span id="countdown">{{$data['user']['rank_id'] * 60}}</span>
        </button>

        <!-- Static Video (Autoplays with popup) -->
        <div id="popupContent" class="p-0 w-full h-auto flex items-center justify-center">
            @if(isset($data['adCampaign']))
            <input type="hidden" name="adId" id="adId" value="{{$data['adCampaign']['id']}}">
            @if($data['adCampaign']['file_type'] == "image")
            <img id="adVideo" src="{{ asset('storage/'.$data['adCampaign']['file']) }}" width="500" height="500" alt="Ad" class="block max-h-[95vh] max-w-[600px] h-auto w-full bg-[#ffdddd] p-0.5 rounded-xl">
            @elseif($data['adCampaign']['file_type'] == "video")
            <video id="adVideo" autoplay muted loop controls disablePictureInPicture playsinline  controlsList="nodownload nofullscreen noremoteplayback" class="block max-h-[95vh] max-w-[600px] h-auto w-full bg-[#ffdddd] p-0.5 rounded-xl" oncontextmenu="return false">
                <source src="{{ asset('storage/'.$data['adCampaign']['file']) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            @else
            <iframe
                id="adVideo"
                width="700"
                height="400"
                class="max-w-full max-h-full object-center"
                src="https://www.youtube.com/embed/{{$data['adCampaign']['description']}}?autoplay=1&mute=1&controls=0&disablekb=1&modestbranding=1&rel=0&showinfo=0&iv_load_policy=3&fs=0"
                data-src="https://www.youtube.com/embed/{{$data['adCampaign']['description']}}?autoplay=1&mute=1&controls=1&disablekb=1&modestbranding=1&rel=0&showinfo=0&iv_load_policy=3&fs=0"
                title="Ad Video"
                frameborder="0"
                allow="autoplay; encrypted-media"
                allowfullscreen>
            </iframe>
            @endif
            @else
            <img id="adVideo" src="{{ asset('assets/videos/ads1.jpg') }}" width="500" height="500" alt="Ad" class="block max-h-[95vh] max-w-[600px] h-auto w-full bg-[#ffdddd] p-0.5 rounded-xl">
            @endif

        </div>
    </div>
</div>

<!-- JavaScript Logic -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const popup = document.getElementById("popup");
        const closeBtn1 = document.getElementById("closePopup1");
        const countdownSpan = document.getElementById("countdown");
        const viewAdsBtn = document.getElementById("viewAdsBtn");

        viewAdsBtn.addEventListener("click", function() {
            popup.classList.remove("hidden");
            closeBtn1.classList.remove("hidden");
            closeBtn1.classList.add("pointer-events-none");
            closeBtn1.innerHTML = `<span id="countdown">{{$data['user']['rank_id'] * 60}}</span>`;

            // 👇👇👇 Add this code for YouTube iframe start
            const adVideo = document.getElementById("adVideo");
            if (adVideo && adVideo.tagName === "IFRAME" && adVideo.dataset.src) {
                adVideo.src = adVideo.dataset.src;
            }

            let countdown = countdownSpan.innerText;

            // Start Countdown
            const countdownInterval = setInterval(() => {
                countdown--;
                document.getElementById("countdown").innerText = countdown;

                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    closeBtn1.innerHTML = `
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>`;
                    closeBtn1.classList.remove("pointer-events-none");
                }
            }, 1000);
        });

        // Close only after countdown
        closeBtn1.addEventListener("click", () => {
            if (!closeBtn1.classList.contains("pointer-events-none")) {
                makeTheAdCount();
                popup.classList.add("hidden");
            }
        });
    });
</script>
<script>
    function makeTheAdCount() {
        let x = document.getElementById("adId").value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            let respo = JSON.parse(this.responseText);

            let img = document.getElementById("todayAdViewImage");
            img.src = "/assets/images/right-icon.svg";
        }
        xhttp.open("POST", "{{route('fadViewed')}}");
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.setRequestHeader("X-CSRF-TOKEN", csrfToken);
        xhttp.send("ad_id=" + x);
    }
</script>

<script src="{{asset('web3/ethers.umd.min.js')}}"></script>

<script src="{{asset('web3/web3.min.js')}}"></script>

<script>
    @if(!Session::has('admin_user_id'))

    async function checkWalletAddress() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        // Get the stored address from wherever it's stored (e.g., local storage)
        var storedAddress = "{{ Session::get('wallet_address') }}"

        // Get the connected wallet address
        var addressConnected = await window.ethereum.request({method: 'eth_requestAccounts'}); // Replace with your code to get the connected address

        // Compare the stored and connected addresses
        if (storedAddress.toLowerCase() !== addressConnected[0].toLowerCase()) {
            // Call your function or perform the desired action
            // handleAccountChange(addressConnected); // Replace with the function you want to call
            showToast("error", "Wallet Address Mismatch! Please connect the correct wallet address.");

            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{route('flogout')}}';

            // Add CSRF token
            var token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = csrfToken;
            form.appendChild(token);

            document.body.appendChild(form);
            setTimeout(function () {
                form.submit();
            }, 300);
        }
    }

    setInterval(checkWalletAddress, 1500); // Call checkWalletAddress() every 5 seconds (5000 milliseconds)

    @endif
</script>

@endsection

<script src="{{asset('assets/js/apexcharts.js')}}"></script>