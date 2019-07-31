<?php
/* @var $posts_category WP_Post[] */
?>
<div class="posts-category">
	<?php foreach($posts_category as $post_category) { ?>
		<div class="post-category">
			<div class="post-category__container">
				<h2 class="post-category__title">
					<?= $post_category->post_title; ?>
				</h2>
				<div class="post-category__content">
					<?= $post_category->post_content; ?>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
