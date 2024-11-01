<?php


namespace xo10\woocommerce\categories\widgets\decorators;

use xo10\woocommerce\categories\widgets\decorators\Default_Decorator as Default_Decorator;
use xo10\woocommerce\categories\widgets\decorators\Basic_Decorator as Basic_Decorator;
use xo10\woocommerce\categories\widgets\decorators\Accordion_Decorator as Accordion_Decorator;
use xo10\woocommerce\categories\widgets\decorators\Plain_Accordion_Decorator as Plain_Accordion_Decorator;
use xo10\woocommerce\categories\widgets\decorators\Sexy_Accordion_Decorator as Sexy_Accordion_Decorator;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}




/**
 * Selects and instantiates an appropriate decorator for use.
 * 
 * 
 * > "A rose by any other name would smell as sweet." - *William Shakespeare*
 * 
 * @package xo10\woocommerce\categories\widget\decorators
 */
class Decorator_Factory {

  /**
   * Private constructor.
   */
  private function __construct() {
  }

  /**
   * Creates and returns a Decorator class each time it is called.
   * 
   * @param string $look The look that the user has selected in the widget's "Look - Effects" field.
   * 
   * @return Decorator The decorator that should be used to help render the look of the widget.
   */
  public static function getDecorator( $look ) {
    $decorator = null;

    switch ( $look ) {
        
        case 'basic':
          $decorator = new Basic_Decorator();
          break; 
      
      
        case 'plain-accordion':
          $decorator = new Plain_Accordion_Decorator();
          break;
      
        case 'sexy-accordion':
          $decorator = new Sexy_Accordion_Decorator();
          break;
      
        
      
      
        default: // 'raw' also
          $decorator = new Default_Decorator();
          break;
    }
    
    return $decorator;
  }

}
