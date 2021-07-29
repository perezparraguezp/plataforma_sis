var ancho = 0;
var alto  = 0;
function init() {
    loadDimensiones();
}
function alertaLateral(texto){
    var toastHTML = '<span>'+texto+'</span>';
    Materialize.toast(toastHTML, 4000);
}
function loadDimensiones() {
    var myWidth = 0, myHeight = 0;
    if( typeof( window.innerWidth ) == 'number' ) {
        //No-IE
        myWidth = window.innerWidth;
        myHeight = window.innerHeight;
    } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
        //IE 6+
        myWidth = document.documentElement.clientWidth;
        myHeight = document.documentElement.clientHeight;
    } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
        //IE 4 compatible
        myWidth = document.body.clientWidth;
        myHeight = document.body.clientHeight;
    }
    ancho = myWidth;
    alto  = myHeight;
    $("#main").css(
        {
            "height":alto-110+"px",
            "overflow-y":"scroll",
        });
}

