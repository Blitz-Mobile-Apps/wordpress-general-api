<?php
class GAPI_customapi extends WP_REST_Controller {

      function __construct(){
            add_action( 'rest_api_init', array($this , 'register_routes') );
      }
      
      /**
      * Register the routes for the objects of the controller.
      */
      public function register_routes() {
            $version = '1';
            $namespace = 'api/v' . $version;
            register_rest_route( $namespace, '/get_app_data',  array(
                  'methods'             => WP_REST_Server::READABLE,
                  'callback'            => array( $this, 'get_app_data' ),
                  'permission_callback' => '',
                  'args'                => array()
            )
            );
            register_rest_route( $namespace, '/contact_form',  array(
                  'methods'             => WP_REST_Server::EDITABLE,
                  'callback'            => array( $this, 'contact_form1' ),
                  'permission_callback' => '',
                  'args'                => array(
                        'name' => array('required' => true),
                        'email' => array('required' => true),
                        'message' => array('required' => true),
                  )
            )
            );            
            register_rest_route( $namespace, '/forgotpassword',  array(
                  'methods'             => WP_REST_Server::EDITABLE,
                  'callback'            => array( $this, 'forget_password1' ),
                  'permission_callback' => '',
                  'args'                => array(
                        'email' => array('required' => true),
                  )
            )
            );
            register_rest_route( $namespace, '/code_verify',  array(
                  'methods'             => WP_REST_Server::EDITABLE,
                  'callback'            => array( $this, 'forget_password2' ),
                  'permission_callback' => '',
                  'args'                => array(
                        'code' => array('required' => true),
                  )
            )
            );
            register_rest_route( $namespace, '/change_password',  array(
                  'methods'             => WP_REST_Server::EDITABLE,
                  'callback'            => array( $this, 'forget_password3' ),
                  'permission_callback' => '',
                  'args'                => array(
                        'code' => array('required' => true),
                        'password' => array('required' => true),
                  )
            )
            );
            // Woocommerce Api
            register_rest_route( $namespace, '/get_shipping_method_by_country',  array(
                  'methods'             => WP_REST_Server::EDITABLE,
                  'callback'            => array( $this, 'get_shipping_method_by_country' ),
                  'permission_callback' => '',
                  'args'                => array(
                        'country' => array('required' => true),
                  )
            )
            );      

            register_rest_route( $namespace, '/get_all_contries',  array(
                  'methods'             => WP_REST_Server::READABLE,
                  'callback'            => array( $this, 'get_all_countries' ),
                  'permission_callback' => '',
                  'args'                => array()
            )
            ); 

            register_rest_route( $namespace, '/get_product_by_id/(?P<id>[\d]+)',  array(
                  'methods'             => WP_REST_Server::READABLE,
                  'callback'            => array( $this, 'get_product' ),
                  'permission_callback' => '',
                  'args'                => array()
            )
            );                                    



      }

      public function get_app_data($request){
            $getdata = get_option('GAPI_app_data');
            $apidata = (!empty($getdata)) ? $getdata: [];
            return new WP_REST_Response(json_decode($apidata), 200 );
      }

