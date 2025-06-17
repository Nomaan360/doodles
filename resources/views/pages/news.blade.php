@extends('layouts.app')

@section('title', 'News')

@section('content')
<section class="w-full p-3 md:p-8 mx-auto max-w-[1400px]">
    <div class="grid grid-cols-1 gap-5">
        <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
            <div class="flex flex-col items-center w-full gap-1 bg-[#101735] rounded-2xl p-4 h-full border border-white border-opacity-25">
                @if(isset($data['data']))
                        @if($data['data']['file_type'] == "image")
                        <img id="adVideo" src="{{ asset('storage/'.$data['data']['file']) }}" width="500" height="500" alt="Ad" class="block max-h-[95vh] max-w-[600px] h-auto w-full bg-[#ffdddd] p-0.5 rounded-xl">
                        @elseif($data['data']['file_type'] == "video")
                        <video id="adVideo" autoplay loop muted playsinline class="block max-h-[95vh] max-w-[600px] h-auto w-full bg-[#ffdddd] p-0.5 rounded-xl">
                            <source src="{{ asset('storage/'.$data['data']['file']) }}" type="video/mp4" loop autoplay playsinline>
                            Your browser does not support the video tag.
                        </video>
                        @else
                        <iframe
                            id="adVideo"
                            width="100%"
                            height="100%"
                            class="max-w-full max-h-full object-center pointer-events-none rounded-xl h-[50vh]"
                            src="https://www.youtube.com/embed/{{$data['data']['description']}}?autoplay=1&mute=0&controls=0&disablekb=1&modestbranding=1&rel=0&showinfo=0&iv_load_policy=3&fs=0"
                            title="Ad Video"
                            frameborder="0"
                            allow="autoplay; encrypted-media"
                            style="min-height: calc(100vh - 230px);"
                            allowfullscreen>
                        </iframe>
                        @endif
                @else
                        <img id="adVideo" src="{{ asset('assets/videos/ads1.jpg') }}" width="500" height="500" alt="Ad" class="block max-h-[95vh] max-w-[600px] h-auto w-full bg-[#ffdddd] p-0.5 rounded-xl">
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
