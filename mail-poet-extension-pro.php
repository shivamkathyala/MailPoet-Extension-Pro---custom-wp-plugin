<?php
/*
Plugin Name: MailPoet Extension Pro
Description: Streamline your email marketing efforts with our MailPoet extension plugin for WordPress. Seamlessly integrate subscriber management, collect data with a customizable form shortcode, and securely insert information into MailPoet using the API. Effortlessly manage subscribers directly within your WordPress dashboard.
Version: 1.0.0
Author: Shivam-Thakur
*/

if ( !defined( 'ABSPATH' ) ) exit;

//check if mailpoet plugin is active or not
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

//creat table in database on activation
function create_table(){
    //access database
    global $wpdb;
    $tableName = $wpdb->prefix."all-subscriber";

    $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (
        SubID int NOT NULL AUTO_INCREMENT,
        FirstName varchar(255) NOT NULL,
        LastName varchar(255) NOT NULL,
        Email varchar(255) NOT NULL,
        SubAddress varchar(255) NOT NULL,
        Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (SubID)
    );";
    require_once(ABSPATH.'/wp-admin/includes/upgrade.php');
    
    $result = dbDelta($sql);
    if ($result === false){
    // Handle the error
    //error_log("Error creating database table: " . $wpdb->last_error);
    }

}
register_activation_hook(__FILE__,'create_table');

function menu(){
    $page_title = "Main-Page";
    $menu_title = "MailPoet Extension Pro";
    $capability = "manage_options";
    $menu_slug = "home_page";
    $function = "home_page";
    $icon_url = plugin_dir_url(__FILE__).'src/admin/image/mail-poet-extension-pro-logo.png';
    $position = 5;
    //menu page 
    add_menu_page($page_title, $menu_title, $capability, $menu_slug , $function, $icon_url, $position);
}
add_action('admin_menu', 'menu');


// Admin side custom CSS
function my_custom_admin_styles() {
    //jquery
    wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js', array(), null, true);
    //custom js
    wp_enqueue_script('custom-plugin-script', plugin_dir_url(__FILE__) . 'src/admin/js/script.js', array('jquery'), null, true);
    //dataTables CSS
    wp_enqueue_style('dataTables', 'https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css');
    
    //dataTables JavaScript
    wp_enqueue_script('dataTables', 'https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js', array('jquery'), null, true);

    wp_enqueue_style('custom-plugin-table-styles', plugin_dir_url(__FILE__) . 'src/admin/css/table-style.css', array(), '1.0.0');
  
}
add_action('admin_enqueue_scripts', 'my_custom_admin_styles');

function custom_my_css() {
    wp_enqueue_style('custom-plugin-styles', plugin_dir_url(__FILE__) . 'src/admin/css/style.css', array(), '1.0.0');

    wp_enqueue_style('custom-css', plugin_dir_url(__FILE__) . 'src/admin/css/custom_css.css', array(), '1.0.0'); 
}
add_action('wp_enqueue_scripts', 'custom_my_css');


 //home page call if the plugin is active
 function home_page(){
    $product_page_url = __DIR__.'/src/admin/Extension.php';
    require_once($product_page_url);
}

//form page shortcode
function mail_poet_shortcode() {
$popup = __DIR__.'/src/admin/Addsubscriber.php';
require_once($popup);
}
add_shortcode('mailpoet_extension_pro','mail_poet_shortcode');
