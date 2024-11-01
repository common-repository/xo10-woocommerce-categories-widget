<?php


namespace xo10\woocommerce\categories\widgets\decorators;

use xo10\woocommerce\categories\widgets\decorators\Abstract_Decorator as Abstract_Decorator;


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Parses all accordion options and handles JS/CSS output.
 * 
 * @package xo10\woocommerce\categories\widget\decorators
 * 
 * @todo add persistence when JS library supports it.
 * 
 * @see http://www.jqueryscript.net/menu/Vertical-Accordion-Menu-Plugin-For-jQuery-Nav-Accordion.html
 */
abstract class Accordion_Decorator extends Abstract_Decorator {
    protected $js_handle = 'xo10-wc-cats-accordion-js';
    protected $js_file = 'accordion.min.js';
    
    protected $css_handle = 'xo10-wc-cats-accordion-css';
    protected $css_file = 'accordion-plain.css';
    

    
    /**
     * Makes sure only valid effects options are accepted.
     */
    protected function digestEffectsOpts() {
        
        parent::digestEffectsOpts();
                
        // All options (accurate as of 16-Dec-2015) for the accordion can be found at:
        // - https://github.com/corycaywood/navAccordion
        //
        // NOTE: Only a few options are allowed to be changed for the moment until the widget is stable.
        // 
        $whitelist = array(
            'expandButtonText'   => '<span class="dashicons dashicons-plus"></span>',
            'collapseButtonText' => '<span class="dashicons dashicons-minus"></span>',
            'buttonWidth'        => '20%', // users only permitted to enter in PIXELS due to security concerns.
            'buttonPosition'     => 'right',
            'multipleLevels'     => true,
            'selectedClass'      => $this->activeCatCssClass(),
            'selectedExpand'     => true,
            'headersOnly'        => false,
            'headersOnlyCheck'   => false,
            'slideSpeed'         => '400', // "fast" or "slow" or can be in numbers e.g. "10", "50000"
            //'parentElement'      => 'li',
            //'childElement'       => 'ul',
            'delayLink'          => false,
            'delayAmount'        => 'fast', // no effect unless delayLink is set.   
        );
        

        if ( empty ( $this->_actualEffects ) ) {
            $this->_actualEffects = $whitelist;
            return;
        }
        
        // only user values that correspond to whitelist are accepted
        foreach( $whitelist as $k=>$v ) {
            if( array_key_exists( strtolower( $k ), $this->_actualEffects ) ) {
                $whitelist[$k] = $this->_actualEffects[strtolower( $k )];
            }
        }
        
        
        // accordion icons
        $whitelist = $this->parseEffectIcon( $whitelist, 'expandButtonText', 'dashicons-plus' );
        $whitelist = $this->parseEffectIcon( $whitelist, 'collapseButtonText', 'dashicons-minus' );
        
        // button position
        if( strcasecmp( $whitelist['buttonPosition'], 'left' )  == 0 || 
            strcasecmp( $whitelist['buttonPosition'], 'right' ) == 0 ) {
            $whitelist['buttonPosition'] = strtolower( $whitelist['buttonPosition'] );
            $this->_toggleBtnPos = $whitelist['buttonPosition'];
        } else {
            unset( $whitelist['buttonPosition'] ); // no point keeping it.         
        }
        
        // parse general options
        $whitelist = $this->parseEffectBoolean( $whitelist, 'multipleLevels', true );
        $whitelist = $this->parseEffectBoolean( $whitelist, 'selectedExpand', true );
        $whitelist = $this->parseEffectBoolean( $whitelist, 'headersOnly', false );
        $whitelist = $this->parseEffectBoolean( $whitelist, 'headersOnlyCheck', false );
        $whitelist = $this->parseEffectSpeed( $whitelist, 'slideSpeed', 400 );
        $whitelist = $this->parseEffectBoolean( $whitelist, 'delayLink', false );
        $whitelist = $this->parseEffectSpeed( $whitelist, 'delayAmount', 400 );
        
        // replace original array
        $this->_actualEffects = $whitelist;
    }

    /**
     * Parses the user-specified option for icons useable by JS. 
     * No checking of valid icons as WordPress may add more icons as time goes by.
     * 
     * @param array $whitelist The options that will be used to initialize the JS.
     * @param string $option The key of the $whitelist array.
     * @param string $default The default value.
     * 
     * @return array The $whitelist which may be altered.
     */
    private function parseEffectIcon( $whitelist, $option, $default ) {
        
        // Check for dash icons. 
        if( substr( $whitelist[$option], 0, 10 ) === 'dashicons-' ) { // case-sensitive check in case icon stylesheet requires it.
            $whitelist[$option] = '<span class="dashicons ' . $whitelist[$option] . '"></span>';
        } else {
            $whitelist[$option] = '<span class="dashicons ' . $default . '"></span>';   
        }

        
        return $whitelist;
    }
    
