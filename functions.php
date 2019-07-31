<?php
//remove_filter('show_admin_bar', 'wc_disable_admin_bar', 10);
//add_filter('show_admin_bar', '__return_false');

require __DIR__ . '/inc/disabled-rest-api.php';
require __DIR__ . '/inc/template-functions.php';
require __DIR__ . '/inc/override-functions.php';
require __DIR__ . '/inc/customizer.php';

require __DIR__ . '/inc/class/simple_html_dom.php';

define('SUFFIX', SCRIPT_DEBUG ? '' : '.min');

/**
 * Устанавливает тему по умолчанию и регистрирует поддержку различных функций WordPress.
 *
 * Обратите внимание, что эта функция навешиваются на хук after_setup_theme,
 * который запускакется до хука init. Init хук запускается слишком поздно для
 * некоторых функций, таких как add_theme_support
 */
function tsvet_setup()
{
	// Разрешить WordPress управлять заголовком документа.
	add_theme_support('title-tag');

	// Включить поддержку custom лого
	add_theme_support('custom-logo');

	// Включить поддержку фонового изображения
	add_theme_support('custom-background', [
		'default-color' => '#000',
//        'default-image' => get_template_directory_uri() . '/images/wallpaper.jpg',
		'wp-head-callback' => '__return_false'
	]);

	register_nav_menus([
		'general' => __('General menu', 'tsvet'),
		'top-left' => __('Top left menu', 'tsvet'),
		'top-right' => __('Top right menu', 'tsvet'),
		'footer' => __('Footer menu', 'tsvet'),
		'legal-information' => __('Legal information', 'tsvet')
	]);

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support('customize-selective-refresh-widgets');

	add_theme_support('post-thumbnails');
	add_image_size('post-thumbnail', 970, 345, true);
	add_image_size('shop_catalog', 370, 180, true);

	add_theme_support('menus');

	add_theme_support('woocommerce');
	add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'tsvet_setup');

function tsvet_wp_get_attachment_metadata($data)
{
	if (isset($data['sizes']['shop_catalog'])) {
		$data['sizes']['shop_catalog']['width'] = 370;
	}
	return $data;
}
add_filter('wp_get_attachment_metadata', 'tsvet_wp_get_attachment_metadata');

/**
 * Регистрация виджетов
 */
function tsvet_widgets_init()
{
	register_sidebar([
		'name' => __('Exchange rates', 'tsvet'),
		'id' => 'exchange-rates',
		'before_widget' => '<div class="exchange-rates">',
		'after_widget' => '</div>',
		'before_title' => '',
		'after_title' => ''
	]);
	register_sidebar([
		'name' => __('Exchange left', 'tsvet'),
		'id' => 'footer-left',
		'before_widget' => '<div class="footer-left-menu">',
		'after_widget' => '</div>',
		'before_title' => '<div class="footer-title-menu">',
		'after_title' => '</div>'
	]);
	register_sidebar([
		'name' => __('Exchange right', 'tsvet'),
		'id' => 'footer-right',
		'before_widget' => '<div class="footer-right-menu">',
		'after_widget' => '</div>',
		'before_title' => '',
		'after_title' => ''
	]);
}
add_action('widgets_init', 'tsvet_widgets_init');

/**
 * Подключаем скрипты и стили
 */
function tsvet_scripts()
{
	init_bootstrap();

	wp_enqueue_style('tsvet-style', get_stylesheet_uri(), array_filter([
		'bxslider',
		'select2',
		'datepicker',
		'datepicker.standalone',
		'woocommerce-general',
		'archive-product'
	], function ($style) {
		return wp_style_is($style, 'registered');
	}), null);

	wp_deregister_script('jquery');
	wp_deregister_script('jquery-migrate');

	wp_enqueue_script('ga', "https://www.googletagmanager.com/gtag/js?id=UA-119417579-1", [], null);
	wp_add_inline_script('ga', "window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'UA-119417579-1');");

	wp_enqueue_script('jquery', get_template_directory_uri() . "/js/jquery" . SUFFIX . ".js", [], '3.2.1', true);
	wp_add_inline_script('jquery', 'jQuery.migrateMute = true');
	wp_enqueue_script('jquery-migrate', get_template_directory_uri() . "/js/jquery-migrate" . SUFFIX . ".js", ['jquery'], '1.4.1', true);

	$deps_scripts = [
		'jquery-migrate',
		'bxslider',
		'select2',
		'datepicker',
		'archive-product'
	];
	if (class_exists('ALM_SHORTCODE') && ALM_SHORTCODE::$counter > 0) {
		$deps_scripts[] = 'ajax-load-more';
		$deps_scripts[] = 'ajax-load-more-progress';
	}
	wp_enqueue_script('tsvet-script', get_template_directory_uri() . "/app.js", array_filter($deps_scripts, function ($script) {
		return wp_script_is($script, 'registered');
	}), null, true);
}
add_action('wp_enqueue_scripts', 'tsvet_scripts', 11);

function tsvet_alm_js_dependencies()
{
	return ['jquery-migrate'];
}
add_filter('alm_js_dependencies', 'tsvet_alm_js_dependencies', 12);

function init_bxslider()
{
	wp_enqueue_style('bxslider', get_template_directory_uri() . "/plugins/bxslider/jquery.bxslider" . SUFFIX . ".css", [], '4.2.12');
	wp_enqueue_script('bxslider', get_template_directory_uri() . "/plugins/bxslider/jquery.bxslider" . SUFFIX . ".js", ['jquery-migrate'], '4.2.12', true);
	wp_add_inline_script('bxslider', "$('.slider').bxSlider({pager: false})");
}

// [slider label="slider"]$content[/slider]
function slider_shortcode($atts, $content)
{
	$category = shortcode_atts(['label' => ''], $atts);
	$category = get_category_by_slug($category['label']);

	add_action('wp_enqueue_scripts', 'init_bxslider');

	return tsvet_render_file("template-parts/slider", null, [
		'category' => $category,
		'slider_posts' => get_posts([
			'category__in' => $category->cat_ID
		]),
		'slider_content' => do_shortcode($content)
	]) ? : '';
}
add_shortcode('slider', 'slider_shortcode');

function init_form()
{
	// init_bootstrap();
	init_datepicker();

	wp_enqueue_script('jScrollPane', get_template_directory_uri() . "/plugins/jScrollPane/jquery.jscrollpane" . SUFFIX . ".js", ['jquery-migrate'], '2.0.23', true);
}

function init_bootstrap()
{
	wp_enqueue_style('bootstrap', get_template_directory_uri() . "/bootstrap/dist/css/bootstrap.css", [], '3.3.7');
	wp_enqueue_style('bootstrap-theme', get_template_directory_uri() . "/bootstrap/dist/css/bootstrap-theme.css", ['bootstrap'], '3.3.7');
	wp_enqueue_script('bootstrap', get_template_directory_uri() . "/bootstrap/dist/js/bootstrap.js", ['jquery-migrate'], '3.3.7');
}

function init_datepicker()
{
	wp_enqueue_style('datepicker', get_template_directory_uri() . "/plugins/bootstrap-datepicker/css/bootstrap-datepicker" . SUFFIX . ".css", ['bootstrap'], '1.6.4');
	wp_enqueue_style('datepicker.standalone', get_template_directory_uri() . "/plugins/bootstrap-datepicker/css/bootstrap-datepicker.standalone" . SUFFIX . ".css", ['datepicker'], '1.6.4');
	wp_enqueue_script('datepicker', get_template_directory_uri() . "/plugins/bootstrap-datepicker/js/bootstrap-datepicker" . SUFFIX . ".js", ['bootstrap'], '1.6.4', true);
	wp_enqueue_script('datepicker-locale', get_template_directory_uri() . "/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js", ['bootstrap', 'datepicker'], '1.6.4', true);
	wp_add_inline_script('datepicker-locale', "$('input.datepicker').datepicker({format: 'dd/mm/yyyy', language: 'ru'})");
	wp_enqueue_script('stackblur', get_template_directory_uri() . "/plugins/StackBlur/stackblur" . SUFFIX . ".js", ['bootstrap'], '0.5', true);
}

function init_searchform()
{
	wp_deregister_script('select2');

	wp_enqueue_style('select2', get_template_directory_uri() . "/plugins/select2/dist/css/select2" . SUFFIX . ".css", [], '4.0.3');
	wp_enqueue_script('select2', get_template_directory_uri() . "/plugins/select2/dist/js/select2" . SUFFIX . ".js", ['jquery-migrate'], '4.0.3', true);
	wp_add_inline_script('select2', "$('select').select2()");

	init_form();
}

function searchform_shortcode($atts, $content)
{
	global $product_search_form_index;
	init_searchform();

	if (empty($product_search_form_index)) {
		$product_search_form_index = 0;
	}
	return tsvet_render_file("woocommerce/product-searchform", null, [
		'countries' => [],
		'cities' => [],
		'index' => $product_search_form_index++,
		'content' => do_shortcode($content)
	]) ? : '';
}
add_shortcode('searchform', 'searchform_shortcode');

// [post title="title_post"]
function post_shortcode($atts)
{
	$post = shortcode_atts(['title' => ''], $atts);

	$page = get_page_by_title($post['title'], OBJECT, 'post');

	return $page->post_content ? : '';
}
add_shortcode('post', 'post_shortcode');

function category_shortcode($atts)
{
	$atts = shortcode_atts([
		'slug' => '',
		'post_template' => '',
		'number_posts' => 4,
		'order' => 'DESC'
	], $atts);
	$category = get_category_by_slug($atts['slug']);
	$params = [
		'posts_category' => get_posts([
			'category' => $category->cat_ID,
			'numberposts' => $atts['number_posts'],
			'order' => $atts['order']
		]),
	];
	if ($atts['post_template']) {
		return tsvet_render_file("template-parts/posts_category", $atts['post_template'], $params);
	}

	return tsvet_render_file("template-parts/posts_category", null, $params);
}
add_shortcode('category', 'category_shortcode');

function logo_shortcode($atts)
{
	$atts = shortcode_atts([
		'name' => '',
		'class' => '',
		'viewbox' => ''
	], $atts);
	if ($atts['name']) {
		$name = $atts['name'];
		unset($atts['name']);
		return tsvet_render_file("template-parts/logos/logo", $name, ['attributes' => $atts]);
	}

	return "<svg class=\"{$atts['class']}\" viewBox=\"{$atts['viewbox']}\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\">
    		<use href=\"#logo\"></use>
        </svg>";
}
add_shortcode('logo', 'logo_shortcode');

function init_vk_comments()
{
	wp_enqueue_script('vk_comments', "https://vk.com/js/api/openapi.js?154", [], null);
}
function vk_comments_shortcode($atts)
{
	add_action('wp_enqueue_scripts', 'init_vk_comments');
	return "<div id=\"vk_comments\"></div>" .
		"<script type=\"text/javascript\">
			window.onload = function () {
				VK.init({apiId: 6481404, onlyWidgets: true});
				VK.Widgets.Comments('vk_comments', {
	                limit: 10, 
	                attach: 'photo', 
	                autoPublish: 1
 				});
            }
        </script>";
}
add_shortcode('vk_comments', 'vk_comments_shortcode');

function gmaps_shortcode($atts)
{
	$atts = shortcode_atts([
		'lat' => '',
		'lng' => '',
		'zoom' => 17
	], $atts);
	if (!$atts['lat'] || !$atts['lng']) {
		return '';
	}

	wp_enqueue_script('gmaps', "https://maps.googleapis.com/maps/api/js?key=AIzaSyAzAvN4B9FEABFIHuC14r14IoF_OOqCPdA&callback=initMap", [], '', true);
	wp_add_inline_script('gmaps', "function initMap() {
		var myLatLng = {lat: {$atts['lat']}, lng: {$atts['lng']}};
		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: {$atts['zoom']},
			center: myLatLng
		});
		var marker = new google.maps.Marker({
			position: myLatLng,
			map: map
		});
	}", 'before');
	return "<div id=\"map\"></div>";
}
add_shortcode('gmaps', 'gmaps_shortcode');

function get_item_array($array, $item_name, $default)
{
	if (!empty($array) && isset($array) && isset($array[$item_name]) && !empty($array[$item_name]) && $array[$item_name]) {
		return $array[$item_name];
	}
	return $default;
}

function tsvet_get_query_var($name, $default, $name_array = '')
{
	if ($name_array != '') {
		return get_item_array($name_array, $name, $default);
	}
	if ($value = get_item_array($_POST, $name, false)) {
		return $value;
	}
	if ($value = get_item_array($_GET, $name, false)) {
		return $value;
	}
	return $default;
}

function request_url($url, $data, $source = '')
{
	$response = tsvet_remote_get($url, [
		'body' => $data,
		'source' => $source
	]);
	return json_decode($response instanceof WP_Error ? "" : $response['body'], true);
}

function tsvet_get_filters_itaka()
{
	$data = [
		'language' => 'ru',
		'date-from' => tsvet_get_query_var('date-from', date('Y-m-d'))
	];

	// children-age - даты рождения детей, '2016-06-01,2012-06-26'
	// dest-region - Куда
	// dep-region - Вылет из, список городов (alanya-province,antalya-province,belek-province) или название страны (turcja)

	foreach (['date-to', 'package-type', 'adults', 'children-age', 'dest-region', 'dep-region'] as $filter_name) {
		if ($value = tsvet_get_query_var($filter_name, '')) {
			$data[$filter_name] = $value;
		}
	}
	$response = request_url('https://www.itaka.pl/sipl-v2/data/filters', $data, 'itaka');
	wp_send_json_success($response['data']);
}
//add_action('wp_ajax_get_filters_itaka', 'tsvet_get_filters_itaka');
//add_action('wp_ajax_nopriv_get_filters_itaka', 'tsvet_get_filters_itaka');

