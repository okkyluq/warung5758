
(function( $ ){
    $.fn.selected = function() {
            $(this).on('click', function(){
                $(this).select();
            });
            return this;
    }; 
})( jQuery );


(function( $ ){ 
   $.fn.calculateTotal = function() {
        var total = 0;
          
		if ($(this).find('tbody tr:not(#adax)').length >= 1) {
			$(this).find('tbody tr').each(function(){
      			total += parseInt($(this).find('input[name="sub_total[]"]').val().replace(/,/g, ''));
      			$('#diskon_akhir').prop('readonly', false);
      			$('#bayar').prop('readonly', false);
      		});
		} else {
			total += 0;
			$('#diskon_akhir').prop('readonly', true);
			$('#bayar').prop('readonly', true);
        }
		
        $('input#total').val(total).mask('#,##0', { reverse: true }).trigger('input');
        var diskon = parseInt($('#diskon_akhir').val().replace(/,/g, ''));
        var akhir = total - ((diskon/100) * total);
		$('input[name="total_akhir"]').val(akhir).mask('#,##0', { reverse: true }).trigger('input');
		$('input[name="bayar"]').val(0).mask('#,##0', { reverse: true }).trigger('input');
		$('input[name="kembali"]').val('').mask('#,##0', { reverse: true }).trigger('input');

   }; 
})( jQuery );


(function( $ ){ 
    $.fn.diskon = function() {
        $(this).on('keyup', function(){
            $(this).val(Math.min(100,Math.max(0,($(this).val()))));
            $('#tabel-detail-transaksi').calculateTotal();
        });
     return this;
}; 
})( jQuery );
 

(function( $ ){
    $.fn.bayar = function() {
        $(this).mask('#,##0', { reverse: true }).on('keyup', function(){
            var total = parseInt($('input[name="total_akhir"]').val().replace(/,/g, ''));
            var bayar = parseInt($(this).val().replace(/,/g, ''));
            $('input[name="kembali"]').val(bayar - total).mask('#,##0', {
                reverse: true,
                translation: {
                    '#': {
                        pattern: /-|\d/,
                        recursive: true
                    }
                },
                onChange: function(value, e) {      
                    e.target.value = value.replace(/(?!^)-/g, '').replace(/^,/, '').replace(/^-,/, '-');
                }
            }).trigger('input');
        });
     return this;
}; 
})( jQuery );


(function( $ ){
   $.fn.jumlah = function() {
   		$(this).on('keyup', function(){
			$(this).val(Math.min(900,Math.max(1,($(this).val().replace(/[^0-9.]/g, '')))));
   		});
		return this;
   }; 
})( jQuery );

(function( $ ){
	$.fn.getSubTotal = function() {
		$('input[name="diskon_akhir"]').val(0).selected().diskon();
		$('input[name="bayar"]').val(0).selected().bayar();
		$('input[name="kembali"]').val('');
		let tr       = $(this).closest('tr');
		let jumlah   = parseInt(tr.find('input[name="jumlah[]"]').val());
		let select   = tr.find('select option:selected');
		let harga_ecer = parseInt(select.attr('data-harga-ecer'));
		let harga_grosir = parseInt(select.attr('data-harga-grosir'));
		let min_grosir = parseInt(select.attr('data-min-grosir'));
		let set_harga;

		// console.log(jumlah + ' = ' + min_grosir)
		
		if (jumlah >= min_grosir) {
			set_harga = harga_grosir;
			tr.find('input[name="level[]"]').val('GROSIR'); 
		} else {
			set_harga = harga_ecer;
			tr.find('input[name="level[]"]').val('ECER'); 
		}

		let sub_total = jumlah * parseInt(set_harga);

		tr.find('input[name="harga[]"]').val(set_harga).mask('#,##0', { reverse: true }).trigger('input');
		tr.find('input[name="sub_total[]"]').val(sub_total).mask('#,##0', { reverse: true }).trigger('input');
		return this;
	}; 
 })( jQuery );


(function( $ ){
	$.fn.getJumlahAkhir = function() {
		let tbody = $(this).closest('tbody');
		var sub_total = 0;
		tbody.find('tr td input[name="sub_total[]"]').each(function(){
			sub_total += parseInt($(this).val().replace(/,/g, ''));
		});

		$('input#total').val(sub_total).mask('#,##0', { reverse: true }).trigger('input');
		$('input[name="total_akhir"]').val(sub_total).mask('#,##0', { reverse: true }).trigger('input');
		return this;
	}; 
 })( jQuery );


