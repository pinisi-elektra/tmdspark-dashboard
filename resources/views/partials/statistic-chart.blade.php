<div class="row">
    <div class="col-md-12 animated fadeIn">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                @if($yearlyStatisticChart)
                <li class="{{ $dailyStatisticChart ? '' : ($monthlyStatisticChart ? 'active' : ($yearlyStatisticChart ? 'active' : '')) }}">
                    <a href="#growth-yearly" data-toggle="tab">
                        Tahun
                    </a>
                </li>
                @endif

                @if($monthlyStatisticChart)
                <li class="{{ $dailyStatisticChart ? '' : ($monthlyStatisticChart ? 'active' : '') }}">
                    <a href="#growth-monthly" data-toggle="tab">
                        Bulan
                    </a>
                </li>
                @endif

                @if($dailyStatisticChart)
                <li class="{{ $dailyStatisticChart ? 'active' : '' }}">
                    <a href="#growth-daily" data-toggle="tab">
                        Hari
                    </a>
                </li>
                @endif

                <li class="pull-left header">
                    <i class="fas fa-chart-bar"></i> Grafik Pertumbuhan
                </li>
            </ul>

            <div class="tab-content no-padding">
                @if($yearlyStatisticChart)
                <div class="chart tab-pane animated fadeIn {{ $dailyStatisticChart ? '' : ($monthlyStatisticChart ? 'active' : ($yearlyStatisticChart ? 'active' : '')) }}" id="growth-yearly">
                    <div class="p-5">
                        {!! $yearlyStatisticChart->container() !!}
                    </div>
                </div>
                @endif

                @if($monthlyStatisticChart)
                <div class="chart tab-pane animated fadeIn {{ $dailyStatisticChart ? '' : ($monthlyStatisticChart ? 'active' : '') }}" id="growth-monthly">
                    <div class="p-5">
                        {!! $monthlyStatisticChart->container() !!}
                    </div>
                </div>
                @endif

                @if($dailyStatisticChart)
                <div class="chart tab-pane animated fadeIn {{ $dailyStatisticChart ? 'active' : '' }}" id="growth-daily">
                    <div class="p-5">
                        {!! $dailyStatisticChart->container() !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('bottom')
    <script>
    </script>

    @if($dailyStatisticChart)
        {!! $dailyStatisticChart->script() !!}
    @endif

    @if($monthlyStatisticChart)
        {!! $monthlyStatisticChart->script() !!}
    @endif

    @if($yearlyStatisticChart)
        {!! $yearlyStatisticChart->script() !!}
    @endif

    <script>
        $(function() {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $('#chartjs-tooltip').remove();

                // Save For Update
                // Chart.helpers.each(Chart.instances, function(instance){
                //     instance.data.datasets[0].data[0] = 9;
                //     instance.update();
                // });
            });
        });
    </script>
@endpush
