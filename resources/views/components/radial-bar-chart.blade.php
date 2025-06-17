<div class="relative flex flex-col gap-4 overflow-hidden text-white bg-transparent md:flex-row md:items-center">
    <div class="w-max rounded-lg p-px text-white relative bg-gradient-to-br from-yellow-500 via-pink-500 to-blue-500">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            aria-hidden="true"
            class="h-14 w-14 bg-[#101735] p-3 rounded-lg">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3"></path>
        </svg>
    </div>
    <div>
        <h6 class="block font-sans text-base font-semibold leading-relaxed tracking-normal text-blue-gray-900 antialiased">
            Capping Chart
        </h6>
        <p class="block max-w-sm font-sans text-sm font-normal leading-normal opacity-50 antialiased">
            Visualize your capping with this interactive chart.
        </p>
    </div>
</div>
<div class="py-6 mt-4 grid place-items-center px-2">
    <div id="radial-bar-chart"></div>
</div>

<script>
    window.addEventListener("load", function() {
        var options = {
            series: [{{$data['user']['roi_income'] + $data['user']['level_income'] + $data['user']['royalty'] + $data['user']['reward'] + $data['user']['direct_income']}}, {{$data['self_investment'] * 5}}, {{$data['total_withdraw']}}],
            chart: {
                height: 350,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: {
                            fontSize: '22px',
                        },
                        value: {
                            fontSize: '16px',
                            color: '#ffffff',
                            formatter: function(w) {
                                return "$" + w
                            }
                        },
                        total: {
                            show: true,
                            label: 'Capping',
                            color: '#ffffff',
                            formatter: function(w) {
                                return "5x"
                            }
                        }
                    }
                }
            },
            labels: ['Income', '5x Return', 'Withdraw'],
            responsive: [{
                breakpoint: 768,
                options: {
                    chart: {
                        height: 250
                    }
                }
            }]
        };

        var rchart = new ApexCharts(document.querySelector("#radial-bar-chart"), options);
        rchart.render();
    });
</script>