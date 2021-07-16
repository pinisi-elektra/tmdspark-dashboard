@extends("crudbooster::admin_template")
@section("content")
<div id="mapContainer">
    <div class="map" id="map"></div>
</div>

<div id="sb" class="p-4 hidden">
    <div class="row">
        <div class="col-md-12">
            <legend>Informasi Harian</legend>
        </div>

        <div class="col-md-12">
            <h4 class="m-0" id="dataLotName"><i class="fas fa-circle-notch fa-spin text-primary"></i></h4>
            <h5 class="text-muted" id="dataLotAddress"><i class="fas fa-circle-notch fa-spin text-primary"></i></h5>
            <h5 class="text-muted">
                <b>Device Code</b>
                <br>
                <span id="dataDeviceCode"><i class="fas fa-circle-notch fa-spin text-primary"></i></span>
            </h5>
            <h5 class="text-muted">
                <b>Device Phone</b>
                <br>
                <span id="dataDevicePhone"><i class="fas fa-circle-notch fa-spin text-primary"></i></span>
            </h5>
            <h5 class="text-muted">
                <b>Trx Terakhir</b>
                <br>
                <span id="dataDeviceLastTrx"><i class="fas fa-circle-notch fa-spin text-primary"></i></span>
            </h5>
            <hr>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="small-box bg-green text-center">
                <div class="inner">
                  <h3>Rp <span id="dataTodayIncome"><i class="fas fa-circle-notch fa-spin"></i></span></h3>
                  <p>Pendapatan Hari Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="small-box bg-blue">
                <div class="inner">
                  <h3 id="dataTodayOpenBill"><i class="fas fa-circle-notch fa-spin"></i></h3>
                  <p>Tagihan Berjalan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="small-box bg-aqua">
                <div class="inner">
                  <h3 id="dataTodayClosedBill"><i class="fas fa-circle-notch fa-spin"></i></h3>
                  <p>Tagihan Selesai</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-md-12 table-responsive">
            <table id="tbl" class="table table-condensed table-hovered table-bordered">
                @foreach($categories as $category)
                <tr>
                    <td width="65%">{{ $category->name }}</td>
                    <td width="35%" class="text-right"><span  id="dataCountCategory{{ Str::slug($category->id) }}"><i class="fas fa-circle-notch fa-spin text-primary"></i></span> Unit</td>
                </tr>
                @endforeach
            </table>
        </div>

        <div class="col-md-12">
            <a class="btn btn-primary btn-block btn-lg dataLotID" target="_blank" href="{{ CRUDBooster::adminPath('open-transactions' ) }}" style="color: #fff">Laporan Tagihan Berjalan <i class="fas fa-chevron-right pull-right"></i></a>
            <a class="btn btn-primary btn-block btn-lg dataLotID" target="_blank" href="{{ CRUDBooster::adminPath('closed-transactions' ) }}" style="color: #fff">Laporan Transaksi <i class="fas fa-chevron-right pull-right"></i></a>
        </div>
    </div>
</div>
@endsection

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
<link rel="stylesheet" href="{{ asset('assets/css/L.Control.Sidebar.css') }}" />
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
</style>
@endpush

