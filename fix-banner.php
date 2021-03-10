<?php
/**
 * Plugin Name:     Fix Banner
 * Plugin URI:      https://github.com/fixonweb/fix-banner
 * Description:     Exibir anuncios interno no seu web site
 * Author:          FIXONWEB
 * Author URI:      https://fixonweb.com.br
 * Text Domain:     fix-banner
 * Domain Path:     /languages
 * Version:         0.1.2
 *
 * @package         Fix_Banner
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require 'plugin-update-checker.php';
$fix1608230887_url_update 	= 'https://github.com/fixonweb/fix-banner';
$fix1608230887_slug 		= 'fix-banner/fix-banner';
$fix1608230887_check 		= Puc_v4_Factory::buildUpdateChecker($fix1608230887_url_update,__FILE__,$fix1608230887_slug);


