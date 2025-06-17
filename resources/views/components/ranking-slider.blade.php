<link rel="stylesheet" href="{{asset('assets/css/swiper-bundle.min.css')}}">
<div class="relative w-full overflow-hidden">
    <!-- Swiper Container -->
    <div class="swiper-container rankingsliderbox">
        <div class="swiper-wrapper">
            @foreach($data['levels'] as $key => $value)
            <div class="swiper-slide rounded bg-black">
                <div class="p-4 text-center text-white relative {{$data['user']['level'] == $value['id'] ? 'myrank' : ''}}">
                    <!-- <img src={{ asset('assets/images/rank/'.$value['id'].'.webp') }} width="200" height="200" alt="Ranking" class="h-20 w-auto mx-auto mb-3"> -->
                    <h3 class="flex items-center justify-center gap-1.5 text-base">
                        <strong>{{$value['level_display_name']}}</strong>
                        <!-- <span></span> -->
                    </h3>
                </div>
            </div>
            @endforeach
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>

    <!-- Navigation Buttons -->
    <!-- <button class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white p-2 rounded-full swiper-button-prev">
        ◀
    </button>
    <button class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white p-2 rounded-full swiper-button-next">
        ▶
    </button> -->
</div>
<script src="{{asset('assets/js/swiper-bundle.min.js')}}"></script>
<!-- Initialize Swiper -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        new Swiper('.swiper-container', {
            loop: false,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            slidesPerView: 6,
            spaceBetween: 15,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                300: {
                    slidesPerView: 2
                },
                370: {
                    slidesPerView: 2
                },
                575: {
                    slidesPerView: 3
                },
                992: {
                    slidesPerView: 4
                },
                1280: {
                    slidesPerView: 5
                },
                1400: {
                    slidesPerView: 6
                },
            }
        });
    });
</script>
<style>
    .myrank::after {
        content: "";
        display: block;
        width: calc(100% - 10px);
        height: calc(100% - 10px);
        top: 50%;
        left: 50%;
        position: absolute;
        z-index: 0;
        border: 2px solid #e1df22;
        border-radius: 10px;
        transform: translate(-50%, -50%);
    }

    .swiper-button-next,
    .swiper-button-prev {
        color: #fff;
        background-color: rgba(0, 0, 0, 0.4);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        top: 45%;
        bottom: unset;
        transform: translateY(50%);
    }

    .swiper-button-next::after,
    .swiper-button-prev::after {
        font-size: 8px;
        font-weight: bold;
    }

    .swiper-button-prev {
        left: 5px;
    }

    .swiper-button-next {
        right: 5px;
    }
</style>