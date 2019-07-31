<header class="site-header">

	
    <div class="site-header__top">
        <div class="container">
			<?php
			wp_nav_menu( [
				'theme_location' => 'top-left',

				'container'   => false,
				'menu_id'     => 'top-left-menu',
				'menu_class'  => 'menu horizontal-menu subbrands float-left',
				'items_class' => 'horizontal-menu__item subbrands__item',
				'link_class'  => 'menu__link subbrands__link',

				'fallback_cb' => '__return_empty_string'
			] );

			wp_nav_menu( [
				'theme_location' => 'top-right',

				'container'   => false,
				'menu_id'     => 'top-right-menu-1',
				'menu_class'  => 'menu horizontal-menu contacts-menu float-right visible-lg-flex',
				'items_class' => 'horizontal-menu__item contacts-menu__item',
				'link_class'  => 'menu__link contacts-menu__link',
				'link_before' => '<span class="contacts-menu__text-link">',
				'link_after' => '</span>',

				'fallback_cb' => '__return_empty_string'
			] );

			if(is_active_sidebar('exchange-rates')) { ?>
			    <div class="visible-lg">
				    <?php dynamic_sidebar('exchange-rates'); ?>
                </div>
			<?php } ?>
        </div>
    </div>
    <div class="site-header__bottom">
        <div class="container">
			<?php
			if ( has_custom_logo() ) {
				$custom_logo = get_custom_logo();
				if(strpos($custom_logo, '.svg') !== false) {
					preg_match("/\<img\s+(.+)\s+\/>/", $custom_logo, $matches);
				    if($matches) {
					    $attrs = array_map(function($attr) {
					        $explode_attr = explode('="', $attr);
					        return [
                                'name' => trim($explode_attr[0]),
                                'value' => trim($explode_attr[isset($explode_attr[1]) ? 1 : 0], " \t\n\r\0\x0B\"")
                            ];
					    }, preg_split("/\"\s+/", $matches[1]));
					    $attrs = array_combine(array_column($attrs, 'name'),
                            array_column($attrs, 'value'));
                        $uploads = wp_get_upload_dir();//
                        $path_svg = str_replace($uploads['baseurl'], $uploads['basedir'], $attrs['src']);
                        echo preg_replace("/\<img\s+(.+)\s+\/>/", file_get_contents($path_svg), $custom_logo);
				    }
                } else {
				    echo $custom_logo;
                }
			}

			wp_nav_menu( [
				'theme_location' => 'general',

				'container'          => 'nav',
				'container_class'    => 'general-navigation float-right visible-lg-flex',
				'menu_id'            => 'general-menu-1',
				'menu_class'         => 'menu general-menu site-header__general-menu horizontal-menu',
				'items_class'        => 'general-menu__item horizontal-menu__item',
				'active_items_class' => 'general-menu__item_current',
				'link_class'         => 'menu__link general-menu__link',

				'fallback_cb' => '__return_empty_string'
			] );
			?>
            <button class="button-menu text-white hidden-lg" aria-label="Меню">
                <svg class="button-menu__icon apps apps_dots" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 35 35">
                    <rect x="5" y="5" width="5" height="5"></rect>
                    <rect x="15" y="5" width="5" height="5"></rect>
                    <rect x="25" y="5" width="5" height="5"></rect>
                    <rect x="5" y="15" width="5" height="5"></rect>
                    <rect x="15" y="15" width="5" height="5"></rect>
                    <rect x="25" y="15" width="5" height="5"></rect>
                    <rect x="5" y="25" width="5" height="5"></rect>
                    <rect x="15" y="25" width="5" height="5"></rect>
                    <rect x="25" y="25" width="5" height="5"></rect>
                </svg>
                <svg class="button-menu__icon apps apps_cross" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 35 35">
                    <rect x="15" y="0" width="5" height="35"></rect>
                    <rect x="0" y="15" width="35" height="5"></rect>
                </svg>
            </button>
        </div>
    </div>
    <div class="hidden-lg mobile-menu full-block">
        <?php if(is_active_sidebar('exchange-rates')) { ?>
        <div class="padding-block mobile-menu__item">
            <?php dynamic_sidebar('exchange-rates'); ?>
        </div>
        <?php }

        wp_nav_menu( [
            'theme_location' => 'general',

            'container'          => 'nav',
            'container_class'    => 'general-navigation',
            'menu_id'            => 'general-menu-2',
            'menu_class'         => 'menu general-menu site-header__general-menu',
            'items_class'        => 'general-menu__item mobile-menu__item',
            'active_items_class' => 'general-menu__item_current',
            'link_class'         => 'menu__link general-menu__link padding-block',

            'fallback_cb' => '__return_empty_string'
        ] );

        wp_nav_menu( [
            'theme_location' => 'top-right',

            'container'   => false,
            'menu_id'     => 'top-right-menu-2',
            'menu_class'  => 'menu horizontal-menu contacts-menu padding-block mobile-menu__item',
            'items_class' => 'horizontal-menu__item contacts-menu__item',
            'link_class'  => 'menu__link contacts-menu__link',
            'link_before' => '<span class="contacts-menu__text-link">',
            'link_after' => '</span>',

            'fallback_cb' => '__return_empty_string'
        ] );
        ?>
    </div>

    
</header>

