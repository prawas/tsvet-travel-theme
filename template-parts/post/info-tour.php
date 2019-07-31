<?php
/* @var $what_price_tour_include [] */
/* @var $beach_line string */
	/* @var $hotel_services [] */
?>
<div class="product-productdescription">
	<?php if(isset($what_price_tour_include)) { ?>
	<div>
		<strong data-block-type="paspolozhenie">Что входит в стоимость тура: </strong> <?php
			$what_price_tour_include = implode(', ', $what_price_tour_include);
			echo strtoupper($what_price_tour_include[0]) . substr($what_price_tour_include, 1);
		?>
	</div>
	<?php } ?>
	<?php if(isset($beach_line)) { ?>
		<div>
			<strong data-block-type="plyazh">Пляжная линия: </strong> <?= $beach_line; ?>
		</div>
	<?php } ?>
	<?php if(isset($hotel_services)) { ?>
		<div class="not-border">
			<strong data-block-type="otel">Услуги в отеле: </strong> <?= $hotel_services; ?>
		</div>
	<?php } ?>
</div>