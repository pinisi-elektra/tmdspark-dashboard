@extends("crudbooster::admin_template")
@section("content")
<div class="box box-primary">
    <div class="box-body p-5">
        <div class="content-invoice">
            <form id="invoice-form" enctype="multipart/form-data" class="invoice-form" role="form" method="post" action="{{ (@$row) ? CRUDBooster::mainpath("edit-save/$row->id") : CRUDBooster::mainpath("add-save") }}">
                @csrf
                <div class="row">
                    <div class="col-xs-12">
                        <img id="logo" class="lazy-img" title="{!! (CRUDBooster::getSetting("appname") == "CRUDBooster") ? "CRUDBooster" : CRUDBooster::getSetting("appname") !!}" data-src="{{ CRUDBooster::getSetting("logo") ? asset(CRUDBooster::getSetting("logo")) : asset("vendor/crudbooster/assets/logo_crudbooster.png") }}" />
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <h5>Dari</h5>
                        <p>{{ CRUDBooster::getSetting('nama_perusahaan') ?? 'Mohon Setting Data Perusahaan' }}</p>
                        <p>{!! nl2br(CRUDBooster::getSetting('alamat_perusahaan')) ?? 'Mohon Setting Data Perusahaan' !!}</p>
                        <p>{{ CRUDBooster::getSetting('nomor_telepon_perusahaan') ?? 'Mohon Setting Data Perusahaan' }} // {{ CRUDBooster::getSetting('email_perusahaan') ?? 'Mohon Setting Data Perusahaan' }}</p>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <h5>Untuk</h5>
                        <div class="form-group">
                            <input value="{{ old('receiver_name') ?? $row->receiver_name }}" type="text" class="form-control" name="receiver_name" id="receiver_name" placeholder="Masukkan nama penerima" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="3" name="receiver_address" id="receiver_address" placeholder="Masukkan alamat penerima" required>{{ old('receiver_address') ?? $row->receiver_address }}</textarea>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <h5>#No Invoice</h5>
                        <div class="form-group">
                            <input value="{{ old('invoice_code') ?? $row->invoice_code }}" type="text" class="form-control" name="invoice_code" id="invoice_code" placeholder="Masukkan nomor invoice" autocomplete="off" required>
                        </div>

                        <h5>Tanggal Invoice</h5>
                        <div class="form-group">
                            <input value="{{ old('invoice_dt') ?? $row->invoice_dt }}" type="date" class="form-control" name="invoice_dt" id="invoice_dt" placeholder="Masukkan tanggal invoice" autocomplete="off" required>
                        </div>

                        <h5>Batas Waktu Pembayaran</h5>
                        <div class="form-group">
                            <input value="{{ old('due_dt') ?? $row->due_dt }}" type="date" class="form-control" name="due_dt" id="due_dt" placeholder="Masukkan batas waktu pembayaran" autocomplete="off">
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <table class="table table-bordered table-hover" id="invoiceItem">
                            <tr>
                                <th width="2%">
                                    <label>
                                        <input id="checkAll" type="checkbox">
                                    </label>
                                </th>
                                <th width="38%">Item</th>
                                <th width="15%">Qty</th>
                                <th width="15%">Rate</th>
                                <th width="15%">Amount</th>
                            </tr>
                            @forelse($childs as $key => $item)
                            <tr>
                                <td>
                                    <label>
                                        <input class="itemRow" type="checkbox">
                                    </label>
                                </td>
                                <td>
                                    <input type="text" name="item_name[]" id="item_name_{{ $loop->iteration }}" value="{{ $item->item_name }}" class="form-control" autocomplete="off" required>
                                </td>
                                <td>
                                    <input type="number" name="quantity[]" id="quantity_{{ $loop->iteration }}" value="{{ $item->quantity }}" class="form-control quantity" autocomplete="off" min="0" required>
                                </td>
                                <td>
                                    <input type="number" name="price[]" id="price_{{ $loop->iteration }}" value="{{ $item->price }}" class="form-control price" autocomplete="off" min="0" required>
                                </td>
                                <td>
                                    <input type="number" name="total[]" id="total_{{ $loop->iteration }}" value="{{ $item->total }}" class="form-control total" autocomplete="off" value="0" required readonly>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td>
                                    <label>
                                        <input class="itemRow" type="checkbox">
                                    </label>
                                </td>
                                <td>
                                    <input type="text" name="item_name[]" id="item_name_1" class="form-control" autocomplete="off" required>
                                </td>
                                <td>
                                    <input type="number" name="quantity[]" id="quantity_1" class="form-control quantity" autocomplete="off" min="0" required>
                                </td>
                                <td>
                                    <input type="number" name="price[]" id="price_1" class="form-control price" autocomplete="off" min="0" required>
                                </td>
                                <td>
                                    <input type="number" name="total[]" id="total_1" class="form-control total" autocomplete="off" value="0" required readonly>
                                </td>
                            </tr>
                            @endforelse
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 text-right">
                        <button class="btn btn-danger delete" id="removeRows" type="button">
                            <i class="fas fa-minus-circle mr-2"></i> Hapus Baris Terpilih
                        </button>
                        <button class="btn btn-success" id="addRows" type="button">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Baris
                        </button>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                        <h5>Note</h5>
                        <div class="form-group">
                            <textarea class="form-control txt" rows="5" name="note" id="note" placeholder="Tambahkan note jika ada...">{{ old('note') ?? $row->note }}</textarea>
                        </div>
                        <br>
                        <div class="form-group">
                            <a href="{{ CRUDBooster::mainPath() }}" class="btn btn-warning btn-lg">
                                <i class="fas fa-chevron-left mr-2"></i> Kembali
                            </a>

                            <button type="submit" class="btn btn-success btn-lg pull-right">
                                <i class="fas fa-plus-circle mr-2"></i> Simpan Invoice
                            </button>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label>Total Sebelum Pajak</label>
                            <div class="input-group">
                                <div class="input-group-addon currency">Rp</div>
                                <input value="{{ old('total_before_tax') ?? ($row->total_before_tax ?? '0') }}" type="number" class="form-control" name="total_before_tax" id="total_before_tax" placeholder="Total Sebelum Pajak" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Persentase Pajak</label>
                            <div class="input-group">
                                <input value="{{ old('tax_per') ?? ($row->tax_per ?? '0') }}" type="number" class="form-control" name="tax_per" id="tax_per" placeholder="Persentase Pajak" min="0">
                                <div class="input-group-addon">%</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Total Pajak</label>
                            <div class="input-group">
                                <div class="input-group-addon currency">Rp</div>
                                <input value="{{ old('total_tax') ?? ($row->total_tax ?? '0') }}" type="number" class="form-control" name="total_tax" id="total_tax" placeholder="Total Pajak" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Total Setelah Pajak</label>
                            <div class="input-group">
                                <div class="input-group-addon currency">Rp</div>
                                <input value="{{ old('total_after_tax') ?? ($row->total_after_tax ?? '0') }}" type="number" class="form-control" name="total_after_tax"  id="total_after_tax" placeholder="Total Setelah Pajak" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Total Diterima</label>
                            <div class="input-group">
                                <div class="input-group-addon currency">Rp</div>
                                <input value="{{ old('amount_paid') ?? ($row->amount_paid ?? '0') }}" type="number" class="form-control" name="amount_paid" id="amount_paid" placeholder="Total Diterima" min="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Total Belum Diterima</label>
                            <div class="input-group">
                                <div class="input-group-addon currency">Rp</div>
                                <input value="{{ old('total_amount_due') ?? ($row->total_amount_due ?? '0') }}" type="number" class="form-control" name="total_amount_due" id="total_amount_due" placeholder="Total Belum Diterima" readonly>
                            </div>
                        </div>
                    </span>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('bottom')
