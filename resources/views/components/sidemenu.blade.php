<aside class="w-full max-w-[240px] hidden md:block overflow-auto text-white" id="sidebar" style="height: calc(100vh - 72px);">
    <span class="hidesidebar md:hidden" onclick="toggleSidebar()"></span>
    <style>
        .newsboxcount::before {
            content: attr(datanewscount);
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: #FF5722;
            color: #ffffff;
            z-index: 30;
            top: -7px;
            right: 7px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    <nav class="sidemenu overflow-auto relative text-base z-50 h-full bg-gradient-to-t from-[#222A40] via-[#101735] to-[#222A40]">
        <ul class="flex flex-wrap px-1 py-6 text-xs sm:text-sm max-w-[220px] mx-auto">
            <li class="w-1/2 p-2 mb-2">
                <a href="{{ route('fdashboard') }}" class="flex flex-col items-center justify-center text-center gap-2 leading-[1.2] {{ Request::is('dashboard') ? 'activemenu' : '' }}">
                    <svg class="p-2 w-14 h-14 min-w-14 min-h-14 bg-gradient-to-r from-indigo-300 to-cyan-300 rounded-sm" width="24" height="24" viewBox="0 0 24 24" fill="#333333" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.4" d="M18.6699 2H16.7699C14.5899 2 13.4399 3.15 13.4399 5.33V7.23C13.4399 9.41 14.5899 10.56 16.7699 10.56H18.6699C20.8499 10.56 21.9999 9.41 21.9999 7.23V5.33C21.9999 3.15 20.8499 2 18.6699 2Z" fill="#333333"></path>
                        <path opacity="0.4" d="M7.24 13.4301H5.34C3.15 13.4301 2 14.5801 2 16.7601V18.6601C2 20.8501 3.15 22.0001 5.33 22.0001H7.23C9.41 22.0001 10.56 20.8501 10.56 18.6701V16.7701C10.57 14.5801 9.42 13.4301 7.24 13.4301Z" fill="#333333"></path>
                        <path d="M6.29 10.58C8.6593 10.58 10.58 8.6593 10.58 6.29C10.58 3.9207 8.6593 2 6.29 2C3.9207 2 2 3.9207 2 6.29C2 8.6593 3.9207 10.58 6.29 10.58Z" fill="#333333"></path>
                        <path d="M17.7099 22C20.0792 22 21.9999 20.0793 21.9999 17.71C21.9999 15.3407 20.0792 13.42 17.7099 13.42C15.3406 13.42 13.4199 15.3407 13.4199 17.71C13.4199 20.0793 15.3406 22 17.7099 22Z" fill="#333333"></path>
                    </svg>
                    <span>Home</span>
                </a>
            </li>
            <li class="w-1/2 p-2 mb-2">
                @if (!empty($provider_user) && isset($provider_user['news_notify']) && $provider_user['news_notify'] > 0)
                    <a href="{{ url('/news') }}" datanewscount="{{$provider_user['news_notify']}}" class="relative newsboxcount flex flex-col items-center justify-center text-center gap-2 leading-[1.2] {{ Request::is('news') ? 'activemenu' : '' }}">
                        <svg class="p-2 w-14 h-14 min-w-14 min-h-14 bg-gradient-to-r from-indigo-300 to-cyan-300 rounded-sm" width="24" height="24" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="icomoon-ignore">
                            </g>
                            <path d="M9.069 2.672v14.928h-6.397c0 0 0 6.589 0 8.718s1.983 3.010 3.452 3.010c1.469 0 16.26 0 20.006 0 1.616 0 3.199-1.572 3.199-3.199 0-1.175 0-23.457 0-23.457h-20.259zM6.124 28.262c-0.664 0-2.385-0.349-2.385-1.944v-7.652h5.331v7.192c0 0.714-0.933 2.404-2.404 2.404h-0.542zM28.262 26.129c0 1.036-1.096 2.133-2.133 2.133h-17.113c0.718-0.748 1.119-1.731 1.119-2.404v-22.12h18.126v22.391z" fill="#000000">

                            </path>
                            <path d="M12.268 5.871h13.861v1.066h-13.861v-1.066z" fill="#000000">

                            </path>
                            <path d="M12.268 20.265h13.861v1.066h-13.861v-1.066z" fill="#000000">

                            </path>
                            <path d="M12.268 23.997h13.861v1.066h-13.861v-1.066z" fill="#000000">

                            </path>
                            <path d="M26.129 9.602h-13.861v7.997h13.861v-7.997zM25.063 16.533h-11.729v-5.864h11.729v5.864z" fill="#000000">

                            </path>
                        </svg>
                        <span>News</span>
                    </a>
                @else
                    <a href="{{ url('/news') }}" class="relative flex flex-col items-center justify-center text-center gap-2 leading-[1.2] {{ Request::is('news') ? 'activemenu' : '' }}">
                        <svg class="p-2 w-14 h-14 min-w-14 min-h-14 bg-gradient-to-r from-indigo-300 to-cyan-300 rounded-sm" width="24" height="24" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="icomoon-ignore">
                            </g>
                            <path d="M9.069 2.672v14.928h-6.397c0 0 0 6.589 0 8.718s1.983 3.010 3.452 3.010c1.469 0 16.26 0 20.006 0 1.616 0 3.199-1.572 3.199-3.199 0-1.175 0-23.457 0-23.457h-20.259zM6.124 28.262c-0.664 0-2.385-0.349-2.385-1.944v-7.652h5.331v7.192c0 0.714-0.933 2.404-2.404 2.404h-0.542zM28.262 26.129c0 1.036-1.096 2.133-2.133 2.133h-17.113c0.718-0.748 1.119-1.731 1.119-2.404v-22.12h18.126v22.391z" fill="#000000">

                            </path>
                            <path d="M12.268 5.871h13.861v1.066h-13.861v-1.066z" fill="#000000">

                            </path>
                            <path d="M12.268 20.265h13.861v1.066h-13.861v-1.066z" fill="#000000">

                            </path>
                            <path d="M12.268 23.997h13.861v1.066h-13.861v-1.066z" fill="#000000">

                            </path>
                            <path d="M26.129 9.602h-13.861v7.997h13.861v-7.997zM25.063 16.533h-11.729v-5.864h11.729v5.864z" fill="#000000">

                            </path>
                        </svg>
                        <span>News</span>
                    </a>
                @endif
                
            </li>
            <li class="w-1/2 p-2 mb-2">
                <a href="{{ url('/profile') }}" class="flex flex-col items-center justify-center text-center gap-2 leading-[1.2] {{ Request::is('profile') ? 'activemenu' : '' }}">
                    <svg class="p-2 w-14 h-14 min-w-14 min-h-14 bg-gradient-to-r from-blue-300 to-orange-300 rounded-sm" width="24" height="24" viewBox="0 0 24 24" fill="#333333" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 13C17.06 13 16.19 13.33 15.5 13.88C14.58 14.61 14 15.74 14 17C14 17.75 14.21 18.46 14.58 19.06C15.27 20.22 16.54 21 18 21C19.01 21 19.93 20.63 20.63 20C20.94 19.74 21.21 19.42 21.42 19.06C21.79 18.46 22 17.75 22 17C22 14.79 20.21 13 18 13ZM20.07 16.57L17.94 18.54C17.8 18.67 17.61 18.74 17.43 18.74C17.24 18.74 17.05 18.67 16.9 18.52L15.91 17.53C15.62 17.24 15.62 16.76 15.91 16.47C16.2 16.18 16.68 16.18 16.97 16.47L17.45 16.95L19.05 15.47C19.35 15.19 19.83 15.21 20.11 15.51C20.39 15.81 20.37 16.28 20.07 16.57Z" fill="#333333"></path>
                        <path opacity="0.4" d="M21.09 21.5C21.09 21.78 20.87 22 20.59 22H3.41003C3.13003 22 2.91003 21.78 2.91003 21.5C2.91003 17.36 6.99003 14 12 14C13.03 14 14.03 14.14 14.95 14.41C14.36 15.11 14 16.02 14 17C14 17.75 14.21 18.46 14.58 19.06C14.78 19.4 15.04 19.71 15.34 19.97C16.04 20.61 16.97 21 18 21C19.12 21 20.13 20.54 20.85 19.8C21.01 20.34 21.09 20.91 21.09 21.5Z" fill="#333333"></path>
                        <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" fill="#333333"></path>
                    </svg>
                    <span>Profile</span>
                </a>
            </li>
            <li class="w-1/2 p-2 mb-2">
                <a href="{{ route('packageDeposit') }}" class="flex flex-col items-center justify-center text-center gap-2 leading-[1.2] {{ Request::is('package-deposit') ? 'activemenu' : '' }}">
                    <svg class="p-2 w-14 h-14 min-w-14 min-h-14 bg-gradient-to-r from-yellow-300 to-orange-300 rounded-sm" width="24" height="24" viewBox="0 0 25 25" fill="transparent" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.5 13V22M12.5 13L4.5 8M12.5 13L20.5 8M8.5 5.5L16.5 10.5M4.5 8L12.5 3L20.5 8V17L12.5 22L4.5 17V8Z" stroke="#333333" stroke-width="1.2" />
                    </svg>
                    <span>Package</span>
                    <!-- <span>Activate Package</span> -->
                </a>
            </li>
            <li class="w-1/2 p-2 mb-2">
                <a href="{{route('fmy_directs')}}" class="flex flex-col items-center justify-center text-center gap-2 leading-[1.2] {{ Request::is('my-directs') ? 'activesub' : '' }}">
                    <svg class="p-2 w-14 h-14 min-w-14 min-h-14 bg-gradient-to-r from-red-300 to-pink-300 rounded-sm" width="24" height="24" viewBox="0 0 32 32" fill="#333333">
                        <path d="M20.4131,14.584,12.416,6.5869a2.0016,2.0016,0,0,0-2.832,0L1.5869,14.584a2.0016,2.0016,0,0,0,0,2.832l3.2915,3.2915L3,22.5859,4.4141,24l1.8784-1.8784L9.584,25.4131a2.0016,2.0016,0,0,0,2.832,0l2.2559-2.2559-1.4156-1.4155L10.998,23.999,3.001,16.002l7.997-8.001,8.001,8.001L17.5,17.5l1.4146,1.4146,1.4985-1.4986a2.0016,2.0016,0,0,0,0-2.832Z" transform="translate(0 0)" />
                        <path d="M30.4131,14.584l-3.2915-3.2915L29,9.4141,27.5859,8,25.7075,9.8784,22.416,6.5869a2.0016,2.0016,0,0,0-2.832,0L17.3281,8.8428l1.4146,1.4145L20.998,8.001l8.001,8.001-8.001,7.997-7.997-7.997,1.5-1.501-1.4156-1.4156L11.5869,14.584a2.0016,2.0016,0,0,0,0,2.832l7.9971,7.9971a2.0016,2.0016,0,0,0,2.832,0l7.9971-7.9971a2.0016,2.0016,0,0,0,0-2.832Z" transform="translate(0 0)" />
                    </svg>
                    <span>Directs</span>
                    <!-- <span>Directs Team</span> -->
                </a>
            </li>
            <li class="w-1/2 p-2 mb-2">
                <a href="{{route('fmy_team')}}" class="flex flex-col items-center justify-center text-center gap-2 leading-[1.2] {{ Request::is('my-team') ? 'activesub' : '' }}">
                    <svg class="p-2 w-14 h-14 min-w-14 min-h-14 bg-gradient-to-r from-fuchsia-300 to-purple-300 rounded-sm" width="24" height="24" viewBox="0 0 24 24" fill="#333333" xmlns="http://www.w3.org/2000/svg">
                        <g id="_24x24_user--dark" data-name="24x24/user--dark">
                            <rect id="Rectangle" width="24" height="24" fill="none" />
                        </g>
                        <path id="Combined_Shape" data-name="Combined Shape" d="M0,12.106C0,8.323,4.5,9.08,4.5,7.567a2.237,2.237,0,0,0-.41-1.513A3.5,3.5,0,0,1,3,3.4,3.222,3.222,0,0,1,6,0,3.222,3.222,0,0,1,9,3.4,3.44,3.44,0,0,1,7.895,6.053,2.333,2.333,0,0,0,7.5,7.567c0,1.513,4.5.757,4.5,4.54,0,0-1.195.894-6,.894S0,12.106,0,12.106Z" transform="translate(6 8)" fill="none" stroke="#333333" stroke-miterlimit="10" stroke-width="1.5" />
                        <path id="Combined_Shape-2" data-name="Combined Shape" d="M4.486,12.967c-.569-.026-1.071-.065-1.512-.114A6.835,6.835,0,0,1,0,12.106C0,8.323,4.5,9.08,4.5,7.567a2.237,2.237,0,0,0-.41-1.513A3.5,3.5,0,0,1,3,3.4,3.222,3.222,0,0,1,6,0,3.222,3.222,0,0,1,9,3.4" transform="translate(1 3)" fill="none" stroke="#333333" stroke-miterlimit="10" stroke-width="1.5" />
                        <path id="Combined_Shape-3" data-name="Combined Shape" d="M-4.486,12.967c.569-.026,1.071-.065,1.512-.114A6.835,6.835,0,0,0,0,12.106C0,8.323-4.5,9.08-4.5,7.567a2.237,2.237,0,0,1,.41-1.513A3.5,3.5,0,0,0-3,3.4,3.222,3.222,0,0,0-6,0,3.222,3.222,0,0,0-9,3.4" transform="translate(23 3)" fill="none" stroke="#333333" stroke-miterlimit="10" stroke-width="1.5" />
                    </svg>
                    <span>Team</span>
                    <!-- <span>Total Team</span> -->
                </a>
            </li>
            <li class="w-1/2 p-2 mb-2">
                <a href="{{route('fgenealogy')}}" class="flex flex-col items-center justify-center text-center gap-2 leading-[1.2] {{ Request::is('genealogy') ? 'activesub' : '' }}">
                    <svg class="p-2 w-14 h-14 min-w-14 min-h-14 bg-gradient-to-r from-yellow-300 via-lime-300 to-green-300 rounded-sm" width="24" height="24" viewBox="0 0 512 512" xml:space="preserve" fill="#333333">
                        <g>
                            <path class="st0" d="M440.781,203.438c1.188-6.375,1.781-12.781,1.781-19.125c0-45.875-29.094-85.984-71.813-100.625
                                C354.859,33.969,308.953,0,256,0s-98.875,33.969-114.75,83.688c-42.734,14.625-71.813,54.75-71.813,100.625
                                c0,6.344,0.594,12.75,1.766,19.125c-24.813,22.813-38.844,54.547-38.844,88.531c0,66.516,54.109,120.625,120.625,120.625
                                c13.219,0,26.125-2.125,38.531-6.313c14.422,10.219,31.078,16.828,48.484,19.359V512h8h16h8v-86.359
                                c17.406-2.531,34.063-9.141,48.484-19.359c12.391,4.188,25.313,6.313,38.531,6.313c66.516,0,120.625-54.109,120.625-120.625
                                C479.641,257.984,465.594,226.25,440.781,203.438z M359.016,380.594c-12.094,0-23.828-2.406-34.922-7.156L315,369.531l-7.563,6.406
                                c-12.313,10.438-27.516,16.844-43.438,18.469v-41.875l62.547-71.469L314.5,270.531L264,328.25v-58.938l50.438-57.656
                                l-12.047-10.531L264,245v-90.344h-16v90.359l-38.406-43.891l-12.047,10.531L248,269.313v58.938l-50.5-57.719l-12.047,10.531
                                L248,352.531v41.875c-15.938-1.625-31.125-8.031-43.453-18.469L197,369.531l-9.109,3.906c-11.078,4.75-22.828,7.156-34.906,7.156
                                c-48.875,0-88.625-39.75-88.625-88.625c0-27.516,12.563-53.031,34.453-70l8.563-6.656l-2.984-10.406
                                c-1.969-6.844-2.953-13.781-2.953-20.594c0-34.344,23.297-64.063,56.656-72.266l9.5-2.344l2.25-9.516
                                C179.344,60.031,214.766,32,256,32s76.656,28.031,86.141,68.188l2.25,9.516l9.5,2.344c33.359,8.203,56.672,37.922,56.672,72.266
                                c0,6.813-1,13.75-2.969,20.594l-2.984,10.406l8.563,6.656c21.906,16.969,34.469,42.484,34.469,70
                                C447.641,340.844,407.875,380.594,359.016,380.594z" />
                        </g>
                    </svg>
                    <span>Genealogy</span>
                </a>
            </li>
            <li class="w-1/2 p-2 mb-2">
                <a href="{{ url('/income-overview') }}" class="flex flex-col items-center justify-center text-center gap-2 leading-[1.2] {{ Request::is('income-overview') ? 'activemenu' : '' }}">
                    <svg class="p-2 w-14 h-14 min-w-14 min-h-14 bg-gradient-to-r from-teal-300 to-blue-300 rounded-sm" width="24" height="24" viewBox="0 0 1024 1024" fill="#333333" xmlns="http://www.w3.org/2000/svg">
                        <path d="M409.798 551.245c-4.196-18.934-19.682-34.42-38.619-38.617-38.375-8.511-71.514 24.626-63.01 63.005 4.196 18.939 19.677 34.417 38.629 38.615 38.373 8.511 71.505-24.624 63-63.004zm39.99-8.862c14.987 67.63-44.232 126.854-111.854 111.856-34.491-7.641-62.115-35.258-69.755-69.744-14.987-67.629 44.242-126.853 111.866-111.855 34.477 7.641 62.102 35.266 69.743 69.743zM621.693 756.37a10.233 10.233 0 003.747-13.988l-39.116-67.757c-2.825-4.892-9.094-6.572-13.987-3.748l-67.744 39.114c-4.904 2.833-6.585 9.101-3.762 13.991l39.114 67.753c2.831 4.901 9.1 6.579 14 3.752l67.748-39.117zm-47.272 74.592c-24.489 14.13-55.798 5.746-69.946-18.743l-39.116-67.757c-14.136-24.481-5.739-55.794 18.749-69.94l67.753-39.12c24.487-14.129 55.8-5.738 69.935 18.743l39.114 67.753c14.148 24.49 5.755 55.81-18.739 69.945l-67.751 39.118zm173.24-237.158c-1.252 20.821-22.113 32.036-40.165 21.607L600.36 553.558c-18.058-10.433-18.757-34.1-1.357-45.586l117.423-77.528c18.778-12.396 41.013.422 39.662 22.898l-8.428 140.462zM713.363 481.55l-72.685 47.99 67.468 38.951 5.216-86.941zM456.458 285.736l-80.404-158.218c-3.461-6.812 1.489-14.878 9.134-14.878h251.628c7.645 0 12.59 8.061 9.127 14.873l-80.397 158.224c-5.124 10.084-1.103 22.412 8.981 27.536s22.412 1.103 27.536-8.981l80.394-158.218c17.319-34.058-7.427-74.394-45.64-74.394H385.189c-38.208 0-62.956 40.328-45.651 74.392l80.406 158.221c5.124 10.083 17.453 14.104 27.536 8.979s14.104-17.453 8.979-27.536z" />
                        <path d="M725.04 909.844c101.8 0 184.32-82.52 184.32-184.32v-43.151c0-197.073-161.327-358.4-358.4-358.4h-79.923c-197.073 0-358.4 161.327-358.4 358.4v43.151c0 101.797 82.526 184.32 184.32 184.32H725.04zm0 40.96H296.957c-124.415 0-225.28-100.862-225.28-225.28v-43.151c0-219.695 179.665-399.36 399.36-399.36h79.923c219.695 0 399.36 179.665 399.36 399.36v43.151c0 124.422-100.858 225.28-225.28 225.28z" />
                    </svg>
                    <span>Overview</span>
                    <!-- <span>Income Overview</span> -->
                </a>
            </li>
            <li class="w-1/2 p-2 mb-2">
                <a href="{{ route('fwithdraw') }}" class="flex flex-col items-center justify-center text-center gap-2 leading-[1.2] {{ Request::is('withdraws') ? 'activemenu' : '' }}">
                    <svg class="p-2 w-14 h-14 min-w-14 min-h-14 bg-gradient-to-r from-indigo-300 to-cyan-300 rounded-sm" width="24" height="24" viewBox="0 0 24 24" fill="#333333" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M23 4C23 2.34315 21.6569 1 20 1H4C2.34315 1 1 2.34315 1 4V14C1 15.6569 2.34315 17 4 17H5C5.55228 17 6 16.5523 6 16C6 15.4477 5.55228 15 5 15H4C3.44772 15 3 14.5523 3 14L3 8H21V14C21 14.5523 20.5523 15 20 15H19C18.4477 15 18 15.4477 18 16C18 16.5523 18.4477 17 19 17H20C21.6569 17 23 15.6569 23 14V4ZM21 6V4C21 3.44772 20.5523 3 20 3H4C3.44772 3 3 3.44771 3 4V6H21Z" fill="#333333" />
                        <path d="M13 22C13 22.5523 12.5523 23 12 23C11.4477 23 11 22.5523 11 22L11 16.4069L9.70714 17.6996C9.31657 18.0903 8.68346 18.0903 8.29289 17.6996C7.90239 17.3093 7.90239 16.676 8.29289 16.2856L11.2924 13.2923C11.683 12.9024 12.3156 12.9028 12.7059 13.293L15.705 16.2922C16.0956 16.6828 16.0956 17.3159 15.705 17.7065C15.3145 18.0969 14.6813 18.0969 14.2908 17.7065L13 16.4157L13 22Z" fill="#333333" />
                    </svg>
                    <span>Withdraw</span>
                </a>
            </li>
            <li class="w-1/2 p-2 mb-2">
                <a href="{{ url('/support-tickets') }}" class="flex flex-col items-center justify-center text-center gap-2 leading-[1.2] {{ Request::is('support-tickets') ? 'activemenu' : '' }}">
                    <svg class="p-2 w-14 h-14 min-w-14 min-h-14 bg-gradient-to-r from-red-300 to-pink-300 rounded-sm" width="24" height="24" viewBox="0 0 24 24" fill="#333333" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12.0002C5 10.694 4.16519 9.58273 3 9.1709V7.6C3 7.03995 3 6.75992 3.10899 6.54601C3.20487 6.35785 3.35785 6.20487 3.54601 6.10899C3.75992 6 4.03995 6 4.6 6H19.4C19.9601 6 20.2401 6 20.454 6.10899C20.6422 6.20487 20.7951 6.35785 20.891 6.54601C21 6.75992 21 7.03995 21 7.6V9.17071C19.8348 9.58254 19 10.694 19 12.0002C19 13.3064 19.8348 14.4175 21 14.8293V16.4C21 16.9601 21 17.2401 20.891 17.454C20.7951 17.6422 20.6422 17.7951 20.454 17.891C20.2401 18 19.9601 18 19.4 18H4.6C4.03995 18 3.75992 18 3.54601 17.891C3.35785 17.7951 3.20487 17.6422 3.10899 17.454C3 17.2401 3 16.9601 3 16.4V14.8295C4.16519 14.4177 5 13.3064 5 12.0002Z" stroke="#333333" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>Tickets</span>
                    <!-- <span>Support Tickets</span> -->
                </a>
            </li>
            <li class="w-1/2 p-2 mb-2">
                <a href="{{ url('/my-nfts') }}" class="flex flex-col items-center justify-center text-center gap-2 leading-[1.2] {{ Request::is('my-nfts') ? 'activemenu' : '' }}">
                    <svg class="p-2 w-14 h-14 min-w-14 min-h-14 bg-gradient-to-r from-blue-300 to-orange-300 rounded-sm" width="24" height="24" viewBox="0 0 512 512" class="icon icon-xl" role="img">
                        <path fill="#000000" d="M88,16a56,56,0,1,0,56,56A56.063,56.063,0,0,0,88,16Zm0,80a24,24,0,1,1,24-24A24.028,24.028,0,0,1,88,96Z" class="ci-primary"></path>
                        <path fill="#000000" d="M200,138.667a56,56,0,1,0,56,56A56.063,56.063,0,0,0,200,138.667Zm0,80a24,24,0,1,1,24-24A24.028,24.028,0,0,1,200,218.667Z" class="ci-primary"></path>
                        <path fill="#000000" d="M88,373.333a56,56,0,1,0-56-56A56.063,56.063,0,0,0,88,373.333Zm0-80a24,24,0,1,1-24,24A24.028,24.028,0,0,1,88,293.333Z" class="ci-primary"></path>
                        <path fill="#000000" d="M200,496a56,56,0,1,0-56-56A56.063,56.063,0,0,0,200,496Zm0-80a24,24,0,1,1-24,24A24.028,24.028,0,0,1,200,416Z" class="ci-primary"></path>
                        <path fill="#000000" d="M312,16a56,56,0,1,0,56,56A56.063,56.063,0,0,0,312,16Zm0,80a24,24,0,1,1,24-24A24.028,24.028,0,0,1,312,96Z" class="ci-primary"></path>
                        <path fill="#000000" d="M424,138.667a56,56,0,1,0,56,56A56.063,56.063,0,0,0,424,138.667Zm0,80a24,24,0,1,1,24-24A24.028,24.028,0,0,1,424,218.667Z" class="ci-primary"></path>
                        <path fill="#000000" d="M312,373.333a56,56,0,1,0-56-56A56.063,56.063,0,0,0,312,373.333Zm0-80a24,24,0,1,1-24,24A24.028,24.028,0,0,1,312,293.333Z" class="ci-primary"></path>
                        <path fill="#000000" d="M424,384a56,56,0,1,0,56,56A56.063,56.063,0,0,0,424,384Zm0,80a24,24,0,1,1,24-24A24.028,24.028,0,0,1,424,464Z" class="ci-primary"></path>
                    </svg>
                    <span>My Nfts</span>
                </a>
            </li>
            <li class="w-1/2 p-2 mb-2">
                <a href="{{ route('flogout') }}" class="flex flex-col items-center justify-center text-center gap-2 leading-[1.2] {{ Request::is('flogout') ? 'activemenu' : '' }}">
                    <svg class="p-2 w-14 h-14 min-w-14 min-h-14 bg-gradient-to-r from-yellow-300 to-orange-300 rounded-sm" width="24" height="24" viewBox="0 0 24 24" fill="#333333" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.5" d="M12 20C7.58172 20 4 16.4183 4 12C4 7.58172 7.58172 4 12 4V20Z" fill="#999999" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16.4697 8.46967C16.1768 8.76256 16.1768 9.23744 16.4697 9.53033L18.1893 11.25H10C9.58579 11.25 9.25 11.5858 9.25 12C9.25 12.4142 9.58579 12.75 10 12.75H18.1893L16.4697 14.4697C16.1768 14.7626 16.1768 15.2374 16.4697 15.5303C16.7626 15.8232 17.2374 15.8232 17.5303 15.5303L20.5303 12.5303C20.8232 12.2374 20.8232 11.7626 20.5303 11.4697L17.5303 8.46967C17.2374 8.17678 16.7626 8.17678 16.4697 8.46967Z" fill="#333333" />
                    </svg>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
        <div class="p-5 sticky top-full">
            <div class="relative flex items-center justify-center rounded-md p-3 gap-4 leading-none mx-auto w-full  bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
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
    </nav>
</aside>