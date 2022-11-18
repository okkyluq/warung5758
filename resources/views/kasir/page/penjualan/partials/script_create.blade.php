<script>
    let html_tr = `<tr><td class="text-left"><input type="hidden" id="item_id"><span class="text-semibold"></span></td><td class="text-center"><input type="text" id="qty" name="qty" value="1" class="touchspin-empty"></td><td><select name="satuan_item" id="satuan_item" class="form-control"><option value="" disabled="" selected="">Pilih Satuan</option></select></td><td><input name="harga" id="harga" class="form-control text-right" placeholder="Harga"></td><td><input name="sub_total" id="sub_total" class="form-control text-right" placeholder="Sub Total" readonly></td><td><ul class="icons-list"><li class="text-danger-600"><a id="button_delete" href="#"><i class="icon-trash"></i></a></li></ul></td></tr>`;
    let tr_empty = `<tr class="tr-empty"><td colspan="6" class="text-center text-bold">Item Belum Ada</td></tr>`;
    let costumer_default = JSON.parse(@json($costumer_default));
    let kas_credit = JSON.parse(@json($kas_credit));
    let kas_cash = JSON.parse(@json($kas_cash));

    $.getTotalPembelian = function(){
        let total = 0;
        $('table#table-transaksi > tbody > tr:not(.tr-empty) ').each(function(index, tr){
            total += Number($(this).find('input#sub_total').val());
        });
        return $.number(total);
    }

    $.getItemSearch = function(options){
        $.ajax({
            url: "{{ url('get-item-penjualan') }}",
            type: "GET",
            data: {
                key: options.key,
                kategori: options.kategori
            },
            beforeSend: function(xhr){

            },
            success: function(data, status, xhr){

                let html_item = `@include('kasir.page.penjualan.partials.item_list')`;
                let url_image = '{{ asset("back/gambar-item") }}';
                $('#container-list-item').empty();
                data.forEach((value, index) => {
                    console.log(value)
                    $(html_item)
                    .find('a#btn-heading-text').attr('data-item', JSON.stringify(value)).end()
                    .find('h6.media-heading').text(value.nama_item).end()
                    .find('img').attr('src', value.gambar_item != null ? url_image + '/' + value.gambar_item : url_image + '/' + 'not-found.jpg').end()
                    .find('ul li:eq(0)').text(value.history_item_count != null ? Number(value.history_item_count / value.get_satuan_penjualan.satuan_item.qty_konversi).toFixed(0) +' '+ value.get_satuan_penjualan.satuan : 0  +' '+ value.get_satuan_penjualan.satuan).end()
                    .find('a#btn-heading-text').on('click', function(){
                        event.preventDefault();
                        $('#table-transaksi tbody tr.tr-empty').remove();
                        let item = JSON.parse($(this).attr('data-item'));
                        let harga_jual = item.satuan_item.find(list => {
                            if(item.satuan_penjualan == list.satuan_id){ return list; }
                        });
                        let option = item.satuan_item.map(list => {
                            return `<option  value="${list.id}" ${item.satuan_penjualan == list.satuan_id ? 'selected' : ''}>${list.satuan.satuan}</option>`;
                        });
                        let sub_total = (harga_jual.harga_jual != 0 ? harga_jual.harga_jual : 0) * 1;
                        $(html_tr)
                        .find('select#satuan_item').append(option).end()
                        .find('span.text-semibold').text(item.nama_item).end()
                        .find('input#item_id').val(item.id).end()
                        .find('input.touchspin-empty').val(1).number(true)
                        .TouchSpin()
                        .on('keyup', function(){
                            let qty   = $(this).val();
                            let harga = $(this).closest('tr').find('input#harga').val();
                            $(this).closest('tr').find('input#sub_total').val(qty * harga);
                            $('#total_akhir').number(true).val($.getTotalPembelian());
                        })
                        .on('touchspin.on.startupspin', function(){
                            let qty   = $(this).val();
                            let harga = $(this).closest('tr').find('input#harga').val();
                            $(this).closest('tr').find('input#sub_total').val(qty * harga);
                            $('#total_akhir').number(true).val($.getTotalPembelian());
                        })
                        .on('touchspin.on.startdownspin', function(){
                            let qty   = $(this).val();
                            let harga = $(this).closest('tr').find('input#harga').val();
                            $(this).closest('tr').find('input#sub_total').val(qty * harga);
                            $('#total_akhir').number(true).val($.getTotalPembelian());
                        })
                        .end()
                        .find('input#harga').val(harga_jual.harga_jual != 0 ? harga_jual.harga_jual : 0).number(true).on('keyup', function(){
                            let qty   = $(this).closest('tr').find('input.touchspin-empty').val();
                            let harga = $(this).val();
                            $(this).closest('tr').find('input#sub_total').val(harga * qty);
                            $('#total_akhir').number(true).val($.getTotalPembelian());
                        }).end()
                        .find('input#sub_total').val(sub_total).number(true).end()
                        .find('#button_delete').on('click', function(){
                            $(this).closest('tr').detach();
                            if($('#table-transaksi tbody tr').length < 1){
                                $('#table-transaksi tbody').append(tr_empty);
                            }
                            $('#total_akhir').number(true).val($.getTotalPembelian());
                        }).end()
                        .appendTo('#table-transaksi tbody');
                        $('#total_akhir').number(true).val($.getTotalPembelian());
                    }).end()
                    .appendTo('#container-list-item');
                })
            },
            error: function(jqXhr, textStatus, errorMessage){
                alert('Ada Masalah !');
                console.log(errorMessage)
            }
        });
    }

    $.getIdTransaksi = function(){
        $.ajax({
            url: "{{ url('get-id-penjualan') }}",
            type: "GET",
            data: {},
            success: function(data, status, xhr){
                $('input[name="no_penjualan"]').val(data);
            },
            error: function(jqXhr, textStatus, errorMessage){
                alert('Ada Masalah Saat Menampilkan No.Penjualan')
            }
        });
    }

    $.setupFormTambahPenjualan =  function(){
        $.loaderStart('body');
        $.getItemSearch({ key: '', kategori: ''});
        $.getIdTransaksi();
        $("#jumlah_uang_muka").val('');
        $("#keterangan").val('');
        let html_table_empty = `<tr class="tr-empty"><td colspan="6" class="text-bold text-center">Data Item Belum Ada</td></tr>`;
        $('table#table-transaksi > tbody').empty().append(html_table_empty);
        $('#total_akhir').val(0);
        $('#uang_muka').val(0);
        $('#bayar').val(0);
        $('#kembalian').val(0);
        $.loaderStop('body', 500);
    }

    $('.btn-category').on('click', function(){
        let category = $(this).attr('data-category');
        $.getItemSearch({ key: '', kategori: category});
    });

    $("#costumer").select2({
		allowClear: true,
		placeholder: 'Pilih Costumer',
	    dropdownAutoWidth : true,
        width: 'resolve',
        ajax: {
            url: "{{ url('getcostumer') }}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.nama_costumer,
                            id: item.id,
                        }
                    })
                };
        },
        cache: true,
        }
	}).append(new Option(costumer_default.label, costumer_default.key, true, true)).trigger('change');

    $("#kas").select2({
		allowClear: true,
		placeholder: 'Pilih Kas',
	    dropdownAutoWidth : true,
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
	}).append(new Option(kas_cash.label, kas_cash.key, true, true)).trigger('change');

    $("#kas_kredit").select2({
		allowClear: true,
		placeholder: 'Pilih Kas',
	    dropdownAutoWidth : true,
        // width: 'resolve',
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

    $('input[name="tgl_penjualan"]').daterangepicker({
        locale: { format: 'DD/MM/YYYY' },
        singleDatePicker: true
    });

    $('#input-pencarian-item').on('keypress', function(event){
        let keycode = (event.keyCode ? event.keyCode : event.which);
        let key = $(this).val();
        if(keycode == '13'){
            if(key.length > 0) {
                $('#btn-clear-item').removeClass('hidden');
            }
            $.getItemSearch({
                key,
                kategori: '',
            });
        }
    });


    $('#btn-cari-item').on('click', function(){
        event.preventDefault();
        let key = $('#input-pencarian-item').val();

        if(key.length > 0) {
            $('#btn-clear-item').removeClass('hidden');
        }

        $.getItemSearch({
            key,
            kategori: '',
        });
    });

    $('#btn-clear-item').on('click', function(){
        event.preventDefault();
        $('#input-pencarian-item').val('');
        $.getItemSearch({
            key: '',
            kategori: '',
        });
        $(this).addClass('hidden');
    });



    $('#tgl_jatuh_tempo').daterangepicker({
        locale: { format: 'DD/MM/YYYY' },
        singleDatePicker: true
    }).on('apply.daterangepicker', function(ev, picker){
        let tgl_sekarang = moment($('input[name="tgl_penjualan"]').data('daterangepicker').startDate._d);
        let tgl_jatuh_tempo = moment(picker.startDate._d);
        $('#hari').val(tgl_jatuh_tempo.diff(tgl_sekarang, 'days'));
    });

    $('#termin').attr('disabled', 'disabled').select2().on('change', function(e){
        $('#jumlah_uang_muka').val('');
        $('#uang_muka').val('');
        $('#keterangan').val('');
        if(e.target.value == '1'){
            $('div#opsi_cash').removeClass('hidden');
            $('div#opsi_credit').addClass('hidden');
            $('#kolom_uang_muka').addClass('hidden');
        } else {
            $('div#opsi_cash').addClass('hidden');
            $('div#opsi_credit').removeClass('hidden');
            let tgl_sekarang = $('input[name="tgl_penjualan"]').data('daterangepicker').startDate._d;
            let hari = $('#hari').val();
            $('#tgl_jatuh_tempo').data('daterangepicker').setStartDate(moment(tgl_sekarang).add(hari, 'days').format('DD/MM/YY'));
            $('#uang_muka').val(0);
            $('#kolom_uang_muka').removeClass('hidden');
        }
    });

    $('#hari').on('change', function(e){
        let tgl_sekarang = $('input[name="tgl_penjualan"]').data('daterangepicker').startDate._d;
        let hari = e.target.value;
        $('#tgl_jatuh_tempo').data('daterangepicker').setStartDate(moment(tgl_sekarang).add(hari, 'days').format('DD/MM/YY'));
    });

    $('#btn-update-uang-muka').on('click', function(){
        let kas_kredit = $('#kas_kredit').val();
        let jumlah = $('#jumlah_uang_muka').val() != '' ? $('#jumlah_uang_muka').val() : 0;
        let keterangan = $('#keterangan').val();

        if(kas_kredit == null || kas_kredit == ''){
            Swal.fire({ type: 'error', title: 'Peringatan', text: 'Kas/Bank Belum DIpilih' });
            return false;
        }

        $('#uang_muka').val(jumlah).number(true);
        $('#modal_uang_muka').modal('hide')
    });

    $('#btn-cancel-uang-muka').on('click', function(){
        $('#modal_uang_muka').modal('hide')
    });

    $('#btn-uang-muka').on('click', function(){
        $('#modal_uang_muka').modal({backdrop: 'static', keyboard: false}).modal('show').on('shown.bs.modal', function(){
            $("#kas_kredit").append(new Option(kas_credit.label, kas_credit.key, true, true)).trigger('change');
            $('#jumlah_uang_muka').number(true);
        });
    });


    $('#btn-opsi-bayar').on('click', function(){
        $('#modal_opsi_bayar').modal('show');
    });

    $('.btn-harga-nominal').on('click', function(){
        let harga = $(this).attr('data-nominal');
        $('#bayar').val(harga).keyup();
        $('#modal_opsi_bayar').modal('hide');
    });

    $('#bayar').number(true).on('keyup', function(){
        let btn_submit = $(this).closest('form').find('button[type="submit"]');
        let total = $('#total_akhir');
        let bayar = $(this);

        if(Number(bayar.val()) >= Number(total.val())){
            btn_submit.prop("disabled", false);
            bayar.parent().removeClass('has-warning')
        } else {
            btn_submit.attr('disabled', true);
            bayar.parent().addClass('has-warning')
        }

        $('#kembalian').number(true).val(Math.abs(Number(total.val()) - Number(bayar.val())));
    });


</script>
