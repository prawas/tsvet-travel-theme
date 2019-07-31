<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* @var $product WC_Product_Simple */
global $product;

$attachment_ids = is_array($params['_product_image_gallery'])?
    $params['_product_image_gallery'] : [$params['_product_image_gallery']];
foreach ($attachment_ids as $key => $attachment_id) {
    if(strpos($attachment_id, ',')) {
        $attachment_id = explode(',', $attachment_id);
        $attachment_ids[$key] = $attachment_id[0];
        unset($attachment_id[0]);
        foreach ($attachment_id as $id) {
            $attachment_ids[] = $id;
        }
    }
}
$attachment_ids = array_unique($attachment_ids);

$title = $product->get_title();
if ( $attachment_ids && count($attachment_ids) > 0
     && (!is_numeric($attachment_ids[0]) || has_post_thumbnail()) ) { ?>
	<div id="gallery" class="flex-gallery">
        <?php if($params['_wc_average_rating']) { ?>
            <div class="event-opinion-flag" >
                <strong><?= $params['_wc_average_rating']; ?></strong> / <?= $params['category_name'] == 'sletat' ? 10 : 6; ?>                </div>
        <?php } ?>
		<div id="slider" class="flexslider flex-slider">
			<ul class="slides flex-slides" >
				<?php foreach ( $attachment_ids as $key => $attachment_id )  {
					if(is_numeric($attachment_id)) {
						$src = '';
						$image = wp_get_attachment_image_src($attachment_id, 'shop_single', false);
						if ( $image ) {
							list( $src, $width, $height ) = $image;
							$attachment_ids[$key] = $src;
						}
					} else {
						$src = $attachment_id;
					} 
					$src = str_replace("-600x435", "", $src); ?>
					<li class="product-slide<?= $key == 0 ? " flex-active-slide" : ""; ?>">
						<a href="<?= $src; ?>" title="<?= $title; ?>">
							<img src="<?= $src; ?>" data-original="<?= $src; ?>" alt="<?= $title; ?>" data-description="<?= $title; ?>">
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
		<div class="flex-caption-wraper">
			<div class="flex-caption">
				<p><?= $title; ?></p>
			</div>
		</div>
		<div class="flex-icons">
			<a href="#" class="gi-fullscreen"></a>
		</div>
	</div>
	<div id="carousel" class="flexslider flex-carousel">
		<ul class="slides flex-slides">
			<?php foreach ( $attachment_ids as $key => $src )  { ?>
				<li class="product-slide<?= $key == 0 ? " flex-active-slide" : ""; ?>">
					<a href="#"><img src="<?= $src; ?>" data-large="Array" alt="<?= $title; ?> #<?= $key; ?>" data-description="<?= $title; ?>"></a>
				</li>
			<?php } ?>
		</ul>
	</div>
<?php }