<style type="text/css">
    input[type=number]{ -moz-appearance: textfield; }
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }

    #logo {
        max-width: 200px;
        margin-bottom: 10px;
    }

    .form-group {
        margin-bottom: 10px;
    }

    h5 {
        margin-bottom: 10px;
        font-weight: bold;
    }
</style>
@endpush

@push('bottom')
<script>
$(function(){
	$(document).on('click', '#checkAll', function() {
		$(".itemRow").prop("checked", this.checked);
    });

	$(document).on('click', '.itemRow', function() {
		if ($('.itemRow:checked').length == $('.itemRow').length) {
			$('#checkAll').prop('checked', true);
		} else {
			$('#checkAll').prop('checked', false);
		}
    });

	var count = $(".itemRow").length;
	$(document).on('click', '#addRows', function() {
		count++;
		var htmlRows = '';
		htmlRows += '<tr>';
		htmlRows += '<td><input class="itemRow" type="checkbox"></td>';
		htmlRows += '<td><input type="text" name="item_name[]" id="item_name_'+count+'" class="form-control" autocomplete="off"></td>';
		htmlRows += '<td><input type="number" name="quantity[]" id="quantity_'+count+'" class="form-control quantity" autocomplete="off"></td>';
		htmlRows += '<td><input type="number" name="price[]" id="price_'+count+'" class="form-control price" autocomplete="off"></td>';
		htmlRows += '<td><input type="number" name="total[]" id="total_'+count+'" class="form-control total" autocomplete="off"></td>';
		htmlRows += '</tr>';
		$('#invoiceItem').append(htmlRows);
    });

	$(document).on('click', '#removeRows', function(){
		$(".itemRow:checked").each(function() {
			$(this).closest('tr').remove();
		});
		$('#checkAll').prop('checked', false);
		calculateTotal();
    });

	$(document).on('blur, keyup', "[id^=quantity_]", function(){
		calculateTotal();
    });

	$(document).on('blur, keyup', "[id^=price_]", function(){
		calculateTotal();
	});
	$(document).on('blur, keyup', "#tax_per", function(){
		calculateTotal();
    });

	$(document).on('blur, keyup', "#amount_paid", function(){
		var amount_paid = $(this).val();
		var total_after_tax = $('#total_after_tax').val();
		if(amount_paid && total_after_tax) {
			total_after_tax = total_after_tax-amount_paid;
			$('#total_amount_due').val(total_after_tax);
		} else {
			$('#total_amount_due').val(total_after_tax);
		}
    });
});

function calculateTotal(){
	var totalAmount = 0;
    $("[id^='price_']").each(function() {
        var id = $(this).attr('id');
        id = id.replace("price_",'');
        var price = $('#price_'+id).val();
        var quantity  = $('#quantity_'+id).val();
        if(!quantity) {
            quantity = 1;
        }
        var total = price*quantity;
        $('#total_'+id).val(parseInt(total));
        totalAmount += total;
    });

    $('#total_before_tax').val(parseInt(totalAmount));
    var tax_per = $("#tax_per").val();
    var total_before_tax = $('#total_before_tax').val();
    if(total_before_tax) {
        var total_tax = total_before_tax*tax_per/100;
        $('#total_tax').val(total_tax);
        total_before_tax = parseInt(total_before_tax)+parseInt(total_tax);
        $('#total_after_tax').val(total_before_tax);
        var amount_paid = $('#amount_paid').val();
        var total_after_tax = $('#total_after_tax').val();
        if(amount_paid && total_after_tax) {
            total_after_tax = total_after_tax-amount_paid;
            $('#total_amount_due').val(total_after_tax);
        } else {
            $('#total_amount_due').val(total_before_tax);
        }
    }
}
</script>
@endpush
