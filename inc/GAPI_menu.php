<?php 
function GAPI_main_menu(){require GAPI_PATH.'/templates/GAPI_general_settings.php';}



add_action('admin_menu', 'wpse149688');
function wpse149688(){
	add_menu_page( 'General Api', 'General Api', 'read', 'GAPI_main_menu', 'GAPI_main_menu');
	// add_submenu_page( 'GAPI_main_menu', 'Import Products', 'Import Products', 'read', 'pb_donwload_list', 'pb_donwload_list');
}

