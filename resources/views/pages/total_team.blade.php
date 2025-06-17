@extends('layouts.app')

@section('title', 'My Team')

@section('content')
<section class="w-full p-3 md:p-8 mx-auto max-w-[1400px]">
    <h2 class="bg-gradient-to-r from-indigo-300 to-cyan-300 relative text-black rounded-sm p-3 text-lg font-normal leading-none mb-5 flex items-center gap-2">My Team</h2>
    <div class="p-4 rounded-xl w-full mx-auto border border-[#16204a] bg-[#101735] relative w-full h-full">
        <div class="overflow-x-auto">
                    <table id="tabledata1" class="w-full text-left border-collapse" style="padding-top: 15px;">
                        <thead>
                            <tr class="bg-white bg-opacity-10 text-white">
                                <th class="px-4 py-2">Sr.</th>
                                <th class="px-4 py-2 text-center"><span class="text-nowrap w-full block text-center">Package</span></th>
                                <th class="px-4 py-2 text-center"><span class="text-nowrap w-full block text-center">All Package</span></th>
                                <th class="px-4 py-2 text-center"><span class="text-nowrap w-full block text-center">Member Code</span></th>
                                <th class="px-4 py-2 text-center"><span class="text-nowrap w-full block text-center">Sponsor</span></th>
                                <th class="px-4 py-2 text-center"><span class="text-nowrap w-full block text-center">Registration Date</span></th>
                                <th class="px-4 py-2 text-center"><span class="text-nowrap w-full block text-center">Activation Date</span></th>
                                <th class="px-4 py-2 text-center"><span class="text-nowrap w-full block text-center">Rank</span></th>
                                <th class="px-4 py-2 text-center"><span class="text-nowrap w-full block text-center">Level</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data['data']) > 0)
                                @foreach ($data['data'] as $key => $value)
                                <tr>
                                    <td class="text-nowrap px-4 py-2">{{ $key + 1 }}</td>
                                    <td class="text-nowrap px-4 py-2 text-center">{{ $value['currentPackage'] }}</td>
                                    <td class="text-nowrap px-4 py-2 text-center">{{ $value['allPackages'] }}</td>
                                    <td class="text-nowrap px-4 py-2 text-center">{{ $value['refferal_code'] }}</td>
                                    <td class="text-nowrap px-4 py-2 text-center">{{ $value['sponser_code'] }}</td>
                                    <td class="text-nowrap px-4 py-2 text-center">{{date('d m, Y', strtotime($value['created_on']))}}</td>
                                    <td class="text-nowrap px-4 py-2 text-center">@if($value['currentPackageDate'] != '-') {{date('d-m-Y', strtotime($value['currentPackageDate']))}} @else - @endif</td>
                                    <td class="text-nowrap px-4 py-2 text-center">{{ $value['rank'] == 0 ? "No Rank" : $value['rank'] }}</td>
                                    <td class="text-nowrap px-4 py-2 text-center">{{ $value['level'] == 0 ? "No level" : $value['level'] }}</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                </div>
    </div>
</section>
@endsection