<footer class="site-footer">
    <div class="container site-footer__content">
		<div class="footer-left-sidebar float-left">
		<?php
			if(is_active_sidebar('footer-left')) {
				dynamic_sidebar('footer-left');
			}
		?>
		</div>

        <div class="footer-center hidden-lg">

			<div style="text-align:center">				
				<img class="site-footer__logo" src="/wp-content/uploads/2019/07/logo-white.svg" alt="цвет трэвел"/>
			</div>
			


		    <div class="footer-center__bottom">
                <?php
                    wp_nav_menu([
                        'theme_location' => 'footer',

                        'container'   => false,
                        'menu_id'     => 'footer-menu_copy',
                        'menu_class'  => 'menu horizontal-menu socials',
                        'items_class' => 'horizontal-menu__item socials__item',
                        'link_class'  => 'menu__link socials__link',

                        'fallback_cb' => '__return_empty_string'
                    ]);

                    wp_nav_menu([
                        'theme_location' => 'legal-information',

                        'container'   => false,
                        'menu_id'     => 'ur-menu_copy',
                        'menu_class'  => 'menu horizontal-menu ur-menu',
                        'items_class' => 'horizontal-menu__item ur-menu__item',
                        'link_class'  => 'menu__link ur-menu__link',

                        'fallback_cb' => '__return_empty_string'
                    ]);
                ?>
            </div>
        </div>

		<div class="footer-right-sidebar float-right">
		<?php
			if(is_active_sidebar('footer-right')) {
				dynamic_sidebar('footer-right');
			}
        ?>
		</div>
		<div class="footer-center visible-lg">
			
			<div style="text-align:center">
				<img class="site-footer__logo" src="/wp-content/uploads/2019/07/logo-white.svg" alt="цвет трэвел"/>	
			</div>
			
			<div class="footer-center__bottom">
                <?php
                    wp_nav_menu( [
                        'theme_location' => 'footer',

                        'container'   => false,
                        'menu_id'     => 'footer-menu',
                        'menu_class'  => 'menu horizontal-menu socials',
                        'items_class' => 'horizontal-menu__item socials__item',
                        'link_class'  => 'menu__link socials__link',

                        'fallback_cb' => '__return_empty_string'
                    ] );

                    wp_nav_menu( [
                        'theme_location' => 'legal-information',

                        'container'   => false,
                        'menu_id'     => 'ur-menu',
                        'menu_class'  => 'menu horizontal-menu ur-menu',
                        'items_class' => 'horizontal-menu__item ur-menu__item',
                        'link_class'  => 'menu__link ur-menu__link',

                        'fallback_cb' => '__return_empty_string'
                    ] );
                ?>
            </div>
		</div>
	</div>
</footer>

<div id="transfer-modal" class="modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Заказать трансфер</h4>
      </div>
      <div class="modal-body">
        <?= do_shortcode('[contact-form-7 id="2111" title="Заказать трансфер"]') ?>
      </div>
    </div>
  </div>
</div>