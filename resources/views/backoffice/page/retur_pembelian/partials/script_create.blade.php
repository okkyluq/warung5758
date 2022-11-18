<script>
    var tgl,supplier,no_pembelian;
    var html_tr = `<tr><td width="150"><input type="hidden" id="kas_id" name="kas_id"><input type="hidden" id="item_id" name="item_id"><input class="form-control" placeholder="Kode Item" readonly id="kode_item" name="kode_item"></td><td><input class="form-control" placeholder="Nama Item" readonly id="nama_item" name="nama_item"></td><td width="120"><input name="qty" id="qty" class="form-control text-right" placeholder="Qty"></td><td width="200"><input type="hidden" id="satuan_item_id" name="satuan_item_id"><input class="form-control" placeholder="Unit Item" readonly id="unit_item" name="unit_item"></td><td width="200"><input name="harga" id="harga" class="form-control text-right" placeholder="Harga"></td><td width="200"><input name="sub_total" id="sub_total" class="form-control text-right" placeholder="Sub Total" disabled></td><td width="50"><ul class="icons-list"><li class="text-danger-600"><a id="button_delete" href="#"><i class="icon-trash"></i></a></li></ul></td></tr>`;
    var tr_not_item = `<tr class="no_item"><td colspan="7" class="text-center">Data Belum Ada</td></tr>`;

    $(document).ready(function() {
        $('input[name="tgl_set"]').daterangepicker({ locale: { format: 'DD/MM/YYYY' }, singleDatePicker: true });
        $(".pencarian-supplier").select2({ allowClear: true, placeholder: 'Pilih Supplier', dropdownAutoWidth : true, width: 'resolve', ajax: { url: "{{ url('getsupplier') }}", dataType: 'json', delay: 250, processResults: function (data) { return { results:  $.map(data, function (item) { return { text: item.nama_supplier, id: item.id, } }) }; }, cache: true, } });

        $('#btn-modal-import-pembelian').on('click', function(){
            $('#modal_import_pembelian').modal('show');
        });

        $('#supplier').select2({
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
        
        $('#supplier_pembelian').select2({
            allowClear: true,
            placeholder: 'Pilih Supplier',
            dropdownParent: $('#modal_import_pembelian'),
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

        $('.tanggal-periode').daterangepicker({
            locale: { format: 'DD/MM/YYYY' },
            opens: 'right'
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

        var TablePencarianPembelian = $('#table-pencarian-pembelian').DataTable({
            bLengthChange: false,
            serverSide: true,
            searching: false,
            autoWidth: false,
            ajax : {
                url : "{{ url('sistem/retur/pencarian-pembelian') }}",
                data : function(d){
                    d.tgl = tgl;
                    d.supplier = supplier;
                    d.no_pembelian = no_pembelian;
                }
            },
            columnDefs: [
                {targets: 0, className: "text-center text-bold"},
                {targets: 1, className: "text-center text-bold"},
                {targets: 2, className: "text-left text-bold"},
                {targets: 3, className: "text-right text-bold"},
                {targets: 4, className: "text-center text-bold"},
            ],
            columns: [
                {data : 'no_pembelian', name : 'no_pembelian', orderable: false},
                {data : 'tgl_pembelian', name : 'tgl_pembelian', orderable: false,},
                {data : 'supplier', name : 'supplier', orderable: false},
                {data : 'total', name : 'total', orderable: false},
                {data : 'action', name : 'action', orderable: false},
            ],
            drawCallback: function(setting){
                $(this).find('.btn-get').on('click', function(e){
                    e.preventDefault();
                    var detail = JSON.parse($(this).closest('tr').find('td:eq(0)').find('a').attr('data-detail'));
                    $('#table-transaksi').find('tr.no_item').detach();
                    console.log(detail)
                    detail.det_transaksi_pembelian.map(function(value, index){
                        $(html_tr)
                        .find('#kas_id').val(detail.kas_id).end()
                        .find('#item_id').val(value.item.id).end()
                        .find('#kode_item').val(value.item.kode_item).end()
                        .find('#nama_item').val(value.item.nama_item).end()
                        .find('#qty').val(value.qty).number(true).on('keyup', function(){
                            let qty = $(this).val();
                            let harga = $(this).closest('tr').find('#harga').val();
                            $(this).closest('tr').find('#sub_total').val(qty * harga);
                        }).end()
                        .find('#unit_item').val(value.satuan_item.satuan.satuan).end()
                        .find('#satuan_item_id').val(value.satuan_item.id).end()
                        .find('#harga').val(value.harga).number(true).on('keyup', function(){
                            let qty = $(this).closest('tr').find('#qty').val();
                            let harga = $(this).val();
                            $(this).closest('tr').find('#sub_total').val(qty * harga);
                        }).end()
                        .find('#sub_total').val(value.sub_total).number(true).end()
                        .find('a#button_delete').on('click', function(e){
                            e.preventDefault();
                            $(this).closest('tr').detach();
                            if($('#table-transaksi tbody').find('tr').length < 1){
                                $(tr_not_item).appendTo('#table-transaksi tbody');
                            }
                            // console.log($('#table-transaksi').find('tbody tr'))

                        }).end()
                        .appendTo('#table-transaksi tbody');
                    });

                    $('#modal_import_pembelian').modal('hide');
                });
            }
        });

    });

</script>