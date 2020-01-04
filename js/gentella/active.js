
// $(document).ready(function(){
var progress = 0;

/* construct manually */
var interval = setInterval(function () {
    if(progress <= 100) {
        var randNum = getRndInteger(progress, progress + 20);
        progress = randNum;
        loadBarProgress(progress);
    }
    else{
        clearInterval(interval);
    }
},2000);
function getRndInteger(min, max) {
    return Math.floor(Math.random() * (max - min) ) + min;
}

function loadBarProgress(progress) {
    var bar1 = new ldBar("#loadBar");
    /* ldBar stored in the element */
    var bar2 = document.getElementById('loadBar').ldBar;
    bar1.set(progress);
}

function sendData(postdata) {
    $.ajax({
        url: 'http://127.0.0.1:8000/api/saveData',
        type: 'POST',
        data: postdata,
        success: function(data){
            data = parseInt(data);
            if(data !== 0) {
                $.ajax({
                    url: 'saveActive',
                    type: 'POST',
                    data: postdata,
                    success: function (data) {

                    }
                });
            }
            else {
                clearInterval(interval);
            }
        },
        error: function (request,error) {
            console.log(error);
        }
    });
}


// });