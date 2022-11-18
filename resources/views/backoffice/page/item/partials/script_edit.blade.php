
<script>
    let html_tr = `<tr><td><input type="hidden" id="satuan_item_id" /><select name="satuan" id="satuan" class="form-control"></select></td><td><div class="input-group"><input type="text" class="form-control" placeholder="Qty Konversi" id="qty_konversi"><span class="input-group-addon"></span></div></td><td><input type="text" class="form-control text-right" placeholder="Harga Jual" name="harga_jual"></td><td><input type="text" class="form-control text-right" placeholder="Harga Beli" name="harga_beli"></td><td class="text-center"><ul class="icons-list"><li class="text-danger-600"><a id="button_delete" href="#"><i class="icon-lock2"></i></a></li></ul></td></tr>`;
    let item = @json($item);

    $(document).ready(function(){

        console.log(item);
        $('input[name="qty_minimal"]').number(true);

        item.satuan_item.map(function(value, index){
            $(html_tr)
            .find('#satuan_item_id').val(value.id).end()
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
            }).append(new Option(value.satuan.satuan, value.satuan_id, false, false)).trigger('change').prop("disabled", true)
            .on('select2:select', function(e){
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
            }).end()
            .find('input#qty_konversi').attr('autocomplete', 'off').val(value.qty_konversi).prop('readonly', true).number(true).end()
            .find('span.input-group-addon').text(item.satuan_item[0].satuan.satuan).end()
            .find('input[name="harga_jual"]').attr('autocomplete', 'off').val(value.harga_jual).number(true).end()
            .find('input[name="harga_beli"]').attr('autocomplete', 'off').val(value.harga_beli).number(true).end()
            .appendTo('#table-list-satuan tbody');
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
            .find('i.icon-lock2').removeClass('icon-lock2').addClass('icon-cancel-circle2').end()
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

            console.log(list_satuan)
            input_satuan_text.val(list_satuan[number_index])
            number_index = (number_index + 1) % list_satuan.length;

            hide_loading('#panel-buat-item', 500);
        });

    });
</script>
