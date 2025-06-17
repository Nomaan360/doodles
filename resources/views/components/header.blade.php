<header class="bg-gradient-to-r from-[#222A40] via-[#101735] to-[#222A40] text-white relative z-[51]">
    <div class="mx-auto flex justify-between items-center">
        <div class="flex items-center justify-start py-3 px-2 md:px-4" style="height: 72px;">
            <div class="w-full h-12 md:w-44 md:h-16 relative mr-2 flex items-center justify-center">
                <img src="{{ asset('assets/images/logo.webp') }}?v={{time()}}" width="64" height="64" alt="Logo" class="max-h-12 h-auto w-full">
            </div>
            <!-- <h1 class="text-lg font-bold uppercase hidden sm:block">AdDoodles</h1> -->
        </div>
        <div class="flex items-center justify-end md:justify-between w-full relative py-3 px-3 md:px-5 gap-4" style="height: 72px;">
            <h2 class="uppercase text-lg leading-none tracking-wide opacity-90 hidden md:block">{{ Request::is('/') ? 'Dashboard' : ucwords(str_replace('-', ' ', Request::segment(1))) }}</h2>
            <!-- Profile Icon -->
            <div class="col-span-1 items-center justify-end flex">
                @if(isset($data['self_investment']))
                <div class="flex flex-col items-center h-auto text-white space-y-2 text-sm bg-white/0 border-r-2 border-white/5 border-opacity-80 last:border-r-0 px-2 md:px-5 py-2">
                    <span class="text-xl text-white">
                        <svg class="w-6 h-6 min-w-6 min-h-6" viewBox="0 0 24 24" fill="none">
                            <path d="M6 10H10" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M21.9977 12.5C21.9977 12.4226 22 11.9673 21.9977 11.9346C21.9623 11.4338 21.5328 11.035 20.9935 11.0021C20.9583 11 20.9167 11 20.8333 11H18.2308C16.4465 11 15 12.3431 15 14C15 15.6569 16.4465 17 18.2308 17H20.8333C20.9167 17 20.9583 17 20.9935 16.9979C21.5328 16.965 21.9623 16.5662 21.9977 16.0654C22 16.0327 22 15.5774 22 15.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                            <circle cx="18" cy="14" r="1" fill="#ffffff" />
                            <path d="M10 22H13C16.7712 22 18.6569 22 19.8284 20.8284C20.6366 20.0203 20.8873 18.8723 20.965 17M20.965 11C20.8873 9.1277 20.6366 7.97975 19.8284 7.17157C18.6569 6 16.7712 6 13 6H10C6.22876 6 4.34315 6 3.17157 7.17157C2 8.34315 2 10.2288 2 14C2 17.7712 2 19.6569 3.17157 20.8284C3.82475 21.4816 4.69989 21.7706 6 21.8985" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M6 6L9.73549 3.52313C10.7874 2.82562 12.2126 2.82562 13.2645 3.52313L17 6" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </span>
                    <span class="inline text-xs md:text-sm font-medium whitespace-pre">{{$data['self_investment']}}</span>
                </div>
                @endif
                <div class="flex flex-col items-center h-auto text-white space-y-2 text-sm bg-white/0 border-r-2 md:border-0 border-white/5 border-opacity-80 last:border-r-0 px-2 md:pl-5 py-2">
                    <span class="text-xl text-white w-6 h-6 min-w-6 min-h-6 overflow-hidden flex items-center justify-center">
                        @if (!empty($provider_user) && isset($provider_user['image']))
                            <img class="w-full h-full object-contain" src="{{asset('storage/'.$provider_user['image'])}}">
                        @else
                            <svg class="w-6 h-6 min-w-6 min-h-6" fill="#ffffff" viewBox="0 0 24 24">
                                <path d="M12 1.2A4.8 4.8 0 1 0 16.8 6 4.805 4.805 0 0 0 12 1.2zm0 8.6A3.8 3.8 0 1 1 15.8 6 3.804 3.804 0 0 1 12 9.8zM20 22H4v-4.5A5.506 5.506 0 0 1 9.5 12h5a5.506 5.506 0 0 1 5.5 5.5zM5 21h14v-3.5a4.505 4.505 0 0 0-4.5-4.5h-5A4.505 4.505 0 0 0 5 17.5z"></path>
                                <path fill="none" d="M0 0h24v24H0z"></path>
                            </svg>
                        @endif
                    </span>
                    <span class="inline text-xs md:text-sm font-medium">#{{Session::get('refferal_code')}}</span>
                </div>
                <button class="flex md:hidden flex-col items-center h-auto text-white space-y-2 text-sm bg-white/0 pl-3 md:pl-5 pr-0 md:pr-2 py-2" onclick="toggleSidebar()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4 5C3.44772 5 3 5.44772 3 6C3 6.55228 3.44772 7 4 7H20C20.5523 7 21 6.55228 21 6C21 5.44772 20.5523 5 20 5H4ZM7 12C7 11.4477 7.44772 11 8 11H20C20.5523 11 21 11.4477 21 12C21 12.5523 20.5523 13 20 13H8C7.44772 13 7 12.5523 7 12ZM13 18C13 17.4477 13.4477 17 14 17H20C20.5523 17 21 17.4477 21 18C21 18.5523 20.5523 19 20 19H14C13.4477 19 13 18.5523 13 18Z" fill="#ffffff" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('sidemenutoggle');
        }
    </script>
</header>