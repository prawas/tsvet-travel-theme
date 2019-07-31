<?php
/* @var $content string */
?>
<form role="search" method="get" class="search-form" action="<?= esc_url( home_url( '/' ) ); ?>">
	<div class="search-form__row search-form__tabs">
		<div class="container search-form__group">
			<div class="search-form__container-tabs">
                <label class="search-form__radio radio">
<!--				<div class="search-form__label-tabs">Туры от tsvet.by</div>-->
                    <input type="radio" name="turoperator" class="radio__input" checked="checked" value="Tsvet" />
                    <span class="radio__label search-form__label-radio">Туры от tsvet</span>
                </label>
				<label class="search-form__radio radio">
					<input type="radio" name="turoperator" class="radio__input" value="Itaka" />
					<span class="radio__label search-form__label-radio">Вылеты из Варшавы</span>
				</label>
				<label class="search-form__radio radio">
					<input type="radio" name="turoperator" class="radio__input" value="Sletat"/>
					<span class="radio__label search-form__label-radio">Вылеты из Минска и Москвы</span>
				</label>
			</div>
		</div>
	</div>
	
	<div class="search-form__row">
        <div class="search-form__background full-block">
            <?= $content; ?>
        </div>
		<div class="container search-form__content">
			<label class="search-form__group">
				<i class="icon-country search-form__icon"></i>
				<div class="search-form__wrap-input">
                    <select name="destination" data-placeholder="Страна">
                        <option value="">Страна</option>
                        <optgroup label="От А до Я" data-turoperator="sletat">
                            <?php foreach(get_countries_sletat() as $code => $country) { ?>
                                <option value="<?= $code; ?>" <?= isset($_GET['destination']) && $code == $_GET['destination'] ? 'selected' : ''; ?>><?= $country; ?></option>
                            <?php } ?>
                        </optgroup>
                        <optgroup label="От А до Я" data-turoperator="itaka">
                            <?php foreach(get_countries_itaka() as $code => $country) { ?>
                                <option value="<?= $code; ?>" <?= isset($_GET['destination']) && $code == $_GET['destination'] ? 'selected' : ''; ?>><?= $country; ?></option>
                            <?php } ?>
                        </optgroup>
                        <optgroup label="От А до Я" data-turoperator="tsvet">
	                        <?php foreach(get_countries_tsvet() as $code => $country) { ?>
                                <option value="<?= $code; ?>" <?= isset($_GET['destination']) && $code == $_GET['destination'] ? 'selected' : ''; ?>><?= $country; ?></option>
	                        <?php } ?>
                        </optgroup>
                    </select>
				</div>
			</label>
			<label class="search-form__group">
				<i class="icon-calendar search-form__icon"></i>
				<div class="search-form__wrap-input">
					<input type="text" class="datepicker search-form__input" name="departure-date" placeholder="Дата вылета">
				</div>
			</label>
			<label class="search-form__group">
				<i class="icon-calendar search-form__icon"></i>
				<div class="search-form__wrap-input">
					<input type="text" class="datepicker search-form__input" name="arrival-date" placeholder="Дата прилета">
				</div>
			</label>
			<label class="search-form__group">
				<i class="icon-man search-form__icon"></i>
				<div class="search-form__wrap-input">
					<input type="text" class="search-form__input" name="number-of-adults" placeholder="Кол-во взрослых">
				</div>	
			</label>
			<label class="search-form__group">
				<i class="icon-location search-form__icon"></i>
				<div class="search-form__wrap-input">
                    <select name="departure" data-placeholder="Вылет из">
                        <option value="">Вылет из</option>
                        <optgroup data-turoperator="sletat">
                            <?php foreach(get_departures_sletat() as $code => $city) { ?>
                                <option value="<?= $code; ?>"><?= $city; ?></option>
                            <?php } ?>
                        </optgroup>
                        <optgroup data-turoperator="itaka">
                            <?php foreach(get_departures_itaka() as $code => $city) { ?>
                                <option value="<?= $code; ?>"><?= $city; ?></option>
                            <?php } ?>
                        </optgroup>
                        <optgroup data-turoperator="tsvet">
                            <?php foreach(get_departures_tsvet() as $code => $city) { ?>
                                <option value="<?= $code; ?>"><?= $city; ?></option>
                            <?php } ?>
                        </optgroup>
                    </select>
				</div>
			</label>		
			<div class="search-form__group">
				<input type="submit" value="Искать" class="search-form__submit"/>
			</div>
		</div>
	</div>
	<input type="hidden" name="s" value="" />
	<input type="hidden" name="post_type" value="product" />
</form>
