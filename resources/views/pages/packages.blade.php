@extends('layouts.app')

@section('title', 'Activate Package')

@section('content')
<section class="w-full p-3 md:p-8 mx-auto max-w-[1400px]">
    <div class="grid grid-cols-1 gap-5 relative z-10">
        <!-- <div class="grid grid-cols-1 md:my-4"></div> -->
        @if(isset($data['confirmTransactionWindow']))
        <div class="data-card container mx-auto">
            <div class="p-3 md:p-6 rounded-md border border-[#1d2753] bg-[#101735] relative shadow-inner w-full mx-auto">
                <div class="border-0 border-white rounded-xl">
                    <div class="relative text-center p-3 md:p-3 shadow-inner">
                        <div class="card-data text-l md:text-xl  font-semibold text-white">System has found transaction for package activation. Please verify transaction to activate. </div>
                        <div class="text-lg text-white mx-auto my-3">Package Amount ${{$data['pending_package_amount']}}</div>
                        <div class="break-words card-data text-l md:text-xl font-semibold text-white">Transaction Hash : {{ substr($data['pending_transaction_hash'], 0, 4) }} ... {{ substr($data['pending_transaction_hash'], -4) }}
                            <button onclick="copyToClipboard('{{$data['pending_transaction_hash']}}');" class="ml-2 p-1 border-l border-white border-opacity-20">
                                <svg class="w-6 h-6 min-w-6 min-h-6 ml-2" viewBox="0 0 1024 1024">
                                    <path fill="#ffffff" d="M768 832a128 128 0 0 1-128 128H192A128 128 0 0 1 64 832V384a128 128 0 0 1 128-128v64a64 64 0 0 0-64 64v448a64 64 0 0 0 64 64h448a64 64 0 0 0 64-64h64z" />
                                    <path fill="#ffffff" d="M384 128a64 64 0 0 0-64 64v448a64 64 0 0 0 64 64h448a64 64 0 0 0 64-64V192a64 64 0 0 0-64-64H384zm0-64h448a128 128 0 0 1 128 128v448a128 128 0 0 1-128 128H384a128 128 0 0 1-128-128V192A128 128 0 0 1 384 64z" />
                                </svg>
                            </button></div>


                        <div class="flex items-center justify-center my-4 relative group">
                            <!-- onclick="activatePackage(this);" -->
                            <a id="transactionStatusLink" class="mb-5 mt-2 block text-center" href="{{route('check_package_transaction')}}">
                                <button class="w-full relative inline-block p-px font-semibold leading-none text-white cursor-pointer rounded-sm" type="button">
                                    <span class="absolute inset-0 rounded-full bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 p-[2px]"></span>
                                    <span class="relative z-10 block px-6 py-3 rounded-sm">
                                        <div class="relative z-10 flex items-center space-x-2 justify-center">
                                            <span class="transition-all duration-500 group-hover:translate-x-1">Verify Transaction</span>
                                            <svg class="w-6 h-6 transition-transform duration-500 group-hover:translate-x-1" data-slot="icon" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                                <path clip-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" fill-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </span>
                                </button>
                            </a>
                        </div>
                        <h3 class="data-des text-base text-white">Your transaction is in process it will take 2-3 minutes to process please wait. </h3>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="grid grid-cols-1 mx-auto w-full gap-5 mt-4">
            <div class="grid-cols-1 grid gap-5">
                <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] relative">
                    <h3 class="font-bold text-xl md:text-2xl mb-5 text-gradient">Doodles Packages</h3>
                    <form class="relative" id="processPackage" method="post" action="{{route('fhandlePackageTransaction')}}">
                        @method('POST')
                        @csrf
                        <!-- Calculator -->
                        <div class="relative mb-4 flex items-center justify-between border border-white border-opacity-5 p-3 rounded gap-3 bg-[#131c45] bg-opacity-50">
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
                            <input type="text" name="amount" id="packageAmount" placeholder="0.0" required="required" class="border-l pl-4 border-white border-opacity-15 outline-none shadow-none bg-transparent w-full block text-base">
                        </div>
                        <!-- button Process start -->
                        <input type="hidden" name="transaction_hash" id="transaction_hash">
                        <div class="flex items-center justify-center mb-2 mt-6 relative group">
                            <!-- onclick="activatePackage(this);" -->
                            <button class="w-full relative inline-block p-px font-semibold leading-none text-white cursor-pointer rounded-sm max-w-fit mr-auto" type="button" onclick="activatePackage(this);">
                                <span class="absolute inset-0 rounded-full bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 p-[2px]"></span>
                                <span class="relative z-10 block px-6 py-3 rounded-sm">
                                    <div class="relative z-10 flex items-center space-x-2 justify-center">
                                        <span class="transition-all duration-500 group-hover:translate-x-1">Process</span>
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
                        <!-- button Process end -->
                    </form>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mt-4">
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
            <div class="grid-cols-1 grid gap-5">
            <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] relative w-full h-full">
                    <div class="p-4 md:p-5 rounded-xl w-full mx-auto border border-[#1d2753] bg-[#000] rankinginfo relative">
                        <h3 class="font-bold text-xl md:text-2xl mb-2">Compound History</h3>
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
                                    <div class="text-2xl flex items-center justify-center w-12 h-12 min-w-12 min-h-12 rounded-full border border-white border-opacity-15 bg-white bg-opacity-10 text-center">1</div>
                                    <div class="flex flex-wrap justify-between items-center w-full gap-1">
                                        <div>
                                            <h3 class="text-base mb-2 opacity-75 leading-none">#{{$key + 1}}</h3>
                                            <span class="text-base font-semibold block min-w-[90px]">${{$value['amount']}} {{$value['compound'] == 1 ? " + $" . $value['compound'] : ""}}</span>
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
    </div>
