<script>
    
    $(document).ready(function() {
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

            data.item.satuan_item.map(list => {
                let option = `<option  data-stock="${Math.round(data.item.history_item_count / list.qty_konversi)}" value="${list.id}" ${data.item.satuan_stock == list.satuan_id ? 'selected' : ''}>${list.satuan.satuan}</option>`;
                $($this).closest('tr').find('select#satuan_item').append(option);

                if(data.item.satuan_stock == list.satuan_id){
                    $($this).closest('tr')
                    .find('input#qty_program').val(Math.round(data.item.history_item_count / list.qty_konversi)).end()
                    .find('input#qty_selisih').val(1 - Math.round(data.item.history_item_count / list.qty_konversi)).end()
                    .find('input#qty_opname').val(1);
                }
            });

            $($this).closest('tr')
            .find('#item_id').val(data.item.id).end()
            .find('#kode_item').val(data.item.kode_item).end()
            .find('#satuan_item').removeAttr('disabled').on('change', function(){
                let stock = $(this).find('option:selected').attr('data-stock');
                $(this).closest('tr').find('input#qty_program').val(stock);
                let qty_opname  = $(this).closest('tr').find('input#qty_opname').val();
                $(this).closest('tr').find('input#qty_selisih').val(qty_opname - stock);
            }).end()
            .find('#qty_opname').removeAttr('disabled').focus().on('keyup', function(){
                let qty_opname  = $(this).val();
                let qty_program = $(this).closest('tr').find('input#qty_program').val();
                $(this).closest('tr').find('input#qty_selisih').val(qty_opname - qty_program);
            });

        }).on('select2:unselect', (e) => {
            let $this = e.target;
            let tr = $($this).closest('tr');
            
            tr.find('#kode_item').val('');
            tr.find('#qty').val('').prop('disabled', true);
            tr.find('#satuan_item option:not([disabled])').remove();
            tr.find('#satuan_item').prop('disabled', true);
        });

        $('#button_delete').on('click', (e) => {
            e.preventDefault();
            let $this = e.target;
            let tr = $($this).closest('tr');

            tr.find('#kode_item').val('');
            tr.find('#qty_opname').val('').prop('disabled', true);
            tr.find('#qty_program').val('').prop('disabled', true);
            tr.find('#qty_selisih').val('').prop('disabled', true);
            tr.find('#satuan_item option:not([disabled])').remove();
            tr.find('#satuan_item').prop('disabled', true);
            $('.select-item').val(null).trigger('change');
        });

        $('.btn-add-row').on('click', function(e){
            e.preventDefault();
            let row = Number($(this).attr('data-row'));

            for (let index = 0; index < row; index++) {
                let tr_html = $('#table-transaksi tbody tr:first').clone();
                $(tr_html)
                .find('#item_id').val('').end()
                .find('#kode_item').val('').end()
                .find('#qty_opname').val('').prop('disabled', true).end()
                .find('#qty_program').val('').prop('disabled', true).end()
                .find('#qty_selisih').val('').prop('disabled', true).end()
                .find('#kode_item').val('').end()
                .find('#satuan_item option:not([disabled])').remove().end()
                .find('#satuan_item').prop('disabled', true).end()
                .find('span.select2-container').detach().end()
                .find('select#item').val('').trigger('change')
                .select2({
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

                    data.item.satuan_item.map(list => {
                        let option = `<option  data-stock="${Math.round(data.item.history_item_count / list.qty_konversi)}" value="${list.id}" ${data.item.satuan_stock == list.satuan_id ? 'selected' : ''}>${list.satuan.satuan}</option>`;
                        $($this).closest('tr').find('select#satuan_item').append(option);

                        if(data.item.satuan_stock == list.satuan_id){
                            $($this).closest('tr')
                            .find('input#qty_program').val(Math.round(data.item.history_item_count / list.qty_konversi)).end()
                            .find('input#qty_selisih').val(1 - Math.round(data.item.history_item_count / list.qty_konversi)).end()
                            .find('input#qty_opname').val(1);
                        }
                    });
                    $($this).closest('tr')
                    .find('#item_id').val(data.item.id).end()
                    .find('#kode_item').val(data.item.kode_item).end()
                    .find('#satuan_item').removeAttr('disabled').on('change', function(){
                        let stock = $(this).find('option:selected').attr('data-stock');
                        $(this).closest('tr').find('input#qty_program').val(stock);
                        let qty_opname  = $(this).closest('tr').find('input#qty_opname').val();
                        $(this).closest('tr').find('input#qty_selisih').val(qty_opname - stock);
                    }).end()
                    .find('#qty_opname').removeAttr('disabled').focus().on('keyup', function(){
                        let qty_opname  = $(this).val();
                        let qty_program = $(this).closest('tr').find('input#qty_program').val();
                        $(this).closest('tr').find('input#qty_selisih').val(qty_opname - qty_program);
                    });
                }).on('select2:unselect', (e) => {
                    let $this = e.target;
                    let tr = $($this).closest('tr');
                    
                    tr.find('#kode_item').val('');
                    tr.find('#qty').val('').prop('disabled', true);
                    tr.find('#satuan_item option:not([disabled])').remove();
                    tr.find('#satuan_item').prop('disabled', true);
                }).end()
                .find('#button_delete').on('click', (e) => {
                    e.preventDefault();
                    let $this = e.target;
                    $($this).closest('tr').detach();
                }).end()
                .appendTo('#table-transaksi tbody');
            }
        });

    });

</script>