(function( $ ){
   $.fn.selected = function() {
           $(this).on('click', function(){
               $(this).select();
           });
           return this;
   }; 
})( jQuery );


(function( $ ){
   $.fn.diskon = function() {
   		$(this).on('keyup', function(){
			// $(this).val($(this).val().replace(/^(0+)/g, '').replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '0'));
            $(this).val(Math.min(100,Math.max(0,($(this).val()))));
            $('#tabel-detail-transaksi').calculateTabel();
         });
		return this;
   }; 
})( jQuery );


(function( $ ){
    $.fn.jumlah = function() {
            $(this).on('keyup', function(){
             // $(this).val($(this).val().replace(/^(0+)/g, '').replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '0'));
             $(this).mask('0#').val(Math.min(999,Math.max(1,($(this).val()))));
            });
         return this;
    }; 
 })( jQuery );


 (function( $ ){
   $.fn.tambahkanTransaksi = function() {
      
      let form = $(this);
      form.submit(function(e){
         e.preventDefault();
         var modal            = $(this).closest('#modal_transaksi');
         var kode_barang      = $(this).find('input[name="form_kode_barang"]').val();
         var nama_barang      = $(this).find('input[name="form_nama_barang"]').val();
         var satuan_barang    = $(this).find('input[name="form_satuan_barang"]').val();
         var stock_lama       = $(this).find('input[name="form_stock_lama"]').val();
         var harga_pokok_lama = $(this).find('input[name="harga_pokok_lama"]').val();
         var jumlah_beli      = $(this).find('input[name="form_jumlah_beli"]').val();
         var harga_beli       = $(this).find('input[name="form_harga_beli"]').val();
         var total_harga_beli = $(this).find('input[name="form_total_harga_beli"]').val();

         var harga = [];
         var check_stop = true;
         $(this).find('div.pengaturan_harga div.form-group.form-horizontal').each(function(index){
            
            var isi = {};
            isi['harga_ecer'] = $(this).find('input[name*="harga_ecer"]').val().replace(/[^0-9.]/g, '');
            isi['harga_grosir'] = $(this).find('input[name*="harga_grosir"]').val().replace(/[^0-9.]/g, '');
            isi['min'] = $(this).find('input[name*="min_stock"]').val();

            if (isi['harga_ecer'] == '') {
               Swal.fire({ type: 'error', title: 'Peringatan', text: 'Harga Ecer Belum Di isi Level '+(index+1) })
               check_stop = false;
               return false;
               
            }

            if (isi['harga_grosir'] == '') {
               Swal.fire({ type: 'error', title: 'Peringatan', text: 'Harga Grosir Belum Di isi Level '+(index+1) })
               check_stop = false;
               return false;
            }

            if (isi['min'] == '') {
               Swal.fire({ type: 'error', title: 'Peringatan', text: 'Harga Min.Grosir Belum Di isi'+(index+1) })
               check_stop = false;
               return false;
            }

            harga.push(isi);
         });

         if (check_stop == false) { return false; }

         if ($('#tabel-detail-transaksi tbody tr#adax').length) {
            $('#tabel-detail-transaksi tbody tr#adax').detach();
         }

         var isi_tabel = `<tr><td> <input type="text" data-harga='${JSON.stringify(harga)}' value="${kode_barang}" style='width:100%;' readonly name="kode_barang[]" class="text-center"> </td>
                        <td> <input type="text" value="${nama_barang}" style='width:100%;' readonly name="nama_barang[]" class="text-left"> </td>
                        <td> <input type="text" value="${jumlah_beli}" style='width:100%;' readonly name="jumlah[]" class="text-center"> </td>
                        <td> <input type="text" value="${satuan_barang}" style='width:100%;' readonly name="satuan[]" class="text-center"> </td>
                        <td> <input type="text" value="${harga_beli}" style='width:100%;' readonly name="harga_beli[]" class="text-center"> </td>
                        <td> <input type="text" value="${total_harga_beli}" style='width:100%;' readonly name="total[]" class="text-center"> </td>
                        <td> <button class="btn-danger" type="button" style="height: 20px; width: 20px; justify-content: center; align-items: center; display: flex;"><i class="icon-trash"></i></button> </td>
                  </tr>;`;

         $(isi_tabel)
         .find('button.btn-danger').on('click', function(){

            $(this).closest('tr').detach();

            if ($('#tabel-detail-transaksi tbody tr').length == 0) {
               var html_tbody = `<tr id="adax"><td colspan="7" class="text-center">Data Belum Ada !</td></tr>`;
               $('#tabel-detail-transaksi tbody').append(html_tbody);
               $('#total').val(0).mask('#,##0', { reverse: true }).trigger('input');
               $('#diskon_akhir').val(0).prop('readonly', true);
               $('#total_akhir').val(0).mask('#,##0', { reverse: true }).trigger('input');
            }

            $('#tabel-detail-transaksi').calculateTabel();

         }).end()
         .appendTo('#tabel-detail-transaksi').promise().then(function(){
            $('#form-transaksi').find('input:not([name="kode_transaksi"]):not([name="tgl_transaksi"])').val('');
            form.find('input').val('');
            modal.modal('hide');
         });
         $('#tabel-detail-transaksi').calculateTabel();
         $('#diskon_akhir').selected().diskon().prop('readonly', false);
      })


   }; 
})( jQuery );


(function( $ ){
   $.fn.calculateTabel = function() {
      let table = $(this);
      var total = 0;
      table.find('tbody tr:not([id="adax"])').each(function(){
         total += parseInt($(this).find('input[name="total[]"]').val().replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '0'));
      });

      var diskon = parseInt($('#diskon_akhir').val());
      var akhir = total - ((diskon/100) * total);
      $('#total').val(total).mask('#,##0', { reverse: true }).trigger('input');
      $('#total_akhir').val(akhir).mask('#,##0', { reverse: true }).trigger('input');
   }; 
})( jQuery );