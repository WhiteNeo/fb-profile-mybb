<?php

/**
*@ Autor: DNT (Dark Neo - Programing and desingn, Iñaki, Ocras - stilization and customization )
*@ Fecha: 2013-05-25
*@ Version: 1.0
*@ Contacto: neogeoman@gmail.es
*@ Web: http://darkneo.skn1.com (temporally)
*/

// Inhabilitar acceso directo a este archivo
if(!defined('IN_MYBB'))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

// Añadir enganche en el perfil de los usuarios...
$plugins->add_hook('member_profile_end','facebook_profile');
// Añadir enganche para los campos de perfil del usuario...
$plugins->add_hook("usercp_profile_start", "facebook_profile_fields");
//Al actualizar los campos necesarios...
$plugins->add_hook("datahandler_user_update", "facebook_profile_fieldsb");
$plugins->add_hook("datahandler_user_update", "facebook_profile_fieldsy");

// Información del plugin
function fb_profile_info()
{

	global $mybb, $cache, $db, $lang;

    //$lang->load("fb_profile", false, true);
	$fb_profile_config = '';

	$query = $db->simple_select('settinggroups', '*', "name='fb_profile'");

	if (count($db->fetch_array($query)))
	{
		//$fb_profile_config = '(<a href="index.php?module=config&action=change&search=fb_profile" style="color:#035488;">'.$db->escape_string($lang->fb_profile_config).'</a>)';
		$fb_profile_config = '(<a href="index.php?module=config&action=change&search=fb_profile" style="color:#035488;">Configurar Plugin</a>)';
	}

	return array(
     // "name"			=> $db->escape_string($lang->fb_profile_name),
    //	"description"	=> $db->escape_string($lang->fb_profile_descrip) . "  " . $fb_profile_config,
        "name"			=> "Perfil tipo facebook",
    	"description"	=> "Agrega la funcionalidad de colocar una imagen de biografia y datos nuevos tipo faceboook en los perfiles de usuario",
	    "website"		=> "http://www.soportemybb.com.",
		"author"		=> "<a href='http://darkneo.skn1.com'>DNT</a>",
		"authorsite"	=> "http://darkneo.skn1.com",
		"version"		=> "1.0",
		"guid" 			=> "",
		"compatibility" => "16*"
	);
}

//Al activar el plugin creamos los cambios en las platillas necesarias
function fb_profile_activate()
{
global $db, $templates;

//Creamos los campos en la base de datos que vamos a utilizar...
	$db->query("ALTER TABLE ".TABLE_PREFIX."users ADD biography VARCHAR(255) NOT NULL AFTER usernotes, ADD youtube VARCHAR(255) NOT NULL AFTER usernotes");

//Creamos la hoja d eestilo para nuestros perfiles de facebook...
	$query_tid = $db->write_query("SELECT tid FROM ".TABLE_PREFIX."themes WHERE def='1'");
	$themetid = $db->fetch_array($query_tid);
	$style = array(
			'name'         => 'fb_profile.css',
			'tid'          => $themetid['tid'],
			'attachedto'   => 'member.php',
			'stylesheet'   => $db->escape_string('.fb_profile_container{
    background: #F1F1F1;
    border: 1px solid #DCDCDC;
    padding: 10px 10px;
    width: 980px;
    height: 253px;
    border-radius: 2px;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
}

#fb_profile_background{
    background: #E9E9E9 url(images/biografia.png) no-repeat;
    width: 975px;
    height: 180px;
    display: block;
    border: 1px solid #BFBFBF;
    border-radius: 2px;
    z-index: 0;
}

.fb_profile_avatar{
	padding: 6px;
	position: absolute;
	background: #D4D4D4;
	width: 110px;
	height: 96px;
	margin-top: -80px;
	margin-left: 30px;
	border: 1px solid #FFF;
	border-radius: 3px;
        z-index: 5;
}

#fb_profile_avatar_item{
	margin-top: -2px;
	margin-left: -398px;
}

