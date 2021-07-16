@extends("crudbooster::admin_template")
@section("content")
<div class="box box-primary">
    <div class="box-header">
        <h5 class="box-title">&mdash; Tambah Area : {{ $lot['name'] }}</h5>
    </div>
    <div class="box-body no-padding ">
        <div class="map" id="map"></div>
    </div>
    <div class="box-footer">
        <form id="form" action="{{ CRUDBooster::mainPath('add-geojson') }}" method="POST">
            @csrf
            <input type="hidden" name="geojson" value="{{ $lot['geojson'] }}"/>
            <input type="hidden" name="id" value="{{ $lot['id'] }}"/>
        </form>
        <a href="{{ CRUDBooster::mainPath() }}" class="btn btn-warning">Kembali</a>
        <button id="save" class="pull-right btn btn-primary">Simpan</button>
    </div>
</div>
@endsection

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
<link rel="stylesheet" href="{{ asset('assets/css/leaflet-geoman.css') }}">
<style>
.map {
    height: 500px;
}
</style>
@endpush

@push('bottom')
<script src="https://unpkg.com/leaflet@latest/dist/leaflet.js"></script>
<script src="{{ asset('assets/js/leaflet-geoman.min.js') }}"></script>
<script>
$(document).ready(function(){
    var map = L.map('map').setView([{{ CRUDBooster::getSetting('lat') }}, {{ CRUDBooster::getSetting('lng') }}], {{ CRUDBooster::getSetting('zoom') }});

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: '&copy; TMD Spark',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoiYmFnemluc2lkZSIsImEiOiJja2xuYzB4NXUwaHJ4MnZxeWU5YnZsZjNiIn0.zO3Pq54RNRyX9zAeDu5Kpw'
    }).addTo(map);

    map.pm.addControls({
        drawMarker: false,
        drawRectangle: false,
        drawCircle: false,
        drawCircleMarker: false,
        drawPolygon: true,
        editPolygon: false,
        drawPolyline: false,
        deleteLayer: true,
    });

    map.pm.setPathOptions({  
        color: '#145984',  
        fillColor: '#fff',  
        fillOpacity: 0.4,  
    });  

    @if($lot['geojson'])
        const theCollection = L.geoJson({!! $lot["geojson"] !!}, {
            pointToLayer: (feature, latlng) => {
                if (feature.properties.customGeometry) {
                    return new L.Circle(latlng, feature.properties.customGeometry.radius);
                } else {
                    return new L.Marker(latlng);
                }
            },
            onEachFeature: (feature, layer) => {
                layer.addTo(map);
            },
        });

        theCollection.addTo(map);
        const b = theCollection.getBounds();
        map.fitBounds(b);
    @endif

    $('#save').click(function(){
        var data = map.pm.getGeomanDrawLayers();
        
        var fg = L.featureGroup();
        map.eachLayer((layer) => {
            if(layer instanceof L.Path || layer instanceof L.Marker){
                fg.addLayer(layer);
            }
        });
        $('input[name="geojson"]').val(JSON.stringify(fg.toGeoJSON()));

        setTimeout(function(){
            $('#form').submit();
        }, 1000);
    });
});
</script>
@endpush