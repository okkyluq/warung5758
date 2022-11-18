// Panels



function show_loading(namepanel) {
        var block = namepanel;
        $(block).block({ 
            message: '<h1 class="icon-loop3 spinner" style="font-size:30px;"></h1>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #ddd'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none'
            }
        });
}


function hide_loading(namepanel, time=300) {
    window.setTimeout(function () {
       $(namepanel).unblock();
    }, time); 
}