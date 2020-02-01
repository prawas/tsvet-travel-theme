
<section class="maintop maintop_<?= isset($_GET['turoperator']) ? strtolower($_GET['turoperator']) : "tsvet" ?>">
    <section class="main">
    <section class="section__container container">

    <h1 class="section__title">Туры от «ЦветТрэвел»</h1>

    <?php if ( have_posts() ) : ?>

    <?php woocommerce_product_loop_start(); ?>

        <?php woocommerce_product_subcategories(); ?>

        <div class="row">
        <?php while ( have_posts() ) : the_post(); ?>

            <?php
                /**
                 * woocommerce_shop_loop hook.
                 *
                 * @hooked WC_Structured_Data::generate_product_data() - 10
                 */
                do_action( 'woocommerce_shop_loop' );
            ?>

            <?php wc_get_template_part( 'content', 'product' ); ?>

        <?php endwhile; // end of the loop. ?>
        </div>

    <?php woocommerce_product_loop_end(); ?>

    <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
    
    <?php
        /**
         * woocommerce_no_products_found hook.
         *
         * @hooked wc_no_products_found - 10
         */
        do_action( 'woocommerce_no_products_found' );
    ?>
    
    <?php endif; ?>

<?php
/**
* woocommerce_after_main_content hook.
*
* @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
*/
do_action( 'woocommerce_after_main_content' );
?>
    </section>
    </section>
</section>
