<article id="post-<?php the_ID(); ?>" <?php post_class('tsvet-post'); ?>>
    <time class="tsvet-post__created">
        <span class="tsvet-post__day-created"><?= get_the_date('d'); ?></span>
        <span class="tsvet-post__month-created"><?= get_the_date('F'); ?></span>
    </time>

	<?php if ( '' !== get_the_post_thumbnail() ) { ?>
        <div class="tsvet-post__thumbnail hidden-xs">
			<?php if(is_single()) { ?>
                <a href="<?= get_permalink(); ?>" class="tsvet-post__link-thumbnail">
					<?php the_post_thumbnail( 'post-thumbnail' ); ?>
                </a>
			<?php } else { ?>
                <div class="tsvet-post__link-thumbnail">
					<?php the_post_thumbnail( 'post-thumbnail' ); ?>
                </div>
			<?php } ?>
        </div><!-- .post-thumbnail -->
	<?php } ?>

    <div class="tsvet-post__container">
        <div class="tsvet-post__title">
			<?php the_title(); ?>
        </div>
		
		<?php if ( '' !== get_the_post_thumbnail() ) { ?>
			<div class="tsvet-post__thumbnail visible-xs">
				<?php if(is_single()) { ?>
					<a href="<?= get_permalink(); ?>" class="tsvet-post__link-thumbnail">
						<?php the_post_thumbnail( 'post-thumbnail' ); ?>
					</a>
				<?php } else { ?>
					<div class="tsvet-post__link-thumbnail">
						<?php the_post_thumbnail( 'post-thumbnail' ); ?>
					</div>
				<?php } ?>
			</div><!-- .post-thumbnail -->
		<?php } ?>
		
        <div class="tsvet-post__content">
			<?php
			global $more;
			$more = is_single() ? 1 : 0;
			the_content('Читать подробнее');
			?>
        </div>
    </div>
</article>