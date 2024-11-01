<?php


namespace xo10\woocommerce\categories\walkers;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once( XO10_WC_CATS_PLUGIN_DIR . '../woocommerce/includes/walkers/class-product-cat-list-walker.php' );


/**
 * Custom walker to render the category elements according to our needs.
 * 
 * @package xo10\woocommerce\categories\walkers
 */
class Cat_List_Walker extends \WC_Product_Cat_List_Walker {

    
  public $decorator = null;
  

  /**
   * Adds thumbnails if required.
   *
   * @see WC_Product_Cat_List_Walker::start_el()
   */
  public function start_el( &$output, $cat, $depth = 0, $args = array(), $current_object_id = 0 ) {

    $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
    $image = wp_get_attachment_image_src( $thumbnail_id, 'detail', false );

    $imgsrc = '';
    if( false === $image ) {
      $imgsrc = wc_placeholder_img_src();
    } else {
      $imgsrc = $image[0];
    }

    $img_constraint_attrs = 'width="' . absint( $this->decorator->imgw ) . '" height="' . absint( $this->decorator->imgh ) . '"';
 
    $active_anchor_html_class = '';
    
    // open <li> tag
    $output .= '<li class="cat-item cat-item-' . $cat->term_id;

    if ( $args['current_category'] == $cat->term_id ) {
            $output .= ' current-cat' . ' ' . $this->decorator->activeCatCssClass();
            $active_anchor_html_class = ' class="' . $this->decorator->activeCatAnchorCssClass() . '"';
    }

    if ( $args['has_children'] && $args['hierarchical'] ) {
            $output .= ' cat-parent' . ' ' . $this->decorator->generalCatParentCssClass();
    }

    if ( $args['current_category_ancestors'] && $args['current_category'] && in_array( $cat->term_id, $args['current_category_ancestors'] ) ) {
            $output .= ' current-cat-parent' . ' ' .  $this->decorator->activeCatParentCssClass();
    }
   
    $output .=  '">';
        
    // post count on extreme left
    
    if ( defined( 'WC_VERSION' ) && version_compare( 'WC_VERSION', '2.3.0', '>=' ) ) {
        $catlink = get_term_link( (int) $cat->term_id, $this->tree_type ); // after WC version 2.3
    } else {
        $catlink = get_term_link( (int) $cat->term_id, 'product_cat' );    // before WC version 2.3
    }
    
    $cat_count_html = '<span class="count">' . $this->decorator->tagBracketLeft() . $cat->count . $this->decorator->tagBracketRight() . '</span>';
    
    $output .= $this->decorator->tagToggleIconOuterLeft();

    // show extreme-left counts; if any.
    if( $args['show_count'] && $this->decorator->countpos == 'extleft' ) {
      $output .= $cat_count_html;
    } 
    
    // Open <a> tag.
    $output .= '<a href="' . $catlink . '" ' . $active_anchor_html_class . '>';

    $output .= $this->decorator->tagToggleIconInnerLeft();
    
    // render category content i.e. image and/or text
    $cat_name = __( $cat->name, 'woocommerce' );
    //$cat_name_html = '<span class="cat-name">' . $cat_name . '</span>';
    $cat_name_html = '<span class="cat-name">' . $cat_name . '</span>';
    $cat_img_html  = '<img src="' . $imgsrc . '" title="' . $cat_name . '" alt="' . $cat_name . '" ' . $img_constraint_attrs . ' />';

    if( $this->decorator->imgtxt == 'text' ) {
      $output .= $cat_name_html;
    } elseif( $this->decorator->imgtxt == 'image' ) {
      $output .= $cat_img_html;
    } elseif( $this->decorator->imgtxt == 'tlir' ) {
      $output .= $cat_name_html . $cat_img_html;
    } else { // iltr i.e. image left, text right
      $output .= $cat_img_html . $cat_name_html;
    }

    $output .= $this->decorator->tagToggleIconInnerRight();
    
    // close <a> tag.
    $output .= '</a>';

    // show extreme-right counts; if any.
    if ( $args['show_count'] && $this->decorator->countpos == 'extright' ) {
            $output .= ' ' . $cat_count_html;
    }
    
    $output .= $this->decorator->tagToggleIconOuterRight();

   }

}
