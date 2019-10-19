<?php
/* @var $content string */
?>
<div class="search-form">
	<div class="search-form__row search-form__tabs">
		<div class="container search-form__group">
			<div class="search-form__container-tabs" id="opmenu">
        <a href="/#opmenu">
        <label class="search-form__radio radio">
          <span class="radio__label search-form__label-radio <?= ($active === 'tsvet' ? 'active' : '') ?>">Туры от «Цвет трэвел»</span>
        </label>
        </a>
        <a href="/туры-из-польши/#opmenu"> 
				<label class="search-form__radio radio">
					<span class="radio__label search-form__label-radio <?= ($active === 'itaka' ? 'active' : '') ?>">Вылеты из Варшавы</span>
				</label>
        </a>
        <a href="/туры-из-беларуси/#opmenu">
				<label class="search-form__radio radio">
					<span class="radio__label search-form__label-radio <?= ($active === 'sletat' ? 'active' : '') ?>">Вылеты из Беларуси, России и Украины</span>
				</label>
        </a>
        <a href="/автобусные-туры/#opmenu">
				<label class="search-form__radio radio">
					<span class="radio__label search-form__label-radio <?= ($active === 'bus' ? 'active' : '') ?>">Автобусные туры</span>
				</label>
        </a>
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
  <div id="tsvet" class="tab-pane fade <?= ($active === 'tsvet' ? 'in active' : '') ?>">
   <?php if ($active === 'tsvet'): ?>
    <section class="section">
      <div class="section__container container">
        <div class="row">
          <div class="col-lg-1"></div>
          <div class="col-lg-10">
            <h2 class="section__title">Заявка на подбор тура</h2>

            <p>Уважаемый клиент!</p>
            <p>Мы хотим сделать Ваш отдых качественным и незабываемым. Чтобы вернувшись, Вы еще несколько месяцев были полны энергий и&nbsp;эмоционально заряжены для&nbsp;новых целей и&nbsp;подвигов.</p>
            <p>Для этого нам необходимо максимально полно представлять о&nbsp;Ваших пожеланиях, прошлом опыте путешествий и&nbsp;цели текущего путешествия. Чем точнее Вы расскажете нам об&nbsp;этом, тем лучше и&nbsp;счастливее мы сможем подобрать для Вас идеальный отдых.</p>
          </div>
        </div>
        <div class="section__content"><?= do_shortcode('[tsvet_poll]') ?></div>
      </div>
    </section>
    <?php endif; ?>
  </div>
  <div id="itaka" class="tab-pane fade <?= ($active === 'itaka' ? 'in active' : '') ?>">
    <?php if ($active === 'itaka'): ?>
    <section class="section">
      <div class="section__container container">
        <div class="section__flex-title">
          <h2 class="section__title">Вылеты из&nbsp;Варшавы</h2>
          <div class="section__title-curr-calc">
            <h5>Пересчет PLN в&nbsp;BYN</h5>
            <div class="form-group">
            <div class="input-group">
              <input type="number" step="0.01" class="form-control" name="pln" id="curr-calc-pln" data-course="<?= do_shortcode('[exrate cur="PLN"]') ?>">
              <span class="input-group-addon">PLN</span>
            </div>
            </div>
            <div class="form-group">
            <div class="input-group">
              <input type="number" step="0.01" class="form-control" name="byn" id="curr-calc-byn" data-course="<?= do_shortcode('[exrate cur="PLN"]') ?>">
              <span class="input-group-addon" readonly>BYN</span>
            </div>
            </div>
          </div>
        </div>
        <div class="section__content">
          <iframe id="iframe-itaka" src="https://tsvet.itaka24.eu/ru/" style="width: 100%; height: 5800px" frameborder="no"></iframe>
        </div>
      </div>
    </section>
    <?php endif; ?>
  </div>
  <div id="sletat" class="tab-pane fade <?= ($active === 'sletat' ? 'in active' : '') ?>">
    <?php if ($active === 'sletat'): ?>
    <section class="section">
      <div class="section__container container">
        <h2 class="section__title">Вылеты из&nbsp;Беларуси, России и&nbsp;Украины</h2>
        <div class="section__content">


        <script type="text/javascript">
        function showteztourSearch() {
            var path = 'https://json.tez-tour.com/static/ats/';
            var now = new Date();
            var dateTo = new Date();
            dateTo.setDate(now.getDate()+7);
            var monthes = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
            var teztourSearchSettings = {
                "fromCountryId":[1102],
                "fromCityId":[345],
                "toCountryId":1104,
                "departureDateMin":( now.getDate() < 10 ? "0"+now.getDate() : now.getDate() )+"."+monthes[now.getMonth()]+"."+now.getFullYear(),
                "departureDateMax":( dateTo.getDate() < 10 ? "0"+dateTo.getDate() : dateTo.getDate() )+"."+monthes[dateTo.getMonth()]+"."+dateTo.getFullYear(),
                "nightsMin":7,
                "nightsMax":15,
                "nightsLimits":[2,20],
                "adults":2,
                "adultsLimits":[1,12],
                "children":0,
                "childrenLimits":[0,12],
                "childrenBirthday":[],
                "priceMin":0,
                "priceMax":9999,
                "currency":5561,
                "findByPrice":true,
                "tourId":[1285],
                "hotelClassId":[9006279, 9006280, 9006281],
                "feedId":[9006288, 9006289],
                "hotelId":[0],
                "hotelInStop":false,
                "noTicketsTo":false,
                "noTicketsFrom":false,
                "locale":"ru",
                "partnerLink":"<?php echo get_site_url() ?>/tez-order-tour/"
            }
            var JSON=window.JSON||{};JSON.stringify=JSON.stringify||function(obj){var t=typeof(obj);if(t!="object"||obj===null){if(t=="string")obj='"'+obj+'"';return String(obj);}else{var n,v,json=[],arr=(obj&&obj.constructor==Array);for(n in obj){v=obj[n];t=typeof(v);if(t=="string")v='"'+v+'"';else if(t=="object"&&v!==null)v=JSON.stringify(v);json.push((arr?"":'"'+n+'":')+String(v));}return(arr?"[":"{")+String(json)+(arr?"]":"}");}};var url=path+'search_'+teztourSearchSettings.locale+'.html';return('<iframe id="teztourSearchFrame" width="908" height="464" marginwidth="0" marginheight="0" frameborder="0" scrolling="no" name='+JSON.stringify(teztourSearchSettings)+' src="'+url+'"></iframe>');
        };
        </script>

        <div id="teztourSearch" style="width:908px;height:464px;"><script type="text/javascript">document.write(showteztourSearch());</script></div>


        </div>
      </div>
    </section>
    <?php endif; ?>
  </div>

  <div id="bus" class="tab-pane fade <?= ($active === 'bus' ? 'in active' : '') ?>">
    <?php if ($active === 'bus'): ?>
    <section class="section">
      <div class="section__container container">
        <h2 class="section__title">Автобусные туры</h2>
        <div class="section__content">

        <script language="javascript" src="https://widget.belturizm.by:55590/3rd/foo/platform.js?partyId=ivrgwymaaut" async></script>
        <div class="foo-widget" data-foo-id="1"></div>

        </div>
      </div>
    </section>
    <?php endif; ?>
  </div>

</div>


