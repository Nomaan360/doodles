@extends('layouts.app')

@section('title', 'My Directs')

@section('content')
<section class="w-full p-3 md:p-8 mx-auto max-w-[1400px]">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
        <div class="grid-cols-1 grid gap-5">
            <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
                <div class="flex items-center space-x-3 w-full gap-1 bg-[#101735] rounded-2xl p-4 h-full">
                    <img src={{ asset('assets/images/icons/direct-investment.webp') }} width="64" height="48" alt="Logo" class="w-12 h-auto">
                    <div class="w-full">
                        <h3 class="text-base mb-2 opacity-75 leading-none">Direct Team Investment</h3>
                        <span class="text-sm">${{$data['user']['direct_business']}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid-cols-1 grid gap-5">
            <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
                <div class="flex items-center space-x-3 w-full gap-1 bg-[#101735] rounded-2xl p-4 h-full">
                    <img src={{ asset('assets/images/icons/team-investment.webp') }} width="64" height="48" alt="Logo" class="w-12 h-auto">
                    <div class="w-full">
                        <h3 class="text-base mb-2 opacity-75 leading-none">Total Team Investment</h3>
                        <span class="text-sm">${{$data['user']['my_business']}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h2 class="bg-gradient-to-r from-indigo-300 to-cyan-300 relative text-black rounded-sm p-3 text-lg font-normal leading-none mb-5 flex items-center gap-2">My Directs</h2>
    <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] relative w-full h-full">
        <div class="mb-4 border-b-2 border-[#1d2753]">
            <ul class="incomeOverview_tab flex flex-nowrap -mb-px text-sm font-medium text-center overflow-auto" data-tabs-toggle="#default-tab-content" role="tablist">
                <li class="me-2" role="presentation">
                    <button class="inline-block p-2 sm:p-4 rounded-t-lg text-sm sm:text-base uppercase text-nowrap" id="table-total_directs" data-tabs-target="#total_directs" type="button" role="tab" aria-controls="total_directs" aria-selected="false">Total Directs</button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-2 sm:p-4 rounded-t-lg text-sm sm:text-base uppercase text-nowrap" id="table-active_directs" data-tabs-target="#active_directs" type="button" role="tab" aria-controls="active_directs" aria-selected="false">Active Directs</button>
                </li>
            </ul>
        </div>
        <div id="default-tab-content">
            <div class="hidden" id="total_directs" role="tabpanel" aria-labelledby="table-total_directs">
                <div class="overflow-x-auto">
                    <table id="tabledata1" class="w-full text-left border-collapse" style="padding-top: 15px;">
                        <thead>
                            <tr class="bg-white bg-opacity-10 text-white">
                                <th class="px-4 py-2">Sr.</th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">User Id</span></th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">Wallet Address</span></th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">Direct</span></th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">Team</span></th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">Package</span></th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">Registration Date</span></th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">Activation Date</span></th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">Rank</span></th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">Level</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data['data']) > 0)
                            @foreach ($data['data'] as $key => $value)
                            <tr>
                                <td class="text-nowrap px-4 py-2">{{ $key + 1 }}</td>
                                <td class="text-nowrap px-4 py-2 text-center text-[#30b8f5]">#{{ $value['refferal_code'] }}</td>
                                <td class="text-nowrap px-4 py-2 text-center">{{ $value['wallet_address'] }}</td>
                                <td class="text-nowrap px-4 py-2 text-center">{{ $value['my_direct'] }}</td>
                                <td class="text-nowrap px-4 py-2 text-center">{{ $value['my_team'] }}</td>
                                <td class="text-nowrap px-4 py-2 text-center text-[#30b8f5]">${{ $value['currentPackage'] }}</td>
                                <td class="text-nowrap px-4 py-2 text-center">{{ date('d-m-Y', strtotime($value['created_on'])) }}</td>
                                <td class="text-nowrap px-4 py-2 text-center">{{ date('d-m-Y', strtotime($value['currentPackageDate'])) }}</td>
                                <td class="text-nowrap px-4 py-2 text-center">{{ $value['rank'] == 0 ? "No Rank" : $value['rank'] }}</td>
                                <td class="text-nowrap px-4 py-2 text-center">{{ $value['level'] == 0 ? "No level" : $value['level'] }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="hidden" id="active_directs" role="tabpanel" aria-labelledby="table-active_directs">
                <div class="overflow-x-auto">
                    <table id="tabledata2" class="w-full text-left border-collapse" style="padding-top: 15px;">
                        <thead>
                            <tr class="bg-white bg-opacity-10 text-white">
                                <th class="px-4 py-2">Sr.</th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">User Id</span></th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">Wallet Address</span></th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">Direct</span></th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">Team</span></th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">Package</span></th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">Registration Date</span></th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">Activation Date</span></th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">Rank</span></th>
                                <th class="px-4 py-2 text-center text-center"><span class="text-nowrap w-full block text-center">Level</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data['data']) > 0)
                            @foreach ($data['data'] as $key => $value)
                            @if($value['totalPackage'] > 0)
                            <tr>
                                <td class="text-nowrap px-4 py-2">{{ $key + 1 }}</td>
                                <td class="text-nowrap px-4 py-2 text-center text-[#30b8f5]">#{{ $value['refferal_code'] }}</td>
                                <td class="text-nowrap px-4 py-2 text-center">{{ $value['wallet_address'] }}</td>
                                <td class="text-nowrap px-4 py-2 text-center">{{ $value['my_direct'] }}</td>
                                <td class="text-nowrap px-4 py-2 text-center">{{ $value['my_team'] }}</td>
                                <td class="text-nowrap px-4 py-2 text-center text-[#30b8f5]">${{ $value['currentPackage'] }}</td>
                                <td class="text-nowrap px-4 py-2 text-center">{{ date('d-m-Y', strtotime($value['created_on'])) }}</td>
                                <td class="text-nowrap px-4 py-2 text-center">{{ date('d-m-Y', strtotime($value['currentPackageDate'])) }}</td>
                                <td class="text-nowrap px-4 py-2 text-center">{{ $value['rank'] == 0 ? "No Rank" : $value['rank'] }}</td>
                                <td class="text-nowrap px-4 py-2 text-center">{{ $value['level'] == 0 ? "No level" : $value['level'] }}</td>
                            </tr>
                            @endif
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection