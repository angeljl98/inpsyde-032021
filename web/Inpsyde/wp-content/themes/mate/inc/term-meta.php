<?php
if( !function_exists('mate_custom_taxonomy_field') ):

	// Add term page
    function mate_custom_taxonomy_field(){

        // this will add the custom meta field to the add new term page

        $cat_color = array(
            'category-color-1' => esc_html__('Category Color 1', 'mate'),
            'category-color-2' => esc_html__('Category Color 2', 'mate'),
            'category-color-3' => esc_html__('Category Color 3', 'mate'),

        ); ?>

        <div class="form-field">

            <label for="term_meta[color_class_term_meta]"><?php esc_html_e('Color Class', 'mate'); ?></label>

            <select id="term_meta[color_class_term_meta]" name="term_meta[color_class_term_meta]">

                <?php foreach ($cat_color as $key => $value): ?>

                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>

                <?php endforeach; ?>

            </select>

            <p class="description"><?php esc_html_e('Select category color class. You can set appropriate categories color on "Categories" section of the theme customizer.', 'mate'); ?></p>

        </div>

        <div class="form-field">
            
            <label><?php esc_html_e('Feature Image', 'mate'); ?></label>

            <div class="twp-img-fields-wrap">
                <div class="attachment-media-view">
                    <div class="twp-img-fields-wrap">
                        <div class="twp-attachment-media-view">

                            <div class="twp-attachment-child twp-uploader">

                                <button type="button" class="twp-img-upload-button">
                                    <span class="dashicons dashicons-upload twp-icon twp-icon-large"></span>
                                </button>

                                <input class="upload-id" name="twp-term-featured-image" type="hidden"/>

                            </div>

                            <div class="twp-attachment-child twp-thumbnail-image">

                                <button type="button" class="twp-img-delete-button">
                                    <span class="dashicons dashicons-no-alt twp-icon"></span>
                                </button>

                                <div class="twp-img-container">
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    
    <?php
    }

endif;

add_action('category_add_form_fields', 'mate_custom_taxonomy_field', 10, 2);


if( !function_exists('mate_taxonomy_edit_meta_field') ):

	// Edit term page
    function mate_taxonomy_edit_meta_field($term){

        // put the term ID into a variable
        $t_id = $term->term_id;

        // retrieve the existing value(s) for this meta field. This returns an array
        $term_meta = get_option("mate_category_color_$t_id");
        $twp_term_image = get_term_meta( $term->term_id, 'twp-term-featured-image', true );

        ?>
        <tr class="form-field">

            <th scope="row" valign="top"><label for="term_meta[color_class_term_meta]"><?php esc_html_e('Color Class', 'mate'); ?></label></th>

            <td>
                <?php
                $cat_color = array(
                    'category-color-1' => __('Category Color 1', 'mate'),
                    'category-color-2' => __('Category Color 2', 'mate'),
                    'category-color-3' => __('Category Color 3', 'mate'),

                );
                ?>
                <select id="term_meta[color_class_term_meta]" name="term_meta[color_class_term_meta]">

                    <?php foreach( $cat_color as $key => $value ):

                    	$color_class_term_meta = sanitize_text_field( wp_unslash( isset( $term_meta['color_class_term_meta'] ) ? $term_meta['color_class_term_meta'] : '' ) ); ?>

                        <option value="<?php echo esc_attr( $key ); ?>"<?php selected( $color_class_term_meta, $key ); ?> >
                        	
                        	<?php echo esc_html( $value ); ?>

                        </option>

                    <?php endforeach; ?>

                </select>

                <p class="description"><?php esc_html_e('Select category color class. You can set appropriate categories color on "Categories" section of the theme customizer.', 'mate'); ?></p>

            </td>

        </tr>

        <tr>
            
            <th scope="row" valign="top"><label><?php esc_html_e('Feature Image', 'mate'); ?></label></th>

            <td>

                <div class="twp-img-fields-wrap">
                    <div class="attachment-media-view">
                        <div class="twp-img-fields-wrap">
                            <div class="twp-attachment-media-view">

                                <div class="twp-attachment-child twp-uploader">

                                    <button type="button" class="twp-img-upload-button">
                                        <span class="dashicons dashicons-upload twp-icon twp-icon-large"></span>
                                    </button>

                                    <input class="upload-id" name="twp-term-featured-image" type="hidden" value="<?php echo esc_url( $twp_term_image ); ?>"/>

                                </div>

                                <div class="twp-attachment-child twp-thumbnail-image">

                                    <button type="button" class="twp-img-delete-button <?php if( $twp_term_image ) { echo 'twp-img-show'; } ?>">
                                        <span class="dashicons dashicons-no-alt twp-icon"></span>
                                    </button>

                                    <div class="twp-img-container">

                                        <?php if( $twp_term_image ){ ?>

                                            <img src="<?php echo esc_url( $twp_term_image ); ?>" style="width:200px;height:auto;">

                                        <?php } ?>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </td>

        </tr>
        <?php
    }

endif;

add_action('category_edit_form_fields', 'mate_taxonomy_edit_meta_field', 10, 2);


if( !function_exists('save_taxonomy_color_class_meta') ):

	// Save extra taxonomy fields callback function.
    function save_taxonomy_color_class_meta( $term_id ){

        if( isset( $_POST['term_meta'] ) ){
            
            $t_id = $term_id;
            $term_meta = get_option( "mate_category_color_$t_id" );
            $cat_keys = array_keys( wp_unslash( $_POST['term_meta'] ) );
            
            foreach ($cat_keys as $key) {

                if( isset( $_POST['term_meta'][$key] ) ){

                    $term_meta[$key] = sanitize_text_field( wp_unslash( $_POST['term_meta'][$key] ) );

                }
            }

            // Save the option array.
            update_option("mate_category_color_$t_id", $term_meta);

        }

        if( isset( $_POST['twp-term-featured-image'] ) ){

            update_term_meta(
                $term_id,
                'twp-term-featured-image',
                esc_url_raw( wp_unslash( $_POST[ 'twp-term-featured-image' ] ) )
            );


        }



    }

endif;

add_action('edited_category', 'save_taxonomy_color_class_meta', 10, 2);
add_action('create_category', 'save_taxonomy_color_class_meta', 10, 2);