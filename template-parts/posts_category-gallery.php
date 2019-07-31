<?php
/* @var $posts_category WP_Post[] */
global $wp_query;
$uploads = wp_get_upload_dir();
?>
<div class="posts-category">
    <svg xmlns="http://www.w3.org/2000/svg" width="0" height="0">
        <defs>
            <filter id="blur" x="-48" y="-44" width="316" height="337">
                <feGaussianBlur stdDeviation="20"></feGaussianBlur>
            </filter>
        </defs>
    </svg>
	<?php foreach($posts_category as $post_category) { ?>
		<div class="post-gallery">
            <div class="post-gallery__container">
                <?php $wp_query->setup_postdata($post_category); ?>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="-46 -40 336 357">
                    <path class="hover-feature" fill="rgba(94, 62, 132, 0.4)" filter="url(#blur)" transform="translate(20 20)" d="M110,249c-2.9,0-5.9-0.8-8.5-2.3L8.5,193c-5.3-3-8.5-8.6-8.5-14.7V70.7C0,64.7,3.2,59.1,8.5,56l93-53.8c2.6-1.5,5.6-2.3,8.5-2.3c2.9,0,5.9,0.8,8.5,2.3l93,53.8c5.3,3,8.5,8.6,8.5,14.7v107.5c0,6.1-3.2,11.7-8.5,14.7l-93,53.8C115.9,248.2,112.9,249,110,249z"></path>
                    <path class="feature" fill="#fff" d="M121.5,276c-3.2,0-6.5-0.8-9.4-2.5L9.4,213.9c-5.8-3.4-9.4-9.6-9.4-16.3V78.4c0-6.7,3.6-12.9,9.4-16.3L112.1,2.5c2.9-1.7,6.1-2.5,9.4-2.5c3.2,0,6.5,0.8,9.4,2.5l102.7,59.6c5.8,3.4,9.4,9.6,9.4,16.3v119.2c0,6.7-3.6,12.9-9.4,16.3l-102.7,59.6C128,275.2,124.7,276,121.5,276z"></path>
				<?php
                $content = get_the_content();
                if(preg_match("/\ssrc=\"([^\"]+)/", $content, $matched)) {
	                $image_path = str_replace($uploads['baseurl'], $uploads['basedir'], $matched[1]);
	                if(file_exists($image_path)) {
	                    list($width, $height) = getimagesize($image_path);
	                    $translate_x = $width / 2;
		                echo "<image xlink:href=\"{$matched[1]}\" x=\"122\" y=\"150\" transform=\"translate(-{$translate_x} -{$height})\" width=\"{$width}\" height=\"{$height}\"></image>";
	                }
                }
                foreach(explode('<br>', get_the_title($post_category)) as $number_line => $line_title) {
				    echo '<text text-anchor="middle" x="122" y="' . ($number_line * 18 + 180) . '" style="font-size: 15px; font-weight: 400;">' . trim($line_title) . '</text>';
                } ?>
                </svg>
			</div>
		</div>
	<?php } ?>
</div>
