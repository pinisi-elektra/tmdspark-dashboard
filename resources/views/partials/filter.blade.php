<div class="row">
    <div class="col-md-12 animated fadeIn">
        <div class="box box-primary">
            <div class="box-header">
                <h5 class="box-title">&mdash; Filter Data</h5>
            </div>
            <div class="box-body">
                <div class="row">
                    <form method="GET" action="{{ CRUDBooster::adminPath() }}">
                        <div class="form-group col-xs-12 col-md-4 col-lg-4">
                            <select id="province_id" name="province_id" class="form-control select2" {{ collect($provinces)->count() > 0 ? '' : 'disabled' }}>
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $key => $value)
                                    <option value="{{ $value['id'] }}" {{ $value['id'] == session('province_id') ? 'selected' : '' }}>{{ $value['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xs-12 col-md-4 col-lg-4">
                            <select id="city_id" name="city_id" class="form-control select2" {{ collect($cities)->count() > 0 ? '' : 'disabled' }}>
                                <option value="">Pilih Kota/Kabupaten</option>
                                @foreach($cities as $key => $value)
                                    <option value="{{ $value['id'] }}" {{ $value['id'] == session('city_id') ? 'selected' : '' }}>{{ $value['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xs-12 col-md-4 col-lg-4">
                            <select id="district_id" name="district_id" class="form-control select2" {{ collect($districts)->count() > 0 ? '' : 'disabled' }}>
                                <option value="">Pilih Kecamatan</option>
                                @foreach($districts as $key => $value)
                                    <option value="{{ $value['id'] }}" {{ $value['id'] == session('district_id') ? 'selected' : '' }}>{{ $value['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xs-12 col-lg-12 text-right mb-0">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                            @if(session('province_id') || session('city_id') || session('district_id'))
                            <a href="{{ CRUDBooster::adminPath() }}" class="btn btn-danger">Hapus</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('bottom')
<script>
$(function(){
    $('.select2').select2();
});
</script>
@endpush
