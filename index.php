<div class="container tsvet-posts">
	<?php if ( have_posts() ) { ?>
		<?php
		// Start the loop.
		while ( have_posts() ) {
			the_post();

			/*
			 * Include the Post-Format-specific template for the content.
			 * If you want to override this in a child theme, then include a file
			 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
			 */
			echo tsvet_render_file('template-parts/post/content');

			// End the loop.
		}

		the_posts_pagination([
			'mid_size' => 2,
			'prev_text' => 'Предыдущая',
			'next_text' => 'Следующая',
		]);
// If no content, include the "No posts found" template.
	} ?>
</div>
<section class="section">
	<div class="section__container container section__container_bg_none">
		<header class="section__header text-center">
			<h2 class="section__title center-block">Ваши комментарии</h2>
			<div class="section__description center-block">Ниже Вы можете оставлять свои заявки на подбор тура, комментарии и вопросы, необходимо быть зарегистрированным в социальной сети ВКонтакте</div>
		</header>
		<?= vk_comments_shortcode([]); ?>
	</div>
</section>
