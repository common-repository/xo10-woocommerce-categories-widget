<?php


namespace xo10\woocommerce\categories\widgets\decorators;

use xo10\woocommerce\categories\widgets\decorators\Abstract_Decorator as Abstract_Decorator;


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


/**
 * Renders widget with basic styles that display positions of category images, names, and product count correctly.
 * 
 * 
 * Basically, it removes all bullet points and set the margins for multiple levels so its easier for
 * end users to visualize the expected results and customize the widget.
 *
 * @package xo10\woocommerce\categories\widget\decorators
 */
class Basic_Decorator extends Abstract_Decorator {
    
    protected $css_handle = 'xo10-wc-cats-css-basic';
    
    /**
     * Adds an opening HTML `<div>` to the root list wrapper.
     */
    public function tagListWrapperOpen() {
        return '<div class="xo10-basic-list-holder">' . parent::tagListWrapperOpen();
    }
    
    /**
     * Adds a closing HTML `<div>` to the root list wrapper.
     */
    public function tagListWrapperClose() {
        return parent::tagListWrapperClose() . '</div>';
    }

    /**
     * Links to a CSS file to style the widget.
     */
    protected function outputStyles() {
        parent::outputStyles();
        
        
        wp_register_style( $this->css_handle, XO10_WC_CATS_PLUGIN_URL . 'includes/decorators/assets/basic.css' );
        wp_enqueue_style( $this->css_handle );
    }
    
    /**
     * Outputs the CSS file.
     */
    public function furnish() {
        if( ! is_admin() ) {
          $this->outputStyles();
        }
    }


}