function tsvet_search_itaka()
{
	$data = [
		'language' => 'ru',
		'package-type' => 'wczasy',
		'date-from' => tsvet_get_query_var('date-from', date('Y-m-d')),
		'page' => tsvet_get_query_var('page', date('1'))
	];

	// children-age - даты рождения детей, '2016-06-01,2012-06-26'
	// dest-region - Куда, список городов (alanya-province,antalya-province,belek-province) или название страны (turcja)
	// dep-region - Вылет из

	foreach (['date-to', 'adults', 'kids', 'children-age', 'dest-region', 'dep-region', 'country-to'] as $filter_name) {
		if ($value = tsvet_get_query_var($filter_name, false)) {
			$data[$filter_name] = $value;
		}
	}
	if (isset($data['country-to'])) {
		$data['dest-region'] .= $data['country-to'];
		unset($data['country-to']);
	}
	$response = request_url('https://www.itaka.pl/sipl-v2/data/holiday/search', $data, 'itaka');
	wp_send_json_success($response['data']);
}
add_action('wp_ajax_search_itaka', 'tsvet_search_itaka');
add_action('wp_ajax_nopriv_search_itaka', 'tsvet_search_itaka');

function get_filters_sletat()
{
	if (!$countries = get_option('countries_sletat')) {
		$countries = tsvet_remote_get('https://sletat.ru/Main.svc/GetCountries', ['source' => 'sletat']);
		$countries = json_decode($countries['body'], true)['GetCountriesResult']['Data']['aaData'];
		sleep(0.5);
		foreach ($countries as &$country) {
			$cities = tsvet_remote_get('https://module.sletat.ru/Main.svc/GetCities', [
				'body' => [
					'countryId' => $country['Id']
				],
				'source' => 'sletat'
			]);
			$country['cities'] = json_decode($cities['body'], true)['GetCitiesResult']['Data']['aaData'];
			sleep(rand(5, 10) / 10);
		}
		add_option('countries_sletat', $countries);
	}
}

/**
 * @param $handle
 */
function tsvet_http_api_curl(&$handle, $attr)
{
	$user_agent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36';
	if (isset($attr['source'])) {
		$referers = [
			'itaka' => 'https://www.itaka.pl/wyniki-wyszukiwania/wczasy',
			'sletat' => 'https://sletat.ru'
		];
		$referer = isset($referers[$attr['source']]) ? $referers[$attr['source']] : get_home_url();
		curl_setopt($handle, CURLOPT_REFERER, $referer);
	} else {
		curl_setopt($handle, CURLOPT_REFERER, get_home_url());
	}
	curl_setopt($handle, CURLOPT_USERAGENT, $user_agent);
}
add_action('http_api_curl', 'tsvet_http_api_curl', 10, 2);

function tsvet_mime_types($mimes)
{
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'tsvet_mime_types');

function tsvet_get_attachment_image_attributes($attr)
{
	if (isset($attr['class']) && strpos($attr['class'], 'attachment-shop_catalog') !== false) {
		$attr['class'] = "product__thumbnail";
	}
	return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'tsvet_get_attachment_image_attributes');

function tsvet_woocommerce_currency_symbols($symbols)
{
	$symbols['BYR'] = 'руб';
	return $symbols;
}
add_filter('woocommerce_currency_symbols', 'tsvet_woocommerce_currency_symbols');

function tsvet_formatted_woocommerce_price($price)
{
	return str_replace(".00", "", $price);
}
add_filter('formatted_woocommerce_price', 'tsvet_formatted_woocommerce_price');

function get_sletat_products()
{
	$page = tsvet_get_query_var('page', 1);

	$hash_request = md5("tours|sletat|" . date('d.m.Y'));
	$dir = __DIR__ . "/backups_tours/sletat";
	if (!is_dir($dir)) {
		mkdir($dir, '0777', true);
	}
	$file = $dir . "/" . $hash_request . ".json";
	$tours = [];
	$count_continue_countries = 0;
	if (file_exists($file)) {
		$tours = json_decode(file_get_contents($file), true);
		$count_continue_countries = $tours['count_continue_countries'];
		$tours = $tours['tours'];
	}
	if (count($tours) < $page * 12) {
		$countries = array_index(get_cities_sletat(), 'id', ["countryId"]);
		$index_country = 0;
		foreach ($countries as $country => $cities) {
			if ($index_country++ < $count_continue_countries) {
				continue;
			}
			$data = ['countryId' => $country];
			$tours_result = get_tours_sletat($data);
			$tours_result = isset($tours_result['aaData']) ? $tours_result['aaData'] : [];
			$_tours = [];
			foreach ($tours_result as $tour) {
				$_tours[] = [
					'hotelId' => $tour[3],
					'id' => $tour[68],
					'gallery' => '',
					'sale' => '',
					'image' => preg_replace("/\.(jpg|png)/", "_180_370.$1", $tour[29]),
					'price' => $tour[42],
					'title' => trim(preg_replace("/\s*[0-5]\*\s*/", "\s", $tour[7])),
					'url' => "/sletat/{$tour[0]}/{$tour[1]}",
				];
			}
			foreach (array_map(function ($tours_hotel) {
				$tours_hotel = array_values($tours_hotel);
				return $tours_hotel[0];
			}, array_index($_tours, 'id', ['hotelId'])) as $tour) {
				$tours[] = $tour;
			}
			if (count($tours) >= $page * 12) {
				break;
			}
		}
		file_put_contents($file, json_encode([
			'tours' => $tours,
			'count_continue_countries' => $index_country
		]));
	}
	return "<div class=\"woocommerce columns-4\"><ul class=\"products\">" . implode("\n", array_map(function ($tour) {
		return template_product($tour);
	}, array_slice($tours, 12 * ($page - 1), 12))) . "</ul></div>";
}
function template_product($product)
{
	$gallery_images = implode(',', $product['gallery']);
	$is_sale = isset($product['sale']) && $product['sale'] ? '<span class="onsale">Распродажа!</span>' : '';
	$sale = isset($product['sale']) && $product['sale'] ? ("<del><span class=\"woocommerce-Price-amount amount\">{$product['sale']}</span></del>") : '';
	$price = isset($product['price']) && $product['price'] ? ("<ins><span class=\"woocommerce-Price-amount amount\">{$product['price']}</span></ins>") : '';
	if (($pos = strpos($product['url'], "?")) !== false) {
		$product['url'] = substr($product['url'], 0, $pos);
	}
	return <<<HTML
		<li class="product type-product status-publish has-post-thumbnail first instock sale shipping-taxable purchasable product-type-simple">   					
            <div class="product__container-thumbnail">
		        <div class="product__buttons">
		            <a href="http://tsvet.local/product{$product['url']}" class="product__button woocommerce-LoopProduct-link woocommerce-loop-product__link">Узнать подробности</a>
		        </div>	
	
				{$is_sale}
				<img width="370" height="180" src="{$product['image']}" class="product__thumbnail wp-post-image" data-original="{$gallery_images}">
			</div>
            <a href="http://tsvet.local/product{$product['url']}" class="product__footer">
	            <h2 class="woocommerce-loop-product__title">{$product['title']}</h2>
				<span class="price">{$sale} {$price}</span>
			</a>
		</li>
HTML;
}
function get_itaka_products()
{
	$page = tsvet_get_query_var('page', 1);
	$response = request_url('https://www.itaka.pl/ru/strony/4466.php', [
		'package-type' => 'wczasy',
		'page' => ceil($page / 2),
		'currency' => 'PLN'
	], 'itaka');
	return "<div class=\"woocommerce columns-4\"><ul class=\"products\">" . implode("\n", array_map(function ($tour) {
		return template_product([
			'image' => "https://itaka.pl" . $tour['photo'],
			'gallery' => explode(',', $tour['searchGallery']),
			'sale' => intval(str_replace("&nbsp;", "", $tour['price'])),
			'price' => intval(str_replace("&nbsp;", "", $tour['ymaxPriceItakaHit'])),
			'url' => "/itaka" . $tour['url'],
			'title' => $tour['title']
		]);
	}, array_slice($response['results'], ($page % 2) * 12, 12))) . "</ul></div>";
}
function get_products()
{
	$turoperator = tsvet_get_query_var('turoperator', 'Tsvet');
	$content = $turoperator == "Itaka" ? get_itaka_products() : ($turoperator == "Sletat" ? get_sletat_products() : WC_Shortcodes::recent_products([
		'per_page' => 12
	]));
	wp_send_json_success([
		'content' => $content,
		'selector_container' => '.products'
	]);
}
add_action('wp_ajax_get_products', 'get_products');
add_action('wp_ajax_nopriv_get_products', 'get_products');

function search_products()
{
	$query_args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page' => 12,
		'orderby' => 'date',
		'order' => 'desc',
		'meta_query' => WC()->query->get_meta_query(),
		'tax_query' => WC()->query->get_tax_query(),
	);
	$products = get_posts($query_args);
	$result_products = [];
	$filter = [];

	$post = array_filter($_POST);

	foreach ($products as $product) {
		/* @var $product WP_Post */
		$is_added_product = true;
		foreach (['departure-date', 'adults'] as $taxonomy) {
			/* @var $terms WP_Term[] */
			$terms = wp_get_object_terms($product->ID, 'pa_' . $taxonomy);
			$name_term = $terms[0]->name;
			if ($taxonomy == 'departure-date') {
				$post[$taxonomy] = strtotime($post[$taxonomy]);
				$name_term = strtotime($name_term);
			}
			if (isset($post[$taxonomy]) && $post[$taxonomy] > $name_term) {
				$is_added_product = false;
				break;
			}
		}
		$terms = wp_get_object_terms($product->ID, 'pa_arrival-date');
		if ($is_added_product && (!isset($post['arrival-date']) || strtotime($post['arrival-date']) >= strtotime($terms[0]->name))) {
			$result_products[] = $product;
			foreach (['country', 'city-of-departure'] as $taxonomy) {
				$terms = wp_get_object_terms($product->ID, 'pa_' . $taxonomy);
				if (!isset($filter[$taxonomy])) {
					$filter[$taxonomy] = [];
				}
				if (!in_array($terms[0]->name, $filter[$taxonomy])) {
					$filter[$taxonomy][] = $terms[0]->name;
				}
			}
		}
	}
	wp_send_json_success([
		'content' => $result_products,
		'selector_container' => '.products',
		'filter' => $filter
	]);
}
add_action('wp_ajax_search_products', 'search_products');
add_action('wp_ajax_nopriv_search_products', 'search_products');

function itaka_query()
{
	$url_itaka = [
		'strony' => 'strony' . (isset($_GET['_page']) ? ('/' . $_GET['_page'] . '.php') : ''),
		'ekskursii' => 'ekskursii/rezultaty-poiska',
		'citybreak' => 'citybreak/rezultaty-poiska',
		'gornolyzhnyj-otdyh' => 'gornolyzhnyj-otdyh/rezultaty-poiska',
		'otdyh' => 'otdyh/rezultaty-poiska'
	][$_GET['url']];
	foreach (['action', 'url', '_page'] as $prop_name) {
		if (isset($_GET[$prop_name])) {
			unset($_GET[$prop_name]);
		}
	}

	$request = request_url("https://www.itaka.pl/ru/{$url_itaka}", array_filter($_GET, function ($attr) {
		return is_array($attr) ? array_filter($attr) : !!$attr;
	}), 'itaka');

	//SIPL-V2 REQUEST BEGIN
	// $itakaSipl2Data = [
	// 	''
	// ]
	// $itakaRequest = request_url("https://www.itaka.pl/sipl-v2/data/search-results/search")
	//SIPL-V2 REQUEST END

	if (isset($_GET['departures'])
		&& is_array($_GET['departures'])
		&& count(array_filter($_GET['departures'], function ($departure) {
		return strpos($departure, ',') !== false;
	})) > 0
		&& isset($request['results'])
		&& is_array($request['results'])
		&& count($request['results']) == 0) {
		$_GET['departures'] = array_map(function ($departure) {
			return implode(',', array_reverse(explode(',', $departure)));
		}, $_GET['departures']);
		$request = request_url("https://www.itaka.pl/ru/{$url_itaka}", array_filter($_GET, function ($attr) {
			return is_array($attr) ? array_filter($attr) : !!$attr;
		}), 'itaka');
	}
	if (isset($request['results']) && is_array($request['results'])) {
		foreach ($request['results'] as $key => $product) {
			$request['results'][$key]['photo'] = "https://www.itaka.pl" . $product['photo'];
			if (($pos = strpos($product['url'], "?")) !== false) {
				$product['url'] = substr($product['url'], 0, $pos);
			}
			$request['results'][$key]['url'] = "/product/itaka" . $product['url'];
		}
	}
	$request['source'] = 'itaka';
	wp_send_json_success($request);
}
add_action('wp_ajax_itaka_query', 'itaka_query');
add_action('wp_ajax_nopriv_itaka_query', 'itaka_query');

