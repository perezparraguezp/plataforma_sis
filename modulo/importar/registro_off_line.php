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
</style>
<form id="start_form" action="#" method="post">
    <input type="hidden" id="total_comments" name="total_comments" value="<?php echo $num_total_rows; ?>" />
    <div class="form-group">
        <label for="batch">Número de elementos que se procesan en cada iteración</label>
        <select class="form-control" id="batch" name="batch">
            <?php
            //divisors
            $num_total_rows = 100;
            for($i = 1; $i < $num_total_rows; $i ++) {
                if ($num_total_rows % $i == 0) {
                    echo '<option value="'.$i.'">'.$i.'</option>';
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <a href="#" class="btn btn-primary" onclick="executeProcess(0);return false;">
            <i class="fa fa-eye"></i> Ejecutar
        </a>
    </div>
</form>
<div id="sending" class="col-lg-12" style="display:none;">
    <h3>Procesando...</h3>
    <div class="progress">
        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" data-progress="0" style="width: 0%;">
            0%
        </div>
    </div>
    <div class="counter-sending">
        (<span id="done">0</span>/<span id="total">0</span>)
    </div>
    <div class="end-process" style="display:none;">
        <div class="alert alert-success">El proceso ha sido completado. <a href="https://www.jose-aguilar.com/scripts/jquery/ajax-progress-bar/">Probar otra vez</a></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script type="text/javascript">
    function executeProcess(offset, batch = false) {
        if (batch == false) {
            batch = parseInt($('#batch').val());
        } else {
            batch = parseInt(batch);
        }

        if (offset == 0) {
            $('#start_form').hide();
            $('#sending').show();
            $('#sended').text(0);
            $('#total').text($('#total_comments').val());

            //reset progress bar
            $('.progress-bar').css('width', '0%');
            $('.progress-bar').text('0%');
            $('.progress-bar').attr('data-progress', '0');
        }

        $.ajax({
            type: 'POST',
            dataType: "json",
            url : "php/insert_infantil.php",
            data: $("#frmload").serialize(),
            success: function(response) {
                $('.progress-bar').css('width', response.percentage+'%');
                $('.progress-bar').text(response.percentage+'%');
                $('.progress-bar').attr('data-progress', response.percentage);

                $('#done').text(response.executed);
                // $('.execute-time').text(response.execute_time);

                if (response.percentage == 100) {
                    $('.end-process').show();
                } else {
                    var newOffset = offset + batch;

                    executeProcess(newOffset, batch);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == 'parsererror') {
                    textStatus = 'Technical error: Unexpected response returned by server. Sending stopped.';
                }
                alert(textStatus);
            }
        });
    }
</script>