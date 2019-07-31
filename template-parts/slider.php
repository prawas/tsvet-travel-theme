<?php
/* @var $category WP_Term */
/* @var $slider_posts WP_Post[] */
/* @var $slider_content string */
?>
<?php if($slider_content) { ?>
<div class="wrapper-slider">
	<ul class="slider">
		<?php foreach($slider_posts as $slide) { ?>
			<li class="slider__item">
				<?= $slide->post_content; ?>
			</li>
		<?php } ?>
	</ul>
	<div class="wrapper-slider__bottom">
		<?= $slider_content; ?>
	</div>
</div>
<?php } else { ?>
<ul class="slider">
	<?php foreach($slider_posts as $slide) { ?>
        <li class="slider__item">
			<?= $slide->post_content; ?>
        </li>
	<?php } ?>
</ul>
<?php } ?>