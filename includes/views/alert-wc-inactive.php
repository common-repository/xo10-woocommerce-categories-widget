<?php
  if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
  }
?>

<div class="error">
	<p>
    <?php
      printf(
        __( '%s not activated because the WooCommerce plugin is not active.', 'xo10-woocommerce-categories-widget' ),
        XO10_WC_CATS_PLUGIN_OFFICIAL_NAME
      );
    ?>
  </p>
</div>
