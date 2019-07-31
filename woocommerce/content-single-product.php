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
<script>
    var search_labelAdults = '<%= count > 0 ? (count + (count == 1 ? " взрослый" : " взрослых")) : "Любое" %>';
    var search_labelChilds = ' и <%= count + (count == 1 ? " ребенок" : " ребенка") %>';
    var eventFindOfferUrl, searchResultsAjaxUrl, search_has_more, search_validateUrl, search_resultsUrl, search_variantsUrl;
	<?php if($params['category_name'] == 'tsvet') { ?>
    searchResultsAjaxUrl = '/wp-admin/admin-ajax.php?action=tsvet_query&query=resultsAjaxUrl';

    search_validateUrl = '/wp-admin/admin-ajax.php?action=tsvet_query&query=validateUrl';
    search_resultsUrl = '/wp-admin/admin-ajax.php?action=tsvet_query&query=resultsUrl';
    search_variantsUrl = '/wp-admin/admin-ajax.php?action=tsvet_query&query=variantsUrl';
	<?php } else if($params['category_name'] == 'itaka') { ?>
    searchResultsAjaxUrl = '/wp-admin/admin-ajax.php?action=itaka_query&url=strony&_page=4466';

    search_validateUrl = '/wp-admin/admin-ajax.php?action=itaka_query&url=strony&_page=17';
    search_resultsUrl = '/wp-admin/admin-ajax.php?action=itaka_query&url=strony&_page=4466';
    search_variantsUrl = '/wp-admin/admin-ajax.php?action=itaka_query&url=strony&_page=560';
	<?php } else { ?>
    searchResultsAjaxUrl = '/wp-admin/admin-ajax.php?action=sletat_query&query=resultsAjaxUrl';

    search_validateUrl = '/wp-admin/admin-ajax.php?action=sletat_query&query=validateUrl';
    search_resultsUrl = '/wp-admin/admin-ajax.php?action=sletat_query&query=resultsUrl';
    search_variantsUrl = '/wp-admin/admin-ajax.php?action=sletat_query&query=variantsUrl';
	<?php } ?>
</script>
<section id="path">
    <section class="path">
        <a href="/"></a>
        <strong>&gt;</strong>
        <?php if($params['category_name'] == 'itaka') { ?>
            <a href="/?turoperator=Itaka&s=&post_type=product">
                <span>Туры с вылетом из Варшавы</span>
            </a>
        <?php } else if($params['category_name'] == 'sletat') { ?>
            <a href="/?turoperator=Sletat&s=&post_type=product">
                <span>Туры с вылетом из Минска и Москвы</span>
            </a>
        <?php } else { ?>
            <a href="/?turoperator=Tsvet&s=&post_type=product">
                <span>Туры от от Tsvet</span>
            </a>
        <?php } ?>
        <strong>&gt;</strong>
        <h1><?= $title; ?></h1>
        <span class="stars star<?= $params['stars'] * 10; ?>"></span>
    </section>