@push('bottom')
<script src="https://unpkg.com/leaflet@latest/dist/leaflet.js"></script>
<script src="{{ asset('assets/js/L.Control.Sidebar.js') }}"></script>
<script>
$(window).load(function(){
    $('#sb').removeClass('hidden');
    
    var collections;
    var map = L.map('map').setView([{{ CRUDBooster::getSetting('lat') }}, {{ CRUDBooster::getSetting('lng') }}], {{ CRUDBooster::getSetting('zoom') }});
    var sidebar = L.control.sidebar('sb', {
        closeButton: true,
        position: 'left',
    });

    map.addControl(sidebar);

    function style(feature) {
        return {
            fillColor: '#fff', 
            fillOpacity: 0.5,  
            weight: 2,
            opacity: 1,
            color: '#145984',
            dashArray: '3'
        };
    }

	var highlight = {
		color: '#145984',  
        fillColor: '#145984',  
        fillOpacity: 0.4,  
	};
    
    function getLotDetail(id) {
        console.log(id) 
        $.ajax({
            url: "{{ url('api/get_lot?token=685cdfd1dcaf9bfc75832b09305dc524') }}&id=" + id, 
            success: function(data, status){
                if(data.api_message == "success") {
                    var data = data.data;
                    var visible = sidebar.isVisible();
                    // var init = '<i class="fas fa-circle-notch fa-spin text-primary"></i>';
                    $('#dataLotName').html(data.name ?? '-');
                    $('#dataLotAddress').html(data.address ?? '-');
                    $('#dataDeviceCode').html(data.device ? data.device.device_code : '-');
                    $('#dataDevicePhone').html(data.device ? data.device.phone : '-');
                    $('#dataDeviceLastTrx').html(data.closed_trx && data.closed_trx[0] ? data.closed_trx[0].stop_at : '-');

                    $('#dataTodayClosedBill').html(data.closedBill);
                    $('#dataTodayOpenBill').html(data.openBill);
                    $('#dataTodayIncome').html(data.todayIncome);

                    $.each(data.categories, function(i, item) {
                        $('#dataCountCategory' + i).html(item);
                    });


                    $('.dataLotID').each(function(){
                        var old = $(this).attr('href');
                        $(this).attr('href', old + "?parking_id=" + data.id);
                    });
                    
                    if(!visible)
                        sidebar.toggle();
                }
            }
        });
    }
    
    function forEachFeature(feature, layer) {
        var popupContent = `
            <div class="text-center">
                <b>`+ feature.properties.name +`</b><br>
                `+ feature.properties.address +`
            </div>
        `;  

        var bounds = layer.getBounds();
        var center = bounds.getCenter();

        function getLayerAction() {
            collections.setStyle(style); 
            layer.setStyle(highlight);  
            map.fitBounds(bounds);
            getLotDetail(feature.properties.id)
        }

        var marker = L.marker(center)
        .bindPopup(popupContent)
        .bindTooltip(feature.properties.name)
        .addTo(map)
        .on("click", function (e) { 
            getLayerAction();
        }); 

        layer.on("click", function (e) { 
            getLayerAction();
        }); 

        var optionElement = document.createElement("option");
        optionElement.innerHTML = feature.properties.name;
        optionElement.value = L.stamp(layer);
    
        setTimeout(function(){
            L.DomUtil.get("marker_select").appendChild(optionElement);
        }, 250)
    }

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: '&copy; TMD Spark',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoiYmFnemluc2lkZSIsImEiOiJja2xuYzB4NXUwaHJ4MnZxeWU5YnZsZjNiIn0.zO3Pq54RNRyX9zAeDu5Kpw'
    }).addTo(map);

    var lots = {!! collect($lots) !!}

    $.getJSON("{{ url('api/get_lots?token=685cdfd1dcaf9bfc75832b09305dc524') }}", function(data) {
        collections = L.geoJson(data.data, {
            onEachFeature: forEachFeature,
            style: style
        }).addTo(map);
    });
        
    map.on('mousedown', function() {
        collections.setStyle(style); 
        sidebar.hide();
    });

    var selector = L.control({
        position: 'topright'
    });

    selector.onAdd = function(map) {
        var div = L.DomUtil.create('div', 'lotSelector');
            div.innerHTML = '<select class="form-control select2" style="width:100%;" id="marker_select"><option value="">Pilih Parkiran</option></select>';
            div.firstChild.onmousedown = div.firstChild.ondblclick = L.DomEvent.stopPropagation;

        return div;
    };

    selector.addTo(map);

    $('#marker_select').select2({
        dropdownParent: $('.map'),
        containerCss : {
            textAlign: 'center',
            minWidth: '15em'
        },
    }).on('select2:select', function (e) {
        var data = e.params.data.id;
        if(map._layers[data]) {
            map._layers[data].fire('click');
        }
    });
});
</script>
@endpush