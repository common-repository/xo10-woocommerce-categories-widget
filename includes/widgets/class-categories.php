<?php


use xo10\woocommerce\categories\widgets\decorators\Decorator_Factory as Decorator_Factory;
use xo10\woocommerce\categories\walkers\Cat_List_Walker as Cat_List_Walker;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once( XO10_WC_CATS_PLUGIN_DIR . '../woocommerce/includes/abstracts/abstract-wc-widget.php' );

/**
 * Product Category Images Widget
 *
 * @extends WC_Widget
 * 
 * @package xo10\woocommerce\categories\widgets
 */
class XO10_WC_Categories_Widget extends WC_Widget {

        // widget-specific
        const WIDGET_SLUG = 'xo10_wc_cats_widget';
        const WIDGET_CSS_CLASS = 'woocommerce-product-categories';
        const WIDGET_DISPLAY_NAME = 'XO10 - WooCommerce Categories';

        // default image dimensions and constraints
        const IMG_W_MIN = 24;
        const IMG_W_MAX = 200;
        const IMG_W_DEFAULT = 42;
        const IMG_H_MIN = 24;
        const IMG_H_MAX = 200;
        const IMG_H_DEFAULT = 42;


	public $cat_ancestors;
	public $current_cat;
        
	/**
	 * Constructor
	 */
	public function __construct() {
            
		$this->widget_cssclass    = XO10_WC_Categories_Widget::WIDGET_CSS_CLASS; // css class
		$this->widget_description = __( 'A list or dropdown of product categories.', 'xo10-woocommerce-categories-widget' );
		$this->widget_id          = XO10_WC_Categories_Widget::WIDGET_SLUG; // option_id in database =  prefixed with "widget_"
		$this->widget_name        = __( 'XO10 - WooCommerce Categories', 'xo10-woocommerce-categories-widget' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Product Categories', 'woocommerce' ),
				'label' => __( 'Title', 'woocommerce' )
			),
			'orderby' => array(
				'type'  => 'select',
				'std'   => 'name',
				'label' => __( 'Order by', 'woocommerce' ),
				'options' => array(
					'order' => __( 'Category Order', 'woocommerce' ),
					'name'  => __( 'Name', 'woocommerce' )
				)
			),
			'dropdown' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show as dropdown', 'woocommerce' )
			),
			'look_effects' => array(
				'type'  => 'select',
				'std'   => 'raw',
				'label' => __( 'Look - Effects', 'xo10-woocommerce-categories-widget' ),
				'options' => array(
					'raw'             => __( 'Raw - None', 'xo10-woocommerce-categories-widget' ),        // no styling, v1.0 of this widget was like this.
                                        'basic'           => __( 'Basic - None', 'xo10-woocommerce-categories-widget' ),      // basic styling
                                        'plain-accordion' => __( 'Plain - Accordion', 'xo10-woocommerce-categories-widget' ), 
                                        'sexy-accordion'  => __( 'Sexy - Accordion', 'xo10-woocommerce-categories-widget' ),  
                                )
			),
                        'effects_opts'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Effect options (optional)', 'xo10-woocommerce-categories-widget' )
			),
			'list_css_class'  => array(
				'type'  => 'text',
				'std'   => 'product-categories',
				'label' => __( 'List HTML class attribute (optional)', 'xo10-woocommerce-categories-widget' )
			),
			'list_css_id'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'List HTML ID attribute (optional)', 'xo10-woocommerce-categories-widget' )
			),
			'hierarchical' => array(
				'type'  => 'checkbox',
				'std'   => 1,
				'label' => __( 'Show hierarchy', 'woocommerce' )
			),
			'show_children_only' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Only show children of the current category', 'woocommerce' )
			),
                  	'hide_empty' => array( // field only available in WC 2.5 but works in earlier versions of WC as well.
				'type'  => 'checkbox',
				'std'   => 1, // we set default is hide-empty so that toggle button won't appear.
				'label' => __( 'Hide empty categories', 'woocommerce' )
			),
			'img_text_display' => array(
				'type'  => 'select',
				'std'   => 'iltr',
				'label' => __( 'Text/Image display', 'xo10-woocommerce-categories-widget' ),
				'options' => array(
					'image' => __( 'Image only', 'xo10-woocommerce-categories-widget' ),
					'text'  => __( 'Text only', 'xo10-woocommerce-categories-widget' ),
					'iltr' => __( 'Image left, Text right', 'xo10-woocommerce-categories-widget' ),
					'tlir' => __( 'Text left, Image right', 'xo10-woocommerce-categories-widget' )
				)
			),
			'img_width' => array(
				'type'  => 'number',
				'step'  => 10,
				'min'   => XO10_WC_Categories_Widget::IMG_W_MIN,
				'max'   => XO10_WC_Categories_Widget::IMG_W_MAX,
				'std'   => XO10_WC_Categories_Widget::IMG_W_DEFAULT,
				'label' => sprintf( __( 'Image width in px (Min = %s, Max = %s)', 'xo10-woocommerce-categories-widget' ), XO10_WC_Categories_Widget::IMG_W_MIN, XO10_WC_Categories_Widget::IMG_W_MAX )
			),
			'img_height' => array(
				'type'  => 'number',
				'step'  => 1,
				'min'   => XO10_WC_Categories_Widget::IMG_H_MIN,
				'max'   => XO10_WC_Categories_Widget::IMG_H_MAX,
				'std'   => XO10_WC_Categories_Widget::IMG_H_DEFAULT,
				'label' => sprintf( __( 'Image height in px (Min = %s, Max = %s)', 'xo10-woocommerce-categories-widget' ), XO10_WC_Categories_Widget::IMG_H_MIN, XO10_WC_Categories_Widget::IMG_H_MAX )
			),
			'count' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show product counts', 'woocommerce' )
			),
			'count_pos' => array(
				'type'  => 'select',
				'std'   => 'extright',
				'label' => __( 'Position of product counts', 'xo10-woocommerce-categories-widget' ),
				'options' => array(
					'extright' => __( 'Extreme Right', 'xo10-woocommerce-categories-widget' ),
					'extleft'  => __( 'Extreme Left', 'xo10-woocommerce-categories-widget' ),
				)
			),
			'count_bracket' => array(
				'type'  => 'select',
				'std'   => 'round',
				'label' => __( 'Bracket type for product counts', 'xo10-woocommerce-categories-widget' ),
				'options' => array(
					'round'  => '( )',                             
					'square' => '[ ]',                     
					'brace'  => '{ }',                            
					'angle'  => '< >',                                  
					'none'   => __( 'None', 'xo10-woocommerce-categories-widget' ), // still contain invisible "span" for styling purposes.
				)
			),
                         
		);
                
                
                parent::__construct();
	}

        
        /**
          * update function.
          *
          * @see WC_Widget->update()
          * @param array $new_instance
          * @param array $old_instance
          * @return array
         */
        public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

                if ( empty( $this->settings ) ) {
                    return $instance;
		}
                
                // Loop settings and get values to save.
		foreach ( $this->settings as $key => $setting ) {

                    if ( ! isset( $setting['type'] ) ) {
                            continue;
                    }

                    // Format the value based on settings type.
                    switch ( $setting['type'] ) {
                        case 'number' :
                                $instance[ $key ] = absint( $new_instance[ $key ] );
                                if ( isset( $setting['min'] ) && '' !== $setting['min'] ) {
                                        $instance[ $key ] = max( $instance[ $key ], $setting['min'] );
                                }
                                if ( isset( $setting['max'] ) && '' !== $setting['max'] ) {
                                        $instance[ $key ] = min( $instance[ $key ], $setting['max'] );
                                }
                        break;
                        case 'textarea' :
                                $instance[ $key ] = wp_kses( trim( wp_unslash( $new_instance[ $key ] ) ), wp_kses_allowed_html( 'post' ) );
                        break;
                        case 'checkbox' :
                                $instance[ $key ] = is_null( $new_instance[ $key ] ) ? 0 : 1;
                        break;
                        default:
                                $instance[ $key ] = sanitize_text_field( $new_instance[ $key ] );
                        break;
                    }

                        $instance[ $key ] = apply_filters( 'woocommerce_widget_settings_sanitize_option', $instance[ $key ], $new_instance, $key, $setting );
                        // Sanitize the value of a setting by its key.
                        $instance[ $key ] = apply_filters( "woocommerce_widget_settings_sanitize_option_$key", $instance[ $key ], $new_instance, $key, $setting );                            

                    if( 'list_css_class' === $key ) {
                        if( trim( $new_instance[$key] ) == false ) { 
                          $instance[$key] = $setting['std'];
                        } else {
                          $css_classes = explode( ' ', trim( $instance[$key] ) );
                          $clean_classes = array();
                          $cleaned = '';

                            foreach( $css_classes as $cssy ) {
                                $cleaned = sanitize_html_class( trim( $cssy ) );
                                if( ! empty( $cleaned ) ) {
                                  $clean_classes[] =  $cleaned;
                                }
                            }

                          $instance[$key] = implode( ' ', $clean_classes );

                        }
                    }

                    if( 'list_css_id' === $key ) {
                        if( trim( $new_instance[$key] ) == false ) {
                          $instance[$key] = $setting['std'];
                        } else {
                          $instance[$key] = sanitize_title_with_dashes( trim( $instance[$key] ) );
                        }
                    }
                        
                    if( 'effects_opts' === $key ) {
                            if( trim( $new_instance[$key] ) == false ) {
                                $instance[$key] = $setting['std'];
                            } else {
                                $allow = substr( trim( $instance[$key] ), 0, 200 );
                                $opts = explode( ',', $allow );
                                $clean_opts = array();
                                $tidy = '';
                                foreach( $opts as $opt ) {

                                    $tidy = trim( $opt ); 

                                    if( ! empty( $tidy ) ) {

                                        if( preg_match( '/^[a-zA-Z0-9=+-_]+$/', $tidy ) && 
                                            preg_match( '/^[^=]*=[^=]*$/', $tidy ) && 
                                            preg_match( '/^[a-zA-Z0-9](.*[a-zA-Z0-9])?$/', $tidy ) ) {
                                            $clean_opts[] = $tidy;
                                        }
                                    }
                                }

                              $instance[$key] = implode( ',', $clean_opts );
                            }
                    }

		}

		$this->flush_widget_cache();

		return $instance;
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget( $args, $instance ) {
		global $wp_query, $post;

		$count              = isset( $instance['count'] ) ? $instance['count'] : $this->settings['count']['std'];
		$hierarchical       = isset( $instance['hierarchical'] ) ? $instance['hierarchical'] : $this->settings['hierarchical']['std'];
		$show_children_only = isset( $instance['show_children_only'] ) ? $instance['show_children_only'] : $this->settings['show_children_only']['std'];
		$dropdown           = isset( $instance['dropdown'] ) ? $instance['dropdown'] : $this->settings['dropdown']['std'];
		$orderby            = isset( $instance['orderby'] ) ? $instance['orderby'] : $this->settings['orderby']['std'];
                $hide_empty         = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : $this->settings['hide_empty']['std'];
		$dropdown_args      = array( 'hide_empty' => $hide_empty );
		$list_args          = array( 'show_count' => $count, 'hierarchical' => $hierarchical, 'taxonomy' => 'product_cat', 'hide_empty' => $hide_empty );

		$imgtxt        = isset( $instance['img_text_display'] ) ? $instance['img_text_display'] : $this->settings['img_text_display']['std'];
		$imgw          = isset( $instance['img_width'] ) ? $instance['img_width'] : $this->settings['img_width']['std'];
		$imgh          = isset( $instance['img_height'] ) ? $instance['img_height'] : $this->settings['img_height']['std'];
		$cpos          = isset( $instance['count_pos'] ) ? $instance['count_pos'] : $this->settings['count_pos']['std'];
		$cbracket      = isset( $instance['count_bracket'] ) ? $instance['count_bracket'] : $this->settings['count_bracket']['std'];
		$css_class     = isset( $instance['list_css_class'] ) ? $instance['list_css_class'] : $this->settings['list_css_class']['std'];
		$css_id        = isset( $instance['list_css_id'] ) ? $instance['list_css_id'] : $this->settings['list_css_id']['std'];
		$look_effects  = isset( $instance['look_effects'] ) ? $instance['look_effects'] : $this->settings['look_effects']['std'];
		$effects_opts      = isset( $instance['effects_opts'] ) ? $instance['effects_opts'] : $this->settings['effects_opts']['std'];

		// Menu Order
		$list_args['menu_order'] = false;
		if ( $orderby == 'order' ) {
			$list_args['menu_order'] = 'asc';
		} else {
			$list_args['orderby']    = 'title';
		}

		// Setup Current Category
		$this->current_cat   = false;
		$this->cat_ancestors = array();

		if ( is_tax('product_cat') ) {

			$this->current_cat   = $wp_query->queried_object;
			$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );

		} elseif ( is_singular('product') ) {

			$product_category = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent' ) );

			if ( $product_category ) {
				$this->current_cat   = end( $product_category );
				$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );
			}

		}

		// Show Siblings and Children Only
		if ( $show_children_only && $this->current_cat ) {

			// Top level is needed
			$top_level = get_terms(
				'product_cat',
				array(
					'fields'       => 'ids',
					'parent'       => 0,
					'hierarchical' => true,
					'hide_empty'   => false
				)
			);

			// Direct children are wanted
			$direct_children = get_terms(
				'product_cat',
				array(
					'fields'       => 'ids',
					'parent'       => $this->current_cat->term_id,
					'hierarchical' => true,
					'hide_empty'   => false
				)
			);

			// Gather siblings of ancestors
			$siblings  = array();
			if ( $this->cat_ancestors ) {
				foreach ( $this->cat_ancestors as $ancestor ) {
					$ancestor_siblings = get_terms(
						'product_cat',
						array(
							'fields'       => 'ids',
							'parent'       => $ancestor,
							'hierarchical' => false,
							'hide_empty'   => false
						)
					);
					$siblings = array_merge( $siblings, $ancestor_siblings );
				}
			}

			if ( $hierarchical ) {
				$include = array_merge( $top_level, $this->cat_ancestors, $siblings, $direct_children, array( $this->current_cat->term_id ) );
			} else {
				$include = array_merge( $direct_children );
			}

			$dropdown_args['include'] = implode( ',', $include );
			$list_args['include']     = implode( ',', $include );

			if ( empty( $include ) ) {
				return;
			}

		} elseif ( $show_children_only ) {
			$dropdown_args['depth']        = 1;
			$dropdown_args['child_of']     = 0;
			$dropdown_args['hierarchical'] = 1;
			$list_args['depth']            = 1;
			$list_args['child_of']         = 0;
			$list_args['hierarchical']     = 1;
		}

		$this->widget_start( $args, $instance );

		// Dropdown
		if ( $dropdown ) {

			$dropdown_defaults = array(
				'show_counts'        => $count,
				'hierarchical'       => $hierarchical,
				'show_uncategorized' => 0,
				'orderby'            => $orderby,
				'selected'           => $this->current_cat ? $this->current_cat->slug : ''
			);
			$dropdown_args = wp_parse_args( $dropdown_args, $dropdown_defaults );

			// Stuck with this until a fix for http://core.trac.wordpress.org/ticket/13258
			wc_product_dropdown_categories( apply_filters( 'woocommerce_product_categories_widget_dropdown_args', $dropdown_args ) );

			wc_enqueue_js( "
				jQuery( '.dropdown_product_cat' ).change( function() {
					if ( jQuery(this).val() != '' ) {
						var this_page = '';
						var home_url  = '" . esc_js( home_url( '/' ) ) . "';
						if ( home_url.indexOf( '?' ) > 0 ) {
							this_page = home_url + '&product_cat=' + jQuery(this).val();
						} else {
							this_page = home_url + '?product_cat=' + jQuery(this).val();
						}
						location.href = this_page;
					}
				});
			" );

		// List
		} else {
                        $decorator = Decorator_Factory::getDecorator( $look_effects );
                        $decorator->widgetID            = $this->id;
                        $decorator->listHtmlID          = $css_id;
			$decorator->listHtmlClasses     = $css_class;
                        $decorator->display_count       = $count; 
                        $decorator->countpos            = $cpos;
                        $decorator->countbracket        = $cbracket;
                        $decorator->imgtxt              = $imgtxt;
                        $decorator->imgw                = $imgw;
                        $decorator->imgh                = $imgh;
                        $decorator->effects_opts        = $effects_opts;
                        
                        $decorator->digest();
                        
                        $listWalker = new Cat_List_Walker();
			$listWalker->decorator = $decorator;

			$list_args['walker']                     = $listWalker;
			$list_args['title_li']                   = '';
			$list_args['pad_counts']                 = 1;
			$list_args['show_option_none']           = __('No product categories exist.', 'woocommerce' );
			$list_args['current_category']           = ( $this->current_cat ) ? $this->current_cat->term_id : '';
			$list_args['current_category_ancestors'] = $this->cat_ancestors;
                        
			echo $decorator->tagListWrapperOpen();
                        
			wp_list_categories( apply_filters( 'woocommerce_product_categories_widget_args', $list_args ) );
                        
			echo $decorator->tagListWrapperClose();

                        $decorator->furnish(); 
		}

		$this->widget_end( $args );
	}
}
