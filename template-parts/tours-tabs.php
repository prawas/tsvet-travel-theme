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
					<span class="radio__label search-form__label-radio" data-toggle="tab" href="#sletat">Вылеты из Беларуси и России</span>
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
          <iframe src="https://tsvet.itaka24.eu/ru/" style="width: 100%; height: 5800px" frameborder="no"></iframe>
        </div>
      </div>
    </section>
  </div>
  <div id="sletat" class="tab-pane fade">
    <section class="section">
      <div class="section__container container">
        <h2 class="section__title">Вылеты из&nbsp;Беларуси и&nbsp;России</h2>
        <div class="section__content">


        <script type="text/javascript">
        function showteztourSearch() {
            var path = 'http://json.tez-tour.com/static/ats/';
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
  </div>
</div>


