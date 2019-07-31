<?php
/**
 * Description tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/description.php.
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
 * @version     2.0.0
 * @var $params []
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;
$destination_title = $params['country'] . ($params['region'] ? " \ {$params['region']}" : "");
?>
<div class="event-information">
    <div class="tab-content">
        <div class="acc-trigger-element">
            <?php if($params['category_name'] != 'sletat') { ?>
            <table class="event-assetsandopinion">
                <tbody>
                <tr>
                    <td class="event-assets hidden-xs">
                        <h3>Преимущества предложения</h3>
                        <ul>
                            <?php foreach(array_filter(explode("\r\n", $post->post_excerpt)) as $except) { ?>
                                <li><?= $except; ?></li>
                            <?php } ?>
                        </ul>
                    </td>
                    <?php if($params['_wc_average_rating'] || $params['percent_rating']) { ?>
                        <td class="event-opinion hidden-xs">
                            <?php if($params['_wc_average_rating']) { ?>
                                <div class="event-opinion-flag">
                                    <strong><?= $params['_wc_average_rating']; ?></strong> / 6
                                </div>
                            <?php } ?>

                            <?php if($params['percent_rating']) { ?>
                                <div class="good-opinion-percentage">
                                    <strong><?= $params['percent_rating']; ?></strong> рейтинг высокий и очень высокий
                                </div>
                            <?php } ?>
                        </td>
                    <?php } ?>
                </tr>
                </tbody>
            </table>
            <?php } ?>
            <?php the_content(); ?>
        </div>
    </div>
</div>