function get_tours_sletat(&$data)
{
	$data = array_merge([
//		'pageSize' => '24',
		'updateResult' => '1',

		'countryId' => '119',
		'cityFromId' => '832',
		'requestId' => '0',
		'visibleOperators' => '',
		's_hotelIsNotInStop' => 'true',
		's_hasTickets' => 'true',
		's_ticketsIncluded' => 'true',
		'jskey' => '1',
		'rtb' => 'true',
		'requestTimeout' => '0',
		's_showcase' => 'false',
		'groupBy' => 'all_sortedHotels',
		'calcFullPrice' => '1',
		'showHotelFacilities' => '1'
	], $data);

	$url_get_tours = 'https://module.sletat.ru/Main.svc/GetTours';
	$response = request_url($url_get_tours, $data, 'sletat');
	$data['requestId'] = isset($response['GetToursResult']['Data']) ? (isset($response['GetToursResult']['Data']['requestId']) ?
		$response['GetToursResult']['Data']['requestId'] : 0) : 0;
	$response = request_url($url_get_tours, $data, 'sletat');

	return isset($response['GetToursResult']['Data']) ?
		$response['GetToursResult']['Data'] : [];
}
function sletat_query()
{
	$page = intval(tsvet_get_query_var('currpage', 1));
	$date_from = new DateTime(tsvet_get_query_var('date_from', date('d-m-Y')));
	if (!$date_to = tsvet_get_query_var('date_to', '')) {
		$date_to = clone $date_from;
		$date_to->add(new DateInterval("P45D"));
	} else {
		$date_to = new DateTime($date_to);
	}
	$data = [
		'pageSize' => '24',
		's_departFrom' => $date_from->format("d/m/Y"),
		's_departTo' => $date_to->format("d/m/Y"),
		'pageNumber' => $page,
	];

	foreach ([
		'adults' => 's_adults',
		'departures' => 'cityFromId',
		'childs' => 's_kids',
		'child_age' => 's_kids_ages',
		'foods' => 'meals',
		'grade' => 'minHotelRating'
	] as $filter_name => $filter_param) {
		if ($value = tsvet_get_query_var($filter_name, '', $_GET)) {
			if (is_array($value)) {
				if (($value = array_filter($value)) && !empty($value)) {
					$data[$filter_param] = implode(',', $value);
				}
			} else {
				$data[$filter_param] = $value;
			}
		}
	}
	if ($destinations = tsvet_get_query_var('destinations', [], $_GET)) {
		$cities = array_index(get_cities_sletat(), 'id');
		$countries = get_countries_sletat();
		foreach ($destinations as $destination) {
			if (isset($cities[$destination])) {
				if (!isset($data['cities'])) {
					$data['cities'] = [];
				}
				$data['cities'][] = $destination;
			}
			if (isset($countries[$destination])) {
				$data['countryId'] = $destination;
			}
		}
		if (isset($data['cities'])) {
			$data['cities'] = implode(',', $data['cities']);
		}
	}
	if ($duration = tsvet_get_query_var('duration', '', $_GET)) {
		$durations = [
			'short' => [0, 5],
			'mid1' => [6, 9],
			'mid2' => [10, 12],
			'long' => [13, 0]
		];
		if (isset($durations[$duration])) {
			list($duration_min, $duration_max) = $durations[$duration];
			if ($duration_min !== 0) {
				$data['s_nightsMin'] = $duration_min;
			}
			if ($duration_max !== 0) {
				$data['s_nightsMax'] = $duration_max;
			}
		}
	}
	if ($standard = tsvet_get_query_var('standard', '', $_GET)) {
		$standards = [
			'3plus' => '402,403,404,405,410,411,406',
			'4plus' => '403,404,405,410,411,406',
			'5plus' => '404,405,410,411,406'
		];
		if (isset($standards[$standard])) {
			$data['stars'] = $standards[$standard];
		}
	}
	if ($price = tsvet_get_query_var('price', '', $_GET)) {
		$prices = [
			'upto2' => [0, 1000],
			'2to3' => [1000, 1600],
			'3to4' => [1600, 2100],
			'4andup' => [2100, 0]
		];
		if (isset($prices[$price])) {
			$exchange_rates = 0.0305; // Курс русского рубля
			list($price_min, $price_max) = $prices[$price];
			if ($price_min != 0) {
				$data['s_priceMin'] = $price_min * $exchange_rates;
			}
			if ($price_max != 0) {
				$data['s_priceMax'] = $price_max * $exchange_rates;
			}
		}
	}

	$tours_result = get_tours_sletat($data);

	$tours = isset($tours_result['aaData']) ? $tours_result['aaData'] : [];

	$facilities_data = $tours_result['hotelFacilitiesData'] ? $tours_result['hotelFacilitiesData'] : [];
	$facilities = array_custom_map(isset($facilities_data['facilities']) ? $facilities_data['facilities'] : [], 'id', 'name');
	$groups = array_custom_map(isset($facilities_data['groups']) ? $facilities_data['groups'] : [], 'id', 'name');
	$hotel_facilities = array_custom_map(isset($facilities_data['hotelFacilities']) ? $facilities_data['hotelFacilities'] : [], 'hotelId', 'facilities');
	$tours = array_index($tours, 68, [3]);
	$count = $tours_result['hotelsCount'] ? $tours_result['hotelsCount'] : 0;

	wp_send_json_success([
		'source' => 'sletat',
		'additionals' => ['interval' => ["5"]],
		'count' => $count,
		'has_more' => $page * 24 <= $count,
		'page' => $page,
		'priceTypeClass' => "person",
		'priceTypeLabel' => "Цена за человека",
		'sorter_debug' => [],
		'results' => array_map(function ($tours_hotel) use ($facilities, $groups, $hotel_facilities, $data) {
			array_custom_multisort($tours_hotel, [35, 42], [SORT_DESC, SORT_ASC], SORT_NUMERIC);
			$tour = array_values($tours_hotel)[0];
			$price = $tour[42];
			$date_from = strtotime($tour[12]);
			$date_to = strtotime($tour[13]);
			$days = ["Sun" => "Вс", "Mon" => "Пн", "Tue" => "Вт", "Wed" => "Ср", "Thu" => "Чт", "Fri" => "Пт", "Sat" => "Сб"];
			$hotel_facilities = isset($hotel_facilities[$tour[3]]) ? $hotel_facilities[$tour[3]] : [];
			return [
				'assetsList' => array_map(function ($group) {
					return $group['name'] . ": " . $group['value'];
				}, array_filter(array_map(function ($group_id, $group_name) use ($facilities, $hotel_facilities) {
					return [
						'name' => $group_name,
						'value' => implode(', ', array_map(function ($facility) use ($facilities) {
							return isset($facilities[$facility['facilityId']]) ? $facilities[$facility['facilityId']] : '';
						}, array_filter($hotel_facilities, function ($facility) use ($group_id) {
							return $facility['groupId'] == $group_id;
						})))
					];
				}, array_keys($groups), array_values($groups)), function ($group) {
					return $group['value'];
				})),
				'attributesList' => [],
				'dateFrom' => strtr(date("D d.m", $date_from), $days),
				'dateFromFull' => strtr(date("D d.m.Y", $date_from), $days),
				'dateToFull' => strtr(date("D d.m.Y", $date_to), $days),
				'depName' => $tour[33],
				'departure' => $tour[33],
				'departureName' => $tour[33],
				'destination' => $tour[31] . " \ " . $tour[19],
				'duration' => $tour[14],
				'isPromotionPriceAvailable' => false,
				'mostPopular' => "",
				'offerId' => $tour[3],
				'ownDeparture' => false,
				'percentItakaHit' => 0,
				'photo' => preg_replace("/\.(jpg|png)/", "_180_360.$1", $tour[29]),
				'price' => $price,
				'productCode' => $tour[0],
				'promotionIcon' => [],
				'rating' => round($tour[35], 1),
				'searchGallery' => "",
				'showOpinionsProjectIframe' => true,
				'stars' => "<span class=\"stars star" . (intval($tour[8]) * 10) . "\"></span>",
				'startDate' => date("Ymd", $date_from),
				'title' => trim(preg_replace("/\s*[0-5]\*\s*/", "\s", $tour[7])),
				'url' => "/product/sletat/{$tour[0]}/{$tour[1]}",
				'ymaxPriceItakaHit' => $price,
			];
		}, array_values($tours))
	]);
}
add_action('wp_ajax_sletat_query', 'sletat_query');
add_action('wp_ajax_nopriv_sletat_query', 'sletat_query');

function tsvet_query()
{
	global $wp_query;

	$get = array_filter($_GET, function ($attr) {
		return is_array($attr) ? array_filter($attr) : !!$attr;
	});
	$query_args = [
		'post_type' => 'product',
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'desc',
		'numberposts' => -1
	];

	$not_posts = $wp_query->query([
		'post_type' => 'product',
		'name' => 'temp'
	]);
	if (count($not_posts) > 0) {
		$query_args['post__not_in'] = array_map(function ($post) {
			return $post->ID;
		}, $not_posts);
	}

	$is_sort_date = false;
	$sort_date_duration = 'asc';
	if (isset($get['order']) && $get['order']) {
		$get['order'] = explode("|", $get['order']);
		if ($get['order'][0] == 'name') {
			$query_args['orderby'] = 'title';
			$query_args['order'] = $get['order'][1];
		}
		if ($get['order'][0] == 'price') {
			$query_args['meta_key'] = '_price';
			$query_args['orderby'] = 'meta_value';
			$query_args['order'] = $get['order'][1];
		}
		if ($get['order'][0] == 'date') {
			$is_sort_date = true;
			$sort_date_duration = $get['order'][1] == 'asc' ? SORT_ASC : SORT_DESC;
		}
	}

	if (isset($get['price']) && $get['price']) {
		$prices = [
			'upto2' => [0, 1000],
			'2to3' => [1000, 1600],
			'3to4' => [1600, 2100],
			'4andup' => [2100, 0]
		];
		if (isset($prices[$get['price']])) {
			$price = $prices[$get['price']];
			$query_args['meta_query'] = [];
			if ($price[0] != 0) {
				$query_args['meta_query'][] = [
					'key' => '_price',
					'value' => $price[0],
					'compare' => '>='
				];
			}
			if ($price[1] != 0) {
				$query_args['meta_query'][] = [
					'key' => '_price',
					'value' => $price[1],
					'compare' => '<='
				];
			}
		}
	}

	$productsEmptyCountry = [];
//	unset($get['date_from']);
	$products = array_filter(get_posts($query_args), function ($product) use ($get, &$productsEmptyCountry) {
		$product->date_from = strtotime(date('d.m.Y'));
		/* @var $terms WP_Term[] */
		$terms = wp_get_object_terms($product->ID, 'pa_terms_and_prices');

		if ($terms && count($terms) > 0) {
			$result = false;
			$dates_for_min = [];
			foreach ($terms as $term) {
				$terms_and_prices = explode("_", $term->name);
				$dates = explode("-", $terms_and_prices[0]);
				if (isset($get['date_from']) && $get['date_from']) {
					$get['date_from'] = strtotime($get['date_from']);
					if ($get['date_from'] > strtotime($dates[0])) {
						$result = true;
						break;
					}
				}
				if (isset($get['date_to']) && $get['date_to']) {
					$get['date_to'] = strtotime($get['date_to']);
					if ($get['date_to'] < strtotime($dates[1])) {
						$result = true;
						break;
					}
				}
				$dates_for_min[] = strtotime($dates[0]);
			}
			if ($result) {
				return false;
			}
			$product->date_from = min($dates_for_min);
		}

		foreach ([
			'destinations' => 'pa_country',
			'departures' => 'pa_city-of-departure',
			'foods' => 'pa_foods'
		] as $prop => $taxonomy) {
			$product->{$prop} = [];
			/* @var $terms WP_Term[] */
			$terms = wp_get_object_terms($product->ID, $taxonomy);
			if ($terms && count($terms) > 0) {
				if (isset($get[$prop]) && $get[$prop]) {
					$props = array_map(function ($term) {
						return str_replace(",", "_", trim(strtolower($term), ","));
					}, is_array($get[$prop]) ? $get[$prop] : explode(',', $get[$prop]));
					$terms = array_filter($terms, function ($term) use ($props) {
						return in_array(urldecode($term->slug), $props);
					});
					if (count($terms) == 0) {
						return false;
					}
				}
				$product->{$prop} = array_map(function ($term) {
					return [
						'slug' => urldecode($term->slug),
						'name' => urldecode($term->name),
					];
				}, $terms);
			}
			if ($taxonomy == 'pa_country' && count($terms) == 0) {
				$productsEmptyCountry[] = $product->post_title;
			}
		}

		foreach ([
			'adults' => 'pa_adults',
			'childs' => 'pa_childs',
			'standard' => 'pa_standard'
		] as $prop => $taxonomy) {
			$product->{$prop} = '';
			/* @var $terms WP_Term[] */
			$terms = wp_get_object_terms($product->ID, $taxonomy);
			if ($terms && count($terms) > 0 && !($terms instanceof WP_Error)) {
				if (isset($get[$prop]) && $get[$prop] && intval($get[$prop]) > intval($terms[0]->slug)) {
					return false;
				}
				$product->{$prop} = $terms[0]->slug;
			}
		}

		return true;
	});

	if ($is_sort_date) {
		array_custom_multisort($products, 'date_from', $sort_date_duration);
	}

	$paged = isset($get['currpage']) && $get['currpage'] ? : 1;


	wp_send_json_success([
		'source' => 'tsvet',
		'additionals' => ['interval' => ["5"]],
		'count' => count($products),
		'has_more' => true,
		'page' => $paged,
		'priceTypeClass' => "person",
		'priceTypeLabel' => "Цена за человека",
		'sorter_debug' => [],
		'productsEmptyCountry' => $productsEmptyCountry,
		'results' => array_map(function ($product) {
			/* @var $product WP_Post */

			$price = get_post_meta($product->ID, '_regular_price', true);
			$sale_price = get_post_meta($product->ID, '_sale_price', true);
			$rating = get_post_meta($product->ID, '_wc_average_rating', true);
			$thumbnail_id = get_post_thumbnail_id($product->ID);
			$image = wp_get_attachment_image_src($thumbnail_id, 'shop_single', false);
			$days = ["Sun" => "Вс", "Mon" => "Пн", "Tue" => "Вт", "Wed" => "Ср", "Thu" => "Чт", "Fri" => "Пт", "Sat" => "Сб"];
			return [
				'assetsList' => array_filter(explode("\r\n", $product->post_excerpt)),
				'attributesList' => [],
				'dateFrom' => strtr(date("D d.m", $product->date_from), $days),
				'dateFromFull' => strtr(date("D d.m.Y", $product->date_from), $days),
				'dateToFull' => strtr(date("D d.m.Y", $product->date_to), $days),
				'depName' => count($product->departures) > 0 ? $product->departures[0]['slug'] : '',
				'departure' => count($product->departures) > 0 ? $product->departures[0]['name'] : '',
				'departureName' => count($product->departures) > 0 ? $product->departures[0]['slug'] : '',
				'destination' => count($product->destinations) > 0 ? $product->destinations[0]['name'] : '',
				'duration' => ($product->date_to - $product->date_from) / 60 / 60 / 24,
				'isPromotionPriceAvailable' => !empty($sale_price),
				'mostPopular' => "",
				'offerId' => $product->ID,
				'ownDeparture' => false,
				'percentItakaHit' => round(10000 * (1 - ($sale_price ? : $price) / $price)) / 100,
				'photo' => $image ? $image[0] : "",
				'price' => $sale_price ? : $price,
				'productCode' => $product->ID,
				'promotionIcon' => [],
				'rating' => $rating,
				'searchGallery' => "",
				'showOpinionsProjectIframe' => true,
				'stars' => "",
				'startDate' => date("Ymd", $product->date_from),
				'title' => $product->post_title,
				'url' => get_post_permalink($product->ID),
				'ymaxPriceItakaHit' => $price,
			];
		}, array_slice($products, ($paged - 1) * 25, 25))
	]);
}
add_action('wp_ajax_tsvet_query', 'tsvet_query');
add_action('wp_ajax_nopriv_tsvet_query', 'tsvet_query');