      public function forget_password1($request){
            if(isset($request['email'])) {
                  global $wpdb;
                  $email            = trim($request['email']);
                  $has_error  = FALSE;
                  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        return new WP_Error( 'error', __( 'Invalid Email Address Provided.'));
                        $has_error  = TRUE;
                  }
                  else if ( !email_exists( $email ) ) {
                        return new WP_Error( 'error', __( 'Email address does not exist.'));
                        $has_error  = TRUE;
                  }
                  if($has_error == FALSE) {
                        $user         = get_user_by('email', $email);
                        $key = generateRandomString(8);
                        $firstname    = $user->first_name;
                        $user_login = $user->user_login;
                        $email        = $user->user_email;
                        $rp_link      = $key;
                        $admin_email = get_option( 'admin_email' );
                        $site_name   = get_option( 'blogname' );
                        $wpdb->update( $wpdb->users, array( 'user_activation_key' => $key ), array( 'user_login' => $user_login ) );
                        if ($firstname == "") $firstname = "Customer";
                        $message       = '<div align="center">
                        <table width="600" cellspacing="5" cellpadding="5" border="0" style="color:#666 !important;background:none repeat scroll 0% 0% rgb(255,255,255);border-radius:3px;border:1px solid rgb(236,233,233)">
                        <tbody>
                        <tr><td style="text-align:center">
                        <!-- <img src="http://dev14.onlinetestingserver.com/tribe/wp-content/uploads/2018/11/logo.png" height="100px" align="center" class="CToWUd a6T" tabindex="0"> -->
                        <div class="a6S" dir="ltr" style="opacity: 0.01; left: 842.641px; top: 334px;"><div id=":14o" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" title="Download" role="button" tabindex="0" aria-label="Download attachment " data-tooltip-class="a1V"><div class="aSK J-J5-Ji aYr"></div></div></div></td></tr>
                        <tr>
                        <th style="background-color:#17aca3;color:white">Hello '.$firstname.'</th>
                        </tr>
                        <tr>
                        <td valign="top" style="text-align:left;color:#666;font-weight:600"><span style="color:#666;padding-bottom:10px;font-weight:300;display:block">We have received a forgot password request. Please use the code below to change your password: </span>
                        '.$rp_link.' <br>
                        </td>
                        </tr>
                        <tr><td><br>Regards,<br><b>Tribe 228.com</b></td></td></tr>
                        </tbody>
                        </table>
                        </div>
                        ';
                        $headers = array();
                        $headers = array('Content-Type: text/html; charset=UTF-8');
                        $subject = "Forget password request received";
                        add_filter( 'wp_mail_content_type', function( $content_type ) {return 'text/html';});
                        $headers[] = 'From: '.$site_name.' <'.$admin_email.'>'."\r\n";
                        wp_mail( $email, $subject, $message, $headers);
                        remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
                        return new WP_REST_Response( 'Forget password email sent successfully.', 200 );
                  }
            }
      }

      public function forget_password2($request){
            global $wpdb;
            $code             = trim($request["code"]);;
            if (empty($code)) {
                  return new WP_Error( 'error', __( 'Code is required!'));
            }
            $mylink = $wpdb->get_row( "SELECT * FROM $wpdb->users WHERE `user_activation_key` = '$code'" );
            if(!empty($mylink)){
                  return new WP_REST_Response( 'Code Verified', 200 );
            }else {
                  return new WP_Error( 'error', __( 'Invalid Code'));
            }
      }

      public function forget_password3($request){
            if(isset($request["code"]) && isset($request ["password"])) {
                  global $wpdb;
                  $code             = trim($request["code"]);
                  $password         = trim($request["password"]);
                  if (empty($code)) {
                        return new WP_Error( 'error', __( 'Code is required!'));
                  }
                  if (empty($password)) {
                        return new WP_Error( 'error', __( 'Password is required!'));
                  }
                  $mylink = $wpdb->get_row( "SELECT * FROM $wpdb->users WHERE `user_activation_key` = '$code'" );
                  if(!empty($mylink)){
                        $user_login = $mylink->user_login;
                        $wpdb->update( $wpdb->users, array( 'user_pass' => md5($password), 'user_activation_key' => '' ), array( 'user_login' => $user_login ) );
                        return new WP_REST_Response( 'Password Changed', 200 );
                  }else {
                        return new WP_Error( 'error', __( 'Invalid Code'));
                  }
            }
      }

      public function contact_form1($request){
            global $wpdb;
            $name            = trim($request['name']);
            $email            = trim($request['email']);
            $message            = trim($request['message']);
            $usersubject            = trim($request['subject']);
            $has_error  = FALSE;
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                  return new WP_Error( 'error', __( 'Invalid Email Address Provided.'));
                  $has_error  = TRUE;
            }
            else if (empty($name) ) {
                  return new WP_Error( 'error', __( 'Name is required!'));
                  $has_error  = TRUE;
            }elseif (empty($message) ) {
                  return new WP_Error( 'error', __( 'Message is required!'));
                  $has_error  = TRUE;
            }

            if ($has_error == FALSE) {
                  $headers = array('Content-Type: text/html; charset=UTF-8');
                  $to = 'charlestsmith888@gmail.com';
                  $subject = "Contact Form Data";
                  $message = '
                  <!doctype html>
                  <html>
                  <head>
                  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                  <title>2 Column</title>
                  <style>
                  body {
                  width: 100% !important;
                  margin: 0;
                  line-height: 1.4;
                  background-color: #F2F4F6;
                  color: #74787E;
                  -webkit-text-size-adjust: none;
                  }
                  .email-body{
                  width:600px;
                  margin: 0 auto;
                  }
                  .button {
                  background-color: #b70f1b !important;
                  padding: 10px 0px;
                  display: block;
                  color: #FFF !important;
                  text-align: center;
                  width: 100% !important;
                  text-decoration: none;
                  border-radius: 3px;
                  box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
                  -webkit-text-size-adjust: none;
                  }
                  /*Media Queries ------------------------------ */
                  @media only screen and (max-width: 600px) {
                  .email-body{
                  width: 100% !important;
                  }
                  }
                  </style>
                  </head>
                  <body>
                  <table width="600" border="0" cellspacing="0" cellpadding="0" class="email-body">
                  <tbody>
                  <tr>
                  <td bgcolor="#ffffff">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                  <tr>
                  <td width="10">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td width="10">&nbsp;</td>
                  </tr>
                  <tr>
                  <td width="10">&nbsp;</td>
                  <td align="center"><img src="'.site_url('/wp-content/uploads/2019/07/FASHION_LOVE_MARKET_Final_File.png').'"></td>
                  <td width="10">&nbsp;</td>
                  </tr>
                  <tr>
                  <td width="10">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td width="10">&nbsp;</td>
                  </tr>
                  </tbody>
                  </table>
                  </td>
                  </tr>
                  <tr>
                  <td bgcolor="#fff"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                  <tr>
                  <td width="10">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td width="10">&nbsp;</td>
                  </tr>
                  <tr>
                  <td width="10">&nbsp;</td>
                  <td><span style="font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; font-size: 20px; color: #000;"><strong>Hi admin,</strong></span></td>
                  <td width="10">&nbsp;</td>
                  </tr>
                  <tr>
                  <td width="10" style="font-size: 12px">&nbsp;</td>
                  <td style="font-size: 12px">&nbsp;</td>
                  <td width="10" style="font-size: 12px">&nbsp;</td>
                  </tr>
                  <tr>
                  <td width="10">&nbsp;</td>
                  <td><span style="font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; font-size: 16px; color: #000;">You have received an inquiry.</span></td>
                  <td width="10">&nbsp;</td>
                  </tr>
                  </tbody>
                  </table>
                  </td>
                  </tr>
                  <tr>
                  <td bgcolor="#ffffff">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                  <tr>
                  <td width="10">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td width="10">&nbsp;</td>
                  </tr>
                  <tr>
                  <td width="10">&nbsp;</td>
                  <td><table width="100%" border="1" cellspacing="0" cellpadding="5"  class="email-body">
                  <tbody>
                  <tr>
                  <td width="150" valign="top"><span style="font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; font-size: 14px; color: #000;">Name</span></td>
                  <td  valign="top"><span style="font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; font-size: 14px; color: #000;">'.$name.'</span></td>
                  </tr>
                  <tr>
                  <td width="150" valign="top"><span style="font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; font-size: 14px; color: #000;">Email</span></td>
                  <td  valign="top"><span style="font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; font-size: 14px; color: #000;">'.$email.'</span></td>
                  </tr>
                  <tr>
                  <td width="150" valign="top"><span style="font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; font-size: 14px; color: #000;">Subject</span></td>
                  <td  valign="top"><span style="font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; font-size: 14px; color: #000;">'.$usersubject.'</span></td>
                  </tr>
                  <tr>
                  <td width="150" valign="top"><span style="font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; font-size: 14px; color: #000;">Message</span></td>
                  <td  valign="top"><span style="font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; font-size: 14px; color: #000;">'.$message.'</span></td>
                  </tr>
                  </tbody>
                  </table></td>
                  <td width="10">&nbsp;</td>
                  </tr>
                  <tr>
                  <td width="10">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td width="10">&nbsp;</td>
                  </tr>
                  </tbody>
                  </table>
                  </td>
                  </tr>
                  <tr>
                  <td bgcolor="#ccc"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                  <tr>
                  <td width="10" style="font-size: 12px">&nbsp;</td>
                  <td style="font-size: 12px">&nbsp;</td>
                  <td width="10" style="font-size: 12px">&nbsp;</td>
                  </tr>
                  <tr>
                  <td width="10">&nbsp;</td>
                  <td align="center"><span style="font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; font-size:12px; color: #000;">
                  All rights reserved.<br>
                  <br>
                  </span></td>
                  <td width="10">&nbsp;</td>
                  </tr>
                  <tr>
                  <td width="10" style="font-size: 12px">&nbsp;</td>
                  <td style="font-size: 12px">&nbsp;</td>
                  <td width="10" style="font-size: 12px">&nbsp;</td>
                  </tr>
                  </tbody>
                  </table>
                  </td>
                  </tr>
                  <tr>
                  <td>&nbsp;</td>
                  </tr>
                  </tbody>
                  </table>
                  </body>
                  </html>
                  ';
                  wp_mail($to, $subject,$message, $headers);
                  return new WP_REST_Response( 'Your request has been submitted!', 200 );
            }
      }



      public function get_shipping_method_by_country($request){
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
           
            if (!empty($request['country'])) {
                        foreach ($methods as $key) {
                              if (in_array($request['country'], $key['countries'])) {
                                    $iiii = 0;
                                    $shippingmethods = '';
                                    foreach ($key['instances'] as $inskey => $insvalue) {

                                          $shippingmethods = $insvalue;           
                                          
                                    }

                              }
                        }
            }

            $instance_id     = $shippingmethods;
            $zone            = WC_Shipping_Zones::get_zone_by( 'instance_id', $instance_id );
            $returndata = array(
                  'zone_id'   => $zone->get_id(),
                  'zone_name' => $zone->get_zone_name(),
                  'methods'   => $zone->get_shipping_methods( false, 'json' ),
            );
            return new WP_REST_Response( $returndata, 200 );
      }



      public function get_all_countries(){
            $all_countries  = WC()->countries->get_countries();
            $data = []; $i = 0;
            foreach ($all_countries as $key => $value) {
                  $data[$i]['code'] = $key;
                  $data[$i]['value'] = $value;
                  $i++;
            }
          return new WP_REST_Response($data, 200 );      
      }



      public function get_product($request){
            if (condition) {
                return new WP_Error( 'Product doest exist exist!', __( 'message'));
            }
            $data = [];
            $product = wc_get_product($request['id']);
            $data['get_type'] = $product->get_type();
            $data['get_name'] = $product->get_name();
            $data['get_slug'] = $product->get_slug();
            $data['get_date_created'] = $product->get_date_created();
            $data['get_date_modified'] = $product->get_date_modified();
            $data['get_status'] = $product->get_status();
            $data['get_featured'] = $product->get_featured();
            $data['get_catalog_visibility'] = $product->get_catalog_visibility();
            $data['get_description'] = $product->get_description();
            $data['get_short_description'] = $product->get_short_description();
            $data['get_sku'] = $product->get_sku();
            $data['get_menu_order'] = $product->get_menu_order();
            $data['get_virtual'] = $product->get_virtual();

            if ($product->has_child()) {
                  $data['get_available_variations'] = $product->get_available_variations();
                  // Get Product Variations
                  $data['get_attributes'] = $product->get_attributes();
                  $data['get_default_attributes'] = $product->get_default_attributes();
            }
            // Get Product Prices
            $data['get_price'] = $product->get_price();
            $data['get_regular_price'] = $product->get_regular_price();
            $data['get_sale_price'] = $product->get_sale_price();
            $data['get_date_on_sale_from'] = $product->get_date_on_sale_from();
            $data['get_date_on_sale_to'] = $product->get_date_on_sale_to();
            $data['get_total_sales'] = $product->get_total_sales();
            // Get Product Tax, Shipping & Stock
            $data['get_tax_status'] = $product->get_tax_status();
            $data['get_tax_class'] = $product->get_tax_class();
            $data['get_manage_stock'] = $product->get_manage_stock();
            $data['get_stock_quantity'] = $product->get_stock_quantity();
            $data['get_stock_status'] = $product->get_stock_status();
            $data['get_backorders'] = $product->get_backorders();
            $data['get_sold_individually'] = $product->get_sold_individually();
            $data['get_purchase_note'] = $product->get_purchase_note();
            $data['get_shipping_class_id'] = $product->get_shipping_class_id();
            // Get Product Dimensions
            $data['get_weight'] = $product->get_weight();
            $data['get_length'] = $product->get_length();
            $data['get_width'] = $product->get_width();
            $data['get_height'] = $product->get_height();
            $data['get_dimensions'] = $product->get_dimensions();
            // Get Linked Products
            $data['get_upsell_ids'] = $product->get_upsell_ids();
            $data['get_cross_sell_ids'] = $product->get_cross_sell_ids();
            $data['get_parent_id'] = $product->get_parent_id();
            // Get Product Taxonomies
            $data['get_category_ids'] = $product->get_category_ids();
            $data['get_tag_ids'] = $product->get_tag_ids();
            // Get Product Downloads
            $data['get_downloads'] = $product->get_downloads();
            $data['get_download_expiry'] = $product->get_download_expiry();
            $data['get_downloadable'] = $product->get_downloadable();
            $data['get_download_limit'] = $product->get_download_limit();
            // Get Product Images
            $data['get_image_id'] = $product->get_image_id();
            // $data['get_image'] = $product->get_image();
            $data['get_gallery_image_ids'] = $product->get_gallery_image_ids();
            // Get Product Reviews
            $data['get_reviews_allowed'] = $product->get_reviews_allowed();
            $data['get_rating_counts'] = $product->get_rating_counts();
            $data['get_average_rating'] = $product->get_average_rating();
            $data['get_review_count'] = $product->get_review_count();
            return new WP_REST_Response($data, 200 );   
      }


}
$controler = new GAPI_customapi();
// return new WP_REST_Response( $parameters, 200 );
// return new WP_Error( 'code', __( 'message'));

