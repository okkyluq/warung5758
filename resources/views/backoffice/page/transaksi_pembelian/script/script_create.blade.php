<script>
    let tr_html = `<tr><td><select name="item" id="item" class="form-control select-item"></select></td><td><input type="text" class="form-control text-right" id="qty" name="qty" readonly></td><td><select name="satuan_item" id="satuan_item" class="form-control" disabled><option value="" disabled selected>Pilih Satuan</option></select></td><td><input type="text" class="form-control text-right" id="harga" name="harga" readonly></td><td><input type="text" class="form-control text-right" id="sub_total" name="sub_total" readonly></td><td class="text-center"><ul class="icons-list"><li class="text-danger-600"><a id="button_delete" data-id="11" href="#"><i class="icon-cancel-circle2"></i></a></li></ul></td></tr>`;

    $(document).ready(function(){
        $.getTotalPembelian = function(){
            let total = 0;
            $('table#table-transaksi > tbody > tr ').each(function(index, tr){
                total += Number($(tr).find('input#sub_total').val());
            });
            return $.number(total);
        }

        $('.select-item').select2({
            allowClear: true,
            maximumSelectionLength: 3,
            placeholder: 'Pilih Item',
            ajax: {
                url: "{{ url('getitemselect2') }}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.nama_item,
                                id: item.id,
                                item: item,
                            }
                        })
                    };
            },
            cache: true,
            }
        }).on('select2:select', (e) => {
            let $this = e.target;
            let data = e.params.data;
            let harga_beli = data.item.satuan_item.find(list => {
                if(data.item.satuan_pembelian == list.satuan_id){ return list; }
            });
            let sub_total = (harga_beli.harga_beli != 0 ? harga_beli.harga_beli : 0) * 1;

            $($this).closest('tr').find('select#satuan_item').empty();
            data.item.satuan_item.map(list => {
                let option = `<option value="${list.id}" ${data.item.satuan_pembelian == list.satuan_id ? 'selected' : ''}>${list.satuan.satuan}</option>`;
                $($this).closest('tr').find('select#satuan_item').append(option);
            });

            $($this).closest('tr')
            .find('input#qty').val(1).number(true).prop('readonly', false)
            .on('keyup', function(){
                let qty   = $(this).val();
                let harga = $(this).closest('tr').find('input#harga').val();
                $(this).closest('tr').find('input#sub_total').val(qty * harga).number(true).prop('readonly', true);
                $('#total_akhir').val($.getTotalPembelian())
            }).end()
            .find('input#harga').val(harga_beli.harga_beli != 0 ? harga_beli.harga_beli : 0).number(true).prop('readonly', false)
            .on('keyup', function(){
                let harga   = $(this).val();
                let qty = $(this).closest('tr').find('input#qty').val();
                $(this).closest('tr').find('input#sub_total').val(qty * harga).number(true).prop('readonly', true);
                $('#total_akhir').val($.getTotalPembelian())
            }).end()
            .find('input#sub_total').val(sub_total).number(true).prop('readonly', true).end()
            .find('#satuan_item').removeAttr('disabled');

            $('#total_akhir').val($.getTotalPembelian())
        }).on('select2:unselect', function(){
            let tr = $(this).closest('tr');
            tr.find('input#qty').val('').prop('readonly', true);
            tr.find('#satuan_item').empty().prop('disabled', true);
            tr.find('#harga').val('').prop('readonly', true);
            tr.find('#sub_total').val('').prop('readonly', true);
            $('#total_akhir').val($.getTotalPembelian())
        });

        $('a#button_delete').on('click', function(){
            event.preventDefault();
            let tr = $(this).closest('tr');
            tr.find('select#item').val(null).trigger('change');
            tr.find('input#qty').val('').prop('readonly', true);
            tr.find('#satuan_item').empty().prop('disabled', true);
            tr.find('#harga').val('').prop('readonly', true);
            tr.find('#sub_total').val('').prop('readonly', true);
            $('#total_akhir').val($.getTotalPembelian())
        });

        $('.btn-add-row').on('click', function(e){
            e.preventDefault();
            let row = Number($(this).attr('data-row'));
            for (let index = 0; index < row; index++) {
                $(tr_html)
                .find('select#item').select2({
                    allowClear: true,
                    maximumSelectionLength: 3,
                    placeholder: 'Pilih Item',
                    ajax: {
                        url: "{{ url('getitemselect2') }}",
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results:  $.map(data, function (item) {
                                    return {
                                        text: item.nama_item,
                                        id: item.id,
                                        item: item,
                                    }
                                })
                            };
                    },
                    cache: true,
                    }
                }).on('select2:select', (e) => {
                    let $this = e.target;
                    let data = e.params.data;
                    let harga_beli = data.item.satuan_item.find(list => {
                        if(data.item.satuan_pembelian == list.satuan_id){ return list; }
                    });
                    let sub_total = (harga_beli.harga_beli != 0 ? harga_beli.harga_beli : 0) * 1;

                    $($this).closest('tr').find('select#satuan_item').empty();
                    data.item.satuan_item.map(list => {
                        let option = `<option value="${list.id}" ${data.item.satuan_pembelian == list.satuan_id ? 'selected' : ''}>${list.satuan.satuan}</option>`;
                        $($this).closest('tr').find('select#satuan_item').append(option);
                    });

                    $($this).closest('tr')
                    .find('input#qty').val(1).number(true).prop('readonly', false)
                    .on('keyup', function(){
                        let qty   = $(this).val();
                        let harga = $(this).closest('tr').find('input#harga').val();
                        $(this).closest('tr').find('input#sub_total').val(qty * harga).number(true).prop('readonly', true);
                        $('#total_akhir').val($.getTotalPembelian())
                    }).end()
                    .find('input#harga').val(harga_beli.harga_beli != 0 ? harga_beli.harga_beli : 0).number(true).prop('readonly', false)
                    .on('keyup', function(){
                        let harga   = $(this).val();
                        let qty = $(this).closest('tr').find('input#qty').val();
                        $(this).closest('tr').find('input#sub_total').val(qty * harga).number(true).prop('readonly', true);
                        $('#total_akhir').val($.getTotalPembelian())
                    }).end()
                    .find('input#sub_total').val(sub_total).number(true).prop('readonly', true).end()
                    .find('#satuan_item').removeAttr('disabled');
                    $('#total_akhir').val($.getTotalPembelian())

                }).on('select2:unselect', function(){
                    let tr = $(this).closest('tr');
                    tr.find('input#qty').val('').prop('readonly', true);
                    tr.find('#satuan_item').empty().prop('disabled', true);
                    tr.find('#harga').val('').prop('readonly', true);
                    tr.find('#sub_total').val('').prop('readonly', true);
                    $('#total_akhir').val($.getTotalPembelian())
                }).end()
                .find('#button_delete').on('click', (e) => {
                    e.preventDefault();
                    let $this = e.target;
                    $($this).closest('tr').detach();
                    $('#total_akhir').val($.getTotalPembelian())
                }).end()
                .appendTo('#table-transaksi tbody');

            }
        });
    });
</script>
