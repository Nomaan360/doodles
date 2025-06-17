@extends('layouts.app')

@section('title', 'Login')

@section('content')
<section class="w-full h-full bg-[#0B1124] flex items-center justify-center" style="height: calc(100vh - 56px);">
    <div class="flex justify-center w-full h-full">
        <div class="max-w-screen-xl m-0 sm:m-10 shadow rounded-lg flex justify-center flex-1">
            <div class="lg:w-1/2 p-6 sm:p-12 flex items-center justify-center">
                <div class="w-full relative loginregbox bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 rounded-md p-px">
                    <div class="bg-[#101735] rounded-md px-4 py-8">
                        <div>
                            <img src="{{ asset('assets/images/logo.webp') }}?v={{time()}}" width="64" height="48" alt="Logo" class="w-44 mx-auto">
                        </div>
                        <div class="mt-4 flex flex-col items-center">
                            <h1 class="text-xl xl:text-2xl font-bold text-gradient">
                                Welcome to AdDoodles!
                            </h1>
                            <div class="w-full flex-1 mt-2 text-center">
                                <p class="mb-4 leading-none mx-auto max-w-fit">Please connect your wallet to continue</p>
                                <div class="mx-auto max-w-sm mt-8">
                                    <!-- button start -->
                                    <div class="flex items-center justify-center my-8 relative group">
                                        <button class="w-full relative inline-block p-px font-semibold leading-none text-white cursor-pointer rounded-sm" onclick="processLogin(this)">
                                            <span class="absolute inset-0 rounded-full bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 p-[2px]"></span>
                                            <span class="relative z-10 block px-6 py-3 rounded-sm">
                                                <div class="relative z-10 flex items-center space-x-2 justify-center">
                                                    <span class="transition-all duration-500 group-hover:translate-x-1">Login</span>
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
                                    <div class="text-sm font-medium text-gray-300 mt-5 sm:mt-5 text-center">
                                        Register your Account Now
                                        <a href="{{ url(path: '/register') }}" class="text-[#90b44f] underline hover:text-[#ffffff]">Sign up.</a>
                                    </div>
                                    <div class="relative flex items-center justify-center rounded-md p-3 gap-4 leading-none mx-auto w-full  bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 max-w-fit mt-6">
                                        <span class="absolute top-1/2 left-1/2 transform translate-x-[-50%] translate-y-[-50%] bg-gradient-to-t from-[#222A40] via-[#101735] to-[#222A40] rounded-md z-0 block w-[calc(100%-3px)] h-[calc(100%-3px)]"></span>
                                        <!-- Instagram -->
                                        <!-- <a href="#"
                                            type="button"
                                            target="_blank"
                                            data-twe-ripple-init
                                            data-twe-ripple-color="light"
                                            class="inline-block text-xs text-white">
                                            <span class="[&>svg]:h-6 [&>svg]:w-8 relative z-20">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="currentColor"
                                                    viewBox="0 0 448 512">
                                                    <path
                                                        d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z" />
                                                </svg>
                                            </span>
                                        </a> -->
                                        <!-- Twitter -->
                                        <!-- <a href="#"
                                            type="button"
                                            target="_blank"
                                            data-twe-ripple-init
                                            data-twe-ripple-color="light"
                                            class="inline-block 6 py-medium uppercase-0 active:shadow-lg">
                                            <span class="[&>svg]:h-6 [&>svg]:w-8 relative z-20">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="currentColor"
                                                    viewBox="0 0 512 512">
                                                    <path
                                                        d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
                                                </svg>
                                            </span>
                                        </a> -->
                                        <!-- Telegram -->
                                        <a href="https://t.me/doodlesad"
                                            type="button"
                                            target="_blank"
                                            data-twe-ripple-init
                                            data-twe-ripple-color="light"
                                            class="inline-block text-xs text-white">
                                            <span class="[&>svg]:h-6 [&>svg]:w-8 relative z-20">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="currentColor"
                                                    viewBox="0 0 496 512">
                                                    <path
                                                        d="M248 8C111 8 0 119 0 256S111 504 248 504 496 393 496 256 385 8 248 8zM363 176.7c-3.7 39.2-19.9 134.4-28.1 178.3-3.5 18.6-10.3 24.8-16.9 25.4-14.4 1.3-25.3-9.5-39.3-18.7-21.8-14.3-34.2-23.2-55.3-37.2-24.5-16.1-8.6-25 5.3-39.5 3.7-3.8 67.1-61.5 68.3-66.7 .2-.7 .3-3.1-1.2-4.4s-3.6-.8-5.1-.5q-3.3 .7-104.6 69.1-14.8 10.2-26.9 9.9c-8.9-.2-25.9-5-38.6-9.1-15.5-5-27.9-7.7-26.8-16.3q.8-6.7 18.5-13.7 108.4-47.2 144.6-62.3c68.9-28.6 83.2-33.6 92.5-33.8 2.1 0 6.6 .5 9.6 2.9a10.5 10.5 0 0 1 3.5 6.7A43.8 43.8 0 0 1 363 176.7z" />
                                                </svg>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script src="{{asset('web3/ethers.umd.min.js')}}"></script>

