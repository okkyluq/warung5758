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
        $(this).on('keyup', function(event){
            this.value = this.value.replace(/\D/g, "");
        });
        return this;
    }; 
})( jQuery );