<?php

/*
Plugin Name: Purdue Landing Page Post Types
Description: Establishes a Landing Page ONLY WordPress site!
Author: Purdue Marketing and Communications
Author URI: https://marcom.purdue.edu/
Version: 1.0.0
 
License:     GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

------------------------------------------------------------------------

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

if ( !defined('ABSPATH') ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}



if ( ! class_exists( 'PurdueLandingPages' ) ) :

    class PurdueLandingPages {
        private $_settings = array();

        public function __construct() {
            // self::includes();
            $this->hooks();
        }

        private static function includes() {
            
        }

        private function hooks() {
            add_action( 'init', array($this, 'lp_post_type'), 0 );
            add_action( 'admin_menu', array($this, 'remove_default_post_type' ));
            add_action( 'admin_bar_menu', array($this, 'remove_default_post_type_menu_bar'), 999 );
            add_action( 'wp_dashboard_setup', array( $this, 'remove_draft_widget'), 999 );
        }

        public function lp_post_type() {

                $labels = array(
                    'name'                  => _x( 'Pages', 'Post Type General Name', 'purdue' ),
                    'singular_name'         => _x( 'Page', 'Post Type Singular Name', 'purdue' ),
                    'menu_name'             => __( 'Page', 'purdue' ),
                    'name_admin_bar'        => __( 'Page', 'purdue' ),
                    'archives'              => __( 'Item Archives', 'purdue' ),
                    'attributes'            => __( 'Item Attributes', 'purdue' ),
                    'parent_item_colon'     => __( 'Parent Item:', 'purdue' ),
                    'all_items'             => __( 'All Items', 'purdue' ),
                    'add_new_item'          => __( 'Add New Item', 'purdue' ),
                    'add_new'               => __( 'Add New', 'purdue' ),
                    'new_item'              => __( 'New Item', 'purdue' ),
                    'edit_item'             => __( 'Edit Item', 'purdue' ),
                    'update_item'           => __( 'Update Item', 'purdue' ),
                    'view_item'             => __( 'View Item', 'purdue' ),
                    'view_items'            => __( 'View Items', 'purdue' ),
                    'search_items'          => __( 'Search Item', 'purdue' ),
                    'not_found'             => __( 'Not found', 'purdue' ),
                    'not_found_in_trash'    => __( 'Not found in Trash', 'purdue' ),
                    'featured_image'        => __( 'Featured Image', 'purdue' ),
                    'set_featured_image'    => __( 'Set featured image', 'purdue' ),
                    'remove_featured_image' => __( 'Remove featured image', 'purdue' ),
                    'use_featured_image'    => __( 'Use as featured image', 'purdue' ),
                    'insert_into_item'      => __( 'Insert into item', 'purdue' ),
                    'uploaded_to_this_item' => __( 'Uploaded to this item', 'purdue' ),
                    'items_list'            => __( 'Items list', 'purdue' ),
                    'items_list_navigation' => __( 'Items list navigation', 'purdue' ),
                    'filter_items_list'     => __( 'Filter items list', 'purdue' ),
                );
                $rewrite = array(
                    'slug'                  => '/',
                    'with_front'            => false,
                    'pages'                 => false,
                    'feeds'                 => false,
                );
                $args = array(
                    'label'                 => __( 'Page', 'purdue' ),
                    'description'           => __( 'Custom page type that allows for editing everything except the basic footer', 'purdue' ),
                    'labels'                => $labels,
                    'supports'              => array( 'title', 'editor', 'revisions', 'custom-fields', 'page-attributes', 'post-formats' ),
                    'hierarchical'          => true,
                    'public'                => true,
                    'show_ui'               => true,
                    'show_in_menu'          => true,
                    'menu_position'         => 5,
                    'menu_icon'             => 'dashicons-admin-page',
                    'show_in_admin_bar'     => true,
                    'show_in_nav_menus'     => false,
                    'can_export'            => true,
                    'has_archive'           => false,
                    'exclude_from_search'   => true,
                    'publicly_queryable'    => true,
                    'rewrite'               => $rewrite,
                    'capability_type'       => 'page',
                    'show_in_rest'          => true,
                );
                register_post_type( 'lndngpg', $args );
            
        }

        
        // ************* Remove default Posts type since no blog *************
        public function remove_default_post_type() {
            remove_menu_page( 'edit.php' );
            remove_menu_page( 'edit.php?post_type=page' );
        }

        // Remove +New post in top Admin Menu Bar
        public function remove_default_post_type_menu_bar( $wp_admin_bar ) {
            $wp_admin_bar->remove_node( 'new-post' );
            $wp_admin_bar->remove_node( 'new-page' );
        }

        // Remove Quick Draft Dashboard Widget
        public function remove_draft_widget(){
            remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        }        

    }

    $PurdueLandingPages = new PurdueLandingPages();

    function PurdueLandingPages_activation() {
        // Clear the permalinks after the post type has been registered.
        flush_rewrite_rules(); 
    }

    function PurdueLandingPages_deactivation() {
        unregister_post_type( 'lndngpg' ); 

        // Clear the permalinks after the post type has been registered.
        flush_rewrite_rules(); 
    }

    register_activation_hook(   __FILE__, 'PurdueLandingPages_activation' );
    register_deactivation_hook( __FILE__, 'PurdueLandingPages_deactivation' );

endif;