(function( $ ){
   $.fn.getProduct = function(setting) {
		var self = $(this);
		$(self).on('keypress', function(e){
		
			if (e.which == 13) {
			e.preventDefault();
			$.ajax({
				url: setting.url+"?q="+self.val(),
				type: 'GET',
				beforeSend: function(){
					self.closest('div.col-md-5').find('span.notif').detach();
				},
				success: function(data, textStatus, jqXHR){ 

					// console.log(data.harga_barang[0].harga_ecer);

					if (data.stock == 0) {
						Swal.fire({ type: 'error', title: 'Peringatan', text: 'Barang yang anda pilih stocknya kosong', })
						self.val('');
						return false;							
					}

					if (jQuery.isEmptyObject(data)) {
						if(self.closest('div.col-md-5').find('span.notif').length >= 1) {
							return false;
						}
						var html_notif = `<span class="notif" style="font-size: 11px;"><b class="text-danger">Barang Tidak Ada !</b></span>`;
						self.closest('div').after(html_notif);
						return false;
					}

					if ($('#tabel-detail-transaksi tbody tr#adax').length) {
						$('#tabel-detail-transaksi tbody tr#adax').detach();
					}

					let status = '';
					$('#tabel-detail-transaksi tbody tr').each(function(){
						var input = $(this).find(`input[name="kode_barang[]"][value="${data.kode_barang}"]`);
						if (input.length) {
							Swal.fire({ type: 'error', title: 'Peringatan', text: 'Barang Sudah Dipilih' })
							status = false;
							
						} 
						self.val('');
					});

					if (status === false) {return false;}

					var isi_tabel =`<tr> 
									<td> <input type="text" value="${data.kode_barang}" style='width:100%;' readonly name="kode_barang[]" class="text-center"> </td>
									<td> <input type="text" style='width:100%;' value="${data.nama_barang}" readonly name="nama_barang[]"> </td>
									<td> <input type="text" value="1" style='width:100%;' name="jumlah[]" class="text-right"> </td>
									<td> <input type="text" value="${data.satuan.satuan}" style='width:100%;' readonly name="satuan[]" class="text-center"></td>
									<td> <select name="level" id="level" class="form-control input-xs"></select> </td>
									<td> <input type="text" data-min="${data.min_stock}" data-ecer="${data.harga_jual_ecer}" data-grosir="${data.harga_jual_grosir}" value="${data.harga_jual_ecer}" style='width:100px;' readonly name="harga[]" class="text-right"> </td>
									<td><input class="text-right" type="text" value="${data.harga_jual_ecer * 1}" style='width:100px;' name="sub_total[]" readonly></td>
									<td class="text-center"> <input type="text" value="ECER" style='width:50px;' name="level[]" class="text-center"> </td>
									<td class="text-center"><a id="button_delete" href=""><i class="icon-trash text-danger-600"></i></a></td>
									</tr>`;
					var option = '';
					$.each(data.harga_barang, function(index, item){
						option += `<option value="${item.id}" data-harga-ecer="${item.harga_ecer}" data-harga-grosir="${item.harga_grosir}" data-min-grosir="${item.min_grosir}">Level ${item.level}</option>`
					});
					$(isi_tabel).find('input').attr('autocomplete', 'off').end()
					.find('input[name="harga[]"]').mask('#,##0', { reverse: true }).trigger('input').end()
					.find('input[name="sub_total[]"]').mask('#,##0', { reverse: true }).trigger('input').end()
					.find('input[name="jumlah[]"]').selected().jumlah().mask('0#').on('keyup', function(){
						$(this).getSubTotal();
						$(this).getJumlahAkhir();
					}).end()
					.find('a#button_delete').on('click', function(){
						event.preventDefault();
						$(this).closest('tr').detach();
						if ($('#tabel-detail-transaksi tbody tr').length == 0) {
							var html_tbody = `<tr id="adax"><td colspan="9" class="text-center">Data Belum Ada !</td></tr>`;
							$('#tabel-detail-transaksi tbody').append(html_tbody);
							$('#diskon_akhir').val(0);
							$('#bayar').val(0);
						}
						$('#tabel-detail-transaksi').calculateTotal();
					}).end()
					.find('input[name="harga[]"]').val(data.harga_barang[0].harga_ecer).mask('#,##0', { reverse: true }).trigger('input').end()
					.find('input[name="sub_total[]"]').val(data.harga_barang[0].harga_ecer * 1).mask('#,##0', { reverse: true }).trigger('input').end()
					.find('select#level').append(option).on('change', function(){
						$(this).getSubTotal();
						$(this).getJumlahAkhir();
					}).end()
					.appendTo('#tabel-detail-transaksi tbody');
					self.val('').end().closest('div.col-md-5').find('span.notif').detach();
					$('#tabel-detail-transaksi').calculateTotal();
					$('input[name="diskon_akhir"]').selected().diskon();
					$('input[name="bayar"]').selected().bayar();
				},
				done: function(){
					console.log('done')
					hide_loading('#panel-buat-barang', 100);
				},
				error: function(){
					hide_loading('#panel-buat-barang', 100);
					if(self.closest('div.col-md-5').find('span.notif').length >= 1) {
						return false;
					}
					var html_notif = `<span class="notif" style="font-size: 11px;"><b class="text-danger">Ada Masalah Saat Pencarian Data !</b></span>`;
					self.closest('div').after(html_notif);
				}
			});
		}
	});
   }; 
})( jQuery );
