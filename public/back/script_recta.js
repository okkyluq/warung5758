

$.checkConnectionRecta = function(setting){
    let icon = $('#info-recta').find('i');
    icon.removeClass('icon-printer').addClass('icon-spinner2 spinner');

    let printer = new Recta(setting.RECTA_API_KEY, setting.RECTA_PORT);
    printer.open().then(() => {
        var html = `<span class="label bg-success"><i class="icon-printer"></i> Print Connected</span>`;
        $('#info-recta').html(html);
    }).catch((e) => {
        var html = `<span class="label bg-danger"><i class="icon-printer"></i>Not Connected, Click To Connected</span>`;
        $('#info-recta').on('click', function(){
            event.preventDefault();
            alert('asasas')
        }).html(html);
        swal( 'Peringatan !', 'Gagal Menghubungkan Ke Print !', 'info' );
    });
}
