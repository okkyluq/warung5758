<script>
    var tgl,supplier,no_pembelian, kode_akun,nama_akun,jenis_akun;

    $.getTotalPembelian = function(){
        let total = 0;
        $('table#table-transaksi > tbody > tr ').each(function(index, tr){
            total += Number($(tr).find('a#jumlah_bayar').text().replace(/,/g, ''));
        });
        return total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    $.appendToTable = function(detail, element_modal){

        $(element_modal).modal('hide');
        let html_tr = `
            <tr><td class="text-bold text-center"></td>
                <td class="text-bold text-center"></td>
                <td class="text-bold text-right"><a href="#" id="jumlah_bayar" data-type="text" data-title="Masukan Jumlah Bayar">1</a></td>
                <td class="text-bold text-left"><a href="#" id="keterangan" data-type="text" data-title="Masukan Keterangan"></a></td>
                <td class="text-bold text-center"><a id="remove" href="#"><i class="icon-trash text-danger"></i></a></td></tr>
        `;
        if ($('#table-transaksi tbody tr#empty-rows').length) {
            $('#table-transaksi tbody tr#empty-rows').detach();
        }
        $(html_tr)
        .find('td:eq(0)')
        .attr('data-akun-id', detail.akun_id).attr('data-history-hutang-id', detail.history_hutang_id)
        .attr('data-id', detail.pembayaran_id).attr('data-type', detail.pembayaran_type).text(detail.type).end()
        .find('td:eq(1)').text(detail.no_ref).end()
        .find('a#jumlah_bayar').text(detail.jumlah_bayar).editable({
                display: function(value, sourceData){
                    $(this).text(value).number( true, 0)
                },
                success: function(e, params){
                    setTimeout(function() {
                        $('#total_hutang').val($.getTotalPembelian());
                    },0);
                }
            }).on('shown', function(ev, editable){
                setTimeout(function() {
                    editable.input.$input.select();
                },0);
            }).end()
        .find('a#keterangan').text(detail.keterangan).editable()
            .on('shown', function(ev, editable){
                setTimeout(function() {
                    editable.input.$input.select();
                },0);
            }).end()
        .find('a#remove').on('click', function(){
                event.preventDefault()
                var html_table_empty = `<tr id="empty-rows"><td colspan="6" class="text-bold text-center">Data Pembayaran Belum Ada</td></tr>`;
                $(this).closest('tr').detach();
                if($('#table-transaksi tbody tr').length == 0){
                    $('#table-transaksi').find('tbody').append(html_table_empty);
                }
                $('#total_hutang').val($.getTotalPembelian());
            }).end()
        .appendTo('#table-transaksi > tbody');
        $('#total_hutang').val($.getTotalPembelian());
    }

    $(document).ready(function() {
        $('#btn-pilih-hutang').on('click', function(){
            $('#modal_hutang').modal('show');
        });

        $('#btn-pilih-akun').on('click', function(){
            $('#modal_akun').modal('show');
        });

        $('#btn-pilih-retur').on('click', function(){
            $('#modal_retur').modal('show');
        });

        $('.tanggal-periode').daterangepicker({
            opens: 'right'
        });

        $('#tgl_pembayaran').daterangepicker({
            locale: { format: 'DD/MM/YYYY' },
            singleDatePicker: true
        });

        $("#supplier").select2({
            allowClear: true,
            placeholder: 'Pilih Supplier',
            ajax: {
                url: "{{ url('getsupplier') }}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.nama_supplier,
                                id: item.id,
                            }
                        })
                    };
            },
            cache: true,
            }
        });

        $(".pencarian-supplier").select2({
            allowClear: true,
            placeholder: 'Pilih Supplier',
            dropdownParent: $('#modal_hutang'),
            ajax: {
                url: "{{ url('getsupplier') }}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.nama_supplier,
                                id: item.id,
                            }
                        })
                    };
            },
            cache: true,
            }
        });

        $('#form-pencarian-pembelian').on('submit', function(e){
            e.preventDefault();
            show_loading('.modal-content');
            let tgl_start = $('#tanggal_pembelian').data('daterangepicker').startDate;
            let tgl_end = $('#tanggal_pembelian').data('daterangepicker').endDate;

            supplier = $('#supplier_pembelian').val();
            no_pembelian = $('#no_pembelian').val();
            tgl = moment(tgl_start).format('DD/MM/YY') + '-' + moment(tgl_end).format('DD/MM/YY')

            TablePencarianPembelian.draw();
	        hide_loading('.modal-content', 500);
        });

        // datatables pencarian pembelian
        var TablePencarianPembelian = $('#table-pencarian-pembelian').DataTable({
            bLengthChange: false,
            processing: true,
            lengthChange: true,
            serverSide: true,
            searching: false,
            ajax : {
                url : "{{ url('keuangan/pencarian-pembelian') }}",
                data : function(d){
					d.tgl = tgl;
					d.supplier = supplier;
					d.no_pembelian = no_pembelian;
				}
            },
            columnDefs :[
                {"target" : 0},
                {"target" : 1},
                {"target" : 2},
                {"target" : 3},
                {"target" : 4},
                {"target" : 5},
                {"target" : 6},
                {"target" : 7},
            ],
            columns: [
                {data : 'no_pembelian', name : 'no_pembelian', orderable: false, class: 'text-center text-bold'},
                {data : 'no_ref', name : 'no_ref', orderable: false, class: 'text-center text-bold'},
                {data : 'tgl_pembelian', name : 'tgl_pembelian', orderable: false, class: 'text-right text-bold'},
                {data : 'supplier', name : 'supplier', orderable: false, class: 'text-center text-bold'},
                {data : 'total', name : 'total', orderable: false, class: 'text-right text-bold'},
                {data : 'terbayar', name : 'terbayar', orderable: false, class: 'text-right text-bold'},
                {data : 'sisa_pembayaran', name : 'sisa_pembayaran', orderable: false, class: 'text-right text-bold'},
                {data : 'action', name : 'action', orderable: false, class: 'text-center text-bold'},
            ],
            drawCallback: function(setting){
                $(this).find('.btn-get').on('click', function(e){
                    e.preventDefault();
                    let detail_element = $(this).closest('tr').find('td:eq(0)').find('a').attr('data-detail');
                    var detail = JSON.parse(detail_element);
                    console.log(detail);
                    let data_transaksi = {
                        akun_id : detail.akun_id,
                        history_hutang_id : detail.id,
                        pembayaran_id : detail.id,
                        pembayaran_type : 'App\\HistoryHutang',
                        model : detail.historyhutangable_type,
                        type : 'HUTANG',
                        no_ref : detail.historyhutangable.no_pembelian,
                        keterangan : 'Transaksi Pembelian No.Pembelian : '+detail.historyhutangable.no_pembelian,
                        jumlah_bayar : detail.sisa_pembayaran,
                    }
                    $.appendToTable(data_transaksi, '#modal_hutang');
                });
            }
        });


        $('#form-pencarian-akun').on('submit', function(e){
            e.preventDefault();
            show_loading('div#modal_akun > .modal-content');
            kode_akun = $('#kode_akun').val();
            nama_akun = $('#nama_akun').val();
            jenis_akun = $('#kategori').val();
            TablePencarianAkun.draw();
	        hide_loading('div#modal_akun > .modal-content', 500);
        });
        // datatables pencarian akun
        var TablePencarianAkun = $('#table-pencarian-akun').DataTable({
            bLengthChange: false,
            processing: true,
            lengthChange: true,
            serverSide: true,
            searching: false,
            ajax : {
                url : "{{ url('keuangan/pencarian-akun') }}",
                data : function(d){
					d.kode_akun = kode_akun;
					d.nama_akun = nama_akun;
					d.jenis_akun = jenis_akun;
				}
            },
            columnDefs :[
                {"target" : 0},
                {"target" : 1},
                {"target" : 2},
                {"target" : 3},
            ],
            columns: [
                {data : 'kategori', name : 'kategori', orderable: false, class: 'text-center text-bold'},
                {data : 'kode_akun', name : 'kode_akun', orderable: false, class: 'text-center text-bold'},
                {data : 'nama_akun', name : 'nama_akun', orderable: false, class: 'text-left text-bold'},
                {data : 'action', name : 'action', orderable: false, class: 'text-center text-bold'},
            ],
            drawCallback: function(setting){
                $(this).find('.btn-get').on('click', function(e){
                    e.preventDefault();
                    let detail_element = $(this).closest('tr').find('td:eq(0)').find('a').attr('data-detail');
                    let detail = JSON.parse(detail_element);
                    let data_transaksi = {
                        akun_id : detail.id,
                        history_hutang_id : null,
                        pembayaran_id : detail.id,
                        pembayaran_type : 'App\\Akun',
                        model : 'App\\Akun',
                        type : 'AKUN',
                        no_ref : detail.kode_akun,
                        keterangan : 'Pembayaran Untuk Akun : '+detail.nama_akun,
                        jumlah_bayar : 0,
                    }
                    $.appendToTable(data_transaksi, '#modal_akun');
                });
            }
        });


        $("#kas").select2({
            allowClear: true,
            placeholder: 'Pilih Kas',
            dropdownAutoWidth : true,
            dropdownParent: $('#modal_uang_muka'),
            ajax: {
                url: "{{ url('getkasselect2') }}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.nama_kas,
                                id: item.id,
                            }
                        })
                    };
            },
            cache: true,
            }
        });

        $('#btn-uang-muka').on('click', function(e){
            e.preventDefault();
            $('#modal_uang_muka').modal({backdrop: 'static', keyboard: false}).modal('show').on('shown.bs.modal', function(){
                $('#jumlah_uang_muka').val($('#total_hutang').val()).mask("#,##0", {reverse: true});
            });
        });

        $('#btn-update-uang-muka').on('click', function(e){
            e.preventDefault();
            let kas = $('#kas').val();
            let jumlah = $('#jumlah_uang_muka').val();
            if(kas == null || kas == ''){
                Swal.fire({ type: 'error', title: 'Peringatan', text: 'Kas/Bank Belum DIpilih' });
                return false;
            }

            $('#total_pembayaran').val(jumlah).mask("#,##0", {reverse: true});
            $('#modal_uang_muka').modal('hide')
        });


        $('#form-transaksi').submit(function(e){
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var tr = $('#table-transaksi tbody tr:not(#empty-rows)');

            var jsonObj = [];
            tr.each(function(){
                item = {}
                item ["history_hutang_id"]      = $(this).find('td:eq(0)').attr('data-history-hutang-id') != null ? $(this).find('td:eq(0)').attr('data-history-hutang-id') : null;
                item ["akun_id"]                = $(this).find('td:eq(0)').attr('data-akun-id');
                item ["data_id"]                = $(this).find('td:eq(0)').attr('data-id');
                item ["data_type"]              = $(this).find('td:eq(0)').attr('data-type');
                item ["type"]                   = $(this).find('td:eq(0)').text();
                item ["no_ref"]                 = $(this).find('td:eq(1)').text();
                item ["jumlah_bayar"]           = parseInt($(this).find('a#jumlah_bayar').text().replace(/,/g, ''));
                item ["keterangan"]             = $(this).find('a#keterangan').text();
                jsonObj.push(item);
            });

            var formData = {
                kode_pembayaran : $('input[name="kode_pembayaran"]').val(),
                supplier : $('select[name="supplier"]').val(),
                tgl_pembayaran : $('input[name="tgl_pembayaran"]').val(),
                kas: $('select[name="kas"]').val(),
                total_hutang: $('#total_hutang').val().replace(/,/g, ''),
                total_pembayaran: $('#jumlah_uang_muka').val().replace(/,/g, ''),
                keterangan: $('textarea#keterangan').val(),
                list_item: JSON.stringify(jsonObj)
            }

            console.log(formData);
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                beforeSend: function(){
                    $.loaderStart('#panel-buat-item')
                },
                success: function(response){
                    if(response.status == "sukses"){
                        window.location.href = "{{ url('keuangan/pembayaran') }}";
                    }
                },
                error: function(response){
                    if ($.isEmptyObject(response.responseJSON.errors) == false) {
                        console.log(response.responseJSON.errors[Object.keys(response.responseJSON.errors)[0]])
                        Swal.fire({ type: 'error', title: 'Peringatan', text: response.responseJSON.errors[Object.keys(response.responseJSON.errors)[0]] })
                    }
                },
                complete: function(){
                    $.loaderStop('#panel-buat-item')
                }

            });

        });



    });
</script>
