(function( $ ){ 
    $.fn.calculateTotal = function() {
         var total = 0;
           
         if ($(this).find('tbody tr:not(#adax)').length >= 1) {
             $(this).find('tbody tr').each(function(){
                   total += parseInt($(this).find('input[name="biaya[]"]').val().replace(/,/g, ''));
               });
         } else {
             total += 0;
         }
         
         $('input[name="total_akhir"]').val(total).mask('#,##0', { reverse: true }).trigger('input');
 
    }; 
 })( jQuery );