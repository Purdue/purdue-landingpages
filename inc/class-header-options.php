<?php

if ( !defined('ABSPATH') ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

class LndngPg_Headers {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' )         );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {

		add_meta_box(
			'lndngpg_header',
			__( 'Header Option', 'purdue' ),
			array( $this, 'render_lndngpg_header' ),
			'lndngpg',
			'side',
			'default'
		);

	}

	public function render_lndngpg_header( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'lndngpgnonce_action', 'lndngpgnonce' );

		// Retrieve an existing value from the database.
		$lndngpg_logo = get_post_meta( $post->ID, 'lndngpg_logo', true );

		// Set default values.
		if( empty( $lndngpg_logo ) ) $lndngpg_logo = '';

		// Form fields.
		echo '<table class="form-table">';

		echo '	<tr>';
		echo '		<th><label for="lndngpg_logo" class="lndngpg_logo_label">' . __( 'Logo', 'purdue' ) . '</label></th>';
		echo '		<td>';
		echo '			<select id="lndngpg_logo" name="lndngpg_logo" class="lndngpg_logo_field">';
		echo '			<option value="dark" ' . selected( $lndngpg_logo, 'dark', false ) . '> ' . __( 'Black Text', 'purdue' ) . '</option>';
		echo '			<option value="light" ' . selected( $lndngpg_logo, 'light', false ) . '> ' . __( 'White Text', 'purdue' ) . '</option>';
		echo '			<option value="none" ' . selected( $lndngpg_logo, 'none', false ) . '> ' . __( 'Hidden Logo', 'purdue' ) . '</option>';
		echo '			</select>';
		echo '		</td>';
		echo '	</tr>';

		echo '</table>';

	}

	public function save_metabox( $post_id, $post ) {

		// Add nonce for security and authentication.
		$nonce_name   = isset( $_POST['lndngpgnonce'] ) ? $_POST['lndngpgnonce'] : '';
		$nonce_action = 'lndngpgnonce_action';

		// Check if a nonce is set.
		if ( ! isset( $nonce_name ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Sanitize user input.
		$new_lndngpg_logo = isset( $_POST[ 'lndngpg_logo' ] ) ? $_POST[ 'lndngpg_logo' ] : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, 'lndngpg_logo', $new_lndngpg_logo );

	}

}

new LndngPg_Headers;