</section>
<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="product_page_form">
        <div class="well" id="form-and-offers">

            <h3>Детали предложения</h3>

            <?php $enddaterange = date_add(date_create(), new DateInterval("P45D")); ?>
            <form action="" method="get" id="search-form" data-child-disable=""
                  data-is-combine-products="false"
                  data-end-daterange="<?= $enddaterange->format("d.m.Y"); ?>">
                <input type="hidden" name="code" value="<?= $code = $params['_sku']; ?>"/>
                <input type="hidden" name="is-resabee" data-is-resabee="false"/>
                <input type="hidden" name="lang" value="ru"/>
                <input type="hidden" name="currency" value="<?= $params['category_name'] == 'itaka' ? "pln" : "rub"; ?>"/>

                <input type="hidden" name="food" value=""/>
                <input type="hidden" name="room" value=""/>
                <input type="hidden" name="adults" value=""/>
                <input type="hidden" name="childs" value=""/>
                <input type="hidden" name="date" value=""/>
                <div id="event-types" class="hidden">
                    <input type="checkbox" checked="checked" value="31" />
                </div>

                <div class="fRow fPersonMain">
                    <label class="hidden">Кол-во человек</label>

                    <div class="fRow fParticipants dropdown">
                        <label>Кол-во человек</label>

                        <a id="participants-count" class="dropdown-toggle btn" data-toggle="dropdown" href="#"
                           data-childs="0" data-adults="2">2 взрослых</a>
                        <div class="dropdown-menu">
                            <span class="dropdown-menu-close">&times;</span>
                            <div class="fPerson fAdults">
                                <label data-title="Взрослые" for="adults-select">Взрослые</label>
                                <select name="adults" data-trans-name="Взрослые" id="adults-select"
                                        data-js-value="adults-select">
									<?php $values_adults = '';
									for($option_adults = 1; $option_adults <= $params['adults']; $option_adults++) {
										echo $option = "<option " . ($option_adults == 2 ? "selected" : "") . " value=\"{$option_adults}\">{$option_adults}</option>";
										$values_adults .= $option;
									} ?>
                                </select>
                            </div>

                            <div class="fPerson fKids">
                                <label data-title="Дети" for="childs-select">Дети</label>
                                <select name="childs" data-trans-name="Дети" id="childs-select"
                                        data-js-value="childs-select">
									<?php $values_childs = '';
									for($option_childs = 0; $option_childs <= $params['childs']; $option_childs++) {
										echo $option = "<option value=\"{$option_childs}\">{$option_childs}</option>";
										$values_childs .= $option;
									} ?>
                                </select>
                            </div>

                            <div id="childs-ages" style="display: none">
                                <div>
                                    <label> Ребенок 1 </label>
                                    <div class="controls dropdown">
                                        <div class="input-append date datepicker">
                                            <input type="text" placeholder="dd.mm.rrrr" name="child_age[]"
                                                   value="08.08.2012"/>
                                            <a href="#" class="datekids-opener"> </a>
                                        </div>
                                    </div>
                                </div>
                                <small>Даты рождения детей требуются для правильной калькуляции цены предложения
                                </small>
                            </div>
                            <div class="childs-ages-footer">
                                <a href="#" class="childs-ages-commit">подтвердить</a>
                                <a href="#" class="childs-ages-rollback">Отменить</a>
                            </div>

                        </div>
                    </div>


                </div>

				<?php $added_price = 0; ?>
				<?php $rooms = (array) $params['rooms']; ?>
                <div class="fRow <?= count($rooms) < 2 ? "onlyOne" : ""; ?>">
                    <label for="room-select">Номер</label>
                    <select name="room" id="room-select" data-js-value="room-select" style="display: none;">
						<?php $values_rooms = ''; foreach($rooms as $value => $room) {
							echo $option = "<option " . ($value == array_keys($rooms)[0] ? 'selected' : ''). " value=\"{$value}\" data-combine-code=\"{$code}\">{$room}</option>";
							$values_rooms .= $option;
						} ?>
                    </select>
                    <div class="dropdown">
                        <a class="dropdown-toggle btn" data-toggle="dropdown" href="#" data-js-value="room-selected">
							<?= count($rooms) > 0 ? array_values($rooms)[0] : '' ; ?>
                        </a>
                        <ul class="dropdown-menu" role="menu" data-js-value="room-list">
							<?php
							$key = 0;
							$list_rooms = '';
							foreach($rooms as $value => $room) {
								$room = explode(" ", preg_replace("/\s+/", " ", trim($room)));
								if(count($room) > 1 &&  floatval(str_replace(",", ".", $room[count($room) - 1])) !== 0) {
									unset($room[count($room) - 1]);
								}
								$room = implode(" ", $room);
								$selected = $key == 0 ? "selected" : '';
								echo $option = <<<HTML
                                    <li role="presentation" class="{$selected}">
                                        <a role="menuitem" tabindex="-1" data-value="{$value}" href="#">
                                            {$room}
                                        </a>
                                    </li>
HTML;
								$list_rooms .= $option;
								$key++;
							}
							?>
                        </ul>
                    </div>
                </div>

				<?php $food_list =  (array) $params['foods']; ?>
                <div class="fRow <?= count($food_list) < 2 ? "onlyOne" : ""; ?>">
                    <label for="food-select">Питание</label>
                    <select name="food" id="food-select" data-js-value="food-select" style="display: none;">
						<?php $values_foods = ''; foreach($food_list as $value => $food) {
							echo $option = "<option " . ($value == array_keys($food_list)[0] ? 'selected' : '') . " value=\"{$value}\" data-combine-code=\"{$code}\">{$food}</option>";
							$values_foods .= $option;
						} ?>
                    </select>
                    <div class="dropdown">
                        <a class="dropdown-toggle btn" data-toggle="dropdown" href="#" data-js-value="food-selected">
							<?= count($food_list) > 0 ? array_values($food_list)[0] : '' ; ?>
                        </a>
                        <ul class="dropdown-menu" role="menu" data-js-value="food-list">
							<?php
							$key = 0;
							$list_foods = '';
							foreach($food_list as $value => $food) {
								$food = explode(" ", preg_replace("/\s+/", " ", trim($food)));
								if(count($food) > 1 &&  floatval(str_replace(",", ".", $food[count($food) - 1])) !== 0) {
									unset($food[count($food) - 1]);
								}
								$food = implode(" ", $food);
								$selected = $key == 0 ? "selected" : '';
								echo $option = <<<HTML
                                    <li role="presentation" class="{$selected}">
                                        <a role="menuitem" tabindex="-1" data-value="{$value}" href="#">
                                            {$food}
                                        </a>
                                    </li>
HTML;
								$list_foods .= $option;
								$key++;
							} ?>
                        </ul>
                    </div>

                </div>

				<?php $departures = (array) $params['city-of-departure']; ?>
                <div class="fRow <?= count($departures) < 2 ? "onlyOne" : ""; ?>">
                    <label for="departure-select"><?= $params['bus_tour'] == 'Да' ? 'Выезд из' : 'Вылет из'; ?></label>
                    <select name="departure" id="departure-select" data-js-value="departure-select" style="display: none;">
						<?php $values_departures = ''; foreach($departures as $value => $departure) {
							echo $option = "<option " . ($value == array_keys($departures)[0] ? 'selected' : '') . " value=\"{$value}\">{$departure}</option>";
							$values_departures .= $option;
						} ?>
                    </select>
                    <div class="dropdown">
                        <a class="dropdown-toggle btn" data-toggle="dropdown" href="#" data-js-value="departure-selected">
							<?= count($departures) > 0 ? array_values($departures)[0] : '' ; ?>
                        </a>
                        <ul class="dropdown-menu" role="menu" data-js-value="departure-list">
							<?php
							$key = 0;
							$list_departures = '';
							foreach($departures as $value => $departure) {
								$selected = $key == 0 ? 'selected' : '';
								echo $option = <<<HTML
                                    <li role="presentation" class="{$selected}">
                                        <a role="menuitem" tabindex="-1" data-value="{$value}" href="#">
                                            {$departure}
                                        </a>
                                    </li>
HTML;
								$list_departures .= $option;
								$key++;
							} ?>
                        </ul>
                    </div>
                </div>
				<?php $regular_price = $params['_regular_price']; ?>

				<?php $terms_and_prices = $params['terms_and_prices']; ?>
                <div class="fRow fDates <?= count($terms_and_prices) < 2 ? "onlyOne" : ""; ?>" data-dep-name="Katowice" data-start-date="20170905">

                    <label for="date-select">Сроки и цены</label>
					<?php
					$months = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
					$days_week = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
					?>
                    <select name="ofr_id" id="date-select" style="display: none;">
						<?php
						$values_terms_and_prices = '';
						$selected_terms_and_prices = '';
						foreach($terms_and_prices as $value => $term) {
							list($label, $sale_price, $price) = explode('_', $term);
//							$sale_price = $sale_price ?: 0;
//							$price = $price ?: $sale_price;
							$label = explode('-', $label);
							foreach($label as $key => $value_label) {
								$label[$key] = strtotime(str_replace(".", "-", $value_label));
							}
							$label[] = $label[0];
							$date_from = $label[0];
							$date = date('Ymd', $label[0]);
							$label = $days_week[date('w', $label[0])] . ' ' . date('d.m', $label[0]) . ' &ndash; ' . $days_week[date('w', $label[1])] . ' ' . date('d.m.y', $label[1]) . ' (' . (abs($label[1] - $label[0]) / 60 / 60 / 24) . ' дней)';

							if($selected = ($value == array_keys($terms_and_prices)[0] ? 'selected' : '')) {
								$selected_terms_and_prices = $label;
							}
							echo $option = <<<HTML
                                <option {$selected} data-offerdate="{$date}" data-combine-code="{$code}" value="{$value}">
                                    {$label}
                                </option>
HTML;
							$values_terms_and_prices .= $option;

							$terms_and_prices[$value] = [
								'label' => $label,
//								'sale_price' => $sale_price . 'rub',
//								'price' => $price,
								'date_from' => $date_from,
                                'value' => $value,
                                'month' => $months[date('n', $date_from) - 1]
							]; ?>
						<?php } ?>
                    </select>

                    <div class="dropdown">
                        <a class="dropdown-toggle btn" data-toggle="dropdown" data-js-value="date-selected" href="#">
                            <div class="additional-icon-holder"></div>
							<?= count($terms_and_prices) > 0 ? array_values($terms_and_prices)[0]['label'] : '' ; ?>
                        </a>
                        <div class="dropdown-menu">
                            <div class="fRow fDuration">
                                <label for="duration-select">Длительность</label>
                                <select name="duration" id="duration-select" data-js-value="duration-select">
                                    <option selected value="">Любое</option>
                                    <option value="mid1">6-9 дней</option>
                                    <option value="long">13-15+ дней</option>
                                </select>
                                <div class="dropdown" style="display: none;">
                                    <a class="dropdown-toggle btn" data-toggle="dropdown" href="#"
                                       data-js-value="duration-selected">
                                        Любое
                                    </a>
                                    <ul class="dropdown-menu" role="menu" data-js-value="duration-list">
                                        <li role="presentation" class="selected">
                                            <a role="menuitem" tabindex="-1" data-value="" href="#">
                                                Любое
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a role="menuitem" tabindex="-1" data-value="mid1" href="#">
                                                6-9 дней
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a role="menuitem" tabindex="-1" data-value="long" href="#">
                                                13-15+ дней
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div id="event-months" class="carousel">
                                <div class="carousel-inner">
									<?php
									$terms_and_prices = array_filter($terms_and_prices, function($term) {
										return $term['date_from'] >= time();
									});
									array_custom_multisort($terms_and_prices, 'date_from');
									$temp_array = array_index($terms_and_prices, 'value', ['month']);

									ob_start();
									foreach ($temp_array as $month => $terms_and_prices) {
										$is_active_item = $month == array_keys($temp_array)[0]; ?>

                                        <div class="item <?= $is_active_item ? "active" : ''; ?>">
                                            <div class="month">
                                                <!--                                                <a class="btn left" href="#event-months" data-slide="prev"></a>-->
                                                <a class="btn left" href="#{{carousel-id}}" data-slide="prev"></a>
                                                <a class="btn right" href="#{{carousel-id}}" data-slide="next"></a>
												<?= $month . ' ' . date('Y'); ?>
                                            </div>
                                            <ul role="menu">
												<?php foreach($terms_and_prices as $key => $term) { ?>
                                                    <li role="presentation" class="<?= $is_active_item && $key == array_keys($terms_and_prices)[0] ? 'selected' : ''; ?>">
                                                        <a role="menuitem" tabindex="-1"
                                                           data-value="<?= $key; ?>" data-offerdate="<?= date('Ymd', $term['date_from']); ?>"
                                                           data-label="<?= $term['label']; ?>" href="#"
                                                           title=" ">
                                                            <div class="additional-icon-holder"></div>
															<?= $term['label']; ?>
                                                        </a>
                                                    </li>
												<?php } ?>
                                            </ul>
                                        </div>
									<?php }
									$list_terms_and_prices = ob_get_clean();
									echo strtr($list_terms_and_prices, [
										'{{carousel-id}}' => 'event-months'
									]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="summary-box" itemprop="offers" data-lang="ru">
                <div class="price-box">
                    <strong>За человека:</strong>
					<?php $price_not_sale = ''; if(($sale_price = $params['_sale_price']) && $regular_price) {
	                $percent_sale_price = round((1 - $sale_price / $regular_price) * 10000) / 100;
	                $round_percent_sale_price = round($percent_sale_price);
					$price_not_sale = <<<HTML
                        <div class="ymaxPrice_product">
                            <span class="price">
                                <span class="price-line_through"></span>
                                <strong data-js-value="ymaxPriceItakaHit">{$regular_price}</strong>
                                <span class="pln">Руб</span>
                                <i class="fa fa-user"></i>
                            </span>
                            <span class="ymax_percent" data-js-value="percentItakaHit" data-value="{$percent_sale_price}">-{$round_percent_sale_price}%</span>
                        </div>
HTML;
                    } 
                    echo $price_not_sale; ?>
                    <span class="price price-box_price">
                        <strong data-js-value="offerPrice"><?= $sale_price ?: $regular_price; ?></strong>
                        <span class="pln">руб</span></span>
                    <time itemprop="priceValidUntil" datetime="<?= date("Y-m-d"); ?>"></time>

                    <strong class="price-common-info">
                        <span>Общая стоимость</span>
                    </strong>
                    <span class="price price-black">
                        <strong data-js-value="totalPrice"
                                data-child-price-small="<?= $params['child_price_6']; ?>"
                                data-child-price-big="<?= $params['child_price_13']; ?>">
                            <?= ($sale_price ?: $regular_price) * 2; ?>
                        </strong>
                        <span class="pln">руб</span>
                    </span>
                </div>

                <div class="submit-box">

                    <a href="#"
                       class="button_submit" data-js-value="reservationUrl"
                       style="display: block;">Оставить заявку</a>

                    <span class="button_call" data-js-value="callButton" style="display: none;">
                        Забронировать по телефону
                        <strong class="button-call-first">+7 499 281 62 01</strong>
                    </span>
                </div>
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