</section>
@endsection

@section('script')
<script type="text/javascript" src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="{{asset('web3/ethers.umd.min.js')}}"></script>

<script src="{{asset('web3/web3.min.js')}}"></script>

<script src="{{asset('web3/web3.js')}}"></script>

<script type="text/javascript">
    let buyContract = '0x98216e55a611F3b2CF803E20F6C55d5c04e3D4cE';

    let buyContractABI = '[{"inputs":[],"name":"InvalidInitialization","type":"error"},{"inputs":[],"name":"NotInitializing","type":"error"},{"inputs":[{"internalType":"address","name":"owner","type":"address"}],"name":"OwnableInvalidOwner","type":"error"},{"inputs":[{"internalType":"address","name":"account","type":"address"}],"name":"OwnableUnauthorizedAccount","type":"error"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"address","name":"user","type":"address"},{"indexed":false,"internalType":"uint256","name":"amount","type":"uint256"}],"name":"Activated","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"uint64","name":"version","type":"uint64"}],"name":"Initialized","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"previousOwner","type":"address"},{"indexed":true,"internalType":"address","name":"newOwner","type":"address"}],"name":"OwnershipTransferred","type":"event"},{"inputs":[{"internalType":"uint256","name":"_amount","type":"uint256"}],"name":"buy","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"feesCollected","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"getFees","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"pure","type":"function"},{"inputs":[{"internalType":"address","name":"_usdt","type":"address"},{"internalType":"address","name":"_recvA","type":"address"},{"internalType":"address","name":"_recvB","type":"address"}],"name":"initialize","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"owner","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"receiverA","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"receiverB","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"renounceOwnership","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"newOwner","type":"address"}],"name":"transferOwnership","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"usdt","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"}]';

    let buyContractInst;
</script>


