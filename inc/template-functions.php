<?php

//add_filter('stylesheet_directory_uri', '__return_empty_string');
//add_filter('template_directory_uri', '__return_empty_string');

/**
 * Удаляем все пустые аттрибуты изображения
 */
add_filter( 'wp_get_attachment_image_attributes', 'array_filter' );

/**
 * Подключить файл и передать в него параметры
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 * @param array $params Параметры, которые нужно передать в подключаемый файл
 *
 * @return string
 */
function tsvet_render_file( $slug, $name = null, $params = [] ) {
	global $rendered_files, $wp_query;
	$key_file = md5( json_encode( array_merge( [ 'slug' => $slug, 'name' => $name ], $params ) ) );
	if ( isset( $rendered_files[ $key_file ] ) ) {
		return $rendered_files[ $key_file ];
	}

	ob_start();
	ob_implicit_flush( false );
	$wp_query->query_vars = array_merge( $wp_query->query_vars, $params );

	if (  file_exists( $slug ) ) {
		if(strpos($slug, get_template_directory()) !== false) {
			$slug = str_replace( [ get_template_directory() . '/', '.php' ], "", $slug );
			get_template_part( $slug, $name );
		} else {
			load_template($slug, false);
		}
	} else {
		get_template_part( $slug, $name );
	}

	$rendered_files[ $key_file ] = ob_get_clean();

	return $rendered_files[ $key_file ];
}

/**
 * Обернуть файл шаблона в layout и вывести его
 *
 * @param string $template Путь к файлу шаблона
 */
function tsvet_template_include( $template ) {
    global $wp_query;
    if(strpos($template, "/archive-product.php") !== false && isset($wp_query->query_vars['taxonomy']) && $wp_query->query_vars['taxonomy'] == 'pa_country') {
        $template = str_replace("/archive-product.php", "/taxonomy-product_cat.php", $template);
    }
	echo tsvet_render_file( 'layout', '', [
		'content' => tsvet_render_file( $template )
	] );
}

add_filter( 'template_include', 'tsvet_template_include' );

/**
 * Получить аттрибуты тега
 *
 * @param string $tag
 *
 * @return array
 */
function tsvet_get_attributes_tag( $tag ) {
	if ( preg_match( "/<[a-z]+(?:\s+([^>]*)|>)/i", $tag, $match ) && isset( $match[1] ) ) {
		$pattern_attribute = "/(?P<names>[^\.=\s\"\']+)(?:=(?P<values>\"[^\"]*\"|\'[^\']*\'))?/i";
		if ( preg_match_all( $pattern_attribute, $match[1], $attributes ) ) {
			return array_combine( $attributes['names'], array_map( function ( $value ) {
				return trim( $value, "\"'" );
			}, $attributes['values'] ) );
		}
	}

	return [];
}

function tsvet_attributes_to_string( $attributes ) {
	return implode( ' ', array_map( function ( $attribute, $value ) {
		if ( is_array( $value ) ) {
			$value = implode( ' ', $value );
		}
		$value = $value !== '' ? $value : $attribute;

		return "{$attribute}=\"{$value}\"";
	}, array_keys( $attributes ), array_values( $attributes ) ) );
}

function tsvet_css_class_to_array( $class ) {
	return isset( $class ) ? ( is_string( $class ) ? explode( ' ', $class ) : $class ) : [];
}

function tsvet_get_menu_by_location( $location ) {
	if ( empty( $location ) ) {
		return false;
	}

	$locations = get_nav_menu_locations();
	if ( ! isset( $locations[ $location ] ) ) {
		return false;
	}

	$menu_obj = get_term( $locations[ $location ], 'nav_menu' );

	return $menu_obj;
}

function get_exchange_rates() {
	$date = date('d-m-Y');
	if ( ($rows = get_option( 'exchange-rates', '' )) && $rows['date'] == $date) {
		$rows = $rows['rows'];
	} else {
		$response = wp_remote_get( 'http://www.nbrb.by/publications/wmastersd.asp', [
			'body' => [
				'datatype' => 0
			]
		] );
		if (!$response instanceof WP_Error && isset($response['body']) && preg_match_all( "/<tr.*?>(.*?)<\/[\s]*tr>/s", $response['body'], $matches ) ) {
			$rows = array_map( function ( $row ) {
				if ( preg_match_all( "/<td.*?>(.*?)<\/[\s]*td>/s", $row, $matches )
				     && count( $matches[1] ) == 3
				     && in_array( strtolower( $matches[1][0] ), [ 'usd', 'eur' ] ) ) {
					return [
						'name'  => trim( $matches[1][0] ),
						'value' => number_format( str_replace( ",", ".", trim( $matches[1][2] ) ), 3 )
					];
				}
				return null;
			}, $matches[1] );
			add_option( 'exchange-rates', [
				'date' => $date,
				'rows' => $rows = array_filter( $rows )
			] );
		} else if(isset($rows['rows'])) {
			$rows = $rows['rows'];
		}
	}

	return "<ul class=\"menu horizontal-menu exchange-rates\">" .
	       implode( '', array_map( function ( $row ) {
		       return "<li class=\"horizontal-menu__item exchange-rates__item\">" .
		              "<div class=\"exchange-rates__label\">Курс {$row['name']}:</div>" .
		              "<div class=\"exchange-rates__value\">{$row['value']} руб</div>" .
		              "</li>";
	       },  $rows) ) .
	       "</ul>";
}