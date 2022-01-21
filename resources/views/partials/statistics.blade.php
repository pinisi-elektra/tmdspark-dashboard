{{-- <div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 animated fadeIn">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 animated fadeIn">
                <div class="info-box bg-blue">
                    <span class="info-box-icon">
                        <i class="fas fa-globe"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Dashboard</span>
                        <span class="info-box-number">
                            <h3>
                                <span class="countUp">{{ number_format($projects->count()) }}</span>
                            </h3>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 animated fadeIn">
                <div class="info-box bg-green">
                    <span class="info-box-icon">
                        <i class="fas fa-check-circle"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total WP Aktif</span>
                        <span class="info-box-number">
                            <h3>
                                <span class="countUp">{{ number_format($projects->sum('merchant_active_count')) }}</span> WP
                            </h3>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 animated fadeIn">
                <div class="info-box bg-red">
                    <span class="info-box-icon">
                        <i class="fas fa-times-circle"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total WP Inactive</span>
                        <span class="info-box-number">
                            <h3>
                                <span class="countUp">{{ number_format($projects->sum('merchant_inactive_count')) }}</span> WP
                            </h3>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 animated fadeIn">
        <legend>Statistik Alat</legend>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                @forelse($projects as $key => $project)
                <li class="{{ $loop->iteration == 1 ? 'active' : '' }}">
                    <a href="#tab_{{ $loop->iteration }}" data-toggle="tab">
                        {{ str_replace("Indonesia ", "", $key) }}
                    </a>
                </li>
                @endforeach

                <li class="pull-left header">
                    <h5><i class="fa fa-circle text-success mr-2 animated flash infinite" style="font-size: 12px"></i> Active/Inactive</h5>
                </li>
            </ul>

            <div class="tab-content no-padding">
                @forelse($projects as $groups)
                    <div class="tab-pane {{ $loop->iteration == 1 ? 'active' : '' }}" id="tab_{{ $loop->iteration }}">
                        <table class="table table-hovered table-condensed table-bordered text-center animated fadeIn">
                            @forelse($groups as $key => $project)
                            <tr>
                                <td width="10%">
                                    {{ $loop->iteration }}<br>
                                </td>
                                <td class="text-center">
                                    <h4 class="mb-1">{{ $key }}</h4>
                                    @foreach($project as $key1 => $solutions)
                                        <span class="p-2 badge" style="background: transparent">
                                            <small class="text-black">{{ $key1 }}</small><br>
                                            @foreach($solutions as $key2 => $solution)
                                                <span style="font-size: 20px" class="text-{{ $key2 == "Active" ? "green" : "red" }}">{{ number_format($solution) }}</span>
                                                @if ($key2 !== array_key_last($solutions))
                                                <span class="text-black" style="font-size: 20px">/</span>
                                                @endif
                                            @endforeach
                                        </span>
                                    @endforeach
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td>
                                    Nama
                                </td>
                            </tr>
                            @endforelse
                        </table>
                    </div>
                @endforeach
            </div>

            {{-- <div class="box box-primary mb-0">
                <div class="box-body no-padding">

                </div>
            </div> --}}
        </div>
    </div>
</div>


{{-- <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 animated fadeIn">
        <div class="info-box bg-green">
            <span class="info-box-icon">
                <i class="fas fa-house-user"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Jumlah Proyek</span>
                <span class="info-box-number">
                    <small>
                        <span class="countUp">{{ number_format($project->count()) }}</span> Active / <span class="countUp">{{ number_format(rand(100,200)) }}</span> Inactive
                    </small>
                </span>

                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>

                <span class="progress-description text-right">
                    Update: {{ now()->format('d M y - H:i') }}
                </span>
            </div>
        </div>
    </div> --}}

@push('head')
<style>
    .table>tbody>tr>td {
        vertical-align: middle;
    }
</style>
@endpush

@push('bottom')
<script>
    $(window).load(function(){
        $('.countUp').counterUp({
            time: 750
        });
    })
</script>
@endpush
