<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
/**
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form();

	return;
}
global $product;
$title = $product->get_title();
$id_product = $product->get_id();

$params = get_params_tout($id_product);
?>

<section id="path">
    <section class="path">
        <a href="/"></a>
        <strong>&gt;</strong>
            <a href="/?turoperator=Tsvet&s=&post_type=product">
                <span>Туры от Tsvet</span>
            </a>
        <strong>&gt;</strong>
        <h1><?= $title; ?></h1>
        <span class="stars star<?= $params['stars'] * 10; ?>"></span>
    </section>
</section>
<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="product_page_form">
        <div class="well" id="form-and-offers">

            <h3>Детали предложения</h3>

            <div class="summary-box" itemprop="offers" data-lang="ru">

                <?php if ($product->get_id() == 512): ?>
                <p style="font-size:16px;padding-left:10px;padding-right:10px">Чтобы уточнить детали предложения, оставьте заявку, и&nbsp;мы обязательно свяжемся с&nbsp;Вами.</p>

                <div class="submit-box">
                    <?= do_shortcode('[contact-form-7 id="2279" title="Хочу в Калининград"]') ?>
                </div>

                <p style="font-size:16px;padding-left:10px;padding-right:10px">или позвоните нам по&nbsp;телефонам:</p>

                <?php else: ?>
                    <p style="font-size:16px;padding-left:10px;padding-right:10px">Чтобы уточнить детали предложения, позвоните нам по&nbsp;телефонам:</p>
                <?php endif; ?>

                <p style="font-size:28px;">
                    <a href="tel:+375295555190">+375 29 5555 190</a>
                </p>
                <p style="font-size:28px;">
                    <a href="tel:+375333228888">+375 33 322 8888</a>
                </p>

            </div>

        </div>
    </div>

    <div class="product_page">
		<?= tsvet_render_file("woocommerce/single-product/product", "thumbnails", ['params' => $params]); ?>

		<?= tsvet_render_file("woocommerce/single-product/description", "", ['params' => $params]); ?>
    </div><!-- .summary -->

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>

<div class="bron-tour" style="display: none;">
    <div class="bron-tour__container">
    <h2 class="bron-tour__title">Бронирование тура</h2>
<?= strtr(preg_replace(["/<br\s*\/?>/", "/<p><\/p>/"], "", wpcf7_contact_form_tag_func([
	'id' => 386,
	'title' => "Бронирование тура",
	'html_class' => "form-bron"
], null, 'contact-form-7')), [
    '{{VALUES_NAME}}' => $title,

    '<option value="{{VALUES_ADULTS}}">{{VALUES_ADULTS}}</option>' => $values_adults,
    '<option value="{{VALUES_CHILDS}}">{{VALUES_CHILDS}}</option>' => $values_childs,
    '<option value="{{VALUES_ROOMS}}">{{VALUES_ROOMS}}</option>' => $values_rooms,
    '<option value="{{VALUES_FOODS}}">{{VALUES_FOODS}}</option>' => $values_foods,
    '<option value="{{VALUES_DEPARTURES}}">{{VALUES_DEPARTURES}}</option>' => $values_departures,
    '<option value="{{VALUES_TERMS_AND_PRICES}}">{{VALUES_TERMS_AND_PRICES}}</option>' => $values_terms_and_prices,

    '<ul class="dropdown-menu">{{VALUES_ROOMS}}</ul>' => "<ul class=\"dropdown-menu\">{$list_rooms}</ul>",
    '<ul class="dropdown-menu">{{VALUES_FOODS}}</ul>' => "<ul class=\"dropdown-menu\">{$list_foods}</ul>",
    '<ul class="dropdown-menu">{{VALUES_DEPARTURES}}</ul>' => "<ul class=\"dropdown-menu\">{$list_departures}</ul>",
    '<div class="carousel-inner">{{VALUES_TERMS_AND_PRICES}}</div>' => '<div class="carousel-inner">' . strtr($list_terms_and_prices, ['{{carousel-id}}' => 'event-months-bron']) . '</div>',

    '{{GUESTS_SELECTED}}' => '2 взрослых',
    '{{VALUES_ADULTS}}' => 2,
    '{{VALUES_CHILDS}}' => 0,
    '{{ROOMS_SELECTED}}' => count($rooms) > 0 ? array_values($rooms)[0] : '',
    '{{FOODS_SELECTED}}' => count($food_list) > 0 ? array_values($food_list)[0] : '',
    '{{DEPARTURES_SELECTED}}' => count($departures) > 0 ? array_values($departures)[0] : '',
    '{{TERMS_AND_PRICES_SELECTED}}' => $selected_terms_and_prices,
    
    '{{PRICE_NOT_SALE}}' => $price_not_sale,
    '{{VALUE_PRICE_NOT_SALE}}' => $regular_price,
    '{{PRICE}}' => $sale_price ?: $regular_price,
    '{{PRICE_TOTAL}}' => ($sale_price ?: $regular_price) * 2,
    '{{LABEL_DEPARTURE}}' => $params['bus_tour'] == 'Да' ? 'Выезд из' : 'Вылет из'
]); ?>
    </div>
</div>
