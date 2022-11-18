
$.loaderStart = function(container){
    $(container).waitMe({
        effect : 'win8',
        text : 'Sedang Memproses',
        bg : "#ffffffa3",
        color : '#37474f'
    });
}

$.loaderStop = function(container, delay = 2000){
    setTimeout(function() {
        $(container).waitMe('hide');
    }, delay);
}