#fb_profile_bar{
	padding:1px;
	border-radius: 2px;
	border: 1px solid #D2D2D2;	
	background: #D4D4D4;
	width: 970px;
	height: 40px;
	margin-top: -12px;
    opacity:0.8;
    filter:alpha(opacity=80); /* For IE8 and earlier */  
}

.fb_profile_menu{
	position: absolute;
	margin-left: 30px;
	margin-top: -1px;
	font-family: Verdana;
	font-size: 12px;
        z-index: 2;
}

#fb_profile_menu_item1{
        background: #21A81A;
        color: #F4F4F4;
	padding: 6px 8px 6px 8px;
	margin: 6px 6px;
	font-family: Verdana;
	font-size: 11px;
	border:2px solid #D8DFEA;
	border-radius: 6px;  
        opacity:0.9;
        filter:alpha(opacity=90); /* For IE8 and earlier */     
}

#fb_profile_menu_item2{
        background: rgb(0, 108, 177);
        color: #F4F4F4;
	padding: 6px 8px 6px 8px;
	margin: 6px 6px;
	font-family: Verdana;
	font-size: 11px;
	border:2px solid #D8DFEA;
	border-radius: 6px;  
        opacity:0.9;
        filter:alpha(opacity=90); /* For IE8 and earlier */     
}