function tsvet_woocommerce_product_query_tax_query($tax_query)
{
	$post = array_filter($_POST);
	foreach (['country', 'city-of-departure'] as $taxonomy) {
		if (isset($post[$taxonomy])) {
			$tax_query[] = [
				'taxonomy' => 'pa_' . $taxonomy,
				'field' => 'slug',
				'terms' => $post[$taxonomy]
			];
		}
	}
	return $tax_query;
}
add_filter('woocommerce_product_query_tax_query', 'tsvet_woocommerce_product_query_tax_query');

function tsvet_woocommerce_shortcode_products_query($query_args)
{
	global $wp_query;
	$_wp_query = clone $wp_query;
	$query_args['paged'] = isset($_POST['page']) && is_numeric($_POST['page']) ? $_POST['page'] : 1;
	$GLOBALS['post_last'] = get_post();
	$posts = $_wp_query->query([
		'post_type' => 'product',
		'name' => 'temp'
	]);
	if (count($posts) > 0) {
		$query_args['post__not_in'] = array_map(function ($post) {
			return $post->ID;
		}, $posts);
	}
	return $query_args;
}
add_filter('woocommerce_shortcode_products_query', 'tsvet_woocommerce_shortcode_products_query');
function tsvet_woocommerce_shortcode_after_recent_products_loop()
{
	global $wp_query;
	if (isset($GLOBALS['post_last'])) {
		$wp_query->post = $GLOBALS['post_last'];
	}
}
add_filter('woocommerce_shortcode_after_recent_products_loop', 'tsvet_woocommerce_shortcode_after_recent_products_loop');

function tsvet_woocommerce_template_loader_files($atts, $default_file)
{
	if (in_array($default_file, ["archive-product.php", "single-product.php"])) {
		init_form();
		wp_enqueue_script('underscore', get_template_directory_uri() . '/plugins/underscore/underscore' . SUFFIX . '.js', [], "1.8.3", true);
		//for($i = 1; $i <= 32; $i++) {
		//	$deps = ['datepicker', 'datepicker.standalone'];
		//	if($i > 1) {
		//		$deps[] = "archive-product-" . ($i - 1);
		//	}
		//	wp_enqueue_style("archive-product" . ($i < 32 ? "-{$i}" : ""), get_template_directory_uri() . "/css/SearchForm-{$i}.css", $deps, null);
		//}
		wp_enqueue_style("archive-product", get_template_directory_uri() . '/css/SearchForm' . SUFFIX . '.css', ['datepicker', 'datepicker.standalone'], null);
		wp_enqueue_script('archive-product', get_template_directory_uri() . '/js/SearchForm' . SUFFIX . '.js', ['underscore', 'datepicker', 'jScrollPane'], null, true);
	}
}
add_filter('woocommerce_template_loader_files', 'tsvet_woocommerce_template_loader_files', 10, 2);

function tsvet_rewrite_rules_array($rules)
{
	return array_merge([
		'product/(itaka|sletat)/(.*)/?$' => 'index.php?category_name=$matches[1]&product=$matches[2]',
		'country/([0-9]{1,2})/?$' => 'index.php?tax=pa_country&terms=$matches[1]',
		'country/([0-9]{1,2})/page/([0-9]{1,2})/?$' => 'index.php?tax=pa_country&terms=$matches[1]&paged=$matches[2]',

		'(tourType/1|tury/pljazhnye-tury)/?$' => 'index.php?product_cat=пляжный-отдых',
		'(tourType/2|tury/ekskursionnye)/?$' => 'index.php?product_cat=экскурсионные-туры',
		'(tourType/3|tury/shopping)/?$' => 'index.php?product_cat=шоппинг-туры',
		'(tourType/4|tury/gornolyzhnye)/?$' => 'index.php?product_cat=горнолыжные-туры',
		'(tourType/5|tury/iz-polshi-itaka)/?$' => 'index.php?product_cat=туры-из-польши-от-itaka',
		'(tourType/11|tury/morskie-kruizy)/?$' => 'index.php?product_cat=морские-круизы',
		'(tourType/20|transfer-iz-bresta-v-evropu)/?$' => 'index.php?product_cat=трансфер-из-бреста-в-европу',
		'(tourType/22|tury/ekskursionnye-tury/kaliningrad)/?$' => 'index.php?product_cat=экскурсионный-тур-в-калининград',
		'(tourType/23|agentskij-otdel)/?$' => 'index.php?name=агентский-отдел',
		'(tourType/14|tury/ekskursionnye/belarus)/?$' => 'index.php?product_cat=экскурсии-по-беларуси',

		'(tourType/1|tury/pljazhnye-tury)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=пляжный-отдых&paged=$matches[2]',
		'(tourType/2|tury/ekskursionnye)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=экскурсионные-туры&paged=$matches[2]',
		'(tourType/3|tury/shopping)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=шоппинг-туры&paged=$matches[2]',
		'(tourType/4|tury/gornolyzhnye)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=горнолыжные-туры&paged=$matches[2]',
		'(tourType/5|tury/iz-polshi-itaka)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=туры-из-польши-от-itaka&paged=$matches[2]',
		'(tourType/11|tury/morskie-kruizy)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=морские-круизы&paged=$matches[2]',
		'(tourType/20|transfer-iz-bresta-v-evropu)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=трансфер-из-бреста-в-европу&paged=$matches[2]',
		'(tourType/22|tury/ekskursionnye-tury/kaliningrad)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=экскурсионный-тур-в-калининград&paged=$matches[2]',
		'(tourType/23|agentskij-otdel)/page/([0-9]{1,2})/?$' => 'index.php?name=агентский-отдел&paged=$matches[2]',
		'(tourType/14|tury/ekskursionnye/belarus)/page/([0-9]{1,2})/?$' => 'index.php?product_cat=экскурсии-по-беларуси&paged=$matches[2]',

		'(tour/146|tury/ekskursionnyj-tur-v-kaliningrad)/?$' => 'index.php?product=туры-в-калининград',
		'(tour/169|tury/andusalija-pljazhnyj-otdyh-itaka)/?$' => 'index.php?product=пляжный-отдых-от-итаки-в-андалусии',
		'(tour/23|tury/pljazhnye-tury-sharm-el-shejh-itaka)/?$' => 'index.php?product=пляжный-отдых-от-итаки-в-шарм-эль-шейхе',
		'(tour/27|tury/otdyh-v-chernogorii-itaka)/?$' => 'index.php?product=отдых-в-черногории-от-итака',
		'(tour/29|tury/otdyh-v-horvatii-itaka)/?$' => 'index.php?product=отдых-в-хорватии-от-итака',
		'(tour/32|tury/otdyh-v-dominikane-itaka)/?$' => 'index.php?product=отдых-в-доминикане-от-itaka',
		'(tour/60|tury/otdyh-na-madejre-itaka)/?$' => 'index.php?product=отдых-на-мадейре-от-itaka',
		'(tour/22|tury/otdyh-grecija-itaka)/?$' => 'index.php?product=отдых-в-греции-от-itaka',
		'(tour/24|tury/otdyh-turcija-itaka)/?$' => 'index.php?product=отдых-в-турции-от-itaka',
		'(tour/25|tury/otdyh-italija-itaka)/?$' => 'index.php?product=отдых-в-италии-от-itaka',
		'(tour/52|tury/otdyh-bolgarija-itaka)/?$' => 'index.php?product=отдых-в-болгарии-от-itaka',
		'(tour/134|tury/pljazhnyj-otdyh-krit)/?$' => 'index.php?product=пляжный-отдых-от-итаки-на-о-крит',
		'(tour/136|tury/pljazhnyj-otdyh-kofu-itaka)/?$' => 'index.php?product=пляжный-отдых-от-итаки-на-о-корфу',
		'(tour/137|tury/pljazhnyj-otdyh-kosta-dorada-itaka)/?$' => 'index.php?product=пляжный-отдых-от-итаки-на-коста-дорада',
		'(tour/138|tury/pljazhnyj-otdyh-zakinf-itaka)/?$' => 'index.php?product=пляжный-отдых-от-итаки-на-о-закинф',
		'(tour/152|tury/kanarskiee-ostrova-itaka)/?$' => 'index.php?product=канарские-острова-от-itaka',
		'(tour/168|tury/pljazhnyj-otdyh-itaka-majorka)/?$' => 'index.php?product=пляжный-отдых-от-итаки-на-майорке',
		'(tour/174|tury/pljazhnyj-otdyh-rodos-itaka)/?$' => 'index.php?product=пляжный-отдых-от-итаки-на-о-родос',

		"tury/ispanija/?$" => "index.php?tax=pa_country&terms=8",
		"tury/grecija/?$" => "index.php?tax=pa_country&terms=11",
		"tury/dominikana/?$" => "index.php?tax=pa_country&terms=17",
		"tury/italija/?$" => "index.php?tax=pa_country&terms=10",
		"tury/turcija/?$" => "index.php?tax=pa_country&terms=9",
		"tury/tailand/?$" => "index.php?tax=pa_country&terms=29",
		"tury/izrail/?$" => "index.php?tax=pa_country&terms=36",
		"tury/bolgarija/?$" => "index.php?tax=pa_country&terms=18",
		"tury/maldivy/?$" => "index.php?tax=pa_country&terms=55",
		"tury/kipr/?$" => "index.php?tax=pa_country&terms=14",
		"tury/marokko/?$" => "index.php?tax=pa_country&terms=12",
		"tury/francija/?$" => "index.php?tax=pa_country&terms=52",
		"tury/polsha/?$" => "index.php?tax=pa_country&terms=1",
		"tury/egipet/?$" => "index.php?tax=pa_country&terms=7",
		"tury/portugalija/?$" => "index.php?tax=pa_country&terms=16",
		"tury/germanija/?$" => "index.php?tax=pa_country&terms=47",
		"tury/kuba/?$" => "index.php?tax=pa_country&terms=45",
		"tury/rossija/?$" => "index.php?tax=pa_country&terms=53",
		"tury/kabo-verde/?$" => "index.php?tax=pa_country&terms=49",
		"tury/ispanija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=8&paged=$matches[1]',
		"tury/grecija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=11&paged=$matches[1]',
		"tury/dominikana/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=17&paged=$matches[1]',
		"tury/italija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=10&paged=$matches[1]',
		"tury/turcija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=9&paged=$matches[1]',
		"tury/tailand/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=29&paged=$matches[1]',
		"tury/izrail/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=36&paged=$matches[1]',
		"tury/bolgarija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=18&paged=$matches[1]',
		"tury/maldivy/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=55&paged=$matches[1]',
		"tury/kipr/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=14&paged=$matches[1]',
		"tury/marokko/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=12&paged=$matches[1]',
		"tury/francija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=52&paged=$matches[1]',
		"tury/polsha/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=1&paged=$matches[1]',
		"tury/egipet/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=7&paged=$matches[1]',
		"tury/portugalija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=16&paged=$matches[1]',
		"tury/germanija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=47&paged=$matches[1]',
		"tury/kuba/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=45&paged=$matches[1]',
		"tury/rossija/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=53&paged=$matches[1]',
		"tury/kabo-verde/page/([0-9]{1,2})/?$" => 'index.php?tax=pa_country&terms=49&paged=$matches[1]',
	], $rules);
}
add_filter("rewrite_rules_array", "tsvet_rewrite_rules_array");
function tsvet_redirect_canonical($redirect_url, $requested_url)
{
	if ($requested_url[strlen($requested_url) - 1] == '?') {
		return;
	}
	return $redirect_url;
}
add_filter("redirect_canonical", "tsvet_redirect_canonical", 10, 2);
function dq_override_post_title($title)
{
	if ($title['title'] == 'Туры' && (strpos($_SERVER['REQUEST_URI'], "/country") !== false
		|| strpos($_SERVER['REQUEST_URI'], "/tour") !== false
		|| strpos($_SERVER['REQUEST_URI'], "/tury") !== false)
		|| strpos($_SERVER['REQUEST_URI'], "/agentskij-otdel") !== false) {
		$title['title'] = woocommerce_page_title(false);
	}
	return $title;
}
add_filter('document_title_parts', 'dq_override_post_title');

function tsvet_query_vars($public_query_vars)
{
	$public_query_vars[] = "tax";
	$public_query_vars[] = "terms";
	return $public_query_vars;
}
add_filter("query_vars", "tsvet_query_vars");

function tsvet_request($query_vars)
{
	if (isset($query_vars['tax'])) {
		$query_vars['taxonomy'] = $query_vars["tax"];
		unset($query_vars["tax"]);
	}

	return $query_vars;
}
add_filter("request", "tsvet_request");

function node_has_class($node, $class)
{
	return (isset($node->attr) && isset($node->attr['class']) && strpos(' ' . $node->attr['class'] . ' ', ' ' . $class . ' ') !== false);
}

function tsvet_remote_get($request_url, $data)
{
	$number_request = 0;
//	for ($i = 1800; $i <= 5000; $i++) {
//		$request = wp_remote_get("https://www.itaka.pl/ru/strony/{$i}.php?code=CFUPALO", []);
//		if($i % 300 == 0) {
//			sleep(1);
//		}
//		if(!($request instanceof WP_Error) && json_decode($request['body'], true)) {
//			echo "<a href=\"https://www.itaka.pl/ru/strony/{$i}.php?code=CFUPALO\">https://www.itaka.pl/ru/strony/{$i}.php?code=CFUPALO</a><br>";
//		}
//	}
	do {
		if ($number_request > 0) {
			usleep(rand(1, 3) * 100000);
		}
		$request = wp_remote_get($request_url, $data);
	} while (++$number_request < 5 && $request instanceof WP_Error);
	return $request;
}
function cache_post_meta($post_id, $key_meta, $value_meta)
{
	if (!isset($GLOBALS['posts_meta'])) {
		$GLOBALS['posts_meta'] = [];
	}
	if (!isset($GLOBALS['posts_meta'][$post_id])) {
		$GLOBALS['posts_meta'][$post_id] = [];
	}
	$GLOBALS['posts_meta'][$post_id][$key_meta] = $value_meta;
}

