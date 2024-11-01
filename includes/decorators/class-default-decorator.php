<?php


namespace xo10\woocommerce\categories\widgets\decorators;

use xo10\woocommerce\categories\widgets\decorators\Abstract_Decorator as Abstract_Decorator;


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Renders widget according to whatever styles are available in the current theme.
 * 
 * @package xo10\woocommerce\categories\widget\decorators
 */
class Default_Decorator extends Abstract_Decorator {

    /**
     * Does nothing. 
     */
    public function furnish() {
    }

}