    /**
     * Parses the user-specified option to a boolean form useable by JS.
     * 
     * @param array $whitelist The options that will be used to initialize the JS.
     * @param string $option The key of the $whitelist array.
     * @param boolean $default The default value.
     * 
     * @return array The $whitelist which may be altered.
     */
    private function parseEffectBoolean( $whitelist, $option, $default ) {
        
        if( strcasecmp( $whitelist[$option], 'true' ) == 0 || 
            strcasecmp( $whitelist[$option], 'false' ) == 0 ) {
            $whitelist[$option] = filter_var( strtolower( $whitelist[$option]) , FILTER_VALIDATE_BOOLEAN );
        } else {
            $whitelist[$option] = $default; 
        }
        
        return $whitelist;
    }
    
    /**
     * Parses the user-specified option to a speed understandable by JS.
     * 
     * @param array $whitelist The options that will be used to initialize the JS.
     * @param string $option The key of the $whitelist array.
     * @param int|string $default The default value.
     * 
     * @return array The $whitelist which may be altered.
     */
    private function parseEffectSpeed( $whitelist, $option, $default ) {
        
        // NOTE: jQuery speeds "fast" = 200, "slow" = 600, default = 400
        
        if( strcasecmp( $whitelist[$option], 'fast' ) == 0 || 
            strcasecmp( $whitelist[$option], 'slow' ) == 0 ) {
            $whitelist[$option] = strtolower( $whitelist[$option] );
        } elseif ( is_numeric( $whitelist[$option] ) ) {
            $speed = absint( $whitelist[$option] );
            if( $speed < 10 || $speed > 50000 ) {
                $whitelist[$option] = $default;
            } else {
                $whitelist[$option] = $speed;
            }
        } else {
            $whitelist[$option] = $default; 
        }
        
        return $whitelist;
    }
    
    /**
     * Forces a HTML ID on the list so that multiple instances of this widget on 
     * the same page still works independently.
     * 
     * @override
     */
    protected function digestHoldingListHtmlID() {
        parent::digestHoldingListHtmlID();
        
        if ( '' === $this->_ulHtmlID ) {
            $this->_ulHtmlID = 'xo10-wc-' . $this->widgetID . '-' . $this->widgetID;
        }
    }
    
    /**
     * Appends the position of the toggle button on the menu to help designers 
     * style the widget.
     */
    protected function digestHoldingListHtmlClasses() {
        parent::digestHoldingListHtmlClasses();
        
        $this->_ulHtmlClasses = str_replace( "togglebtn-off", " togglebtn-" . $this->_toggleBtnPos, $this->_ulHtmlClasses );
    }
    
    /**
     * Adds an extra closing HTML `</div>` to the root list wrapper.
     */
    public function tagListWrapperClose() {
        return parent::tagListWrapperClose() . '</div>';
    }

    /**
     * Assigns "selected" to the active category's HTML "class" attribute".
     */
    public function activeCatCssClass() {
        return 'selected'; // already used in our Flat styling .scss file
    }

    /**
     * Assigns "selected" to the active parent category's HTML "class" attribute".
     */
    public function activeCatParentCssClass() {
        return $this->activeCatCssClass();
    }

    /**
     * Enqueues default WP dashicons and custom CSS for the widget.
     */
    protected function outputStyles() {
        parent::outputStyles();
        
        wp_enqueue_style( 'dashicons' );

        
        
        wp_register_style( $this->css_handle, XO10_WC_CATS_PLUGIN_URL . 'includes/decorators/assets/' . $this->css_file );   
        
        wp_enqueue_style( $this->css_handle );

    }
    
    /**
     * Initializes and activates the accordion display.
     */
    protected function outputScripts() {
        parent::outputScripts();

        
        
        wp_register_script( 
            $this->js_handle, 
            XO10_WC_CATS_PLUGIN_URL . 'includes/decorators/assets/' . $this->js_file,
            array('jquery') 
        );

        wp_enqueue_script( $this->js_handle );

        
        wc_enqueue_js("
                jQuery( '#" . $this->_ulHtmlID . "' ).navAccordion(" . 
                    json_encode( $this->_actualEffects, JSON_PRETTY_PRINT ) . 
                ");
        ");

    }
    
    /**
     * Outputs the CSS and JS required for the "dynamic" accordion menu display.
     */
    public function furnish() {
        if( ! is_admin() ) {
           $this->outputStyles();
           $this->outputScripts();
        }
    }

}