/**
 * @param $post_id
 * @param $key_meta
 * @param string|array|integer $default
 * @param bool $single
 *
 * @return string|array|integer
 */
function get_cache_post_meta($post_id, $key_meta, $default = '', $single = true)
{
	if (isset($GLOBALS['posts_meta']) && isset($GLOBALS['posts_meta'][$post_id]) && isset($GLOBALS['posts_meta'][$post_id][$key_meta])) {
		return $GLOBALS['posts_meta'][$post_id][$key_meta];
	}
	if ($result = get_post_meta($post_id, $key_meta, !is_array($default))) {
		return $result;
	}
	/* @var $terms WP_Term[] */
	$terms = wp_get_object_terms($post_id, 'pa_' . $key_meta);
	if ($terms && !($terms instanceof WP_Error) && count($terms) > 0) {
		$terms = array_map(function ($term) {
			return [
				'key' => urldecode($term->slug),
				'value' => urldecode($term->name)
			];
		}, $terms);
		return !is_array($default) ? (empty($terms) ? '' : $terms[0]['value']) : array_custom_map($terms, 'key', 'value');
	}
	return $default;
}

/**
 * @param $html simple_html_dom
 * @param $name
 *
 * @return array
 */
function get_options_select_by_name($html, $name)
{
	return array_custom_map(array_map(function ($option) {
		/* @var $option simple_html_dom_node */
		return [
			'key' => $option->getAttribute('value'),
			'value' => trim($option->innertext)
		];
	}, $html->find("select[name=\"{$name}\"] option")), 'key', 'value');
}

/**
 * @param $html simple_html_dom_node[]
 * @param $attribute
 * @param string $default
 *
 * @return string
 */
function get_attribute_html($html, $attribute, $default = '')
{
	if (count($html) > 0) {
		return $html[0]->getAttribute($attribute);
	}
	return $default;
}
/**
 * @param $html simple_html_dom_node[]
 * @param string $default
 *
 * @return string
 */
function get_innertext_html($html, $default = '')
{
	if (count($html) > 0) {
		return $html[0]->innertext;
	}
	return $default;
}
function get_sletat_tour_params(&$product, $text_json)
{
	$json = json_decode($text_json, true);
	$tour = isset($json['ActualizePriceResult']['Data']) ? (isset($json['ActualizePriceResult']['Data']['data']) ?
		$json['ActualizePriceResult']['Data']['data'] : []) : [];

	if (empty($tour)) {
		return get_default_params_tout();
	}

	$product->post_title = $tour[6];
	$product->post_excerpt = "";
	$params_content = [
		'what_price_tour_include' => array_map(function ($resource) {
			return mb_strtolower($resource['name']);
		}, isset($json['ActualizePriceResult']['Data']['resources']) ? (isset($json['ActualizePriceResult']['Data']['resources']) ?
			$json['ActualizePriceResult']['Data']['resources'] : []) : []),
	];

	$gallery = [];
	if ($tour[32]) {
		for ($i = 0; $i < $tour[45]; $i++) {
			$gallery[] = "http://hotels.sletat.ru/i/im/{$tour[32]}_{$i}_870_435.jpg";
		}
		$hotel_info = request_url("https://module.sletat.ru/Main.svc/GetHotelInfo", ['hotelId' => $tour[32]], 'sletat');
		$hotel_info = isset($hotel_info['GetHotelInfoResult']['Data']) ?
			$hotel_info['GetHotelInfoResult']['Data'] : [];

		$params_content['hotel_services'] = "<div class=\"list\">" . implode('', array_map(function ($index, $hotel_service) {
			return "<div class=\"list__item\">
				<span style=\"font-weight: 700;\">" . $hotel_service['Name'] . "</span>: " .
				implode(", ", array_map(function ($facility) {
				return $facility['Name'];
			}, $hotel_service['Facilities'])) .
				"</div>";
		}, array_keys($hotel_info['HotelFacilities']), $hotel_info['HotelFacilities'])) . "</div>";
	}
	$product->post_content = tsvet_render_file('template-parts/post/info-tour', '', $params_content)
		. "[gmaps lat=\"{$hotel_info['Latitude']}\" lng=\"{$hotel_info['Longitude']}\"]";
	return array_merge(get_default_params_tout(), [
		'_product_image_gallery' => $gallery,
		'category_name' => 'sletat',
		'_wc_average_rating' => $tour[48],
		'stars' => intval($tour[35]),
		'adults' => $tour[53],
		'childs' => $tour[54],
		'rooms' => $tour[50],
		'foods' => $tour[49],
		'city-of-departure' => $tour[1],
		'country' => $tour[0],
		'region' => $tour[2],
		'terms_and_prices' => [$tour[4] . " - " . $tour[10]],
		'_regular_price' => $tour[18]
	]);
}
function get_itaka_tour_params(&$product, $text_html)
{
	$html = new simple_html_dom($text_html);
	$map = $html->find('.car-map');
	$product->post_title = get_attribute_html($html->find('#product-code h2'), 'data-product-name');
	$product->post_excerpt = implode("\r\n", array_map(function ($node) {
		return $node->innertext;
	}, $html->find('#product-tab-overall .event-assetsandopinion .event-assets ul li')));
	$product->post_content = "<div class=\"product-productdescription\">" . get_innertext_html($html->find('#product-tab-productdescription')) . "</div>";
	if (count($map) > 0) {
		$lat = get_attribute_html($map, 'data-mapcoordinateslat');
		$lng = get_attribute_html($map, 'data-mapcoordinateslng');
		$product->post_content .= "[gmaps lat=\"{$lat}\" lng=\"{$lng}\"]";
	}

	return array_merge(get_default_params_tout(), [
		'_product_image_gallery' => array_map(function ($node) {
			/* @var $node simple_html_dom_node */
			return $node->getAttribute('data-original');
		}, $html->find('#gallery .slides img')),
		'category_name' => 'itaka',
		'_wc_average_rating' => str_replace(',', '.', get_innertext_html($html->find('#gallery .event-opinion-flag strong'))),
		'stars' => preg_match("/star(\d+)/", get_attribute_html($html->find('#product-code h2 .stars'), 'class'), $matched) ? round($matched[1] / 10) : 0,
		'_sku' => get_attribute_html($html->find('#product-code'), 'data-product-code'),
		'country' => ($destination = explode('\\', get_innertext_html($html->find("#product-code .destination-title")))) ? $destination[0] : '',
		'region' => isset($destination[1]) ? $destination[1] : '',
		'_regular_price' => str_replace("&nbsp;", "", get_innertext_html($html->find('[data-js-value="ymaxPriceItakaHit"]'))),
		'_sale_price' => str_replace("&nbsp;", "", get_innertext_html($html->find('[data-js-value="offerPrice"]'))),
		'percent_rating' => get_innertext_html($html->find('.good-opinion-percentage strong')),

		'adults' => count($html->find('select[name="adults"] option')),
		'childs' => count($html->find('select[name="childs"] option')),
		'rooms' => get_options_select_by_name($html, 'room'),
		'foods' => get_options_select_by_name($html, 'food'),
		'city-of-departure' => get_options_select_by_name($html, 'departure'),
		'terms_and_prices' => array_custom_map(array_map(function ($option) use ($html) {
			/* @var $option simple_html_dom_node */
			$key = $option->getAttribute('value');

			$date_from = get_attribute_html([$option], 'data-offerdate-formatted', date('d.m.Y'));
			$date_to = get_attribute_html([$option], 'data-returndate-formatted', date('d.m.Y'));
			return [
				'key' => $key,
				'value' => $date_from . "-" . $date_to
			];
		}, $html->find("select[name=\"ofr_id\"] option")), 'key', 'value'),
	]);
}
/**
 * @param $posts WP_Post[]
 *
 * @return array
 */
function tsvet_the_posts($posts)
{
	global $wp_query, $wp_object_cache;
	if (!$wp_query->post) {
		if (isset($wp_query->query_vars['category_name'])
			&& in_array($wp_query->query_vars['category_name'], ['itaka', 'sletat'])) {
			$category_name = $wp_query->query_vars['category_name'];
			$request_url = isset($wp_query->query_vars['product']) ? [
				'itaka' => "https://www.itaka.pl/{$wp_query->query_vars['product']}",
				'sletat' => "https://module.sletat.ru/Main.svc/ActualizePrice?" . preg_replace("/(\d+)\/(\d+)/", "offerId=$1&sourceId=$2", $wp_query->query_vars['product'])
			][$category_name] : "";
			$request_url = trim($request_url, "/");

			$posts = $wp_query->query([
				'post_type' => 'product',
				'name' => 'temp'
			]);
			if (empty($posts)) {
				add_filter('wp_insert_post_empty_content', '__return_false', 1000);
				$post_id = wp_insert_post([
					'post_category' => $category_name,
					'post_type' => 'product',
					'post_status' => 'publish',
					'post_name' => 'temp',
					'post_date_gmt' => date('Y-m-d H:i:s'),
					'post_modified_gmt' => date('Y-m-d H:i:s')
				]);
				remove_filter('wp_insert_post_empty_content', '__return_false', 1000);
				$posts = [get_post($post_id)];
			}

			if ($request_url) {
				$hash_request = md5($request_url);
				$dir = __DIR__ . "/backups_tours/{$category_name}";
				if (!is_dir($dir)) {
					mkdir($dir, '0777', true);
				}
				$file = $dir . "/" . $hash_request . ".json";
				if (false && file_exists($file)) {
					$params = json_decode(file_get_contents($file), true);
					foreach (['post_title', 'post_excerpt', 'post_content'] as $key) {
						if (isset($params[$key])) {
							$posts[0]->$key = $params[$key];
							unset($params[$key]);
						}
					}
				} else {
					$request = tsvet_remote_get($request_url, [
						'source' => $category_name
					]);
					$params = [];
					if (is_array($request) && isset($request['body'])) {
						$params = $category_name == 'itaka' ? get_itaka_tour_params($posts[0], $request['body']) :
							get_sletat_tour_params($posts[0], $request['body']);
						file_put_contents($file, json_encode(array_merge($params, [
							'post_title' => $posts[0]->post_title,
							'post_excerpt' => $posts[0]->post_excerpt,
							'post_content' => $posts[0]->post_content,
						])));
					}
				}

				foreach ($params as $param => $value) {
					cache_post_meta($posts[0]->ID, $param, $value);
				}
			}
		} else if (isset($wp_query->query_vars['taxonomy']) && $wp_query->query_vars['taxonomy'] == 'pa_country') {
			$posts = $wp_query->query([
				'post_type' => 'product',
				'taxonomy' => 'pa_country',
				'term' => $wp_query->query_vars['terms'],
				'paged' => $wp_query->query_vars['paged'],
				'suppress_filters' => true
			]);
		}
		if (!empty($posts)) {
			$wp_query->post = $posts[0];
			$GLOBALS['post'] = $posts[0];
			$wp_object_cache->replace($wp_query->post->ID, $wp_query->post, 'posts');
			return $posts;
		}
	}
	return $posts;
}
add_filter("the_posts", "tsvet_the_posts");

function tsvet_after_main_content()
{
	wp_enqueue_script('lazyload', get_template_directory_uri() . '/js/jquery.lazyload' . SUFFIX . '.js', [], null, true);
	wp_enqueue_script('magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup' . SUFFIX . '.js', [], null, true);
	wp_enqueue_script('gallery', get_template_directory_uri() . '/js/gallery' . SUFFIX . '.js', ['flexslider', 'lazyload', 'magnific-popup'], null, true);
}
add_action('woocommerce_after_main_content', 'tsvet_after_main_content');

remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);







function array_get_value($array, $key, $default = null)
{
	if ($key instanceof \Closure) {
		return $key($array, $default);
	}
	if (is_array($key)) {
		$lastKey = array_pop($key);
		foreach ($key as $keyPart) {
			$array = array_get_value($array, $keyPart);
		}
		$key = $lastKey;
	}
	if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
		return $array[$key];
	}
	if (($pos = strrpos($key, '.')) !== false) {
		$array = array_get_value($array, substr($key, 0, $pos), $default);
		$key = substr($key, $pos + 1);
	}
	if (is_object($array)) {
		// this is expected to fail if the property does not exist, or __get() is not implemented
		// it is not reliably possible to check whether a property is accessible beforehand
		return $array->$key;
	} elseif (is_array($array)) {
		return (isset($array[$key]) || array_key_exists($key, $array)) ? $array[$key] : $default;
	}
	return $default;
}
function array_get_column($array, $name, $keepKeys = true)
{
	$result = [];
	if ($keepKeys) {
		foreach ($array as $k => $element) {
			$result[$k] = array_get_value($element, $name);
		}
	} else {
		foreach ($array as $element) {
			$result[] = array_get_value($element, $name);
		}
	}
	return $result;
}

