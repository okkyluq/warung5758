String.prototype.replaceBetween = function(start, end, what) {
    return this.substring(0, start) + what + this.substring(end);
};

$.checkConnectionRecta = function(setting){
    let icon = $('#info-recta').find('i');
    icon.removeClass('icon-printer').addClass('icon-spinner2 spinner');

    let printer = new Recta(setting.RECTA_API_KEY, setting.RECTA_PORT);
    printer.open().then(() => {
        var html = `<span class="label bg-success"><i class="icon-printer"></i> Print Connected</span>`;
        $('#info-recta').html(html);
    }).catch((e) => {
        var html = `<span class="label bg-danger"><i class="icon-printer"></i> Print Not Connected, Click To Connected Again</span>`;
        $('#info-recta').on('click', function(){
            event.preventDefault();
            alert('asasas')
        }).html(html);
        swal( 'Peringatan !', 'Gagal Menghubungkan Ke Print !', 'info' );
    });
}


$.tesPrintRecta = function(setting){
    let printer = new Recta(setting.RECTA_API_KEY, setting.RECTA_PORT);
    printer.open().then(function () {
        printer.align('center')
          .text('Hello World !!')
          .bold(true)
          .text('This is bold text')
          .bold(false)
          .underline(true)
          .text('This is underline text')
          .underline(false)
          .barcode('CODE39', '123456789')
          .cut()
          .print()
    })
}

$.setLineQtyPrice = function(qty, harga, subtotal){
    let text      = "================================";
    let qty_harga = qty + ' x ' + harga;
    let output    = text.replaceBetween(0, qty_harga.length, qty_harga);
    let output2   = output.replaceBetween(32 - $.number(subtotal).toString().length, 32, $.number(subtotal));
    return output2.replace(/=/g, ' ');
}

$.setFooterLine = function(info, value){
    let text      = "================================";
    let output    = text.replaceBetween(0, info.length, info);
    let output2   = output.replaceBetween(32 - ('Rp.'+$.number(value).toString()).length, 32, ('Rp.'+$.number(value)));

    return output2.replace(/=/g, ' ');
}


$.rectaPrintStruk = function(setting){
    let printer = new Recta(setting.RECTA_API_KEY, setting.RECTA_PORT);
    printer.open().then(function () {
        printer.align('center')
                .bold(true)
                .text(setting.NAMA_WARUNG)
                .bold(false)
                .text(setting.ALAMAT_WARUNG)
                .bold(false)
                .text('Telp. '+setting.TELP_WARUNG)
                .text('IG : '+setting.AKUN_IG)
                .bold(false)
                .text('================================')
                .bold(false)
                .align('left')
                .text('No. : '+setting.NO_TRANSAKSI)
                .text('Tgl : '+setting.TGL)
                .text('================================')
                // list item
                .bold(false)
                .align('left')
                .print()
                $.each(setting.LIST_ITEM, function(index, value){
                    printer
                    .text(value.name_item)
                    .text($.setLineQtyPrice($.number(value.qty), $.number(value.harga), (value.qty * value.harga)))
                });
                // end list item
                printer 
                .text('================================')
                .bold(true)
                .text($.setFooterLine('Total :', setting.TOTAL_HARGA))
                .print()

                if(setting.BAYAR != 0 && setting.KEMBALIAN != 0){
                    printer
                    .bold(true)
                    .text($.setFooterLine('Bayar :', $.number(setting.BAYAR)))
                    .text($.setFooterLine('Kembalian :', $.number(setting.KEMBALIAN)))
                    .print()
                } else {
                    printer
                    .bold(true)
                    .text($.setFooterLine('Bayar :', $.number(setting.BAYAR)))
                    .text($.setFooterLine('Kembalian :', $.number(setting.KEMBALIAN)))
                    .print()
                }

                printer
                .feed(1)
                .bold(true)
                .align('center')
                .text('TERIMA KASIH ATAS KUNJUNGANYA')
                .feed(3)
                .print();
    });
}

