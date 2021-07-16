@if(isset($print) && isset($row))
<html>
    <head>
        {{-- <link rel="preconnect" href="https://fonts.gstatic.com"> --}}
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
        <style>
            /* @font-face {
                font-family: 'Roboto';
                font-style: normal;
                font-weight: 400;
                src: '{{ storage_path('fonts/Roboto-Regular.ttf') }}' format('truetype');
            }

            @font-face {
                font-family: 'Roboto-Bold';
                font-style:normal;
                font-weight: 700;
                src: url('{{ asset('assets/fonts/Roboto-Bold.ttf') }}') format('truetype');
            } */

            html,   body {
                font-size: 13px;
                font-family: "Roboto" !important;
            }

            #logo {
                max-width: 200px;
            }

            p {
                margin-top: 2px;
                margin-bottom: 10px;
            }

            .p-head {
                margin-bottom: 0px;
                /* font-family: 'Roboto-Bold'; */
                font-weight: 700;
                color: #1a1919
            }

            hr {
                margin: 10px 0;
                background:#1a1919;
                border-color:#1a1919;
            }

            th {
                font-weight: 700;
                /* font-family: 'Roboto-Medium'; */
                color: #1a1919;
            }

            tbody > tr > td{
                 padding: 2px 0;
                 vertical-align: middle;
            }

            .separator {
                display: block;
                width: 100%;
                border-bottom: 1px solid #145984;
            }
        </style>
    </head>
    <body>
@endif
        <table width="100%">
            <tr>
                <td>
                    <img id="logo" src="{{ CRUDBooster::getSetting("logo") ? asset(CRUDBooster::getSetting("logo")) : asset("vendor/crudbooster/assets/logo_crudbooster.png") }}" />
                </td>
            </tr>
        </table>

        <div class="separator" style="height: 4px; margin: 5px 0 0; border-width: 3px"></div>
        <div class="separator" style="height: 4px; margin: 0 0 15px; border-width: 2px;"></div>

        <table width="100%">
            <tr>
                <td style="text-align: right">
                    <p class="p-head">#No Invoice</p>
                    <p>{{ $row->invoice_code }}</p>

                    <p class="p-head">Tanggal Invoice</p>
                    <p>{{ Carbon::parse($row->invoice_dt)->format('d/M/Y') }}</p>

                    <p class="p-head">Batas Waktu Pembayaran</p>
                    <p>{{ $row->due_dt ? Carbon::parse($row->due_dt)->format('d/M/Y') : 'Tidak ada batasan waktu' }}</p>
                </td>
            </tr>
        </table>

        <div class="separator" style="height: 5px; margin: 5px 0 15px;"></div>

        <table width="100%">
            <tr>
                <td style="vertical-align: top;" width="50%">
                    <p class="p-head">Dari</p>
                    <p>{{ CRUDBooster::getSetting('nama_perusahaan') ?? 'Mohon Setting Data Perusahaan' }}</p>
                    <p>{!! nl2br(CRUDBooster::getSetting('alamat_perusahaan')) ?? 'Mohon Setting Data Perusahaan' !!}</p>
                    <p>{{ CRUDBooster::getSetting('nomor_telepon_perusahaan') ?? 'Mohon Setting Data Perusahaan' }} // {{ CRUDBooster::getSetting('email_perusahaan') ?? 'Mohon Setting Data Perusahaan' }}</p>
                </td>
                <td style="text-align: right; vertical-align: top;" width="50%">
                    <p class="p-head">Untuk</p>
                    <p>{{ $row->receiver_name }}</p>
                    <p>{{$row->receiver_address }}</p>
                </td>
            </tr>
        </table>

        <div class="separator" style="height: 5px; margin: 5px 0 15px;"></div>

        <table width="100%">
            <thead>
                <tr>
                    <th width="5%" style="text-align: left;">#</th>
                    <th width="35%" style="text-align: left;">Item</th>
                    <th width="15%" style="text-align: right;">Qty</th>
                    <th width="15%" style="text-align: right;">Rate</th>
                    <th width="15%" style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($childs as $key => $item)
                    <tr>
                        <td>
                            <p>{{ $loop->iteration }}</p>
                        </td>
                        <td>
                            <p>{{ $item->item_name }}</p>
                        </td>
                        <td style="text-align: right;">
                            <p>{{ number_format($item->quantity) }}</p>
                        </td>
                        <td style="text-align: right;">
                            <p>Rp {{ number_format($item->price) }}</p>
                        </td>
                        <td style="text-align: right;">
                            <p>Rp {{ number_format($item->total) }}</p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="separator" style="height: 5px; margin: 5px 0 15px;"></div>

        <table width="100%">
            <tr>
                <td style="vertical-align: top;" width="50%">
                    <p class="p-head">Note</p>
                    <p>{!! $row->note ? nl2br($row->note) : 'Tidak ada note'  !!}</p>
                </td>
                <td style="text-align: right; vertical-align: top;" width="50%">
                    <p class="p-head">Total Sebelum Pajak</p>
                    <p>Rp {{ number_format($row->total_before_tax) }}</p>

                    <p class="p-head">Persentase Pajak</p>
                    <p>{{ number_format($row->tax_per) }}%</p>

                    <p class="p-head">Total Pajak</p>
                    <p>Rp {{ number_format($row->total_tax) }}</p>
                    </div>

                    <p class="p-head">Total Setelah Pajak</p>
                    <p>Rp {{ number_format($row->total_after_tax) }}</p>

                    <p class="p-head">Total Diterima</p>
                    <p>Rp {{ number_format($row->amount_paid) }}</p>

                    <p class="p-head">Total Belum Diterima</p>
                    <p>Rp {{ number_format($row->total_amount_due) }}</p>
                </td>
            </tr>
        </table>
@if(isset($print) && isset($row))
    </body>
</html>
@endif