function array_custom_multisort(&$array, $key, $direction = SORT_ASC, $sortFlag = SORT_REGULAR)
{
	$keys = is_array($key) ? $key : [$key];
	if (empty($keys) || empty($array)) {
		return;
	}
	$n = count($keys);
	if (is_scalar($direction)) {
		$direction = array_fill(0, $n, $direction);
	} elseif (count($direction) !== $n) {
		return;
	}
	if (is_scalar($sortFlag)) {
		$sortFlag = array_fill(0, $n, $sortFlag);
	} elseif (count($sortFlag) !== $n) {
		return;
	}
	$args = [];
	foreach ($keys as $i => $key) {
		$flag = $sortFlag[$i];
		$args[] = array_get_column($array, $key);
		$args[] = $direction[$i];
		$args[] = $flag;
	}
	// This fix is used for cases when main sorting specified by columns has equal values
	// Without it it will lead to Fatal Error: Nesting level too deep - recursive dependency?
	$args[] = range(1, count($array));
	$args[] = SORT_ASC;
	$args[] = SORT_NUMERIC;
	$args[] = &$array;
	call_user_func_array('array_multisort', $args);
}
function array_index($array, $key, $groups = [])
{
	$result = [];
	$groups = (array)$groups;
	foreach ($array as $element) {
		$lastArray = &$result;
		foreach ($groups as $group) {
			$value = array_get_value($element, $group);
			if (!array_key_exists($value, $lastArray)) {
				$lastArray[$value] = [];
			}
			$lastArray = &$lastArray[$value];
		}
		if ($key === null) {
			if (!empty($groups)) {
				$lastArray[] = $element;
			}
		} else {
			$value = array_get_value($element, $key);
			if ($value !== null) {
				if (is_float($value)) {
					$value = (string)$value;
				}
				$lastArray[$value] = $element;
			}
		}
		unset($lastArray);
	}
	return $result;
}
function array_custom_map($array, $from, $to, $group = null)
{
	$result = [];
	foreach ($array as $element) {
		$key = array_get_value($element, $from);
		$value = array_get_value($element, $to);
		if ($group !== null) {
			$result[array_get_value($element, $group)][$key] = $value;
		} else {
			$result[$key] = $value;
		}
	}
	return $result;
}

function get_departures_sletat()
{
	$hash_request = md5("departures|sletat|" . date('d.m.Y'));
	$dir = __DIR__ . "/backups_tours/sletat";
	if (!is_dir($dir)) {
		mkdir($dir, '0777', true);
	}
	$file = $dir . "/" . $hash_request . ".json";
	if (false && file_exists($file)) {
		$departures = json_decode(file_get_contents($file), true);
	} else {
		$response = request_url("https://module.sletat.ru/Main.svc/GetDepartCities", [], 'sletat');
		$departures = isset($response['GetDepartCitiesResult']['Data']) ?
			$response['GetDepartCitiesResult']['Data'] : [];
		$departures = array_custom_map($departures, 'Id', 'Name');
		file_put_contents($file, json_encode($departures));
	}
	return $departures;
	return [
		"832" => "Москва",
		"1264" => "Санкт-Петербург",
		"1357" => "Абакан",
		"1347" => "Актау",
		"1342" => "Актобе",
		"1312" => "Алматы",
		"1329" => "Алушта",
		"1335" => "Анадырь",
		"1372" => "Анапа",
		"1275" => "Архангельск",
		"1323" => "Астана",
		"1292" => "Астрахань",
		"1346" => "Атырау",
		"1373" => "Баку",
		"1305" => "Барнаул",
		"1370" => "Батуми",
		"1306" => "Белгород",
		"1351" => "Бишкек",
		"1331" => "Благовещенск",
		"1340" => "Братск",
		"1321" => "Брест",
		"1302" => "Брянск",
		"1380" => "Варшава",
		"1360" => "Вильнюс",
		"1364" => "Витебск",
		"1293" => "Владивосток",
		"1314" => "Владикавказ",
		"1279" => "Волгоград",
		"1283" => "Воронеж",
		"1381" => "Гданьск",
		"1366" => "Гомель",
		"1368" => "Гродно",
		"1376" => "Грозный",
		"1320" => "Днепр",
		"1316" => "Донецк",
		"1382" => "Душанбе",
		"1265" => "Екатеринбург",
		"1378" => "Ереван",
		"1315" => "Запорожье",
		"1324" => "Ивано-Франковск",
		"1375" => "Ижевск",
		"1285" => "Иркутск",
		"1266" => "Казань",
		"1280" => "Калининград",
		"1383" => "Калуга",
		"1343" => "Караганда",
		"1282" => "Кемерово",
		"1311" => "Киев",
		"1384" => "Киров",
		"1317" => "Кишинев",
		"1345" => "Костанай",
		"1270" => "Краснодар",
		"1281" => "Красноярск",
		"1338" => "Курган",
		"1287" => "Курск",
		"1354" => "Липецк",
		"1325" => "Луганск",
		"1322" => "Львов",
		"1334" => "Магадан",
		"1288" => "Магнитогорск",
		"1358" => "Махачкала",
		"1299" => "Минеральные Воды",
		"1308" => "Минск",
		"1367" => "Могилев",
		"1284" => "Мурманск",
		"1313" => "Нальчик",
		"1298" => "Нижневартовск",
		"1289" => "Нижнекамск",
		"1268" => "Нижний Новгород",
		"1341" => "Новокузнецк",
		"1339" => "Новороссийск",
		"1267" => "Новосибирск",
		"1353" => "Новый Уренгой",
		"1374" => "Норильск",
		"1319" => "Одесса",
		"1278" => "Омск",
		"1290" => "Оренбург",
		"1304" => "Орск",
		"1348" => "Павлодар",
		"1336" => "Пенза",
		"1276" => "Пермь",
		"1333" => "Петропавловск-Камчатский",
		"1385" => "Псков",
		"1371" => "Рига",
		"1269" => "Ростов-на-Дону",
		"1271" => "Самара",
		"1300" => "Саратов",
		"1365" => "Севастополь",
		"1326" => "Симферополь",
		"1291" => "Сочи",
		"1355" => "Ставрополь",
		"1294" => "Сургут",
		"1337" => "Сызрань",
		"1296" => "Сыктывкар",
		"1377" => "Таллинн",
		"1356" => "Ташкент",
		"1369" => "Тбилиси",
		"1330" => "Тольятти",
		"1301" => "Томск",
		"1328" => "Трускавец",
		"1273" => "Тюмень",
		"1309" => "Улан-Удэ",
		"1295" => "Ульяновск",
		"1350" => "Уральск",
		"1362" => "Ургенч",
		"1344" => "Усть-Каменогорск",
		"1274" => "Уфа",
		"1286" => "Хабаровск",
		"1297" => "Ханты-Мансийск",
		"1318" => "Харьков",
		"1361" => "Хельсинки",
		"1303" => "Чебоксары",
		"1272" => "Челябинск",
		"1352" => "Чита",
		"1349" => "Шымкент",
		"1307" => "Южно-Сахалинск",
		"1332" => "Якутск",
		"1327" => "Ялта",
		"1379" => "Ярославль"
	];
}
function get_countries_sletat()
{
	$hash_request = md5("countries|sletat|" . date('d.m.Y'));
	$dir = __DIR__ . "/backups_tours/sletat";
	if (!is_dir($dir)) {
		mkdir($dir, '0777', true);
	}
	$file = $dir . "/" . $hash_request . ".json";
	if (file_exists($file)) {
		$countries = json_decode(file_get_contents($file), true);
	} else {
		$response = request_url("https://module.sletat.ru/Main.svc/GetCountries", [], 'sletat');
		$countries = isset($response['GetCountriesResult']['Data']) ?
			$response['GetCountriesResult']['Data'] : [];
		$countries = array_custom_map($countries, 'Id', 'Name');
		file_put_contents($file, json_encode($countries));
	}
	return sort_alphabet($countries);
	return [
		"1" => "Абхазия",
		"2" => "Австралия",
		"3" => "Австрия",
		"4" => "Азербайджан",
		"5" => "Албания",
		"6" => "Ангилья",
		"7" => "Андорра",
		"184" => "Антарктида",
		"8" => "Антигуа",
		"9" => "Аргентина",
		"10" => "Армения",
		"11" => "Аруба",
		"12" => "Багамы",
		"13" => "Бангладеш",
		"14" => "Барбадос",
		"15" => "Бахрейн",
		"16" => "Беларусь",
		"17" => "Белиз",
		"18" => "Бельгия",
		"180" => "Бермудские острова",
		"19" => "Болгария",
		"20" => "Боливия",
		"21" => "Босния и Герцеговина",
		"22" => "Ботсвана",
		"23" => "Бразилия",
		"24" => "Бруней",
		"25" => "Буркина-Фасо",
		"173" => "Бурунди",
		"190" => "Бутан",
		"26" => "Великобритания",
		"27" => "Венгрия",
		"28" => "Венесуэла",
		"29" => "Вьетнам",
		"154" => "Гана",
		"30" => "Гваделупа",
		"31" => "Гватемала",
		"149" => "Германия",
		"33" => "Гондурас",
		"179" => "Гонконг",
		"34" => "Гренада",
		"196" => "Гренландия ",
		"35" => "Греция",
		"36" => "Грузия",
		"37" => "Дания",
		"38" => "Джибути",
		"170" => "Доминика",
		"39" => "Доминикана",
		"41" => "Замбия",
		"42" => "Зимбабве",
		"43" => "Израиль",
		"44" => "Индия",
		"45" => "Индонезия",
		"46" => "Иордания",
		"47" => "Иран",
		"48" => "Ирландия",
		"49" => "Исландия",
		"50" => "Испания",
		"51" => "Италия",
		"52" => "Кабо-Верде",
		"53" => "Казахстан",
		"54" => "Камбоджа",
		"167" => "Камерун",
		"55" => "Канада",
		"56" => "Катар",
		"57" => "Кения",
		"58" => "Кипр",
		"59" => "Китай",
		"171" => "Колумбия",
		"155" => "Конго",
		"60" => "Коста-Рика",
		"61" => "Куба",
		"62" => "Кыргызcтан",
		"200" => "Кюрасао",
		"63" => "Лаос",
		"64" => "Латвия",
		"65" => "Ливан",
		"66" => "Литва",
		"176" => "Лихтенштейн",
		"67" => "Люксембург",
		"68" => "Маврикий",
		"69" => "Мадагаскар",
		"70" => "Македония",
		"71" => "Малайзия",
		"157" => "Мали",
		"72" => "Мальдивы",
		"73" => "Мальта",
		"74" => "Марианские о-ва",
		"75" => "Марокко",
		"76" => "Мартиника",
		"77" => "Мексика",
		"78" => "Мозамбик",
		"79" => "Молдавия",
		"80" => "Монако",
		"81" => "Монголия",
		"82" => "Мьянма (Бирма)",
		"83" => "Намибия",
		"84" => "Непал",
		"181" => "Нигерия",
		"86" => "Нидерланды",
		"177" => "Никарагуа",
		"87" => "Новая Зеландия",
		"88" => "Норвегия",
		"89" => "о. Кука",
		"90" => "ОАЭ",
		"91" => "Оман",
		"92" => "Пакистан",
		"172" => "Палау",
		"93" => "Панама",
		"185" => "Папуа Новая Гвинея",
		"95" => "Парагвай",
		"96" => "Перу",
		"98" => "Польша",
		"99" => "Португалия",
		"100" => "Реюньон",
		"150" => "Россия",
		"102" => "Румыния",
		"178" => "Сан-Марино",
		"164" => "Сан-Томе и Принсипи",
		"103" => "Саудовская Аравия",
		"104" => "Свазиленд",
		"199" => "Северная Корея",
		"105" => "Сейшелы",
		"158" => "Сенегал",
		"189" => "Сен-Мартен",
		"195" => "Сент-Винсент и Гренадины",
		"194" => "Сент-Китс и Невис",
		"106" => "Сент-Люсия",
		"107" => "Сербия",
		"108" => "Сингапур",
		"109" => "Сирия",
		"110" => "Словакия",
		"111" => "Словения",
		"159" => "Судан",
		"112" => "США",
		"193" => "Таджикистан",
		"114" => "Тайвань",
		"113" => "Таиланд",
		"115" => "Танзания",
		"116" => "Теркс и Кайкос",
		"156" => "Того",
		"117" => "Тунис",
		"118" => "Туркменистан",
		"119" => "Турция",
		"161" => "Уганда",
		"120" => "Узбекистан",
		"121" => "Украина",
		"192" => "Уругвай",
		"122" => "Фиджи",
		"123" => "Филиппины",
		"124" => "Финляндия",
		"125" => "Франция",
		"169" => "Французская Полинезия",
		"126" => "Хорватия",
		"168" => "ЦАР",
		"166" => "Чад",
		"127" => "Черногория",
		"128" => "Чехия",
		"129" => "Чили",
		"130" => "Швейцария",
		"131" => "Швеция",
		"197" => "Шпицберген",
		"132" => "Шри-Ланка",
		"133" => "Эквадор",
		"165" => "Эритрея",
		"134" => "Эстония",
		"135" => "Эфиопия",
		"136" => "ЮАР",
		"137" => "Южная Корея",
		"138" => "Ямайка",
		"139" => "Япония"
	];
}
function get_cities_sletat()
{
	$hash_request = md5("cities|sletat|" . date('d.m.Y'));
	$dir = __DIR__ . "/backups_tours/sletat";
	if (!is_dir($dir)) {
		mkdir($dir, '0777', true);
	}
	$file = $dir . "/" . $hash_request . ".json";
	if (file_exists($file)) {
		$cities = json_decode(file_get_contents($file), true);
	} else {
		$cities = [];
		foreach (get_countries_sletat() as $country_id => $country) {
			$response = request_url("https://module.sletat.ru/Main.svc/GetCities", [
				'countryId' => $country_id
			], 'sletat');
			$_cities = isset($response['GetCitiesResult']['Data']) ?
				$response['GetCitiesResult']['Data'] : [];
			foreach ($_cities as $city) {
				$cities[] = [
					'id' => $city['Id'],
					'name' => $city['Name'],
					'countryId' => $country_id
				];
			}
		}
		file_put_contents($file, json_encode($cities));
	}
	return $cities;
	return [
		["id" => "832", "name" => "Москва", "countryId" => "150"],
		["id" => "1264", "name" => "Санкт-Петербург", "countryId" => "150"],
		["id" => "1357", "name" => "Абакан", "countryId" => "150"],
		["id" => "1347", "name" => "Актау", "countryId" => "53"],
		["id" => "1342", "name" => "Актобе", "countryId" => "53"],
		["id" => "1312", "name" => "Алматы", "countryId" => "53"],
		["id" => "1329", "name" => "Алушта", "countryId" => "150"],
		["id" => "1335", "name" => "Анадырь", "countryId" => "150"],
		["id" => "1372", "name" => "Анапа", "countryId" => "150"],
		["id" => "1275", "name" => "Архангельск", "countryId" => "150"],
		["id" => "1323", "name" => "Астана", "countryId" => "53"],
		["id" => "1292", "name" => "Астрахань", "countryId" => "150"],
		["id" => "1346", "name" => "Атырау", "countryId" => "53"],
		["id" => "1373", "name" => "Баку", "countryId" => "4"],
		["id" => "1305", "name" => "Барнаул", "countryId" => "150"],
		["id" => "1370", "name" => "Батуми", "countryId" => "36"],
		["id" => "1306", "name" => "Белгород", "countryId" => "150"],
		["id" => "1351", "name" => "Бишкек", "countryId" => "62"],
		["id" => "1331", "name" => "Благовещенск", "countryId" => "150"],
		["id" => "1340", "name" => "Братск", "countryId" => "150"],
		["id" => "1321", "name" => "Брест", "countryId" => "16"],
		["id" => "1302", "name" => "Брянск", "countryId" => "150"],
		["id" => "1380", "name" => "Варшава", "countryId" => "98"],
		["id" => "1360", "name" => "Вильнюс", "countryId" => "66"],
		["id" => "1364", "name" => "Витебск", "countryId" => "16"],
		["id" => "1293", "name" => "Владивосток", "countryId" => "150"],
		["id" => "1314", "name" => "Владикавказ", "countryId" => "150"],
		["id" => "1279", "name" => "Волгоград", "countryId" => "150"],
		["id" => "1283", "name" => "Воронеж", "countryId" => "150"],
		["id" => "1381", "name" => "Гданьск", "countryId" => "98"],
		["id" => "1366", "name" => "Гомель", "countryId" => "16"],
		["id" => "1368", "name" => "Гродно", "countryId" => "16"],
		["id" => "1376", "name" => "Грозный", "countryId" => "150"],
		["id" => "1320", "name" => "Днепр", "countryId" => "121"],
		["id" => "1316", "name" => "Донецк", "countryId" => "121"],
		["id" => "1382", "name" => "Душанбе", "countryId" => "193"],
		["id" => "1265", "name" => "Екатеринбург", "countryId" => "150"],
		["id" => "1378", "name" => "Ереван", "countryId" => "10"],
		["id" => "1315", "name" => "Запорожье", "countryId" => "121"],
		["id" => "1324", "name" => "Ивано-Франковск", "countryId" => "121"],
		["id" => "1375", "name" => "Ижевск", "countryId" => "150"],
		["id" => "1285", "name" => "Иркутск", "countryId" => "150"],
		["id" => "1266", "name" => "Казань", "countryId" => "150"],
		["id" => "1280", "name" => "Калининград", "countryId" => "150"],
		["id" => "1383", "name" => "Калуга", "countryId" => "150"],
		["id" => "1343", "name" => "Караганда", "countryId" => "53"],
		["id" => "1282", "name" => "Кемерово", "countryId" => "150"],
		["id" => "1311", "name" => "Киев", "countryId" => "121"],
		["id" => "1384", "name" => "Киров", "countryId" => "150"],
		["id" => "1317", "name" => "Кишинев", "countryId" => "79"],
		["id" => "1345", "name" => "Костанай", "countryId" => "53"],
		["id" => "1270", "name" => "Краснодар", "countryId" => "150"],
		["id" => "1281", "name" => "Красноярск", "countryId" => "150"],
		["id" => "1338", "name" => "Курган", "countryId" => "150"],
		["id" => "1287", "name" => "Курск", "countryId" => "150"],
		["id" => "1354", "name" => "Липецк", "countryId" => "150"],
		["id" => "1325", "name" => "Луганск", "countryId" => "121"],
		["id" => "1322", "name" => "Львов", "countryId" => "121"],
		["id" => "1334", "name" => "Магадан", "countryId" => "150"],
		["id" => "1288", "name" => "Магнитогорск", "countryId" => "150"],
		["id" => "1358", "name" => "Махачкала", "countryId" => "150"],
		["id" => "1299", "name" => "Минеральные Воды", "countryId" => "150"],
		["id" => "1308", "name" => "Минск", "countryId" => "16"],
		["id" => "1367", "name" => "Могилев", "countryId" => "16"],
		["id" => "1284", "name" => "Мурманск", "countryId" => "150"],
		["id" => "1313", "name" => "Нальчик", "countryId" => "150"],
		["id" => "1298", "name" => "Нижневартовск", "countryId" => "150"],
		["id" => "1289", "name" => "Нижнекамск", "countryId" => "150"],
		["id" => "1268", "name" => "Нижний Новгород", "countryId" => "150"],
		["id" => "1341", "name" => "Новокузнецк", "countryId" => "150"],
		["id" => "1339", "name" => "Новороссийск", "countryId" => "150"],
		["id" => "1267", "name" => "Новосибирск", "countryId" => "150"],
		["id" => "1353", "name" => "Новый Уренгой", "countryId" => "150"],
		["id" => "1374", "name" => "Норильск", "countryId" => "150"],
		["id" => "1319", "name" => "Одесса", "countryId" => "121"],
		["id" => "1278", "name" => "Омск", "countryId" => "150"],
		["id" => "1290", "name" => "Оренбург", "countryId" => "150"],
		["id" => "1304", "name" => "Орск", "countryId" => "150"],
		["id" => "1348", "name" => "Павлодар", "countryId" => "53"],
		["id" => "1336", "name" => "Пенза", "countryId" => "150"],
		["id" => "1276", "name" => "Пермь", "countryId" => "150"],
		["id" => "1333", "name" => "Петропавловск-Камчатский", "countryId" => "150"],
		["id" => "1385", "name" => "Псков", "countryId" => "150"],
		["id" => "1371", "name" => "Рига", "countryId" => "64"],
		["id" => "1269", "name" => "Ростов-на-Дону", "countryId" => "150"],
		["id" => "1271", "name" => "Самара", "countryId" => "150"],
		["id" => "1300", "name" => "Саратов", "countryId" => "150"],
		["id" => "1365", "name" => "Севастополь", "countryId" => "150"],
		["id" => "1326", "name" => "Симферополь", "countryId" => "150"],
		["id" => "1291", "name" => "Сочи", "countryId" => "150"],
		["id" => "1355", "name" => "Ставрополь", "countryId" => "150"],
		["id" => "1294", "name" => "Сургут", "countryId" => "150"],
		["id" => "1337", "name" => "Сызрань", "countryId" => "150"],
		["id" => "1296", "name" => "Сыктывкар", "countryId" => "150"],
		["id" => "1377", "name" => "Таллинн", "countryId" => "134"],
		["id" => "1356", "name" => "Ташкент", "countryId" => "120"],
		["id" => "1369", "name" => "Тбилиси", "countryId" => "36"],
		["id" => "1330", "name" => "Тольятти", "countryId" => "150"],
		["id" => "1301", "name" => "Томск", "countryId" => "150"],
		["id" => "1328", "name" => "Трускавец", "countryId" => "121"],
		["id" => "1273", "name" => "Тюмень", "countryId" => "150"],
		["id" => "1309", "name" => "Улан-Удэ", "countryId" => "150"],
		["id" => "1295", "name" => "Ульяновск", "countryId" => "150"],
		["id" => "1350", "name" => "Уральск", "countryId" => "53"],
		["id" => "1362", "name" => "Ургенч", "countryId" => "120"],
		["id" => "1344", "name" => "Усть-Каменогорск", "countryId" => "53"],
		["id" => "1274", "name" => "Уфа", "countryId" => "150"],
		["id" => "1286", "name" => "Хабаровск", "countryId" => "150"],
		["id" => "1297", "name" => "Ханты-Мансийск", "countryId" => "150"],
		["id" => "1318", "name" => "Харьков", "countryId" => "121"],
		["id" => "1361", "name" => "Хельсинки", "countryId" => "124"],
		["id" => "1303", "name" => "Чебоксары", "countryId" => "150"],
		["id" => "1272", "name" => "Челябинск", "countryId" => "150"],
		["id" => "1352", "name" => "Чита", "countryId" => "150"],
		["id" => "1349", "name" => "Шымкент", "countryId" => "53"],
		["id" => "1307", "name" => "Южно-Сахалинск", "countryId" => "150"],
		["id" => "1332", "name" => "Якутск", "countryId" => "150"],
		["id" => "1327", "name" => "Ялта", "countryId" => "150"],
		["id" => "1379", "name" => "Ярославль", "countryId" => "150"]
	];
}

