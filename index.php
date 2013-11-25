<?php
require_once('DB/db.php');
	require_once('lib.php');
session_start();

//se saca el idioma
$lang = getLang();

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
	<style type="text/css" media="screen">
			#inner {
			    width: 50%;
			    margin: 0 auto;
			}
			.jqm-content {
				padding-right: 25%;
				padding-left: 25%;
			}
	</style>
	<title>Cloudinator</title>
</head>
<body>

<section id="login" data-role="page">
<div data-role="header" data-theme="b">
    <h1>Cloudinator</h1>

    <a href="#popupNested" data-rel="popup" data-role="button" data-inline="true" data-icon="bars" data-theme="b" data-transition="pop"><?php echo get_string("language", $lang)?></a>
</div>




<div data-role="popup" id="popupNested" data-theme="none">
   		<fieldset data-role="controlgroup">
		        <input name="radio-choice-1" id="es" value="es" <?php if($lang=="es"){echo "checked=checked";} ?>  type="radio">
		        <label for="es">Español</label>
		        <input name="radio-choice-1" id="pt" value="pt" <?php if($lang=="pt"){echo "checked=checked";} ?> type="radio">
		        <label for="pt">Português</label>
		        <input name="radio-choice-1" id="en" value="en" <?php if($lang=="en"){echo "checked=checked";} ?> type="radio">
		        <label for="en">English</label>
		</fieldset>

</div><!-- /popup -->








<div data-role="content" id="content" class="jqm-content ui-content" >

<form action="">

<CENTER> <p><strong><?php echo get_string("loginbefore", $lang)?></strong></p></CENTER> 
<table style="width: 100%; margin: 0 auto;">
	
	<tr>
		<td><label for="text-username"><?php echo get_string("email", $lang)?>:</label></td>	
		<td><input type="text" name="text-username" id="text-username" value=""></td>
	</tr>
	<tr>
		<td><label for="passwordcloud"><?php echo get_string("password", $lang)?>:</label></td>
		<td><input type="password" name="passwordcloud" id="passwordcloud" value="" ></td>
	</tr>
	<tr>

		<td><label for="select-choice-1" class="select"><?php echo get_string("companytointerview", $lang)?></label></td>
		<td>
			<select name="select-choice-1" id="select-choice-1">
			    <option value="new"><?php echo get_string("newcompany", $lang)?></option>
			</select>
		</td>
	</tr>
</table>

<table style="width: 100%; margin: 0 auto;">
	
	<tr>
		<td><a href="#" id="btnLogin" data-role="button"><?php echo get_string("login", $lang)?></a></td>
	</tr>
</table>
</form>
</div>  	
<div id="errorMsg" style="background-color:red;color: #FFFFFF;"><?php echo get_string("namepassinvalid", $lang)?></div>
</section>

<!-- Aqui nuestro dialog con el mensaje de error  -->
    <section id="pageError" data-role="dialog">
        <header data-role="header">
            <h1>Error</h1>
        </header>
        <article data-role="content">
            <p><?php echo get_string("namepassinvalid", $lang)?></p>
            <a href="#" data-role="button" data-rel="back"><?php echo get_string("accept", $lang)?></a>
        </article>
    </section>

<script src="js/cloudnav.js" type="text/javascript"></script>
<script type="text/javascript" src="http://webcursos.uai.cl/jira/s/es_ES-jovvqt-418945332/850/3/1.2.9/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?collectorId=2ab5c7d9"></script> <!-- JIRA (para reportar errores)-->
<style type="text/css">.atlwdg-trigger.atlwdg-RIGHT{background-color:red;top:70%;z-index:10001;}</style>
</body>
</html>