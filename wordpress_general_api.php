<?php
/**
* @package w_a_p_l
* @version 1.0
*/
/*
Plugin Name: Wordpress General Api
Plugin URI: #
Description: Wordpress General Api
Version: 1.0
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: GAPI
Author URI: #
*/
/*
Copyright (C) Year  Author  Email : charlestsmith888@gmail.com
Woocommerce Advanced plugin layout is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.
Woocommerce Advanced plugin layout is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with Woocommerce Advanced plugin layout; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('GAPI_PATH', dirname(__FILE__));
$plugin = plugin_basename(__FILE__);
define('GAPI_URL', plugin_dir_url($plugin));


require GAPI_PATH.'/inc/GAPI_main_class.php';
require GAPI_PATH.'/inc/GAPI_menu.php';
require GAPI_PATH.'/inc/GAPI_rest_api_class.php';





// Styling and scripts
add_action('booking_script_css', 'booking_script_css_styles');
function booking_script_css_styles(){
    echo '<link rel="stylesheet" href="'.GAPI_URL.'assets/css/bootstrap.css">';
}




// temporarry
function mailtrap($phpmailer) {
  $phpmailer->isSMTP();
  $phpmailer->Host = 'smtp.mailtrap.io';
  $phpmailer->SMTPAuth = true;
  $phpmailer->Port = 2525;
  $phpmailer->Username = '8d42ca2c04f8b1';
  $phpmailer->Password = '1500a5daf584f2';
}
add_action('phpmailer_init', 'mailtrap');



add_action('init',  'functionasdaf');


function functionasdaf(){
	

  

  $available_zones = WC_Shipping_Zones::get_zones();
  $all_countries  = WC()->countries->get_countries();
  $available_zones_names = array();
  $i = 0;
  foreach ($available_zones as $zone ) {
    if( !in_array( $zone['zone_name'], $available_zones_names ) ) {
      $available_zones_names[$i]['name'] = $zone['zone_name'];
      $available_zones_names[$i]['methods'] = $zone['shipping_methods'];
      $available_zones_names[$i]['available_Coutries'] = $zone['zone_locations'];

    }
    $i++;
  }
  $ii = 0;
  $methods = [];
  foreach ($available_zones_names as $key1 => $value1) {
    $instance = [];
    $availcountries = [];
    foreach ($value1['methods'] as $key => $value11) {
      $instance[] = $value11->instance_id;
    }

    $countriescode = [];
    foreach ($value1['available_Coutries'] as $key22) {
        $countriescode[] = $key22->code;
    }



    $methods[$ii]['name'] = $value1['name'];
    $methods[$ii]['instances'] = $instance;
    $methods[$ii]['countries'] = $countriescode;
    $ii++;
  }




  // $all_countries  = WC()->countries->get_countries();
  // pr($all_countries);
  // die();


  // $instance_id     = 3;
  // $zone            = WC_Shipping_Zones::get_zone_by( 'instance_id', $instance_id );
  // $shipping_method = WC_Shipping_Zones::get_shipping_method( $instance_id );
  //   $shipping_method->set_post_data( wp_unslash( $_POST['data'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
  //   $shipping_method->process_admin_options();
  //   WC_Cache_Helper::get_transient_version( 'shipping', true );
   
  //   wp_send_json_success(
  //     array(
  //       'zone_id'   => $zone->get_id(),
  //       'zone_name' => $zone->get_zone_name(),
  //       'methods'   => $zone->get_shipping_methods( false, 'json' ),
  //       'errors'    => $shipping_method->get_errors(),
  //     )
  //   );



// pr($methods);
// die();





// $methods = WC_Shipping_Zones::get_shipping_method(0);


// foreach ($existing_zones as $key => $value) {
// 	$zone[]  = new WC_Shipping_Zone($key);
	
// }

// $zones = $zone->get_data();


// pr($zones);
// die();


}

// $all_zones = WC_Shipping_Zones::get_zones();
// pr($all_zones);