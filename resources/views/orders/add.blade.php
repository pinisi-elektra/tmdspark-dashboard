@extends("crudbooster::admin_template")
@section("content")
<form method="post" action="{{ CRUDBooster::mainpath("add-save") }}">
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-add"></i> Add Order
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <select id="selectSeller" class='form-control' name="seller_id" required>
                            @foreach($sellers as $key => $seller)
                                <option data-details="{{ json_encode($seller) }}" data-photo="{{ Avatar::create($seller['name'])->toBase64() }}" value="{{ $seller['id'] }}" {{ $seller['id'] == CRUDBooster::myId() ? 'selected' : '' }}>{{ $seller['name'] == CRUDBooster::myName() ? 'My Stocks' : $seller['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    @foreach($sellers->first()->stocks->get() as $list)
                    {{ dd($list) }}
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="sc-product-item">
                            <img class="product_image" data-name="product_image" src="{{ url($list['photo']) }}" alt="{{ $list['name'] }}">
                            <h4 data-name="product_name">{{ Str::limit($list['name']) }}</h4>
                            <p data-name="product_desc">
                                Price : {{ number_format($list['price']) }}<br>
                                Current Stock :  {{ number_format($list['stocks']['current_stock']) }}<br>
                            </p>
                            <input name="product_price" value="{{ $list['price'] }}" type="hidden" />
                            <input name="product_stock" value="{{ $list['stocks']['current_stock'] }}" type="hidden" />
                            <input name="product_id" value="12" type="hidden" />
                            <button class="sc-add-to-cart btn btn-success">Add to cart</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div id="smartcart"></div>
        </div>
        <div class="panel-footer">
            <input type="submit" class="btn btn-primary" value="Save changes" />
        </div>
    </div>
</form>
@endsection

@push('bottom')
<style type="text/css">
    .sc-product-item {
        margin-bottom: 20px;
    }

    .product_image {
        width: 100%
    }

    .select2-container {
        width: 100%;
    }

    .select2-container--default .select2-selection--single {
        border-radius: 0px !important;
    }

    .select2-container .select2-selection--single {
        padding: 10px 0;
        height: fit-content;
        margin: auto 0;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: unset;
    }

    .select2-selection__arrow {
        height: -webkit-fill-available !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #145984 !important;
        border-color: #1b699a !important;
        color: #fff !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff !important;
    }

    .img-selection {
        width: 40px;
        height: 40px;
        margin-right: 10px;
    }

    .select2-selection__rendered, .select2-results__option {
        display: flex !important;
        margin: auto 0;
    }

    .select2-selection__rendered > *, .select2-results__option > * {
        align-self: center;
    }
</style>
@endpush

@push('bottom')
    <script>
        function formatData (data) {
            var element = $(data.element);
            var photo = element.data('photo');
            var details = element.data('details');

            if(details) {

            var addresses = "";

            if(details.address != null)
                addresses += details.address + ", ";

            if(details.city.name != null)
                addresses += details.city.name + ", ";

            if(details.province.name != null)
                addresses += details.province.name;
            }

            var $result = "";
            if(photo) {
                $result= $(`
                    <div>
                        <img src="` + photo + `" class="img-selection img-circle"/>
                    </div>
                    <div>
                        <span>` + data.text + `</span><br>
                        <small class="text-muted">` +  addresses + `</small>
                    </div>
                `);
            }
            return $result;
        };

        $(document).ready(function(){
            var sc = $('#smartcart');
            sc.smartCart();

            $("#selectSeller").select2({
                templateResult: formatData,
                templateSelection: formatData
            });

            $("#selectSeller").on('change', function(e){
                sc.addClass("hidden");
                var data = $("#selectSeller option:selected").text();
                sc.removeClass("hidden");
                console.log(data);
            });
        });
    </script>

@endpush
