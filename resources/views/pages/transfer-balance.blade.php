@extends('layouts.app')

@section('title', 'Transfer Balance')

@section('style')
    <style type="text/css">
        button:disabled{
            opacity: 0.5;
        }
    </style>
@endsection

@section('content')
<section class="w-full p-3 md:p-8 mx-auto max-w-[1400px]">
    <div class="grid grid-cols-1 gap-5 relative z-10">
        <div class="grid grid-cols-1 xl:grid-cols-4">
            <div class="cols-span-1 xl:col-span-1"></div>
            <div class="cols-span-1 xl:col-span-2 grid-cols-1 grid">
                <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] relative">
                    <h3 class="font-bold text-xl md:text-2xl mb-4 text-gradient">Transfer Balance</h3>
                    <form class="relative" id="transferForm" method="POST" action="{{route('ftransferBalanceProcess')}}">
                        @method('POST')
                        @csrf

                        <div class="relative mb-4 flex items-center justify-between border border-white border-opacity-5 p-3 rounded gap-3 bg-[#131c45] bg-opacity-50">
                            <svg class="w-6 h-6 min-w-6 min-h-6" viewBox="0 0 24 24" fill="none">
                                <path d="M21 6V3.50519C21 2.92196 20.3109 2.61251 19.875 2.99999C19.2334 3.57029 18.2666 3.57029 17.625 2.99999C16.9834 2.42969 16.0166 2.42969 15.375 2.99999C14.7334 3.57029 13.7666 3.57029 13.125 2.99999C12.4834 2.42969 11.5166 2.42969 10.875 2.99999C10.2334 3.57029 9.26659 3.57029 8.625 2.99999C7.98341 2.42969 7.01659 2.42969 6.375 2.99999C5.73341 3.57029 4.76659 3.57029 4.125 2.99999C3.68909 2.61251 3 2.92196 3 3.50519V14M21 10V20.495C21 21.0782 20.3109 21.3876 19.875 21.0002C19.2334 20.4299 18.2666 20.4299 17.625 21.0002C16.9834 21.5705 16.0166 21.5705 15.375 21.0002C14.7334 20.4299 13.7666 20.4299 13.125 21.0002C12.4834 21.5705 11.5166 21.5705 10.875 21.0002C10.2334 20.4299 9.26659 20.4299 8.625 21.0002C7.98341 21.5705 7.01659 21.5705 6.375 21.0002C5.73341 20.4299 4.76659 20.4299 4.125 21.0002C3.68909 21.3876 3 21.0782 3 20.495V18" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M7.5 15.5H11.5M16.5 15.5H14.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M16.5 12H12.5M7.5 12H9.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M7.5 8.5H16.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <input type="text" name="refferal_code" id="refferal_code" onkeyup="getUserDetails(this.value);"  placeholder="Enter User Id." required="required" class="border-l pl-4 border-white border-opacity-15 outline-none shadow-none bg-transparent w-full block text-base">
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span style="color: white;" class="text-left min-w-[100px]" id="refferalName"></span>
                        </div>

                        <div class="relative mb-4 flex items-center justify-between border border-white border-opacity-5 p-3 rounded gap-3 bg-[#131c45] bg-opacity-50">
                            <svg class="w-6 h-6 min-w-6 min-h-6" viewBox="0 0 24 24" fill="none">
                                <path d="M21 6V3.50519C21 2.92196 20.3109 2.61251 19.875 2.99999C19.2334 3.57029 18.2666 3.57029 17.625 2.99999C16.9834 2.42969 16.0166 2.42969 15.375 2.99999C14.7334 3.57029 13.7666 3.57029 13.125 2.99999C12.4834 2.42969 11.5166 2.42969 10.875 2.99999C10.2334 3.57029 9.26659 3.57029 8.625 2.99999C7.98341 2.42969 7.01659 2.42969 6.375 2.99999C5.73341 3.57029 4.76659 3.57029 4.125 2.99999C3.68909 2.61251 3 2.92196 3 3.50519V14M21 10V20.495C21 21.0782 20.3109 21.3876 19.875 21.0002C19.2334 20.4299 18.2666 20.4299 17.625 21.0002C16.9834 21.5705 16.0166 21.5705 15.375 21.0002C14.7334 20.4299 13.7666 20.4299 13.125 21.0002C12.4834 21.5705 11.5166 21.5705 10.875 21.0002C10.2334 20.4299 9.26659 20.4299 8.625 21.0002C7.98341 21.5705 7.01659 21.5705 6.375 21.0002C5.73341 20.4299 4.76659 20.4299 4.125 21.0002C3.68909 21.3876 3 21.0782 3 20.495V18" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M7.5 15.5H11.5M16.5 15.5H14.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M16.5 12H12.5M7.5 12H9.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M7.5 8.5H16.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <input type="text" name="amount" id="amount" placeholder="0.0" required="required" class="border-l pl-4 border-white border-opacity-15 outline-none shadow-none bg-transparent w-full block text-base">
                        </div>
                        <div class="flex items-center justify-between">
                            <span style="color: white;">Available Balance</span>
                            <span style="color: white;" class="text-right min-w-[100px]" id="amountInUsdt">{{$data['user']['registration_bonus']}}</span>
                        </div>
                        <!-- button start -->
                        <div class="flex items-center justify-center mt-8 relative group">
                            <button class="w-full relative inline-block p-px font-semibold leading-none text-white cursor-pointer rounded-sm" id="transferButton" disabled>
                                <span class="absolute inset-0 rounded-full bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 p-[2px]"></span>
                                <span class="relative z-10 block px-6 py-3 rounded-sm">
                                    <div class="relative z-10 flex items-center space-x-2 justify-center">
                                        <span class="transition-all duration-500 group-hover:translate-x-1">Transfer</span>
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
            <div class="cols-span-1 xl:col-span-1"></div>
        </div>
        <div class="grid grid-cols-1 gap-5 mt-4">
            <div class="grid-cols-1 grid gap-5">
                <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] relative space-y-4">
                    <div class="overflow-x-auto">
                        <table id="withdrawalsTable" class="w-full text-left border-collapse" style="padding-top: 15px;">
                            <thead>
                                <tr class="bg-white bg-opacity-10 text-white">
                                    <th class="px-4 py-2">Sr No.</th>
                                    <th class="px-4 py-2">Amount</th>
                                    <th class="px-4 py-2">Transfer Type</th>
                                    <th class="px-4 py-2">User Id</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($data['transfer']))
                                    @foreach ($data['transfer'] as $key => $value)
                                    <tr>
                                        <td class="text-nowrap mr-3 px-4 py-2 flex items-center">{{ $key + 1 }}</td>
                                        <td class="text-nowrap px-4 py-2">${{ $value['amount'] }}</td>
                                        <td class="text-nowrap px-4 py-2 {{$value['user_id'] == $data['user']['id'] ? 'text-red-400' : 'text-green-400'}}">{{ $value['user_id'] == $data['user']['id'] ? "SEND" : "RECEIVE" }}</td>
                                        <td class="text-nowrap px-4 py-2">#{{$value['user_id'] == $data['user']['id'] ? $value['for_user_refferal_code'] : $value['user_refferal_code']}}</td>
                                        <td class="text-nowrap px-4 py-2 text-green-400">Complete</td>
                                        <td class="text-nowrap px-4 py-2 text-[#30b8f5]">{{ date('d-m-Y H:i'), strtotime($value['created_on']) }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script type="text/javascript">
    function getUserDetails(x) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                let respo = JSON.parse(this.responseText);
                if(respo.status_code == 1)
                {
                    document.getElementById("refferalName").innerHTML = respo.data;
                    document.getElementById('transferButton').disabled = false;
                }else
                {
                    document.getElementById("refferalName").innerHTML = respo.message;
                    document.getElementById('transferButton').disabled = true;
                }
            }
        xhttp.open("POST", "{{route('freferralCodeDetails')}}");
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.setRequestHeader("X-CSRF-TOKEN", csrfToken);
        xhttp.send("refferal_code="+x);
    }

    document.getElementById('transferForm').addEventListener('submit', function(event) {
        document.getElementById('transferButton').disabled = true;
    });
</script>
@endsection