function get_departures_itaka()
{
	return [
		"DLM" => "",
		"AGA" => "Agadir",
		"ALC" => "Alicante",
		"AYT" => "Antalya",
		"ACE" => "Arrecife",
		"ATH" => "Athens",
		"AUG" => "Augustów",
		"BCN" => "Barcelona",
		"BIK" => "Białystok",
		"BIA" => "Bielsko - Biała",
		"BJV" => "Bodrum",
		"BOJ" => "Bourgas",
		"BM" => "Brno",
		"BRQ" => "Brno (airport)",
		"BYD,BZG" => "Bydgoszcz",
		"CTA" => "Catania",
		"CHQ" => "Chania",
		"CFU" => "Corfu",
		"CZA" => "Częstochowa",
		"OWN" => "только проживание",
		"NBE" => "Enfidha",
		"ELK" => "Ełk",
		"FAO" => "Faro",
		"FNC" => "Funchal",
		"GDA,GDN" => "Gdańsk",
		"GIW" => "Gliwice",
		"GOG" => "Gorzów Wielkopolski",
		"GRA" => "Grajewo",
		"GRS" => "Grosseto",
		"GUB" => "Głubczyce",
		"HER" => "Heraklion",
		"HRG" => "Hurghada",
		"IBZ" => "Ibiza",
		"ADB" => "Izmir",
		"KCW,KTW" => "Katowice",
		"KVA" => "Kavala",
		"KIE" => "Kielce",
		"KGS" => "Kos",
		"KOS" => "Koszalin",
		"KKQ,KRK" => "Kraków",
		"KUB" => "Kudowa Zdrój",
		"SUF" => "Lamezia Terme",
		"LCA" => "Larnaca",
		"LPA" => "Las Palmas",
		"LEG" => "Legnica",
		"LEE" => "Leszno",
		"LUL" => "Lublin",
		"AGP" => "Malaga",
		"RMF" => "Marsa Alam",
		"MJT" => "Mytilene",
		"MLW" => "Mława",
		"OLB" => "Olbia",
		"OLS" => "Olsztyn",
		"OPE" => "Opole",
		"OV" => "Ostrava",
		"OSR" => "Ostrava (airport)",
		"PMO" => "Palermo",
		"PMI" => "Palma de Mallorca",
		"PFO" => "Paphos",
		"PT0" => "Piotrków Trybunalski",
		"PNB" => "Piwniczna",
		"PIL" => "Piła",
		"POZ,PZP" => "Poznań",
		"PRG" => "Praha (airport)",
		"PHD" => "Praha - Dálnice",
		"PHH" => "Praha - Hlavní Nádraží",
		"PHL" => "Praha - Letňany",
		"PHN" => "Praha - Nové Butovice",
		"PHA" => "Praha - Roztyly",
		"PHS" => "Praha - Stodůlky",
		"PHR" => "Praha - Václav Havel",
		"PHZ" => "Praha - Zličín",
		"PH0" => "Praha 0",
		"PVK" => "Preveza",
		"FUE" => "Puerto del Rosario",
		"RAD" => "Radom",
		"TFS" => "Reina Sofia",
		"RHO" => "Rhodos",
		"RJK" => "Rijeka",
		"RMI" => "Rimini",
		"RZE,RZW" => "Rzeszów",
		"SID" => "Sal",
		"SMI" => "Samos",
		"SPC" => "Santa Cruz de La Palma",
		"SSH" => "Sharm el-Sheikh",
		"STA" => "Stawiski",
		"SUW" => "Suwałki",
		"SZZ,SCE" => "Szczecin",
		"SCU" => "Szczuczyn",
		"SLU" => "Słupsk",
		"TAR" => "Tarnów",
		"TIA" => "Tirana",
		"TOR" => "Toruń",
		"TOB" => "Torzym",
		"VAR" => "Varna",
		"WZZ,WAW" => "Warszawa",
		"WRP,WRO" => "Wrocław",
		"ZTH" => "Zakynthos",
		"ZGB" => "Zgorzelec",
		"ZGA" => "Zielona Góra",
		"LOM" => "Łomża",
		"LDZ" => "Łódź"
	];
}
function get_countries_itaka()
{
	return sort_alphabet([
		"albania" => "Албания",
		"andora" => "Андорра",
		"antarktyda" => "Antarktyda",
		"argentyna" => "Аргентина",
		"austria" => "Австрия",
		"belgia" => "Бельгия",
		"bhutan" => "Bhutan",
		"bosnia-i-hercegowina" => "Босния и Герцеговина",
		"botswana" => "Ботсвана",
		"bulgaria" => "Болгария",
		"chile" => "Чили",
		"chiny" => "Китай",
		"chorwacja" => "Хорватия",
		"cypr" => "Кипр",
		"czarnogora" => "Czarnogóra",
		"czechy" => "Чешская республика",
		"czechy-narty" => "Чешская республика (лыжы)",
		"dania" => "Дания",
		"dominikana" => "Доминикана",
		"egipt" => "Египет",
		"ekwador" => "Эквадор",
		"etiopia" => "Etiopia",
		"francja" => "Франция",
		"gambia" => "Gambia",
		"grecja" => "Греция",
		"gruzja" => "Грузия",
		"gwatemala" => "Гватемала",
		"hiszpania" => "Испания",
		"holandia" => "Нидерланды",
		"indie" => "Индия",
		"indonezja" => "Индонезия",
		"irlandia" => "Ирландия",
		"islandia" => "Исландия",
		"hiszpania-narty" => "Испания (лыжы)",
		"izrael" => "Израиль",
		"jordania" => "Jordania",
		"kambodza" => "Камбоджа",
		"kanada" => "Канада",
		"kostaryka" => "Коста-Рика",
		"krolestwo-suazi" => "Королевство Свазиленд",
		"kuba" => "Куба",
		"laos" => "Лаос",
		"luksemburg" => "Luksemburg",
		"macedonia" => "Македония",
		"madagaskar" => "Мадагаскар",
		"madera" => "Мадейра",
		"malezja" => "Малайзия",
		"malta" => "Мальта",
		"maroko" => "Марокко",
		"meksyk" => "Мексика",
		"monaco" => "Monaco",
		"namibia" => "Намибия",
		"nepal" => "Непал",
		"niemcy" => "Германия",
		"norwegia" => "Норвегия",
		"oman" => "Оман",
		"panama" => "Панама",
		"peru" => "Перу",
		"porto-santo" => "Порто Санто",
		"portugalia" => "Португалия",
		"rosja" => "Россия",
		"rpa" => "ЮАР",
		"rumunia" => "Румыния",
		"serbia" => "Сербия",
		"slowacja" => "Словакия",
		"slowenia" => "Словения",
		"sri-lanka" => "Шри-Ланка",
		"stany-zjednoczone" => "США",
		"szkocja" => "Шотландия",
		"szwajcaria" => "Швейцария",
		"tajlandia" => "Таиланд",
		"tanzania" => "Танзания",
		"tunezja" => "Тунис",
		"turcja" => "Турция",
		"wegry" => "Венгрия",
		"wielka-brytania" => "Великобритания",
		"wietnam" => "Вьетнам",
		"wlochy" => "Италия",
		"wyspy-kanaryjskie" => "Канарские острова",
		"wyspy-zielonego-przyladka" => "Острова Зеленого Мыса",
		"zanzibar" => "Занзибар",
		"zimbabwe" => "Зимбабве",
		"zjednoczone-emiraty-arabskie" => "Объединенные Арабские Эмираты",
	]);
}
function get_cities_itaka()
{
	return [
		['id' => "durres", "name" => "Дуррес", "countryId" => "albania"],
		['id' => "saranda", "name" => "Саранда", "countryId" => "albania"],
		['id' => "shengjin", "name" => "Shëngjin", "countryId" => "albania"],
		['id' => "sozopol", "name" => "Созополь", "countryId" => "bulgaria"],
		['id' => "sloneczny-brzeg", "name" => "Солнечный Берег", "countryId" => "bulgaria"],
		['id' => "zlote-piaski", "name" => "Золотые Пески", "countryId" => "bulgaria"],
		['id' => "larnaka", "name" => "Ларнака", "countryId" => "cypr"],
		['id' => "pafos", "name" => "Пафос", "countryId" => "cypr"],
		['id' => "la-romana", "name" => "Ла-Романа", "countryId" => "dominikana"],
		['id' => "punta-cana", "name" => "Пунта-Кана", "countryId" => "dominikana"],
		['id' => "hurghada", "name" => "Хургада", "countryId" => "egipt"],
		['id' => "marsa-alam", "name" => "Марса Алам", "countryId" => "egipt"],
		['id' => "sharm-el-sheikh", "name" => "Sharm el Sheikh", "countryId" => "egipt"],
		['id' => "chalkidiki", "name" => "Халкидики", "countryId" => "grecja"],
		['id' => "kavala", "name" => "Кавала", "countryId" => "grecja"],
		['id' => "korfu", "name" => "Корфу", "countryId" => "grecja"],
		['id' => "kos", "name" => "Кос", "countryId" => "grecja"],
		['id' => "kreta", "name" => "Крит", "countryId" => "grecja"],
		['id' => "lefkada", "name" => "Лефкада", "countryId" => "grecja"],
		['id' => "lesbos", "name" => "Лесбос", "countryId" => "grecja"],
		['id' => "peloponez", "name" => "Пелопоннес", "countryId" => "grecja"],
		['id' => "rodos", "name" => "Родос", "countryId" => "grecja"],
		['id' => "samos", "name" => "Самос", "countryId" => "grecja"],
		['id' => "thassos", "name" => "Тасос", "countryId" => "grecja"],
		['id' => "zakynthos", "name" => "Закинтос", "countryId" => "grecja"],
		['id' => "costa-blanca", "name" => "Коста Бланка", "countryId" => "hiszpania"],
		['id' => "costa-brava", "name" => "Коста Брава", "countryId" => "hiszpania"],
		['id' => "costa-dorada", "name" => "Коста Дорада", "countryId" => "hiszpania"],
		['id' => "costa-de-la-luz", "name" => "Коста-де-ла-Лус", "countryId" => "hiszpania"],
		['id' => "costa-del-sol-costa-tropical", "name" => "Коста-дель-Соль, Коста-Тропикаль", "countryId" => "hiszpania"],
		['id' => "ibiza", "name" => "Ибица", "countryId" => "hiszpania"],
		['id' => "majorka", "name" => "Майорка", "countryId" => "hiszpania"],
		['id' => "granada", "name" => "Гранада", "countryId" => "hiszpania-narty"],
		['id' => "sierra-nevada", "name" => "Сьерра Невада", "countryId" => "hiszpania-narty"],
		['id' => "cayo-coco", "name" => "Кайо-Коко", "countryId" => "kuba"],
		['id' => "cayo-santa-maria", "name" => "Кайо Санта Мария", "countryId" => "kuba"],
		['id' => "agadir", "name" => "Агадир", "countryId" => "maroko"],
		['id' => "salalah", "name" => "Салала", "countryId" => "oman"],
		['id' => "algarve", "name" => "Алгарве", "countryId" => "portugalia"],
		['id' => "khao-lak", "name" => "Као Лак", "countryId" => "tajlandia"],
		['id' => "krabi", "name" => "Краби", "countryId" => "tajlandia"],
		['id' => "phuket", "name" => "Пхукет", "countryId" => "tajlandia"],
		['id' => "hammamet", "name" => "Хаммамет", "countryId" => "tunezja"],
		['id' => "monastir", "name" => "Монастир", "countryId" => "tunezja"],
		['id' => "sousse", "name" => "Сусс", "countryId" => "tunezja"],
		['id' => "alanya", "name" => "Алания", "countryId" => "turcja"],
		['id' => "antalya", "name" => "Antalya", "countryId" => "turcja"],
		['id' => "belek", "name" => "Белек", "countryId" => "turcja"],
		['id' => "bodrum", "name" => "Бодрум", "countryId" => "turcja"],
		['id' => "cesme", "name" => "Чешме", "countryId" => "turcja"],
		['id' => "didyma", "name" => "Дидим", "countryId" => "turcja"],
		['id' => "kemer", "name" => "Кемер", "countryId" => "turcja"],
		['id' => "kusadasi", "name" => "Kusadasi", "countryId" => "turcja"],
		['id' => "marmaris", "name" => "Мармарис", "countryId" => "turcja"],
		['id' => "sarigerme", "name" => "Sarigerme", "countryId" => "turcja"],
		['id' => "side", "name" => "Сиде", "countryId" => "turcja"],
		['id' => "kalabria", "name" => "Калабрия", "countryId" => "wlochy"],
		['id' => "riwiera-adriatycka", "name" => "Адриатическая Ривьера", "countryId" => "wlochy"],
		['id' => "sardynia", "name" => "Сардиния", "countryId" => "wlochy"],
		['id' => "sycylia", "name" => "Сицилия", "countryId" => "wlochy"],
		['id' => "toskania", "name" => "Тоскана", "countryId" => "wlochy"],
		['id' => "fuerteventura", "name" => "Фуэртевентура", "countryId" => "wyspy-kanaryjskie"],
		['id' => "gran-canaria", "name" => "Гран-Канария", "countryId" => "wyspy-kanaryjskie"],
		['id' => "la-gomera", "name" => "La Gomera", "countryId" => "wyspy-kanaryjskie"],
		['id' => "la-palma", "name" => "Ла Пальма", "countryId" => "wyspy-kanaryjskie"],
		['id' => "lanzarote", "name" => "Лансароте", "countryId" => "wyspy-kanaryjskie"],
		['id' => "teneryfa", "name" => "Тенерифе", "countryId" => "wyspy-kanaryjskie"],
	];
}

