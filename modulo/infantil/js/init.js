function alertaLateral(texto){
    var toastHTML = '<span>'+texto+'</span>';
    Materialize.toast(toastHTML, 4000);
}
function menuEhOpen_infantil(menu) {
    $.post('menu.php',{
        menu:menu
    },function(data){
        $("#div_menu").html(data);
        //loadEstadisticaGeneral();
    });
}
function infoLateral(){
    var div = 'right-sidebar-nav';
    $.post('info/lateral.php',{
    },function(data){
        $("#"+div).html(data);

    });
}
function loadMenu_Infantil(menu,php,rut) {
    var div = 'content';
    $.post('menu/'+php+'.php',{
        rut:rut
    },function(data){
        $("#"+div).html(data);
    });
    $( "aside #slide-out li" ).removeClass( "active" );
    $( "aside #slide-out #"+menu ).addClass( "active" );
}
function loading_div(div){
    $.post('../../php/loading.php',{
    },function(data){
        $("#"+div).html(data);
    });
}