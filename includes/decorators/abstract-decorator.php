<?php


namespace xo10\woocommerce\categories\widgets\decorators;


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Contains all methods that are required to spruce up the look of our widget.
 * 
 * @package xo10\woocommerce\categories\widget\decorators
 */
abstract class Abstract_Decorator {

    public $widgetID;
    public $listHtmlID = '';
    public $listHtmlClasses = '';
    public $countpos = 'extright';
    public $countbracket = 'round';
    public $imgtxt = 'iltr';
    public $imgw = 42;
    public $imgh = 42;
    public $effects_opts = '';
    public $display_count = 0;
    
    protected $_actualEffects = array();
    protected $_toggleBtnPos = 'right';
    protected $_ulHtmlID = '';
    protected $_ulHtmlClasses = '';
    
        
    /**
     * Initializes all variables based on class variables set by user.
     */
    public function digest() {
        $this->digestEffectsOpts();
        
        $this->digestHoldingListHtmlID();
        $this->digestHoldingListHtmlClasses();
    }

    /**
     * Extracts all the effects-related options string into an array for internal processing.
     */
    protected function digestEffectsOpts() {
        if( empty( $this->effects_opts ) ) {
            $this->_actualEffects = array();
            return;
        }
        
        $in = explode( ',', $this->effects_opts );
        foreach( $in as $i ) {
            list($k, $v) = explode( '=', $i );
            $this->_actualEffects[$k] = $v;
        }
        
        $this->_actualEffects  = array_change_key_case( $this->_actualEffects );
    }
    
    /**
     * Generates the values for the "id" attribute of HTML elements that wrap the root list (e.g. `<ul>`). 
     * 
     * @return string An alphanumeric string.
     */    
    protected function digestHoldingListHtmlID() {
        $this->_ulHtmlID = $this->listHtmlID;
    }
    
    /**
     * Generates the values for the "class" attribute of HTML elements that wrap the root list (e.g. `<ul>`). 
     * 
     * @return string A string that conforms to CSS class name standards.
     */
    protected function digestHoldingListHtmlClasses() {
        $imgTxtClass = 'show-' . $this->imgtxt;
            
        if ( $this->display_count ) {
            $imgTxtClass .= ' countpos-' . $this->countpos;
        }
        
        $htmlClasses = $this->listHtmlClasses;
        
        $this->_ulHtmlClasses = $htmlClasses . ' ' . $imgTxtClass;
    }
    
    /**
     * Gets the _opening_ root tag(s) that wrap all the category list items.
     *
     * @return string An HTML `<ul>` element with optionally a CSS ID and or a set of CSS classes.
     */
    public function tagListWrapperOpen() {
        $openHtmlID = '';
        
        if( '' !== $this->digestHoldingListHtmlID() ) {
            $openHtmlID = ' id="' . esc_attr( $this->_ulHtmlID ) . '" ';
        }
        
	return '<ul' . $openHtmlID . ' class = "' . esc_attr( $this->_ulHtmlClasses ) . '">';
        
    }
    
    /**
     * Gets the _closing_ root tag(s) that wrap all the category list items.
     *
     * @return string An HTML `</ul>`.
     */
    public function tagListWrapperClose() {
        return '</ul>';
    }
 
    
    /**
     * Gets the _left_ bracket for the product count.
     * 
     * @return string The opening bracket.
     */
    public function tagBracketLeft() {
        $char = '';

        switch ($this->countbracket) {
            case 'round':
                $char = '(';
                break;
            case 'square':
                $char = '[';
                break;
            case 'brace':
                $char = '{';
                break;
            case 'angle':
                $char = '<';
                break;
            default: // i.e. none
                $char = '';
                break;
        }

        $char = '<span class="bracket left">' . htmlspecialchars($char) . '</span>';

        return $char;
    }

    /**
     * Gets the _right_ bracket for the product count.
     * 
     * @return string The closing bracket.
     */
    public function tagBracketRight() {
        $char = '';

        switch ($this->countbracket) {
            case 'round':
                $char = ')';
                break;
            case 'square':
                $char = ']';
                break;
            case 'brace':
                $char = '}';
                break;
            case 'angle':
                $char = '>';
                break;
            default: // i.e. none
                $char = '';
                break;
        }

        $char = '<span class="bracket right">' . htmlspecialchars($char) . '</span>';
        
        return $char;
    }

    /**
     * Gets the CSS category class for the _active_ category e.g. `<li class="xxx"> <li>`.
     * 
     * @return string The CSS class(es) that represents an active category.
     */
    public function activeCatCssClass() {
        return '';
    }
    
    /**
     * Gets the CSS class for the active category's _anchor_ e.g. `<a class="aaa"> <a>`.
     * 
     * @return string The CSS class(es) that represents an active category's anchor.
     */
    public function activeCatAnchorCssClass() {
        return '';
    }

    /**
     * Gets the CSS category class for the _active_ category's _parent_ e.g. `<li class="yyy"> <ul><li class="xxx"></li></ul> <li>`.
     * 
     * @return string The CSS class(es) for an active category's parent.
     */
    public function activeCatParentCssClass() {
        return '';
    }

    /**
     * Gets the CSS category class for the _non-active_ category's _parent_ e.g. `<li class="zzz"> <ul><li></li></ul> <li>`.
     * 
    * @return string The CSS class(es) for a non-active category's parent.
     */
    public function generalCatParentCssClass() {
        return '';
    }

    /**
     * Gets the expand/collapse icon that appears to _left_ of any images or text 
     * within a list within an _anchor_.
     * Example: `<li><a><TAG>icon</TAG> XXX </a><span>count</span></li>`.
     * 
     * @return string A full tag and not an opening tag without a corresponding closing tag.
     */
    public function tagToggleIconInnerLeft() {
        
        return '';
    }
    
    /**
     * Gets the expand/collapse icon that appears to _right_ of any images or text 
     * within a list within an _anchor_.
     * Example: `<li><a> XXX <TAG>icon</TAG></a><span>count</span></li>`.
     * 
     * @return string A full tag and not an opening tag without a corresponding closing tag.
     */
    public function tagToggleIconInnerRight() {
       
        
        return '';
    }
    
    /**
     * Gets the expand/collapse icon that appears to _extreme left_ of any images, text or post counts
     * within a list within an _list_.
     * Example: `<li><TAG>icon</TAG><a> XXX </a><span>count</span></li>`.
     * 
     * @return string A full tag and not an opening tag without a corresponding closing tag.
     */
    public function tagToggleIconOuterLeft() {
        
        return '';
    }
    
    /**
     * Gets the expand/collapse icon that appears to _extreme right_ of any images, text or post counts
     * within a list within an _list_.
     * Example: `<li><a> XXX </a><span>count</span><TAG>icon</TAG></li>`.
     * 
     * @return string A full tag and not an opening tag without a corresponding closing tag.
     */
    public function tagToggleIconOuterRight() {
        
        return '';
    }
    
    /**
     * Output all necessary CSS styles for the front-end.
     */
    protected function outputStyles() {
    }
    
    /**
     * Output all necessary Javascripts for the front-end.
     */
    protected function outputScripts() {
    }
    
    /**
     * Outputs any CSS/JS required by the widget.
     */
    public abstract function furnish();
}
