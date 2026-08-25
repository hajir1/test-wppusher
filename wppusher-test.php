<?php
/**
 * Plugin Name: WP Pusher Test
 * Description: Kumpulan shortcode untuk web fakultas UM
 * Version: 1.3
 * Author: Hajir
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load semua file shortcode dari tiap folder
require_once plugin_dir_path( __FILE__ ) . 'footer-um/footer-um.php';
require_once plugin_dir_path( __FILE__ ) . 'menu/menu.php';
// require_once plugin_dir_path( __FILE__ ) . 'menu/Menu.php';     (kalau nanti ada)
// require_once plugin_dir_path( __FILE__ ) . 'navbar/Navbar.php'; (kalau nanti ada)