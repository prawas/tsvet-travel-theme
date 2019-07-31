<?php
/* @var $content string */
?>
<div class="search-form">
	<div class="search-form__row search-form__tabs">
		<div class="container search-form__group">
			<div class="search-form__container-tabs">
                <label class="search-form__radio radio">
                    <input type="radio" name="turoperator" class="radio__input" value="Tsvet" />
                    <span class="radio__label search-form__label-radio" data-toggle="tab" href="#tsvet">Туры от «Цвет трэвел»</span>
                </label>
				<label class="search-form__radio radio">
					<input type="radio" name="turoperator" class="radio__input" checked="checked" value="Itaka" />
					<span class="radio__label search-form__label-radio" data-toggle="tab" href="#itaka">Вылеты из Варшавы</span>
				</label>
				<label class="search-form__radio radio">
					<input type="radio" name="turoperator" class="radio__input" value="Sletat"/>
					<span class="radio__label search-form__label-radio" data-toggle="tab" href="#sletat">Вылеты из Минска и Москвы</span>
				</label>
			</div>
		</div>
	</div>
    <div class="search-form__row">
        <div class="search-form__background full-block">
            <?= $content; ?>
        </div>
        <div class="container search-form__content"></div>
    </div>
</div>


<div class="tab-content">
  <div id="tsvet" class="tab-pane fade">
    <section class="section">
      <div class="section__container container">
        <h2 class="section__title">Наши предложения</h2>
        <div class="section__content"><?= do_shortcode('[recent_products columns="3" per_page="12"]') ?></div>
      </div>
      <p><a class="section__show-more" href="get_products">Загрузить еще</a></p>
    </section>
  </div>
  <div id="itaka" class="tab-pane fade in active">
    <section class="section">
      <div class="section__container container">
        <h2 class="section__title">Вылеты из&nbsp;Варшавы</h2>
        <div class="section__content">
          <iframe src="https://tsvet.itaka24.eu/ru/" style="width: 100%; height: 5800px" frameborder="no"></iframe>
        </div>
      </div>
    </section>
  </div>
  <div id="sletat" class="tab-pane fade">
    <section class="section">
      <div class="section__container container">
        <h2 class="section__title">Вылеты из&nbsp;Минска и&nbsp;Москвы</h2>
        <div class="section__content">
          <p>2</p>
        </div>
      </div>
    </section>
  </div>
</div>


