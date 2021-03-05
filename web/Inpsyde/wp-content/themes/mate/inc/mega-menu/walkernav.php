<?php

namespace mate;

use Walker_Nav_Menu;

class Mate_Walkernav extends Walker_Nav_Menu
{

    public $megaMenuID;

    public $count;

    public function __construct()
    {
        $this->megaMenuID = 0;

        $this->count = 0;
    }

    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $submenu = ($depth > 0) ? ' sub-menu' : '';
        $output .= "\n$indent<ul class=\"dropdown-menu$submenu depth_$depth\" >\n";

        
    }

    public function end_lvl(&$output, $depth = 0, $args = array())
    {

        $output .= "</ul>";
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {   

        $hasMegaMenu = get_post_meta( $item->ID, 'menu-item-mm-megamenu-posts', true );
        $hasMegaMenu_subcat = get_post_meta( $item->ID, 'menu-item-mm-megamenu-subcat', true );

        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $li_attributes = '';
        $class_names = $value = '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;

        if ($this->megaMenuID != 0 && $this->megaMenuID != intval($item->menu_item_parent) && $depth == 0) {
            $this->megaMenuID = 0;
        }
        
        if( ( $hasMegaMenu || $hasMegaMenu_subcat ) && $depth == 0 ) {
            array_push($classes, 'menu-item-has-children');
            array_push($classes, 'twp-megamenu');
            $this->megaMenuID = $item->ID;
        }

        if( isset($args->has_children) ){
            $classes[] = ($args->has_children) ? 'dropdown' : '';
        }
        $classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
        $classes[] = 'menu-item-'.$item->ID;

        if( isset($args->has_children) ){
            if ($depth && $args->has_children) {
                $classes[] = 'dropdown-submenu';
            }
        }

        $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = ' class="'.esc_attr($class_names).'"';

        $id = apply_filters('nav_menu_item_id', 'menu-item-'.$item->ID, $item, $args);
        $id = strlen($id) ? ' id="'.esc_attr($id).'"' : '';

        $output .= $indent.'<li'.esc_attr( $id ).$value.$class_names.$li_attributes.'>';

        $attributes = !empty($item->attr_title) ? ' title="'.esc_attr($item->attr_title).'"' : '';
        $attributes .= !empty($item->target) ? ' target="'.esc_attr($item->target).'"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="'.esc_attr($item->xfn).'"' : '';
        $attributes .= !empty($item->url) ? ' href="'.esc_attr($item->url).'"' : '';

        if( isset($args->has_children) ){
            $attributes .= ($args->has_children) ? '' : '';
        }
        $item_output = '';
        if( isset($args->before) ){
            $item_output = $args->before;
        }
        $item_output .= '<a'.$attributes.'>';

    
        $link_before = '';
        $link_after = '';
        if( isset($args->link_before) ){
            $link_before = $args->link_before;
        }
        if( isset($args->link_after) ){
            $link_after = $args->link_after;
        }
        $item_output .= $link_before.apply_filters('the_title', $item->title, $item->ID).$link_after;

            // add support for menu item title
            if ( strlen($item->attr_title) > 2 ) {
                $item_output .= '<h3 class="tit">'.esc_html( $item->attr_title ).'</h3>';
            }
            $object_id = isset( $item->object_id ) ? $item->object_id : '';
            $child_cats = get_term_children($object_id,'category');
            $mm_posts_query = get_posts( array( 'numberposts' => 10,'category' => $object_id ) );
            
            if( isset( $args->has_children ) && !$args->has_children && ( ( $child_cats && $hasMegaMenu_subcat ) || ( $hasMegaMenu && $mm_posts_query ) && $depth == 0 ) ) {

                $item_output .= '<span class="icon">'.mate_the_theme_svg('chevron-down',true).'</span>';

            }
            // add support for menu item descriptions
            if (strlen($item->description) > 2) {
                $item_output .= '</a><span class="menu-description">'.esc_html( $item->description ).'</span>';
            }

        

        $has_children = '';
        if( isset( $args->has_children ) ){
            $has_children = $args->has_children;
        }

        $item_output .= ( ( $depth == 0 || 1 ) && $has_children ) ? '<span class="icon">'.mate_the_theme_svg('chevron-down',true).'</span></a>' : '</a>';

        if( isset( $args->after ) ){
            
            $item_output .= $args->after;

        }

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output)
    {
        if (!$element) {
            return;
        }

        $id_field = $this->db_fields['id'];

        //display this element
        if( is_array($args[0] ) ){

            $args[0]['has_children'] = !empty( $children_elements[$element->$id_field] );

        }elseif( is_object( $args[0] ) ) {

            $args[0]->has_children = !empty( $children_elements[$element->$id_field] );

        }

        $cb_args = array_merge( array(&$output, $element, $depth ), $args );
        call_user_func_array( array(&$this, 'start_el' ), $cb_args );

        $id = $element->$id_field;

        // descend only when the depth is right and there are childrens for this element
        if( ( $max_depth == 0 || $max_depth > $depth + 1 ) && isset( $children_elements[$id] ) ){

            foreach ($children_elements[ $id ] as $child) {

                if (!isset($newlevel)) {

                    $newlevel = true;
                    //start the child delimiter
                    $cb_args = array_merge(array(&$output, $depth), $args);
                    call_user_func_array(array(&$this, 'start_lvl'), $cb_args);

                }

                $this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );

            }

            unset( $children_elements[ $id ] );

        }

        if (isset($newlevel) && $newlevel) {
            //end the child delimiter
          $cb_args = array_merge(array(&$output, $depth), $args);
            call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
        }

        //end this element
        $cb_args = array_merge(array(&$output, $element, $depth), $args);
        call_user_func_array(array(&$this, 'end_el'), $cb_args);
    }

    public function end_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

        $hasMegaMenu = get_post_meta( $item->ID, 'menu-item-mm-megamenu-posts', true );
        $hasMegaMenu_subcat = get_post_meta( $item->ID, 'menu-item-mm-megamenu-subcat', true );
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $li_attributes = '';
        $class_names = $value = '';
        
        $object_id = isset( $item->object_id ) ? $item->object_id : '';

        if( $object_id && isset( $item->object ) && $item->object == 'category' && $depth == 0 && ( $hasMegaMenu || $hasMegaMenu_subcat ) ){

            $child_cats = get_term_children($object_id,'category');
            $mm_posts_query = get_posts( array( 'numberposts' => 10,'category' => $object_id ) );
            
            if( ( $child_cats && $hasMegaMenu_subcat ) || ( $hasMegaMenu && $mm_posts_query ) ){

                $output .= '<ul class="dropdown-menu theme-megamenu-content">';

                    if( $hasMegaMenu_subcat && $child_cats ){

                        $output .= '<li class="megamenu-content-left">';
                            $output .= '<div class="">';
                                    foreach( $child_cats as $child_cat ){

                                        $cat_obj = get_category( $child_cat );
                                        $cat_link = get_category_link( $child_cat );

                                        $output .= '<div>';
                                            $output .= '<a href="'.esc_url( $cat_link ).'">';
                                                $output .= esc_html( $cat_obj->cat_name );
                                            $output .= '</a>';
                                        $output .= '</div>';

                                    }
                            $output .= '</div>';
                        $output .= '</li>';

                    }

                    if( $hasMegaMenu && $mm_posts_query ){

                        $output .= '<li class="megamenu-content-right">';
                            $output .= '<div>';

                                foreach( $mm_posts_query as $mm_posts_content ){

                                    $url = '';
                                    if( $mm_posts_content->ID ){

                                        $url = get_the_permalink($mm_posts_content->ID);

                                    }

                                    $output .= '<div>';
                                        $output .= '<a href="'.esc_url( $url ).'">';
                                            $output .= esc_html( $mm_posts_content->post_title );
                                        $output .= '</a>';
                                    $output .= '</div>';

                                }

                            $output .= '</div>';
                        $output .= '</li>';

                    }

                $output .= '</ul>';

            }
        }

        $classes = empty($item->classes) ? array() : (array) $item->classes;

        if ($this->megaMenuID != 0 && $this->megaMenuID != intval($item->menu_item_parent) && $depth == 0) {
            $this->megaMenuID = 0;
        }

        $output .= "</li>\n";
    }
    
}
