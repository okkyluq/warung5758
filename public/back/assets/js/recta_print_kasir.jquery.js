String.prototype.replaceBetween = function(start, end, what) {
    return this.substring(0, start) + what + this.substring(end);
};

function rubah(angka){
    var reverse = angka.toString().split('').reverse().join(''),
    ribuan = reverse.match(/\d{1,3}/g);
    ribuan = ribuan.join(',').split('').reverse().join('');
    return ribuan;
  }

function set_line3(harga, qty, sub){
    var text = "================================";
    var harga = harga;
    var qty = qty.toString()+'x';
    var sub = sub;

    str = text.replaceBetween(0, harga.length, harga);
    str = str.replaceBetween(15, 15+qty.length, qty);
    str = str.replaceBetween(32 - sub.length, 32, sub);
    anu = str.replace(/=/g, ' ');
    return anu;
}

function set_line2(isi, value){
    var text = "================================";
    var isi = isi;
    var value = value;
    str = text.replaceBetween(0, isi.length, isi);
    str = str.replaceBetween(32 - value.length, 32, value);
    anu = str.replace(/=/g, ' ');
    return anu;
}




(function( $ ){
    $.fn.rectaPrintKasir = function(options) {
        var table = $(this);
        itemObj = [];
        table.find('tbody tr:not([id="adax"])').each(function(){
			isi = {}
			isi["nama_barang"] = $(this).find('input[name="nama_barang[]"]').val();
			isi["jumlah"]      = $(this).find('input[name="jumlah[]"]').val();
			isi["satuan"]      = $(this).find('input[name="satuan[]"]').val();
			isi["harga"]       = $(this).find('input[name="harga[]"]').val();
            isi["sub_total"]   = $(this).find('input[name="sub_total[]"]').val();
			itemObj.push(isi);
        });
        
        var printer = new Recta(options.api_key, options.port);
        
        printer.open().then(function () {
            moment.locale('id'); 
            printer.align('center')
                    .bold(false) 
                    .text(options.nama_toko)
                    .bold(false)
                    .text(options.jalan)
                    .bold(false)
                    .text('Telp. 082193157880')
                    .bold(false)
                    .text(moment().format('dddd, D MMMM YYYY, h:mm:ss'))
                    .bold(false)
                    .text('================================')
                    .print()
            
            $.each(itemObj, function(index, value){
                printer.align('left')
                        .bold(false)
                        .text(value.nama_barang)
                        .print()
                printer.align('center')
                        .bold(false)
                        .text(set_line3(value.harga, value.jumlah, value.sub_total))
                        .print()
            });

            printer.align('center')
                    .bold(false)
                    .text('================================')
                    .bold(false)
                    .text(set_line2("Total : ", options.sub_total))
                    .bold(false)
                    .text(set_line2("Diskon : ", options.diskon))
                    .bold(false)
                    .text(set_line2("Total Akhir : ", options.total_akhir))
                    .bold(false)
                    .text(set_line2("Tunai : ", options.bayar))
                    .bold(false)
                    .text(set_line2("Kembali : ", options.kembali))
                    .bold(false)
                    .text("***TERIMA KASIH***")
                    .bold(false)
                    .text("No.Transaksi: "+options.no_transaksi)
                    .bold(false)
                    .text("KASIR: "+options.kasir)
                    .cut()
                    .print()

        }); 
    };
})( jQuery );


(function($){
    $.extend({
        rectaPrintData: function(data, setting){
            // console.log(data);
            var printer = new Recta(setting.api_key, setting.port);
            printer.open().then(function () {
                moment.locale('id'); 
                printer.align('center')
                        .bold(false) 
                        .text(setting.nama_toko)
                        .bold(false)
                        .text(setting.jalan)
                        .bold(false)
                        .text('Telp. 082193157880')
                        .bold(false)
                        .text(moment().format('dddd, D MM YYYY, h:mm:ss'))
                        .bold(false)
                        .text('================================')
                        .print()
                
                $.each(data.det_transaksi_penjualan, function(index, value){
                    printer.align('left')
                            .bold(false)
                            .text(value.barang.nama_barang)
                            .print()
                    printer.align('center')
                            .bold(false)
                            .text(set_line3(rubah(value.harga), value.jumlah.toString(), rubah(value.total)))
                            .print()
                });

                printer.align('center')
                        .bold(false)
                        .text('================================')
                        .bold(false)
                        .text(set_line2("Total : ", rubah(data.sub_total)))
                        .bold(false)
                        .text(set_line2("Diskon : ", data.diskon.toString()))
                        .bold(false)
                        .text(set_line2("Total Akhir : ", rubah(data.total_akhir)))
                        .bold(false)
                        .text(set_line2("Tunai : ", rubah(data.bayar)))
                        .bold(false)
                        .text(set_line2("Kembali : ", rubah(data.kembalian)))
                        .bold(false)
                        .text("***TERIMA KASIH***")
                        .bold(false)
                        .text("No.Transaksi: "+data.kode_transaksi.toString())
                        .bold(false)
                        .text("KASIR: "+data.user.name.toString())
                        .cut()
                        .print()

            });
        }

    });
})(jQuery);