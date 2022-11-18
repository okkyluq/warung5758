<script>
let html_tr = `<tr><td><select name="satuan" id="satuan" class="form-control"><option value="" disabled selected>Pilih Satuan</option></select></td><td><div class="input-group"><input type="text" class="form-control" placeholder="Qty Konversi" id="qty_konversi"><span class="input-group-addon"></span></div></td><td><input type="text" class="form-control text-right" placeholder="Harga Jual" name="harga_jual"></td><td><input type="text" class="form-control text-right" placeholder="Harga Beli" name="harga_beli"></td><td class="text-center"><ul class="icons-list"><li class="text-danger-600"><a id="button_delete" href="#"><i class="icon-cancel-circle2"></i></a></li></ul></td></tr>`;

$(document).ready(function(){

    let satuan_default = JSON.parse(@json($satuan_default));
    let option_default = satuan_default != null ? new Option(satuan_default.label, satuan_default.key, true, true) : null;

    $('span.input-group-addon.first').text(satuan_default != null ? satuan_default.label : '');
    $('#qty_konversi').val(satuan_default != null ? 1 : '');
    $('input#satuan_penjualan').val(satuan_default != null ? satuan_default.label : '');
    $('input#satuan_pembelian').val(satuan_default != null ? satuan_default.label : '');
    $('input#satuan_stock').val(satuan_default != null ? satuan_default.label : '');
    $('input#satuan_minimal').val(satuan_default != null ? satuan_default.label : '');

    $('input[name="qty_minimal"]').number(true);
    $('input[name="harga_jual"]').number(true);
    $('input[name="harga_beli"]').number(true);

    $("#satuan").select2({
		allowClear: true,
		placeholder: 'Pilih Satuan',
        ajax: {
            url: "{{ url('getsatuan') }}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.satuan,
                            id: item.id,
                            item: item,
                        }
                    })
                };
        },
        cache: true,
        },
        escapeMarkup: function (markup) { console.log(markup); return markup; },
        language: {
            noResults: (markup) => {
                return 'no result';
            }
        }
	}).on('select2:select', function(e){
        let item = e.params.data.item;
        $(this).closest('tr')
        .find('#qty_konversi').val(1).end()
        .find('input[name="harga_jual"]').number(true).end()
        .find('input[name="harga_beli"]').number(true).end()
        .find('span.input-group-addon').text(item.satuan);


        $('input#satuan_minimal').val(item.satuan);
        $('input#satuan_penjualan').val(item.satuan);
        $('input#satuan_pembelian').val(item.satuan);
        $('input#satuan_stock').val(item.satuan);

    }).on('select2:unselect', function(e){
        let tr = $(this).closest('tr');
        tr.find('select#satuan').val(null).trigger('change').end()
        .find('input[name="harga_beli"]').val('').end()
        .find('input[name="harga_jual"]').val('').end()
        .find('span.input-group-addon').text('').end()
        .find('#qty_konversi').val('').end();
    }).append(option_default).trigger('change');



    $('#button_delete').on('click', function(){
        event.preventDefault();
        let tr = $(this).closest('tr');
        tr.find('select#satuan').val(null).trigger('change').end()
        .find('input[name="harga_beli"]').val('').end()
        .find('input[name="harga_jual"]').val('').end()
        .find('span.input-group-addon').text('').end()
        .find('#qty_konversi').val('').end();
    });

    $('#btn-add-row').on('click', function(){
        let tr_first = $(this).closest('table').find('tbody tr:first');
        let tr_count = $(this).closest('table').find('tbody tr').length;
        if($(tr_first).find('select#satuan').val() == null){
            Swal.fire({ type: 'error', title: 'Peringatan', text: "Silahkan Isi Satuan Pertama Dulu" });
            return false;
        }
        if(tr_count > 2){
            Swal.fire({ type: 'error', title: 'Peringatan', text: "Item Tidak Boleh Memiliki 3 Lebih Dari Satuan" });
            return false;
        }
        let label_first_satuan = $(tr_first).find('select#satuan').select2('data')[0].text;

        $(html_tr)
        .find('select#satuan').select2({
            allowClear: true,
            placeholder: 'Pilih Satuan',
            ajax: {
                url: "{{ url('getsatuan') }}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.satuan,
                                id: item.id,
                                item: item,
                            }
                        })
                    };
            },
            cache: true,
            }
        }).on('select2:select', function(e){
            let item = e.params.data.item;
            $(this).closest('tr')
            .find('#qty_konversi').number(true).end()
            .find('input[name="harga_jual"]').number(true).end()
            .find('input[name="harga_beli"]').number(true).end()
            .find('span.input-group-addon').text(label_first_satuan)
        }).on('select2:unselect', function(e){
            let tr = $(this).closest('tr');
            tr.find('select#satuan').val(null).trigger('change').end()
            .find('input[name="harga_beli"]').val('').end()
            .find('input[name="harga_jual"]').val('').end()
            .find('span.input-group-addon').text('').end()
            .find('#qty_konversi').val('').end();

        }).end()
        .find('a#button_delete').on('click', function(){
            event.preventDefault();
            let tr = $(this).closest('tr');
            $(this).closest('tr').detach();
        }).end()
        .appendTo('#table-list-satuan tbody');
    });



    var number_index = 0;
    $('.refresh').on('click', function(){
        show_loading('#panel-buat-item');
        let list_satuan = [];
        let input_satuan_text = $(this).closest('.input-group').find('input.satuan-text');
        $('#table-list-satuan tbody tr').each(function(index, value){
            let check = $(value).find('select#satuan').select2('data')[0].text;
            if(check != 'Pilih Satuan'){
                list_satuan.push(check);
            }
        });

        input_satuan_text.val(list_satuan[number_index])
        number_index = (number_index + 1) % list_satuan.length;

	    hide_loading('#panel-buat-item', 500);
    });

});
</script>
