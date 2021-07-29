<div class="row">
    <div class="col l12">

    </div>
</div>
<script type="text/javascript">

    function boxInfoAgrupacion(id){
        $.post('php/modal/perfil/agrupacion.php',{
            id:id
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                document.getElementById("btn-modal").click();
            }
        });
    }
    function delete_documento(id){
        if(confirm("Seguro que desea eliminar este documento del sistema")){
            $.post('php/db/delete/documento.php',{
                id:id
            },function(data){
                if(data !== 'ERROR_SQL'){
                    loadListaDocumentos();
                }
            });
        }
    }
    function loadForm_updateAtributo(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/formulario/perfil/ingreso_atributo.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
                $('#tipo_contrato').jqxDropDownList({
                    theme: 'energyblue',
                    filterable: true,
                    filterPlaceHolder: "Buscar",
                    width: '100%',
                    height: '25px'
                });
            }else{

            }
        });

    }
    function loadForm_newPersonal(){
        var div = 'contenido_menu';

        $.post('formulario/nuevo_profesional.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
                $('#tipo_contrato').jqxDropDownList({
                    theme: 'energyblue',
                    filterable: true,
                    filterPlaceHolder: "Buscar",
                    width: '100%',
                    height: '25px'
                });
                $(".fecha").jqxDateTimeInput({ width: '300px', height: '25px' ,formatString: "yyyy-MM-dd"});
            }else{

            }
        });
    }
    function loadGrid_profesionales() {
        var div = 'contenido_menu';

        $.post('grid/profesionales.php',{
        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
                $('#tipo_contrato').jqxDropDownList({
                    theme: 'energyblue',
                    filterable: true,
                    filterPlaceHolder: "Buscar",
                    width: '100%',
                    height: '25px'
                });
                $(".fecha").jqxDateTimeInput({ width: '300px', height: '25px' ,formatString: "yyyy-MM-dd"});
            }else{

            }
        });
    }
</script>
<div class="card-panel">
    <div class="row">
        <div class="col l4">
            <button class="btn col l12" onclick="loadForm_newPersonal()">NUEVO PROFESIONAL</button>
        </div>
        <div class="col l4">
            <button class="btn col l12" onclick="loadGrid_profesionales()">LISTAR PROFESIONALES</button>
        </div>
    </div>
    <div class="row margin"></div>
</div>
<div id="contenido_menu" class="container content">

</div>