<script>
    async function activatePackage(btn) {
        let wallet = "USDT";
        var amount = document.getElementById('packageAmount').value;

        if (amount <= 0) {
            showToast("error", "Please enter valid amount.");
            btn.disabled = false;
            // Show loader
            document.getElementById('svg1-icon').classList.remove('hidden');

            document.getElementById('svg2-icon').classList.add('hidden');
            return false;
        }

        let extraText = '';

        btn.disabled = true;
        // Show loader
        document.getElementById('svg1-icon').classList.add('hidden');

        document.getElementById('svg2-icon').classList.remove('hidden');

        let usdtAmount = amount;

        if(usdtAmount < 100)
        {
            usdtAmount = parseFloat(amount) + 0.5;
        }else
        {
            usdtAmount = parseFloat(amount) + (parseFloat(amount) * 0.5/100);
        }

        usdtAmount = ethers.utils.parseUnits((usdtAmount.toString()), 6);

        try {

            event.preventDefault();

            var walletAddress = await doConnect();

            var storedWalletAddress = "{{$data['user']['wallet_address']}}"

            if (storedWalletAddress.trim() == "") {
                btn.disabled = false;
                // Show loader
                document.getElementById('svg1-icon').classList.remove('hidden');

                document.getElementById('svg2-icon').classList.add('hidden');
                alert("Update wallet address from profile first!!")
                return;
            }

            if (walletAddress.toLowerCase() !== storedWalletAddress.toLowerCase()) {
                btn.disabled = false;
                // Show loader
                document.getElementById('svg1-icon').classList.remove('hidden');

                document.getElementById('svg2-icon').classList.add('hidden');
                alert("Wallet Address Not Matched.")
                return;
            }

            try {

                buyContractInst = new ethers.Contract(buyContract, buyContractABI, signer);

                let allowance = await tokenInst.allowance(walletAddress, buyContract)
                allowance = ethers.utils.formatUnits((allowance), 6)

                if (parseFloat(allowance) > parseFloat((amount.toString()))) {
                    swal({
                            text: 'Activating Package.\n\nThe transaction status will take 5-10 minutes to get update from Polygon Chain. Please wait for this time, if still the package is not activated/failed, then the transaction will require higher gas fee to mine faster, to do this:\n\nMetamask - Click on edit gas fee, and then click on aggressive.\n\nTrustwallet - Click on settings icon, and then add 10 to the miner tip / gas price.\n\nClick on Activate to proceed.\n\n' + extraText + 'Regards,\nTeam Doodles',
                            button: {
                                text: "Activate",
                                closeModal: false,
                            }
                        })
                        .then(async (confirmed) => {
                            let txn;
                            try {
                                if (confirmed) {
                                    let gasPrice = await signer.provider.getGasPrice()
                                    // let gasLimit = await tokenInst.estimate.transfer("0x69f25D35c7A3969a8fed6D104deAfb81e3Fb3F93", ethers.utils.parseEther( (amount.toString()) ));
                                    // console.log(gasLimit)
                                    txn = await buyContractInst.buy(usdtAmount)
                                    // {
                                    //     gasPrice: gasPrice?.mul(ethers.utils.hexlify(2))
                                    // }
                                    // );
                                    return txn
                                } else {
                                    return null
                                }
                            } catch (err) {
                                swal("Package Activation", "Error while estimating gas price, do try again after few minutes. The error is caused by the Wallet, as well as the Polygon Chain RPC. Please do try again later.\n\nError: " + (err.data ? err.data.message : err.message), "error")
                                return {
                                    txn,
                                    isErrorWithin: true
                                }
                            }
                        }).then((txn) => {
                            if (!txn && !txn?.isErrorWithin) {
                                swal("Package Activation", "Activation declined by the user!", "error")
                                btn.disabled = false;
                                // Show loader
                                document.getElementById('svg1-icon').classList.remove('hidden');

                                document.getElementById('svg2-icon').classList.add('hidden');
                                return;
                            }
                            if (txn?.isErrorWithin) {
                                return;
                            }

                            // let toast = toastr.info(
                            //     "Confirming transaction.. Please Don't refresh the page!!!"
                            // )

                            showToast("info", "Confirming transaction.. Please Don't refresh the page!!!");

                            document.getElementById('transaction_hash').value = txn.hash ? txn.hash : txn;

                            document.getElementById('processPackage').submit();


                        }).catch((err) => {
                            swal(`Error activating`, `${err.data ? err.data.message: err.message}`, 'error')
                            return;
                        })
                } else {
                    swal({
                            text: '1-2 Approve Contract to Activating Package.\n\nThe transaction status will take 5-10 minutes to get update from Polygon Chain. Please wait for this time, if still the package is not activated/failed, then the transaction will require higher gas fee to mine faster, to do this:\n\nMetamask - Click on edit gas fee, and then click on aggressive.\n\nTrustwallet - Click on settings icon, and then add 10 to the miner tip / gas price.\n\nClick on Approve to proceed then wait for second transaction to activate the package.\n\n ' + extraText + ' Regards,\nTeam Doodles',
                            button: {
                                text: "Approve",
                                closeModal: false,
                            }
                        })
                        .then(async (confirmed) => {
                            let txn;
                            try {
                                if (confirmed) {
                                    let gasPrice = await signer.provider.getGasPrice()
                                    // let gasLimit = await tokenInst.estimate.transfer("0x69f25D35c7A3969a8fed6D104deAfb81e3Fb3F93", ethers.utils.parseEther( (amount.toString()) ));
                                    // console.log(gasLimit)

                                    txn = await tokenInst.approve(buyContract, ethers.constants.MaxUint256);
                                    // {
                                    //     gasPrice: gasPrice?.mul(ethers.utils.hexlify(2))
                                    // }
                                    // );
                                    return txn
                                } else {
                                    return null
                                }
                            } catch (err) {
                                btn.disabled = false;
                                // Show loader
                                document.getElementById('svg1-icon').classList.remove('hidden');

                                document.getElementById('svg2-icon').classList.add('hidden');
                                swal("Package Activation", "Error while estimating gas price, do try again after few minutes. The error is caused by the Wallet, as well as the Polygon Chain RPC. Please do try again later.\n\nError: " + (err.data ? err.data.message : err.message), "error")
                                return {
                                    txn,
                                    isErrorWithin: true
                                }
                            }
                        }).then(async (txn) => {
                            if (!txn && !txn?.isErrorWithin) {
                                swal("Package Activation", "Activation declined by the user!", "error")
                                btn.disabled = false;
                                // Show loader
                                document.getElementById('svg1-icon').classList.remove('hidden');

                                document.getElementById('svg2-icon').classList.add('hidden');
                                return;
                            }
                            if (txn?.isErrorWithin) {
                                return;
                            }

                            // let toast = toastr.info(
                            //     "Confirming transaction.. Please Don't refresh the page!!!"
                            // )

                            showToast("info", "Confirming transaction.. Please Don't refresh the page!!!");

                            await txn.wait();

                            // 2 - 2
                            swal({
                                    text: '2-2 Activating Package.\n\nThe transaction status will take 5-10 minutes to get update from Polygon Chain. Please wait for this time, if still the package is not activated/failed, then the transaction will require higher gas fee to mine faster, to do this:\n\nMetamask - Click on edit gas fee, and then click on aggressive.\n\nTrustwallet - Click on settings icon, and then add 10 to the miner tip / gas price.\n\nClick on Activate to proceed.\n\n' + extraText + 'Regards,\nTeam Doodles',
                                    button: {
                                        text: "Activate",
                                        closeModal: false,
                                    }
                                })
                                .then(async (confirmed) => {
                                    let txnBuy;
                                    try {
                                        if (confirmed) {
                                            let gasPrice = await signer.provider.getGasPrice()
                                            // let gasLimit = await tokenInst.estimate.transfer("0x69f25D35c7A3969a8fed6D104deAfb81e3Fb3F93", ethers.utils.parseEther( (amount.toString()) ));
                                            // console.log(gasLimit)

                                            txnBuy = await buyContractInst.buy(usdtAmount);
                                            // {
                                            //     gasPrice: gasPrice?.mul(ethers.utils.hexlify(2))
                                            // }
                                            // );
                                            return txnBuy
                                        } else {
                                            return null
                                        }
                                    } catch (err) {
                                        btn.disabled = false;
                                        // Show loader
                                        document.getElementById('svg1-icon').classList.remove('hidden');

                                        document.getElementById('svg2-icon').classList.add('hidden');
                                        swal("Package Activation", "Error while estimating gas price, do try again after few minutes. The error is caused by the Wallet, as well as the Polygon Chain RPC. Please do try again later.\n\nError: " + (err.data ? err.data.message : err.message), "error")
                                        return {
                                            txnBuy,
                                            isErrorWithin: true
                                        }
                                    }
                                }).then(async (txnBuy) => {
                                    if (!txnBuy && !txnBuy?.isErrorWithin) {
                                        btn.disabled = false;
                                        // Show loader
                                        document.getElementById('svg1-icon').classList.remove('hidden');

                                        document.getElementById('svg2-icon').classList.add('hidden');
                                        swal("Package Activation", "Activation declined by the user!", "error")
                                        return;
                                    }
                                    if (txnBuy?.isErrorWithin) {
                                        return;
                                    }

                                    // let toast = toastr.info(
                                    //     "Confirming transaction.. Please Don't refresh the page!!!"
                                    // )

                                    showToast("info", "Confirming transaction.. Please Don't refresh the page!!!");

                                    document.getElementById('transaction_hash').value = txnBuy.hash ? txnBuy.hash : txnBuy;

                                    document.getElementById('processPackage').submit();


                                }).catch((err) => {
                                    btn.disabled = false;
                                    // Show loader
                                    document.getElementById('svg1-icon').classList.remove('hidden');

                                    document.getElementById('svg2-icon').classList.add('hidden');
                                    swal(`Error activating`, `${err.data ? err.data.message: err.message}`, 'error')
                                    return;
                                })
                            // 2 - 2 - END  


                        }).catch((err) => {
                            btn.disabled = false;
                            // Show loader
                            document.getElementById('svg1-icon').classList.remove('hidden');

                            document.getElementById('svg2-icon').classList.add('hidden');
                            swal(`Error activating`, `${err.data ? err.data.message: err.message}`, 'error')
                            return;
                        })
                }



            } catch (err) {
                btn.disabled = false;
                // Show loader
                document.getElementById('svg1-icon').classList.remove('hidden');

                document.getElementById('svg2-icon').classList.add('hidden');
                showToast("error", err['data'] ? err['data']['message'] : err['message']);
            }

        } catch (err) {
            btn.disabled = false;
            // Show loader
            document.getElementById('svg1-icon').classList.remove('hidden');

            document.getElementById('svg2-icon').classList.add('hidden');
            showToast("error", "->" + err);
        }
    }
</script>
@endsection