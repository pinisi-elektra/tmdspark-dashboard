@extends("crudbooster::admin_template")
@section("content")
<div id="mapContainer">
    <div class="map" id="map"></div>
</div>
@endsection

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
<style>
    #mapContainer {
        margin-top: 100px;
        margin-bottom: 50px;
        margin-left: 0px;
        height: -webkit-fill-available;
        width: -webkit-fill-available;
        position: absolute;
        top: 0;
        left: 0;
    }

    @media (min-width: 767px) {
        #mapContainer {
            margin-top: 50px;
            margin-bottom: 50px;
            margin-left: 250px;
        }
    }

    .map {
        height: 100%;
    }

    .select2-container {
        width: -webkit-fill-available !important;
    }

    #tbl > tbody > tr > td {
        white-space: pre-wrap;
    }

    .leaflet-popup-content {
        margin: 0;
        font-size: 10px;
    }

    .leaflet-popup-close-button {
       display: none; 
    }
</style>
@endpush

@push('bottom')
<script src="https://unpkg.com/leaflet@latest/dist/leaflet.js"></script>
<script src="https://unpkg.com/esri-leaflet@3.0.1/dist/esri-leaflet.js"></script>
<script src="https://unpkg.com/proj4@2.4.3"></script>
<script src="https://unpkg.com/proj4leaflet@1.0.1"></script>
<script>
$(window).load(function(){
    var selectedMarker = null;
    var selectedDeviceId = null;

    var markers = {};
    var polylines = {};
    var groups = [];

    var iconActive = L.icon({
        iconUrl: '{{asset('assets/images/markerActive.png')}}',
        iconSize: [15, 18],
    });

    var icon = L.icon({
        iconUrl: '{{asset('assets/images/marker.png')}}',
        iconSize: [15, 18],
    });

    var map = L.map('map').setView([{{ CRUDBooster::getSetting('lat') }}, {{ CRUDBooster::getSetting('lng') }}], {{ CRUDBooster::getSetting('zoom') }});
    L.esri.basemapLayer('Topographic').addTo(map);

    var daopLists = [];
    var selectorDaop = L.control({
        position: 'topright'
    });
    
    selectorDaop.onAdd = function(map) {
        var div = L.DomUtil.create('div', 'daopSelector');
            div.innerHTML = '<select class="form-control select2" style="width:100%;" id="daop_select"><option value="">Pilih Device</option></select>';
            div.firstChild.onmousedown = div.firstChild.ondblclick = L.DomEvent.stopPropagation;

        return div;
    };

    selectorDaop.addTo(map);

    $('.select2').select2({
        dropdownParent: $('.map'),
        containerCss : {
            textAlign: 'center',
            minWidth: '15em'
        },
    }).on('select2:select', function (e) {
        var x = e.params.data.id;
        $.each(markers, function(i, data){
            if(data._group_id != x) {
                map.removeLayer(data);
            }
            else {
                map.addLayer(data);
            }
        });

        $.each(polylines, function(i, data){
            if(data._group_id != x) {
                map.removeLayer(data);
            }
            else {
                map.addLayer(data);
            }
        });

        // if(markers[data]) {
        //     // map._layers[data].fire('click');
        // }
    });
    
    var colors = [
        '#f1c40f',
        '#2ecc71',
        '#3498db',
        '#9b59b6',
        '#34495e',
        '#c0392b',
        '#f1c40f',
        '#e67e22',
        '#e74c3c',
        '#d35400',
    ];

    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: '{{url('api/get_tests?token=685cdfd1dcaf9bfc75832b09305dc524')}}',
        success: function(data) {
            var obj = data;
            var lastDevice = null;
            var cnt = 0
            $.each(obj, function(i, datas) {
                cnt++;

                if(lastDevice != data.deviceId) {
                    lastDevice = data.deviceId;
                }

                var polygroups = [];
                var dot_groups = [];

                $.each(datas, function(j, dot) {
                    var lat = parseFloat(dot.lat);
                    var lng = parseFloat(dot.lng);

                    var center = L.latLng({
                        lat: lat,
                        lng: lng,
                    });
                    var bounds = center.toBounds(500);
                    var popupContent = `
                        <div class="text-center">
                            <b>Device ID : `+ dot.deviceId +`</b>
                        </div>
                    `;  

                    var popup = L.popup({"autoClose": false, "closeOnClick": null})
                    .setLatLng(center)
                    .setContent(popupContent)
                    
                    markers[dot.id] = L.marker(center, {
                        icon: icon
                    })
                    .bindPopup(popup)
                    .on("click", function(e) {
                        if(selectedMarker) {
                            selectedMarker.closePopup();
                            selectedMarker.setIcon(icon);
                            selectedMarker = null;
                        }
                        selectedMarker = e.target;
                        selectedMarker.setIcon(iconActive);

                        var latlng = e.target.getLatLng();
                        map.flyTo(latlng);
                    })
                    .on('add', function(e) {
                        e.target._group_id = dot.deviceId;
                        if(daopLists.indexOf(dot.deviceId) === -1) {
                            daopLists.push(dot.deviceId)
                            var optionElement = document.createElement("option");
                            optionElement.innerHTML = dot.deviceId;
                            optionElement.value = dot.deviceId;
                        
                            setTimeout(function(){
                                L.DomUtil.get("daop_select").appendChild(optionElement);
                            }, 250)
                        }
                    })
                    .addTo(map);

                    polygroups.push([lat, lng]);

                    polylines[dot.id] = new L.Polyline(polygroups, {
                        color: colors[cnt],
                        weight: 1,
                        opacity: 1,
                        smoothFactor: 5
                    }).on("add", function(e){
                        e.target._group_id = dot.deviceId;
                    }).addTo(map);

                    var deviceId = dot.deviceId;
                });
            });
        }
    });
});
</script>
@endpush