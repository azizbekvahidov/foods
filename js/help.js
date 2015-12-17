
function preloadShow(){
    $("body").append("\
            <div class='loader'>\
                <div class='circle'></div>\
                <div class='circle'></div>\
                <div class='circle'></div>\
                <div class='circle'></div>\
                <div class='circle'></div>\
            </div>\
            <div id='preLoad'>\
            </div>\
            <div class='waiter'><h5>Подождите пожалуйста ...</h5></div>\
        ");
}

function preloadHide(){
    $('.loader').remove();
    $('#preLoad').remove();
    $('.waiter').remove();
}