<script src="{{asset('web3/web3.min.js')}}"></script>

<script src="{{asset('web3/web3.js')}}"></script>

<script>
    async function processLogin(btn) {
        btn.disabled = true;
        // Show loader
        document.getElementById('svg1-icon').classList.add('hidden');

        document.getElementById('svg2-icon').classList.remove('hidden');

        // Connect to wallet
        let address = await doConnect();

        if (address != undefined) {

            const message = `login-${address}-`+ new Date().getTime();
            const hashedMessage = Web3.utils.sha3(message);
            let signature = await ethereum.request({
                method: "personal_sign",
                params: [hashedMessage, address],
            });
            
            const r = signature.slice(0, 66);
            const s = "0x" + signature.slice(66, 130);
            const v = parseInt(signature.slice(130, 132), 16);

            // Get the CSRF token from the meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Create a new XMLHttpRequest object
            const xhttp = new XMLHttpRequest();

            // Configure the request (POST method, to your endpoint)
            xhttp.open("POST", "{{route('fuserValidate')}}", true);

            // Set the appropriate headers for the request
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.setRequestHeader("X-CSRF-TOKEN", csrfToken); // Set the CSRF token header

            // Define a function to handle the response
            xhttp.onload = function() {
                if (this.status == 200) {
                    let response = JSON.parse(this.responseText);
                    if (response.status_code == 0) {
                        // Success
                        // Create a new form element
                        let form = document.createElement("form");
                        form.method = "POST"; // Change to "GET" if needed
                        form.action = "{{route('floginProcess')}}"; // Change to your target URL

                        // Create an input field for wallet address
                        let input = document.createElement("input");
                        input.type = "hidden"; // Hidden field (won't show on the page)
                        input.name = "wallet_address";
                        input.value = address; // Set your value

                        // Create an input field for wallet address
                        let rScriptinput = document.createElement("input");
                        rScriptinput.type = "hidden"; // Hidden field (won't show on the page)
                        rScriptinput.name = "rScript";
                        rScriptinput.value = r; // Set your value

                        // Create an input field for wallet address
                        let rsScriptinput = document.createElement("input");
                        rsScriptinput.type = "hidden"; // Hidden field (won't show on the page)
                        rsScriptinput.name = "rsScript";
                        rsScriptinput.value = s; // Set your value

                        // Create an input field for wallet address
                        let rsvScriptinput = document.createElement("input");
                        rsvScriptinput.type = "hidden"; // Hidden field (won't show on the page)
                        rsvScriptinput.name = "rsvScript";
                        rsvScriptinput.value = v; // Set your value

                        // Create an input field for wallet address
                        let hashedMessageinput = document.createElement("input");
                        hashedMessageinput.type = "hidden"; // Hidden field (won't show on the page)
                        hashedMessageinput.name = "hashedMessageScript";
                        hashedMessageinput.value = hashedMessage; // Set your value

                        // Create an input field for wallet address
                        let walletAddressScriptinput = document.createElement("input");
                        walletAddressScriptinput.type = "hidden"; // Hidden field (won't show on the page)
                        walletAddressScriptinput.name = "walletAddressScript";
                        walletAddressScriptinput.value = address; // Set your value

                        // Create a hidden input for CSRF token
                        let csrfInput = document.createElement("input");
                        csrfInput.type = "hidden"; // Hidden field
                        csrfInput.name = "_token"; // The CSRF token name Laravel expects
                        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Get CSRF token from the meta tag

                        // Append input to the form
                        form.appendChild(input);
                        form.appendChild(rScriptinput);
                        form.appendChild(rsScriptinput);
                        form.appendChild(rsvScriptinput);
                        form.appendChild(hashedMessageinput);
                        form.appendChild(walletAddressScriptinput);
                        form.appendChild(input);

                        // Append the CSRF token input to the form
                        form.appendChild(csrfInput);

                        // Append form to body
                        document.body.appendChild(form);

                        // Submit form programmatically
                        form.submit();
                    } else {
                        // Hide loader
                        document.getElementById('svg1-icon').classList.remove('hidden');

                        document.getElementById('svg2-icon').classList.add('hidden');

                        btn.disabled = false;
                        // Error
                        showToast("error", 'You are not registered with us please register first!');
                    }
                } else {
                    // Hide loader
                    document.getElementById('svg1-icon').classList.remove('hidden');

                    document.getElementById('svg2-icon').classList.add('hidden');

                    btn.disabled = false;
                    // Error
                    showToast("error", 'An error occurred. Please try again later.');
                }
            };

            // Send the request with the data
            xhttp.send("wallet_address=" + walletAddress + "&type=API");
        } else {
            // Hide loader
            document.getElementById('svg1-icon').classList.remove('hidden');

            document.getElementById('svg2-icon').classList.add('hidden');

            btn.disabled = false;
            showToast("warning", 'Please install Web3 wallet extension like metamask, trustwallet');
        }
    }
</script>
@endsection