.fb_profile{
    background: #ffffff; /* Old browsers */
    background: -moz-linear-gradient(top, #ffffff 0%, #f1f1f1 50%, #e1e1e1 51%, #f6f6f6 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(50%,#f1f1f1), color-stop(51%,#e1e1e1), color-stop(100%,#f6f6f6)); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top, #ffffff 0%,#f1f1f1 50%,#e1e1e1 51%,#f6f6f6 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top, #ffffff 0%,#f1f1f1 50%,#e1e1e1 51%,#f6f6f6 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top, #ffffff 0%,#f1f1f1 50%,#e1e1e1 51%,#f6f6f6 100%); /* IE10+ */
    background: linear-gradient(to bottom, #ffffff 0%,#f1f1f1 50%,#e1e1e1 51%,#f6f6f6 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#ffffff\', endColorstr=\'#f6f6f6\',GradientType=0 ); /* IE6-9 */
    border: 2px solid #424242;
    border-radius: 3px;
    color: #333333;
    cursor: pointer;
    display: inline-block;
    font-family: \'lucida grande\',tahoma,verdana,arial,sans-serif;
    font-size: 11px;
    font-weight: bold;
    line-height: 13px;
    text-decoration: none;
    margin: 0 5px;
    padding: 5px 10px 5px 5px;
    white-space: nowrap;
}

.fb_profile:hover{
    color: #f75209;
    text-decoration: none;
}

.fb_profile:active{
    color: #82abc7;
    text-decoration: none;
}


.fb_profile_menu {
        width: 958px;
        background: #ffffff; /* Old browsers */
        background: -moz-linear-gradient(top,  #ffffff 0%, #f3f3f3 50%, #ededed 51%, #ffffff 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(50%,#f3f3f3), color-stop(51%,#ededed), color-stop(100%,#ffffff)); /*
Chrome,Safari4+ */
        background: -webkit-linear-gradient(top,  #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top,  #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top,  #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%); /* IE10+ */
        background: linear-gradient(to bottom,  #ffffff 0%,#f3f3f3 50%,#ededed 51%,#ffffff 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#ffffff\', endColorstr=\'#ffffff\',GradientType=0 ); /* IE6-9 */
        text-align: center;
        padding: 15px 10px;
        margin-left: 0px;
        margin-top: -10px;
        z-index: 3;
}

.fb_profile_menu a:link {
	color: #254b82;
	text-decoration: none;
        font-family: Verdana, Tahoma, Courier;
        font-size: 12px;
        font-weight:bold;
        background-color: transparent;
}

.fb_profile_menu a:hover {
	color: Orange;
	text-decoration: none;
        font-family: Verdana, Tahoma, Courier;
        font-size: 12px;
        font-weight:bold;
        background-color: transparent;
}

.fb_profile_menu a:visited {
	color: #000;
	text-decoration: none;
        font-family: Verdana, Tahoma, Courier;
        font-size: 12px;
        font-weight:bold;
        background-color: transparent;
}

#fb_profile_menu a{
        color: #000;
	margin: 0 -4px 0 0;
        padding: 14px 10px;
        border-right: 1px solid rgb(236, 236, 236);
        font-weight: 600;
}

#fb_profile_menu a:hover{
	color: rgba(0, 0, 0, 0.7);
        border-top: 0.5px solid rgb(0, 173, 255);
        border-bottom: 2px solid rgb(0, 173, 255);
}

.fb_profile span{
   margin-left: 24px;}'),
			'lastmodified' => TIME_NOW
		);
		$sid = $db->insert_query('themestylesheets', $style);
		$db->update_query('themestylesheets', array('cachefile' => "css.php?stylesheet={$sid}"), "sid='{$sid}'", 1);
		$query = $db->simple_select('themes', 'tid');
		while($theme = $db->fetch_array($query))
		{
			require_once MYBB_ADMIN_DIR.'inc/functions_themes.php';
			update_theme_stylesheet_list($theme['tid']);
		}
		
	//Insertamos las plantillas que vamos a utilizar...
$fb_biography_user = array(
		"title"		=> "fb_biography",
		"template"	=> $db->escape_string('<center>
<table border="0" height="100%">
<tr>
<td align="center">
<div  class="fb_profile_container">
<strong>Perfil de {$memprofile[\'fb_user\']}<br />
{$online_status}</strong><br />
<a href="usercp.php?action=profile#biography">
<div id="fb_profile_background">
<img src="{$fb_data[\'biography\']}" style="width: 975px; height: 180px;" />
</div>
</a>
<a href="usercp.php?action=avatar">
<div class="fb_profile_avatar">
<img src="{$memprofile[\'avatar\']}" style="width: 110px; height: 96px;" />
</div>
</a>
<div id="fb_profile_avatar_item">
<strong>{$formattedname}</strong>
<br />
</div>
<div class="fb_profile_menu" id="fb_profile_menu">
<a href="usercp.php?action=profile#biography"><span>Opciones</span></a>
<a href="search.php?action=finduser&uid={$memprofile[\'uid\']}"><span><strong>Mensajes<span id="fb_profile_menu_item1">{$memprofile[\'postnum\']}</span></strong></span></a> 
<a href="reputation.php?uid={$memprofile[\'uid\']}"><span><strong>Reputacion<span id="fb_profile_menu_item2">{$memprofile[\'reputation\']}</span></strong></span></a>
</div>
<br /><br /><br />
{$fb_profile[\'buddies\']}
</div>
<br /><br /><br />
</td>
</tr>
</table>
</center>
<br />'),
		"sid"		=> -1);	

$fb_biography_nonuser = array(
		"title"		=> "fb_biography_none",
		"template"	=> $db->escape_string('<center>
<table border="0" height="100%">
<tr>
<td align="center">
<div  class="fb_profile_container">
<strong>Perfil de {$memprofile[\'fb_user\']}<br />
{$online_status}</strong><br />
<div id="fb_profile_background">
<img src="{$fb_data[\'biography\']}" style="width: 975px; height: 180px;" />
</div>
<div class="fb_profile_avatar">
<img src="{$memprofile[\'avatar\']}" style="width: 110px; height: 96px;" />
</div>
<div id="fb_profile_avatar_item">
<strong>{$formattedname}</strong>
<br />
</div>
<div class="fb_profile_menu" id="fb_profile_menu">
<a href="member.php?action=emailuser&uid={$memprofile[\'uid\']}"><span>Enviar Correo</span></a>
<a href="private.php?action=send&uid={$memprofile[\'uid\']}"><span>Enviar MP</span></a>
<a href="search.php?action=finduser&uid={$memprofile[\'uid\']}"><span><strong>Mensajes<span id="fb_profile_menu_item1">{$memprofile[\'postnum\']}</strong></span></span></a> 
<a href="reputation.php?uid={$memprofile[\'uid\']}"><span><strong>Reputacion<span id="fb_profile_menu_item2">{$memprofile[\'reputation\']}</strong></span></span></a>
</div>
<br /><br /><br />
{$fb_profile[\'buddies\']}
</div>
<br /><br /><br />
</td>
</tr>
</table>
</center>
<br />'),
		"sid"		=> -1);	
	
$youtube = array(
		"title"		=> "youtube_video",
		"template"	=> $db->escape_string('<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder" width="450">
<tr>
<td class="thead">
<div class="expcolimage"><img src="{$theme[\'imgdir\']}/collapse{$collapsedimg[\'boardstats\']}.gif" id="youtube_1_img" class="expander" alt="[-]" title="[-]" /></div>
<div class="strong">Video de Youtube</div>
</td>
</tr>
<tbody style="{$collapsed[\'boardstats_e\']}" id="youtube_1_e">
<tr>
<td align="center">
<iframe class="youtube-player" type="text/html" width="450" height="400" src="http://www.youtube.com/embed/{$fb_data[\'youtube\']}?version=3&rel=0" frameborder="0"></iframe>
</td>
</tr>
</tbody>
</table>'),
		"sid"		=> -1);

$youtube_none = array(
		"title"		=> "youtube_video_none",
		"template"	=> $db->escape_string('<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder" width="450">
<tr>
<td class="thead">
<div class="expcolimage"><img src="{$theme[\'imgdir\']}/collapse{$collapsedimg[\'boardstats\']}.gif" id="youtube_1_img" class="expander" alt="[-]" title="[-]" /></div>
<div class="strong">Video de Youtube</div>
</td>
</tr>
<tbody style="{$collapsed[\'boardstats_e\']}" id="youtube_1_e">
<tr>
<td align="center">
{$text_youtube}
</td>
</tr>
</tbody>
</table>'),
		"sid"		=> -1);		

	$db->insert_query("templates", $fb_biography_user);
	$db->insert_query("templates", $fb_biography_nonuser);
	$db->insert_query("templates", $youtube);
	$db->insert_query("templates", $youtube_none);
		
    //Insertamos los cambios en las plantillas que deseamos, para traer nuestras variables...
    require MYBB_ROOT.'/inc/adminfunctions_templates.php';
	find_replace_templatesets("member_profile", '#'.preg_quote('{$header}').'#', '{$header}
{$fb_biography}
{$youtube}');
	find_replace_templatesets('usercp_profile','#{\$customfields\}#','{$customfields}
{$fb_biography_field}
{$youtube_field}');    
} 

//Al desactivar eliminamos los cambios que hicimos en las plantillas
function fb_profile_deactivate()
{
global $db;
     //Eliminamos las tabla de la base de datos...
	 //$sql ="DROP TABLE IF EXISTS `".TABLE_PREFIX."fbprofile`;";
     //$db->write_query($sql);
	 $db->query("ALTER TABLE ".TABLE_PREFIX."users DROP COLUMN biography, DROP COLUMN youtube");
    
    //Eliminamos la hoja de estilo creada...
    	$db->delete_query('themestylesheets', "name='fb_profile.css'");
		$query = $db->simple_select('themes', 'tid');
		while($theme = $db->fetch_array($query))
		{
			require_once MYBB_ADMIN_DIR.'inc/functions_themes.php';
			update_theme_stylesheet_list($theme['tid']);
		}
	$db->delete_query("templates","title = 'fb_biography'");
	$db->delete_query("templates","title = 'fb_biography_none'");
	$db->delete_query("templates","title = 'youtube_video'");
	$db->delete_query("templates","title = 'youtube_video_none'");
    //Deshacemos los cambios en las plantillas...
    require MYBB_ROOT.'/inc/adminfunctions_templates.php';
	find_replace_templatesets("member_profile", '#'.preg_quote('{$fb_biography}').'#', '');
	find_replace_templatesets("member_profile", '#'.preg_quote('{$youtube}').'#', '');	
	find_replace_templatesets("usercp_profile", '#'.preg_quote('{$fb_biography_field}').'#', '');	 
	find_replace_templatesets("usercp_profile", '#'.preg_quote('{$youtube_field}').'#', '');		
} 

//Funcion que crea los perfiles tipo facebook
function facebook_profile(){
 global $mybb, $db, $memprofile, $buddy, $fb_biography, $fb_profile, $youtube, $templates, $theme, $online_status;
 /* 25 - Mayo - 2013 Dark Neo 
  *Creamos la lista de amigos en tu perfil
  * Esta funcion trae la lista de amistades, si no tienes igualmente no trae datos
  * si tienes te pone 6 unicamente en tu lista y luego un boton para ver todos*/
 //Decimos que si estamos en el perfil, tu lista de amigos no está vacia y no eres invitado...
 if(THIS_SCRIPT == 'member.php' && !empty($memprofile['buddylist']) && !$mybb->user['uid'] == 0)
 {
      //Ejecutamos la consulta para traer los datos...
      $query = $db->query("SELECT u.uid, u.username AS useruname, u.avatar, u.usergroup, u.displaygroup
						   FROM " . TABLE_PREFIX . "users u 
						   WHERE u.uid IN({$memprofile['buddylist']}) ORDER BY u.uid LIMIT 0, 6");
						   
			while($buddy = $db->fetch_array($query))
			{
			      if($buddy['avatar'] == ''){
				  $buddy['avatar'] = 'images/default_avatar.gif';
				  }
				  $fb_profile['uid'] = (int)$buddy['uid'];
				  $fb_profile['avatar'] = htmlspecialchars_uni($buddy['avatar']);
			      $fb_profile['profilelink'] = get_profile_link($buddy['uid']);
				  if(my_strlen($buddy['useruname']) > 6)
				  {
					$buddy['useruname'] = my_substr($buddy['useruname'], 0, 4)."...";
				  }
				  $buddy['useruname']	= htmlspecialchars_uni($buddy['useruname']);
				  $fb_profile['buddy_name'] = format_name($buddy['useruname'],$buddy['usergroup'],$buddy['displaygroup']);
				  $fb_profile['buddies'] .= '<a href="' . $fb_profile['profilelink'] . '"><img src="' . $fb_profile['avatar'] . '" style="padding:4px; width: 40px; height: 40px; border: 1px solid #C3C3C3; border-radius: 6px; background: #D4D4D4; opacity:0.8; filter:alpha(opacity=80); margin-left: 10px;"><span style="position:absolute; margin-left: -45px; margin-top: 37px; z-index: 6;">' . $fb_profile['buddy_name'] . '</span></a>';
			}
              
			//Obtenemos la lista de usuarios, si es que el usuario tiene amigos y los ordenamos uno a uno  
		    $buddylist=explode(',',$memprofile['buddylist']);
		    $buddies_count=count($buddylist);
		    if(in_array($fb_profile['uid'],$buddylist)){			
			 $fb_profile['buddies'] = '<div style="margin-left: 200px;">' . $fb_profile['buddies'] . '<br /><a onclick="MyBB.popupWindow(\'misc.php?action=buddypopup\', \'Lista de Amigos\', 400, 400);" href="#">Ver '.$buddies_count.' amigo(s)</a></div>';
			}
 }
 //Si no tienes amigos en tu lista y no eres invitado, te mostramos los contenidos...
 else if(empty($memprofile['buddylist']) && !$mybb->user['uid'] == 0){
	 $fb_profile['buddies'] = '<div style="margin-left: 200px;">Este usuario no tiene amigos en su lista de amigos...</div>';
 }
 /* 25 - Mayo - 2013
  * Aqui termina la funcion*/

 /* 26 - Mayo - 2013 Dark Neo 
  * Función que crea los perfiles tipo facebook, utilizamos la parte de arriba para traer la lista de amigos xD...
  * Esta función es hasta la linea final, trae datos si eres el propietario o si no y permite cambiar tu avatar 
  * y tu imagen de biografia si eres el propietario del perfil actual */
   //Ejecutamos la consulta para traer los datos...
   $query = $db->query("SELECT youtube, biography, username, usergroup, displaygroup FROM " . TABLE_PREFIX . "users WHERE uid = ".intval($memprofile['uid'])." LIMIT 1");
   $fb_data = $db->fetch_array($query);    
   
   $memprofile['fb_user'] = format_name($fb_data['username'],$fb_data['usergroup'],$fb_data['displaygroup']);
	if($memprofile['avatar'] == ''){$memprofile['avatar']='images/default_avatar.png';}
// Si eres el propietario del perfil que estas viendo...
 if($memprofile['uid'] == $mybb->user['uid']){
    $text_youtube = 'Aun no tienes videos en tu perfil, <a href="usercp.php?action=profile#youtube">clic aqui para agregar un video...</a>';
	 if(empty($fb_data['biography']))
     eval("\$fb_biography = \"".$templates->get("fb_biography")."\";");
	 else{
	 eval("\$fb_biography = \"".$templates->get("fb_biography")."\";");
	 }
 }
// Si no eres el propietario del perfil xD...
 else{
 $text_youtube = 'Este usuario no ha escogido un video...';
    eval("\$fb_biography = \"".$templates->get("fb_biography_none")."\";");
}
	if(empty($fb_data['youtube'])){
		eval("\$youtube = \"".$templates->get("youtube_video_none")."\";");
	}
	else{
		eval("\$youtube = \"".$templates->get("youtube_video")."\";");
	}
}

/* 26 - Mayo - 2013
 * Aqui termina la funcion*/

/* 29 - Mayo - 2013
 * Creamos los campos de perfil nuevos, para traer la url de biografia y de youtube...*/

 
function facebook_profile_fields(){

global $mybb, $fb_biography_field, $youtube_field;

$fb_data['youtube'] = $mybb->user['youtube'];
$fb_data['biography'] = $mybb->user['biography'];

$fb_biography_field ='<br />
<fieldset id="biography" class="trow2">
<legend><strong>Imagen de la Biograf&iacute;a</strong></legend>
<table cellspacing="0" cellpadding="{$theme[\'tablespace\']}">
<tr>
Ingresa la URL de la imagen para tu biograf&iacute;a:<br /><b>Ej. http://www.darkneo.xunem.com/images/biografia.png</b><br />
<input type="text" class="textbox" name="biography" size="50" value="'.$fb_data['biography'].'" />
</tr>
</table>
</fieldset>';
	
$youtube_field ='<br />
<fieldset id="youtube" class="trow2">
<legend><strong>Video de Youtube</strong></legend>
<table cellspacing="0" cellpadding="{$theme[\'tablespace\']}">
<tr>
Ingresa la ID de tu video de youtube:<br /><b>Ej. xkqo_WKwawA</b><br />
<input type="text" class="textbox" name="youtube" size="30" value="'.$fb_data['youtube'].'" />
</tr>
</table>
</fieldset>';

}

//Actualizamos los campos cuando traigan datos nuevos...
function facebook_profile_fieldsb($fb_biography){

global $mybb, $db;

	if(isset($mybb->input['biography']))
	{
		$fb_biography->user_update_data['biography'] = $db->escape_string($mybb->input['biography']);
	}
}

function facebook_profile_fieldsy($youtube){

global $mybb, $db;

	if(isset($mybb->input['youtube']))
	{
		$youtube->user_update_data['youtube'] = $db->escape_string($mybb->input['youtube']);
	}
}

/* 29 - Mayo - 2013
 * Fin de la funcion que crea los campos de perfil nuevos...*/

?>
