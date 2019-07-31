<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<section class="section">
    <div class="section__container container">
        <h2 class="section__title"><?php woocommerce_page_title(); ?></h2>
        <div class="section__content">
            <div class="woocommerce columns-3 ">
                <ul class="products">
	                <?php
                    woocommerce_product_subcategories();
                    while ( have_posts() ) { the_post();
	                    wc_get_template_part( 'content', 'product' );
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <p>
        <?php $url = $wp->request;
        if(strpos($url, "/page/")) {
            $url = preg_replace("/\/page\/[0-9]{1,2}/", "", $url);
        }
        $page = get_query_var( 'paged' );
        if($page == 0) {
            $page = 1;
        } ?>
        <a class="section__show-more section__show-more_next" href="/<?= $url . "/page/" . ($page + 1); ?>">Загрузить еще</a>
    </p>
</section>
