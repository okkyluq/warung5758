var link_next = '';


$.stockMinimal = function(url){
    let html_li = `<li class="media"><div class="media-body"><a href="#" class="media-heading"><span class="text-semibold">JUS ANGGUR</span></a><span class="text-muted">Sisa Stock 10 PCS</span></div></li>`;
    let btn_muat = `<li class="media text-center"><button type="button" class="btn btn-xs border-slate text-slate-800 btn-flat"><i class="icon-loop3 position-left"></i> Muat Data</button></li>`;
    let container_stock_limit = $('#container-stock-limit');
    let icon_notif = container_stock_limit.find('i#icon-notif');
    let count_notif = container_stock_limit.find('span#count-notif');
    let container_dropdown = container_stock_limit.find('#container-dropdown');

    $.ajax({
        url: url,
        type: 'GET',
        beforeSend: function(jqXHR, settings){
            $.loaderStart(container_dropdown);
            icon_notif.removeClass('icon-bubble-notification').addClass('icon-spinner9 spinner')
        },
        success: function(data, status, xhr){
            link_next = data.next_page_url;
            count_notif.text(data.total)

            $.each(data.data, function(index, value){
                let tipe_item = ['BRG JADI', 'BRG PRODUKSI', 'BAHAN BAKU'];
                $(html_li)
                .find('a.media-heading').text(`[${tipe_item[value.tipe_item]}] - ${value.nama_item}`).end()
                .find('span.text-muted').text(`Sisa Stock ${value.stock_tersedia} ${value.satuan}`).end()
                .appendTo(container_stock_limit.find('ul.media-list'));
            });

            if(link_next != null){
                $(btn_muat)
                .find('button').on('click', function(e){
                    e.stopPropagation();
                    $(this).closest('li.media').detach();
                    $.stockMinimal(link_next);
                }).end()
                .appendTo(container_stock_limit.find('ul.media-list'));
            }

        },
        error: function(jqXhr, textStatus, error){
            alert('Ada Masalah Saat Memuat Data Stock Item Minimun');
        },
        complete: function(){
            icon_notif.removeClass('icon-spinner9 spinner').addClass('icon-bubble-notification')
            $.loaderStop(container_dropdown);
        }
    });




}


$.stockMinimal(window.location.origin + '/' + 'get-stock-minimal');