function get_departures_tsvet()
{
	return get_departures_itaka();
}
function get_countries_tsvet()
{
	$countries = get_terms([
		'taxonomy' => 'pa_country',
		'count' => true
	]);
	$countries = array_custom_map($countries, 'slug', 'name');
	$countries = sort_alphabet($countries);
	return $countries;
}
function sort_alphabet($array)
{
	uasort($array, function ($a, $b) {
		$la = mb_substr($a, 0, 1, 'utf-8');
		$lb = mb_substr($b, 0, 1, 'utf-8');
		if (ord($la) > 122 && ord($lb) > 122) {
			return $a > $b ? 1 : -1;
		}
		if (ord($la) > 122 || ord($lb) > 122) {
			return $a < $b ? 1 : -1;
		}
	});
	return $array;
}
function get_cities_tsvet()
{
	return [];
}
function get_geos_for_filters()
{
	if (isset($_GET['turoperator']) && in_array($_GET['turoperator'], ['Sletat', 'Itaka'])) {
		if ($_GET['turoperator'] == 'Sletat') {
			$cities = get_cities_sletat();
			$countries = get_countries_sletat();
		} else {
			$cities = get_cities_itaka();
			$countries = get_countries_itaka();
		}
		$cities = array_index($cities, 'id', ['countryId']);
	} else {
		$cities = get_cities_tsvet();
		$countries = get_countries_tsvet();
	}

	return [$cities, $countries];
}

function get_default_params_tout()
{
	return [
		'_product_image_gallery' => [],
		'category_name' => '',
		'_wc_average_rating' => 0,
		'stars' => '',
		'_sku' => '',
		'adults' => 2,
		'childs' => 0,
		'rooms' => [],
		'foods' => [],
		'city-of-departure' => [],
		'country' => '',
		'region' => '',
		'terms_and_prices' => [],
		'_regular_price' => 0,
		'_sale_price' => 0,
		'percent_rating' => 0,
		'child_price_6' => 0,
		'child_price_13' => 0,
		'bus_tour' => 'Нет'
	];
}
function get_params_tout($tour_id)
{
	$results = [];
	foreach (get_default_params_tout() as $param => $value) {
		$results[$param] = get_cache_post_meta($tour_id, $param, $value);
	}
	return $results;
}

function tsvet_widget_title($title, $instance, $id_base)
{
	return implode('', array_map(function ($line) {
		return "<div>{$line}</div>";
	}, explode('_br_', $title)));
}
add_filter('widget_title', 'tsvet_widget_title', 10, 3);

function tsvet_navigation_markup_template($template, $class)
{
	global $wp_query;
	$total = isset($wp_query->max_num_pages) ? $wp_query->max_num_pages : 1;
	$current = get_query_var('paged') ? intval(get_query_var('paged')) : 1;

	$prev = $total > 1 && $current == 1 ? "<span class=\"prev page-numbers\">Предыдущая</span>" : "";
	$next = $total > 1 && $current == $total ? "<span class=\"next page-numbers\">Следующая</span>" : "";

	return 'pagination' == $class ? '
	<nav class="navigation %1$s" role="navigation">
		<div class="nav-links">' . $prev . '%3$s' . $next . '</div>
	</nav>' : $template;
}
add_filter('navigation_markup_template', 'tsvet_navigation_markup_template', 10, 2);

function get_page_tsvet($params)
{
	$url = wp_parse_url($_POST['url']);
	$page = get_page_by_path($url['path']);
	wp_send_json_success([$page->post_content]);
}
add_action('wp_ajax_get_page', 'get_page_tsvet');
add_action('wp_ajax_nopriv_get_page', 'get_page_tsvet');

function tsvet_woocommerce_product_data_tabs($tabs)
{
	return [
		'general' => $tabs['general'],
		'attribute' => $tabs['attribute']
	];
}
add_filter('woocommerce_product_data_tabs', 'tsvet_woocommerce_product_data_tabs');

function wc_meta_box_product_data_output($post)
{
	ob_start();
	WC_Meta_Box_Product_Data::output($post);
	$output = ob_get_clean();
	$pos_start_removed = strpos($output, '<span class="type_box hidden">');
	$pos_end_removed = strpos($output, '<ul class="product_data_tabs wc-tabs">');
	$output = substr($output, 0, $pos_start_removed) . substr($output, $pos_end_removed);
	echo strtr($output, [
		'options_group show_if_external' => 'options_group show_if_external hidden',
		'options_group pricing show_if_simple show_if_external hidden' => 'options_group pricing show_if_simple show_if_external'
	]);
}
function tsvet_add_meta_boxes()
{
	global $wp_meta_boxes;
	if (isset($wp_meta_boxes['product']['normal']['high']['woocommerce-product-data'])) {
		$wp_meta_boxes['product']['normal']['high']['woocommerce-product-data']['callback'] = 'wc_meta_box_product_data_output';
	}
}
add_action('add_meta_boxes', 'tsvet_add_meta_boxes', 31);

function tsvet_register_taxonomies()
{
//	unregister_taxonomy( 'product_cat' );
	unregister_taxonomy('product_tag');
}
add_action('init', 'tsvet_register_taxonomies', 5);
remove_filter('post_type_link', 'wc_product_post_type_link', 10);

function tsvet_admin_menu()
{
	remove_menu_page('woocommerce');
}
add_action('admin_menu', 'tsvet_admin_menu', 100);

function tsvet_site_transient_update_plugins($value)
{
	if (isset($value->response['woocommerce/woocommerce.php'])) {
		return null;
	}
	return $value;
}
add_filter('site_transient_update_plugins', 'tsvet_site_transient_update_plugins');

function site_the_content($content)
{
	if (is_product() && strpos($content, '<div class="product-productdescription">') === false) {
		return '<div class="product-productdescription product-productdescription_custom">' . $content . '</div>';
	}
	return $content;
}
add_filter('the_content', 'site_the_content');

function tours_tabs_shortcode($atts, $content)
{
	return tsvet_render_file("template-parts/tours-tabs", null, [
		'content' => do_shortcode($content)
	]) ? : '';
}
add_shortcode('tours_tabs', 'tours_tabs_shortcode');