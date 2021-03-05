<?php 

// Create fields
// Show columns
// Save/Update fields
// Update the Walker nav

function mate_menu_fields_list() {
	//note that menu-item- gets prepended to field names
	//i.e.: field-01 becomes menu-item-field-01
	//i.e.: icon-url becomes menu-item-icon-url
	return array(
		'mm-megamenu-posts' => esc_html__( 'Display recent post title from this category', 'mate' ),
		'mm-megamenu-subcat' => esc_html__( 'Display child category on dropdown', 'mate' ),
	);
}

// Setup fields
function mate_mega_menu_fields( $id, $item, $depth, $args ) {

	$fields = mate_menu_fields_list();

	if( isset( $item->object ) && $item->object == 'category' ){

		foreach ( $fields as $_key => $label ) :

			$key   = sprintf( 'menu-item-%s', $_key );
			$id    = sprintf( 'edit-%s-%s', $key, $item->ID );
			$name  = sprintf( '%s[%s]', $key, $item->ID );
			$value = get_post_meta( $item->ID, $key, true );
			$class = sprintf( 'field-%s', $_key );

			$css = '';
			if( $depth > 0 ){
				$css = esc_attr('display: none');
			} ?>

			<p style="<?php echo esc_attr( $css ); ?>" class="description description-wide <?php echo esc_attr( $class ) ?>">

				<label for="<?php echo esc_attr( $id ); ?>">
					<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="1" <?php echo ( $value == 1 ) ? 'checked="checked"' : ''; ?> /><?php echo esc_html( $label ); ?>
				</label>

			</p>

		<?php
		endforeach;

	}
}

add_action( 'wp_nav_menu_item_custom_fields', 'mate_mega_menu_fields', 10, 4 );

// Create Columns
function mate_megamenu_columns( $columns ) {

	$fields = mate_menu_fields_list();

	$columns = array_merge( $columns, $fields );

	return $columns;
}

add_filter( 'manage_nav-menus_columns', 'mate_megamenu_columns', 99 );

// Save fields
function mate_megamenu_save( $menu_id, $menu_item_db_id, $menu_item_args ) {
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}

	check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

	$fields = mate_menu_fields_list();

	foreach ( $fields as $_key => $label ) {
		$key = sprintf( 'menu-item-%s', $_key );

		// Sanitize.
		if ( ! empty( $_POST[ $key ][ $menu_item_db_id ] ) ) {
			// Do some checks here...
			$value = sanitize_text_field( wp_unslash( $_POST[ $key ][ $menu_item_db_id ] ) );
		} else {
			$value = null;
		}

		// Update.
		if ( ! is_null( $value ) ) {
			update_post_meta( $menu_item_db_id, $key, $value );
		} else {
			delete_post_meta( $menu_item_db_id, $key );
		}
	}
}

add_action( 'wp_update_nav_menu_item', 'mate_megamenu_save', 10, 3 );
