<?php
/**
 * Plugin Name:     Fix Banner
 * Plugin URI:      https://github.com/fixonweb/fix-banner
 * Description:     Exibir anuncios interno no seu web site
 * Author:          FIXONWEB
 * Author URI:      https://fixonweb.com.br
 * Text Domain:     fix-banner
 * Domain Path:     /languages
 * Version:         0.1.4
 * Fix ID:          161539
 * @package         Fix_Banner
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require 'plugin-update-checker.php';
$fix1608230887_url_update 	= 'https://github.com/fixonweb/fix-banner';
$fix1608230887_slug 		= 'fix-banner/fix-banner';
$fix1608230887_check 		= Puc_v4_Factory::buildUpdateChecker($fix1608230887_url_update,__FILE__,$fix1608230887_slug);


function fix161539_cpts_anuncios() {

	$labels = array(
		"name" => "Banners",
		"singular_name" => "Banner",
	);

	$args = array(
		"label" => "Banner",
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"delete_with_user" => false,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "banner", "with_front" => true ),
		"query_var" => true,
		"supports" => array( "title", "thumbnail" ),
	);

	register_post_type( "banner", $args );
}
add_action( 'init', 'fix161539_cpts_anuncios' );

function fix_161530_tax_set_local() {
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name'              => _x( 'Banner-Points', 'taxonomy general name', 'textdomain' ),
        'singular_name'     => _x( 'Banner-Point', 'taxonomy singular name', 'textdomain' ),
        'search_items'      => __( 'Search Banner-Points', 'textdomain' ),
        'all_items'         => __( 'All Banner-Points', 'textdomain' ),
        'parent_item'       => __( 'Parent Banner-Point', 'textdomain' ),
        'parent_item_colon' => __( 'Parent Banner-Point:', 'textdomain' ),
        'edit_item'         => __( 'Edit Banner-Point', 'textdomain' ),
        'update_item'       => __( 'Update Banner-Point', 'textdomain' ),
        'add_new_item'      => __( 'Add New Banner-Point', 'textdomain' ),
        'new_item_name'     => __( 'New Banner-Point Name', 'textdomain' ),
        'menu_name'         => __( 'Banner-Point', 'textdomain' ),
    );
 
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'banner-point' ),
    );
 
    register_taxonomy( 'banner-point', array( 'banner' ), $args );
 
    unset( $args );
    unset( $labels );
 
}
add_action( 'init', 'fix_161530_tax_set_local', 0 );

add_shortcode("fix-banner", "fix161539_anuncio");
add_shortcode("fix161539_anuncio", "fix161539_anuncio");
function fix161539_anuncio($atts, $content = null){
	extract(shortcode_atts(array(
		"set_local" => 'naoinformado',
	), $atts));

	// return $set_local;
	$anuncio_args = array(
	    'post_type'  => 'banner',
	    'numberposts' => 1,
	    'orderby'        => 'rand',
	    // 'meta_query' => array(
	    //     array(
	    //         'key'   => 'fix160942_access',
	    //         'value' => $acesso,
	    //     )
	    // )
		'tax_query' => array(
			array(
				'taxonomy' => 'banner-point',
				'field'    => 'slug',
				// 'terms'    => array( 'jazz', 'improv' )
				'terms'    => array( $set_local )
			)
		)
	);
	$anuncios = get_posts( $anuncio_args );
	// echo '<pre>';
	// print_r($anuncios);
	// echo '</pre>';
	// return '';


	if($anuncios){

		$fix161539_exibicoes = get_post_meta( $anuncios[0]->ID, 'fix161539_exibicoes', true );
		if(!$fix161539_exibicoes) $fix161539_exibicoes = 1;
		update_post_meta( $anuncios[0]->ID, 'fix161539_exibicoes', $fix161539_exibicoes + 1 );

		$fix161539_data_recente = get_post_meta( $anuncios[0]->ID, 'fix161539_data_recente', true );
		if(!$fix161539_data_recente) $fix161539_data_recente = date('d/m/Y H:i:s');
		update_post_meta( $anuncios[0]->ID, 'fix161539_data_recente', $fix161539_data_recente );

		$args = array(
			'post_type' => 'attachment',
			// 'post_type' => 'any',
			'posts_per_page' => -1,
			'post_status'    => 'any',
			'post_parent' => $anuncios[0]->ID,
			// 'post_parent' => $anuncios[0]->ID,

			//Tamanhos default de imagens do wordpress thumbnail,medium, large, full
			'size'        => 'thumbnail'
		);
		$attachments = get_posts($args);

	// echo '<pre>';
	// print_r($attachments);
	// echo '</pre>';
	// return '';

		?>
		<div style="text-align: center;">
			<img src="<?php echo $attachments[0]->guid; ?>" alt="">	
		</div>
		<div style="display:none;text-align: center;">
			estartisticas: <?php echo $fix161539_exibicoes ?> | <?php echo $fix161539_data_recente ?>
		</div>
		<?php

	}
}
