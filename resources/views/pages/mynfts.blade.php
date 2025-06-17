@extends('layouts.app')

@section('title', 'Login')

@section('content')
<section class="w-full p-3 md:p-8 mx-auto max-w-[1400px]">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($data['nfts'] as $key => $value)
        <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
            <div class="flex flex-col items-center w-full gap-1 bg-[#101735] rounded-2xl p-4 h-full border border-white border-opacity-25">
                <img src={{ asset('assets/images/nfts/'.$value['package_id'].'.webp') }} width="350" height="350" alt="Logo" class="w-full h-auto rounded-xl">
                <h3 class="text-sm sm:text-base md:text-2xl leading-none flex items-start justify-between capitalize my-3">NFT ${{$value['amount']}}</h3>
                <!-- Skate Button Start-->
                <div class="flex flex-col gap-2 mb-3 max-w-full text-left">
                    <h3 class="text-sm sm:text-base opacity-75 leading-none flex items-center justify-between py-3 border-b border-white border-opacity-25">Token Id : <span class="text-white font-bold">{{$value['tokenId']}}</span></h3>

                    <div class="w-full">
                        <h3 class="text-base leading-none my-1">Nft Address :</h3>
                        <div class="bg-white bg-opacity-5 px-2 py-0.5 leading-none rounded flex items-center justify-between">
                            <span id="referral-link" class="text-xs text-xs truncate text-ellipsis overflow-hidden">{{$value['nftAddress']}}</span>
                            <button onclick="copyText('{{$value['nftAddress']}}'); showToast('success', 'Copied to clipboard!')" class="ml-2 p-1 border-l border-white border-opacity-20">
                                <svg class="w-6 h-6 min-w-6 min-h-6 ml-2" viewBox="0 0 1024 1024">
                                    <path fill="#ffffff" d="M768 832a128 128 0 0 1-128 128H192A128 128 0 0 1 64 832V384a128 128 0 0 1 128-128v64a64 64 0 0 0-64 64v448a64 64 0 0 0 64 64h448a64 64 0 0 0 64-64h64z"></path>
                                    <path fill="#ffffff" d="M384 128a64 64 0 0 0-64 64v448a64 64 0 0 0 64 64h448a64 64 0 0 0 64-64V192a64 64 0 0 0-64-64H384zm0-64h448a128 128 0 0 1 128 128v448a128 128 0 0 1-128 128H384a128 128 0 0 1-128-128V192A128 128 0 0 1 384 64z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="w-full">
                        <h3 class="text-base leading-none my-1">Transaction Hash :</h3>
                        <div class="bg-white bg-opacity-5 px-2 py-0.5 leading-none rounded flex items-center justify-between">
                            <span id="referral-link" class="text-xs text-xs truncate text-ellipsis overflow-hidden">{{$value['nftTransactionHash']}}</span>
                            <button onclick="copyText('{{$value['nftTransactionHash']}}'); showToast('success', 'Copied to clipboard!')" class="ml-2 p-1 border-l border-white border-opacity-20">
                                <svg class="w-6 h-6 min-w-6 min-h-6 ml-2" viewBox="0 0 1024 1024">
                                    <path fill="#ffffff" d="M768 832a128 128 0 0 1-128 128H192A128 128 0 0 1 64 832V384a128 128 0 0 1 128-128v64a64 64 0 0 0-64 64v448a64 64 0 0 0 64 64h448a64 64 0 0 0 64-64h64z"></path>
                                    <path fill="#ffffff" d="M384 128a64 64 0 0 0-64 64v448a64 64 0 0 0 64 64h448a64 64 0 0 0 64-64V192a64 64 0 0 0-64-64H384zm0-64h448a128 128 0 0 1 128 128v448a128 128 0 0 1-128 128H384a128 128 0 0 1-128-128V192A128 128 0 0 1 384 64z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <a href="javascript:void(0);" onclick="importNFT('{{$value['nftAddress']}}', {{$value['tokenId']}});" class="w-full relative inline-block p-px font-semibold leading-none text-white cursor-pointer rounded-sm">
                        <span class="absolute inset-0 rounded-full bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500 p-[2px]"></span>
                        <span class="relative z-10 block px-4 py-2 rounded-sm">
                            <div class="relative z-10 flex items-center space-x-2 justify-center">
                                <span class="transition-all duration-500 group-hover:translate-x-1">Import</span>
                                <svg class="w-6 h-6 transition-transform duration-500 group-hover:translate-x-1" data-slot="icon" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                    <path clip-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" fill-rule="evenodd"></path>
                                </svg>
                            </div>
                        </span>
                    </a>
                </div>
                <!-- Skate Button End -->
            </div>
        </div>
        @endforeach
        <!-- AdSense Native Ad Unit (after last card) -->
        <div class="relative rounded-none shadow-md block text-left p-0 bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
            <div class="flex flex-col items-center w-full gap-1 bg-[#101735] rounded-2xl p-4 h-full border border-white border-opacity-25">
                <div class="w-full h-full flex flex-col justify-center items-center text-white text-center py-8">
                    <h3 class="text-lg font-bold mb-4">Sponsored Ad</h3>
                    <!-- Popup Ad -->
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-8097613720905089"
                         data-ad-slot="8255909285"
                         data-ad-format="auto"
                         data-full-width-responsive="true"></ins>
                    <script>
                         (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
            </div>
        </div>
    </div>

    @if(count($data['nfts']) == 0)
        <h3 class="text-base md:text-xl leading-none flex items-start justify-center mx-auto capitalize my-3 text-center mt-5">NO NFT'S FOUND</h3>
    @endif

    <!-- button Topup Balance start -->

</section>
<script type="text/javascript">
    function copyText(Text) {
        navigator.clipboard.writeText(Text).catch(() => {
            console.error("Failed to copy text!");
        });
    }
</script>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8097613720905089" crossorigin="anonymous"></script>
@endsection

@section('script')


<script src="{{asset('web3/ethers.umd.min.js')}}"></script>

<script src="{{asset('web3/web3.min.js')}}"></script>

<script src="{{asset('web3/web3.js')}}"></script>
<script type="text/javascript">
    async function importNFT(nft, tokenId) {
        var walletAddress = await doConnect();
        try{
            await window.ethereum.request({
              method: 'wallet_watchAsset',
              params: {
                type: 'ERC721', // This currently won't work in MetaMask
                options: {
                  address: nft,
                  tokenId: tokenId.toString(),
                  symbol: 'Doodles',
                  image: "{{ asset('assets/images/nfts/1.webp') }}",
                  decimals: 0,
                },
              },
            });
        }catch(e)
        {
            showToast("warning", e.data ? e.data.message : e.message);
        }
    }
</script>
@endsection