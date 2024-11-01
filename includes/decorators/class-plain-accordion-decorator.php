<?php


namespace xo10\woocommerce\categories\widgets\decorators;

use xo10\woocommerce\categories\widgets\decorators\Accordion_Decorator as Accordion_Decorator;


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Renders widget as a "dynamic" accordion menu with a "plain" look.
 * 
 * @package xo10\woocommerce\categories\widget\decorators
 */
class Plain_Accordion_Decorator extends Accordion_Decorator {
    
    protected $js_handle = 'xo10-wc-cats-accordion-js-plain';
    protected $js_file = 'accordion.min.js';
    
    protected $css_handle = 'xo10-wc-cats-accordion-css-plain';
    protected $css_file = 'accordion-plain.css';
    
    /**
     * Adds an extra opening HTML `<div>` to the root list wrapper for our styling purposes.
     */
    public function tagListWrapperOpen() {
        return '<div class="xo10-accordion-list-holder">' . parent::tagListWrapperOpen();
    }
}
