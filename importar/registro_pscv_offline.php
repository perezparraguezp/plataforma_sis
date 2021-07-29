<link rel="stylesheet" type="text/css" href="http://www.jqueryeasy.com/wp-content/themes/bigfoot/style.css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="jQuery Easy RSS Feed" href="http://www.jqueryeasy.com/feed/" />
<link rel="alternate" type="application/atom+xml" title="jQuery Easy Atom Feed" href="http://www.jqueryeasy.com/feed/atom/" />
<link rel="pingback" href="http://www.jqueryeasy.com/xmlrpc.php" />
<link rel="shortcut icon" href="http://www.jqueryeasy.com/wp-content/themes/bigfoot/images/favicon.ico" />
<link rel='stylesheet' id='wp-pagenavi-css'  href='http://www.jqueryeasy.com/wp-content/plugins/wp-pagenavi/pagenavi-css.css?ver=2.70' type='text/css' media='all' />
<script type='text/javascript' src='http://www.jqueryeasy.com/wp-includes/js/l10n.js?ver=20101110'></script>
<script type='text/javascript' src='http://www.jqueryeasy.com/wp-includes/js/jquery/jquery.js?ver=1.6.1'></script>
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="http://www.jqueryeasy.com/xmlrpc.php?rsd" />
<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="http://www.jqueryeasy.com/wp-includes/wlwmanifest.xml" />
<link rel='index' title='jQuery Easy' href='http://www.jqueryeasy.com/' />
<meta name="generator" content="WordPress 3.2.1" />
<!-- All in One SEO Pack 1.6.13.8 by Michael Torbert of Semper Fi Web Design[280,300] -->
<meta name="description" content="Blog donde podrás encontrar una serie artículos, tutoriales,  relacionados a la creación de aplicaciones web, utilizando las últimas tecnologías que actualmente existen como jQuery, PHP, Mysql, CSS3 y mas." />
<meta name="keywords" content="aplicaciones, jquery,css,php,html,javascript,java,aplicaciones jquery,seo,android,codeinigter,xml, aplicaciones codeigniter, cursos jquery, cursos, tutoriales" />
<link rel="canonical" href="http://www.jqueryeasy.com/" />
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<!-- /all in one seo pack -->
<link href="http://www.jqueryeasy.com/wp-content/plugins/fuzzy-seo-booster/seoqueries.css" rel="stylesheet" type="text/css" />
<!-- START: Syntax Highlighter ComPress -->
<script type="text/javascript" src="http://www.jqueryeasy.com/wp-content/plugins/syntax-highlighter-compress/scripts/shCore.js"></script>
<script type="text/javascript" src="http://www.jqueryeasy.com/wp-content/plugins/syntax-highlighter-compress/scripts/shAutoloader.js"></script>
<link type="text/css" rel="stylesheet" href="http://www.jqueryeasy.com/wp-content/plugins/syntax-highlighter-compress/styles/shCoreDefault.css"/>
<!-- END: Syntax Highlighter ComPress -->
<style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
<script language="javascript" type="text/javascript" src="http://www.jqueryeasy.com/wp-content/themes/bigfoot/javascripts/jquery.js"></script>
<script language="javascript" type="text/javascript" src="http://www.jqueryeasy.com/wp-content/themes/bigfoot/javascripts/tabber.js"></script>
<script language="javascript" type="text/javascript" src="http://www.jqueryeasy.com/wp-content/themes/bigfoot/javascripts/superfish.js"></script>
<!--[if lt IE 7]>
<script type="text/javascript" src="http://www.jqueryeasy.com/wp-content/themes/bigfoot/javascripts/pngfix.js"></script>
<script type="text/javascript" src="http://www.jqueryeasy.com/wp-content/themes/bigfoot/javascripts/menu.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="http://www.jqueryeasy.com/wp-content/themes/bigfoot/css/ie.css" />
<![endif]-->
<style type="text/css">
    #demos{
        width:90%;
        margin:10px auto 0 auto;
        padding:30px;
        border:1px solid #DFDFDF;
        font:normal 12px Arial, Helvetica, sans-serif
    }
    #demos h3{
        border-bottom:1px solid #DFDFDF;
        padding-bottom:7px;
        margin:10px 0
    }
    table{
        margin-top:15px;
        width:100%
    }
    table td{
        padding:7px;
        border:1px solid #CCC
    }
    #myProgress {
        width: 100%;
        background-color: #ddd;
    }

    #myBar {
        width: 1%;
        height: 30px;
        background-color: #4CAF50;
    }
</style>

<div id="demos">
    <div id="form_load_excel">
        <h3>CARGAR ARCHIVO EXCEL - REGISTROS PSCV</h3>
        <hr />
        <form name="frmload" id="frmload" method="post" enctype="multipart/form-data">
            <input type="file" name="file" id="file" />
            <br />
            <hr />
            <br />
            <input type="button"
                   onclick="uploadFile_XLS()"
                   style="background-color: #438eb9;color: white;padding: 10px;"
                   value="----- IMPORTAR REGISTROS OFF-LINE -----" />
        </form>
    </div>
    <div id="loading" style="display: none;text-align: center;">
        <img src="../loding.gif" width="300px" />
        <br />
        <header>CARGANDO DATOS</header>
        <div id="myProgress">
            <div id="myBar"></div>
            <div id="progress"></div>
        </div>
    </div>
    <div id="show_excel">
    </div>
    <script type="text/javascript">
        function uploadFile_XLS() {
            var formData = new FormData(document.getElementById("frmload"));
            $("#loading").show();
            $("#form_load_excel").hide();
            $("#show_excel").html('');
            var i = 1
            var elem = document.getElementById("myBar");
            var width = 1;

            //formData.append(f.attr("name"), $(this)[0].files[0]);
            $.ajax({
                url: "importar_registro_pscv.php",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    // setting a timeout
                    var input = document.getElementById('file');
                    var file = input.files[0];
                    var size = file.size;
                    var width = 0;
                    var i = 0;
                    while( i < size){
                        width = parseInt((i/size)*100);
                        elem.style.width = width + "%";
                        i++;
                    }

                },
            })
                .done(function(res){
                    $("#show_excel").html( res);
                    $("#loading").hide();
                    $("#form_load_excel").show();
                    // $("#form_load_excel").reset();
                });

        }

    </script>
</div>