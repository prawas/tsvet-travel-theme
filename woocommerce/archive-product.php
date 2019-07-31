
<?php if (isset($_GET['turoperator']) && $_GET['turoperator'] == 'Tsvet') { ?>
    <?php 
} else if (isset($_GET['turoperator']) && $_GET['turoperator'] == 'Sletat') { ?>
    <?php 
} else { ?>
            <?php tsvet_render_file("template-parts/logos/logo", 'itaka', ['attributes' => [
                'class' => 'itaka-logo header-search__logo'
            ]]); ?>
    <?php 
} ?>
<script>
    var search_labelAdults = '<%= count > 0 ? (count + "в.") : "Любое" %>';
    var search_labelChilds = ' и <%= count %> д.';
    var eventFindOfferUrl, searchResultsAjaxUrl, search_has_more, search_validateUrl, search_resultsUrl, search_variantsUrl;
    <?php if (isset($_GET['turoperator']) && $_GET['turoperator'] == 'Tsvet') { ?>
        searchResultsAjaxUrl = '/wp-admin/admin-ajax.php?action=tsvet_query&query=resultsAjaxUrl';

        search_validateUrl = '/wp-admin/admin-ajax.php?action=tsvet_query&query=validateUrl';
        search_resultsUrl = '/wp-admin/admin-ajax.php?action=tsvet_query&query=resultsUrl';
        search_variantsUrl = '/wp-admin/admin-ajax.php?action=tsvet_query&query=variantsUrl';
    <?php 
} else if (isset($_GET['turoperator']) && $_GET['turoperator'] == 'Sletat') { ?>
        searchResultsAjaxUrl = '/wp-admin/admin-ajax.php?action=sletat_query&query=resultsAjaxUrl';

        search_validateUrl = '/wp-admin/admin-ajax.php?action=sletat_query&query=validateUrl';
        search_resultsUrl = '/wp-admin/admin-ajax.php?action=sletat_query&query=resultsUrl';
        search_variantsUrl = '/wp-admin/admin-ajax.php?action=sletat_query&query=variantsUrl';
    <?php 
} else { ?>
        searchResultsAjaxUrl = '/wp-admin/admin-ajax.php?action=itaka_query&url=strony&_page=4466';

        search_validateUrl = '/wp-admin/admin-ajax.php?action=itaka_query&url=strony&_page=17';
        search_resultsUrl = '/wp-admin/admin-ajax.php?action=itaka_query&url=strony&_page=4466';
        search_variantsUrl = '/wp-admin/admin-ajax.php?action=itaka_query&url=strony&_page=560';
    <?php 
} ?>
</script>
<section class="maintop maintop_<?= isset($_GET['turoperator']) ? strtolower($_GET['turoperator']) : "tsvet" ?>">
    <section class="main">
        <script type="text/html" id="destination-popup-regions-template">
            <span class="option-parent-children-counter">
				<%= parentNodeChildrenCounter %>
				<% if ( Number(String(parentNodeChildrenCounter).slice(-1)) <= 1 || Number(String(parentNodeChildrenCounter).slice(-1)) > 4  || ( parentNodeChildrenCounter >= 10 && parentNodeChildrenCounter < 22) || Number(String(parentNodeChildrenCounter).slice(-1)) == 0 ) { %>
					<% if ( Number(String(parentNodeChildrenCounter).slice(-1)) == 1) { %>
						регион
					<% } else { %>
						 регионов
					<% } %>
				<% } else { %>
					региона
				<% } %>
				<i class="fa fa-chevron-down down"></i>
				<i class="fa fa-chevron-up up"></i>
			</span>
        </script>

        <script type="text/html" id="destination-popup-header-template">
            <div class="destination-popup-header">
                <h3><span class="hidden-xs"><%= title %>:</span><span class="visible-xs"><%= title %></span></h3>
                <span class="destination-popup-close">
				<i class="fa fa-times"></i>
			</span>
            </div>
        </script>

        <script type="text/html" id="destination-popup-footer-template">
            <div class="destination-popup-footer">
                <div class="left-side">
                    <% if (showRegions) { %>
                    <span class="destination-regions destination-regions-open">Развернуть элемент Регион</span>
                    <span class="destination-regions destination-regions-close">Свернуть регионы</span>
                    <% } else { %>
                    <span class="destination-regions">&nbsp;</span>
                    <% } %>
                </div>
                <div class="right-side destination-toggle-translate" data-on-all="Пометить всё" data-off-all="Снять все пометки">
                    <span class="destination-toggle destination-all">Пометить всё</span>
                    <span class="destination-submit btn-details">Выбрать</span>

                </div>
            </div>
        </script>

        <script type="text/html" id="popup-bg-template">
            <!--<div class="popup-bg"></div>-->
        </script>

        <form method="get" action="" id="search-form">
            <input id="filter-input" type="hidden" name="filter" value=""/>
            <input id="page-input" type="hidden" name="currpage" value="1"/>
            <input id="price-type-input" type="hidden" name="pricetype" value="person"/>
            <input id="event-type-input" type="hidden" name="eventtype" value="0"/>
            <input type="hidden" name="lang" value="ru"/>
            <input type="hidden" name="currency" value="pln"/>

            <div class="searchbig">
                <div class="container">
                    <div class="mobilefilters"></div>
                    <div class="filters pernam">
                        <a href="#" id="collapseFilterBtn" class="btn-filters fcollapse hidden-xs"
                           title="Ukryj filtry">
                            <p id="filters-txt" data-filterexpand=" Rozwiń filtry"
                               data-filtercollapse="Zwiń filtry"></p>
                        </a>
                        <script type="text/javascript">
                            var weekDayTitles = [
                                'Вс',
                                'Пн',
                                'Вт',
                                'Ср',
                                'Чт',
                                'Пт',
                                'Сб'
                            ];
                        </script>
                        <script id="calChildsTemp" type="text/html">

                            <input type="text" placeholder="dd.mm.rrrr" name="child_age[]"
                                   value="<% defaultDate %>"/>

                        </script>
                        <div class="affix-wrapper">
                            <?php
                            $date_from = date_create_from_format("d/m/Y", tsvet_get_query_var('departure-date', date("d/m/Y"), $_GET))->format("d.m.Y");
                            $date_to = tsvet_get_query_var('arrival-date', '', $_GET);
                            $enddaterange = date_add(date_create(), new DateInterval("P45D"));
                            $date_to = $date_to ? date_create_from_format("d/m/Y", $date_to) : $enddaterange;
                            $date_to = $date_to->format("d.m.Y");
                            ?>
                            <div class="fRow fDate" id="date-range" data-from="<?= $date_from; ?>" data-to="<?= $date_to; ?>" data-enddaterange="<?= $enddaterange->format("d.m.Y"); ?>" data-newserch-valid-enddaterange="<?= $enddaterange->format("d.m.Y"); ?>">
                                <label>Даты</label>
                                <div>
                                    <div class="infield-label lightholder placeholder-hide">
                                        <input name="date_from" class="search-hidden-input" value="<?= $date_from; ?>">
                                        <input readonly data-target="#fDate-open__from" data-toggle="dropdown" class="input" type="text" id="date_from" autocomplete="off" placeholder="Вылет от">
                                        <div id="fDate-open__from" class="fDate-cal__from">
                                            <div class="dropdown-menu">
                                                <a href="#" class="calendar popover-close">×</a>
                                                <div id="fDate-cal__from"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="infield-label lightholder placeholder-hide">
                                        <input name="date_to" class="search-hidden-input" value="<?= $date_to; ?>">
                                        <input readonly data-target="#fDate-open__to" data-toggle="dropdown" class="input" type="text" id="date_to" autocomplete="off" placeholder="Возврат до">
                                        <div id="fDate-open__to" class="fDate-cal__to">
                                            <div class="dropdown-menu">
                                                <a href="#" class="calendar popover-close">×</a>
                                                <div id="fDate-cal__to"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="fRow fDuration">
                                <label data-title="Длительность" for="duration-select">Длительность</label>
                                <select name="duration" data-trans-name="Длительность" id="duration-select"
                                        data-js-value="duration-select">
                                    <option selected value="">Любое</option>
                                    <option value="short">Недолгое пребывание (меньше чем 6 дней)</option>
                                    <option value="mid1">6-9 дней</option>
                                    <option value="mid2">9-12 дней</option>
                                    <option value="long">13-15+ дней</option>
                                </select>
                            </div>
							<?php
								$multiParam = (isset($_GET['turoperator']) && $_GET['turoperator'] == 'Sletat') ? "multiple" : "";
							?>
                            <div class="fRow fToPopup"
                                 data-placeholder-trans="Введите страну, регион..."
                                 data-popup-title="Выберите направление путешествия">
                                <label for="destination-select" data-title="Страна">Страна</label>
                                <span class="hidden mobile-label">Страна:</span>
                                <select  name="destinations[]" id="country-select" data-trans-name="Страна" <?=$multiParam?>>
                                    <option value="">Любая</option>
                                     <?php
                                    $destination = tsvet_get_query_var('destination', 166, $_GET);
                                    if (isset($_GET['turoperator']) && $_GET['turoperator'] == 'Itaka') {
                                        $destination = $destination == 166 ? "" : $destination; ?>
                                        <optgroup label="Самые популярные">
                                            <?php foreach ([
                                                "cypr" => "Кипр",
                                                "dominikana" => "Доминикана",
                                                "francja" => "Франция",
                                                "kenia" => "Кения",
                                                "oman" => "Оман",
                                                "tajlandia" => "Таиланд",
                                                "wyspy-kanaryjskie" => "Канарские острова"
                                            ] as $code => $country) { ?>
                                                <option value="<?= $code; ?>"><?= $country; ?></option>
                                            <?php 
                                        } ?>
                                        </optgroup>
	                                <?php 
                            } ?>
                                    <optgroup label="От А до Я">
                                        <?php
                                        list($cities, $counties) = get_geos_for_filters();
                                        foreach ($counties as $code => $country) {
                                            echo "<option value=\"{$code}\" data-class=\"option-parent\" " . ($code == $destination ? 'selected' : '') . ">{$country}</option>";
                                            if (isset($cities[$code])) {
                                                foreach ($cities[$code] as $city_id => $city) {
                                                    echo "<option value=\"{$city_id}\" data-class=\"option-child\" " . ($city_id == $destination ? 'selected' : '') . ">{$country} - {$city['name']}</option>";
                                                }
                                            }
                                        } ?>
                                    </optgroup>   
                                </select>
                            </div>
                            <div class="fRow fFrom departure-popup" style="display: block"
                                 data-placeholder-trans="Wpisz miasto..." data-popup-title="Wybierz miejsce wyjazdu">
                                <label data-title="Вылет из" for="departures-select">Вылет из</label>
                                <select name="departures[]" data-trans-name="Вылет из" id="departures-select"
                                        data-js-value="departures-select" multiple class="noneimportant">
	                                <?php
                                if (isset($_GET['turoperator']) && $_GET['turoperator'] == 'Sletat') {
                                    $departure = tsvet_get_query_var('departure', 832, $_GET);
                                    foreach (get_departures_sletat() as $code => $city) { ?>
                                            <option value="<?= $code; ?>" <?= $code == $departure ? 'selected' : ''; ?>><?= $city; ?></option>
		                                <?php 
                                }
                            } else if (isset($_GET['turoperator']) && $_GET['turoperator'] == 'Itaka') {
                                $departure = tsvet_get_query_var('departure', "", $_GET); ?>

                                        <option value="">Любое</option>
                                        <?php foreach (get_departures_itaka() as $code => $city) { ?>
                                            <option value="<?= $code; ?>" <?= $code == $departure ? 'selected' : ''; ?>><?= $city; ?></option>
                                        <?php 
                                    } ?>
                                    <?php 
                                } else {
                                    $departure = tsvet_get_query_var('departure', "", $_GET); ?>

                                        <option value="">Любое</option>
		                                <?php foreach (get_departures_tsvet() as $code => $city) { ?>
                                            <option value="<?= $code; ?>" <?= $code == $departure ? 'selected' : ''; ?>><?= $city; ?></option>
		                                <?php 
                                } ?>
	                                <?php 
                            } ?>
                                </select>
                                <span class="hidden mobile-label">Вылет из:</span>
                                <div class="fake-input">Любое</div>
                            </div>

                            <div class="fRow">
                                <div class="infield-label fParticipants dropdown">
                                    <label>Кол-во человек</label>
                                    <button id="participants-count" class="dropdown-toggle btn" data-toggle="dropdown"
                                            href="#" data-childs="0" data-adults="<?= $adults = tsvet_get_query_var('number-of-adults', 2, $_GET); ?>">
                                        Любое
                                    </button>
                                    <div class="dropdown-menu">
                                        <span class="dropdown-menu-close">&times;</span>
                                        <div class="fPerson fAdults">
                                            <label data-title="Взрослые" for="adults-select">Взрослые</label>
                                            <select name="adults" data-trans-name="Взрослые" id="adults-select"
                                                    data-js-value="adults-select">
                                                <?php for ($i = 0; $i < 7; $i++) { ?>
                                                    <option <?= $adults == $i ? 'selected' : ''; ?> value="<?= $i; ?>"><?= $i; ?></option>
                                                <?php 
                                            } ?>
                                            </select>
                                            <div class="fake-input"><?= $adults; ?></div>
                                        </div>
                                        <div class="fPerson fKids">
                                            <label data-title="Дети" for="childs-select">Дети</label>
                                            <select name="childs" data-trans-name="Дети" id="childs-select"
                                                    data-js-value="childs-select">
                                                <option selected value="0">0</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                            </select>
                                            <div class="fake-input">0</div>
                                        </div>
                                        <div id="childs-ages">
                                            <div>
                                                <label> Ребенок 1 </label>
                                                <div class="controls dropdown">
                                                    <div class="input-append date datepicker">
                                                        <input type="text" placeholder="dd.mm.rrrr" name="child_age[]"/>
                                                        <a href="#" class="datekids-opener"> </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <small>Даты рождения детей требуются для правильной калькуляции цены
                                                предложения
                                            </small>
                                        </div>
                                        <div class="childs-ages-footer">
                                            <a href="#" class="childs-ages-commit">подтвердить</a>
                                            <a href="#" class="childs-ages-rollback">Отменить</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="infield-label">
                                    <label data-title="Питание" for="foods-select">Питание</label>
	                                <?php if (isset($_GET['turoperator']) && $_GET['turoperator'] == 'Sletat') { ?>
                                        <select name="foods[]" data-trans-name="Питание" id="foods-select"
                                            data-js-value="foods-select" multiple>
                                            <option value="115">Завтраки, обеды, ужины, напитки</option>
                                            <option value="114">Завтраки</option>
                                            <option value="112">Завтраки, обеды, ужины</option>
                                            <option value="121">Завтраки, обеды, ужины - расширенное меню</option>
                                            <option value="113">Завтраки, ужины</option>
                                            <option value="122">Завтраки, ужины - расширенное меню</option>
                                            <option value="117">Без питания</option>
                                            <option value="116">Завтраки, обеды, ужины, напитки - расширенное меню</option>
                                        </select>
                                    <?php 
                                } else { ?>
                                        <select name="foods[]" data-trans-name="Питание" id="foods-select"
                                                data-js-value="foods-select" multiple>
                                            <option value="6">3 posiłki</option>
                                            <option value="1">Все включено</option>
                                            <option value="4">Без питания</option>
                                            <option value="3">Завтрак</option>
                                            <option value="2">śniadania i obiadokolacje</option>
                                        </select>
                                    <?php 
                                } ?>
                                </div>
                            </div>
                            <div class="fRow standard ">
                                <label data-title="Категория отеля" for="standard-select">Категория отеля</label>
                                <select name="standard" data-trans-name="Категория отеля" id="standard-select"
                                        data-js-value="standard-select">
                                    <option selected value="">Любое</option>
                                    <option value="3plus">От 3*</option>
                                    <option value="4plus">От 4*</option>
                                    <option value="5plus">От 5*</option>
                                </select>
                            </div>
                            <div class="fRow">
                                <label data-title="Цена за человека" for="price-select">Цена за человека</label>
                                <select name="price" data-trans-name="Цена за человека" id="price-select"
                                        data-js-value="price-select">
                                    <option selected value="">Любое</option>
                                    <option value="upto2">До 1000 РУБ</option>
                                    <option value="2to3">1000 РУБ - 1600 РУБ</option>
                                    <option value="3to4">1600 РУБ - 2100 РУБ</option>
                                    <option value="4andup">2100+ РУБ</option>
                                </select>
                            </div>
                            <?php if (isset($_GET['turoperator']) && $_GET['turoperator'] == 'Itaka') { ?>
                            <div class="fRow">
                                <label data-title="Акция" for="promotions-select">Акция</label>
                                <select name="promotions[]" data-trans-name="Акция" id="promotions-select"
                                        data-js-value="promotions-select" multiple>
                                    <option value="itakihit">I...taki HIT!</option>
                                    <option value="lmsonly">Last Minute</option>
                                    <option value="galaonly">Itaka &amp; Gala</option>
                                    <option value="odnowacenowa">Odnowa cenowa</option>
                                    <option value="winter1718">ZIMA 17/18</option>
                                    <option value="winter1718childs">ZIMA 17/18 - dziecko -50%</option>
                                    <option value="weekInWeek">TYDZIEŃ W TYDZIEŃ</option>
                                    <option value="weekInWeekChilds">TYDZIEŃ W TYDZIEŃ - dziecko -50%</option>
                                </select>
                            </div>
                            <div class="fRow">
                                <label data-title="Удобства" for="easements-select">Удобства</label>
                                <select name="easements[]" data-trans-name="Удобства" id="easements-select"
                                        data-js-value="easements-select" multiple>
                                    <option value="36">Bez paszportu</option>
                                    <option value="21">Zjeżdżalnie wodne</option>
                                    <option value="9">Ułatwienia dla niepełnosprawnych</option>
                                    <option value="16">Golf</option>
                                    <option value="17">Tenis</option>
                                    <option value="14">Sporty wodne</option>
                                    <option value="10">Windsurfing</option>
                                </select>
                            </div>
                            <?php 
                        } ?>
                            <div class="fRow">
                                <label data-title="Оценки" for="grade-select">Оценки</label>
                                <select name="grade" data-trans-name="Оценки" id="grade-select"
                                        data-js-value="grade-select">
                                    <option selected value="">Любое</option>
	                                <?php if (isset($_GET['turoperator']) && $_GET['turoperator'] == 'Sletat') {
                                    for ($grade = 3; $grade < 10; $grade += 0.5) { ?>
                                            <option value="<?= $grade; ?>">Рейтинг от <?= number_format($grade, 1, ',', ''); ?></option>
                                        <?php 
                                    }
                                } else { ?>
                                        <option value="3plus">Рейтинг от 3,0</option>
                                        <option value="3halfplus">Рейтинг от 3,5</option>
                                        <option value="4plus">Рейтинг от 4,0</option>
                                        <option value="4halfplus">Рейтинг от 4,5</option>
                                        <option value="5plus">Рейтинг от 5,0</option>
                                        <option value="5halfplus">Рейтинг от 5,5</option>
                                    <?php 
                                } ?>
                                </select>
                            </div>
                        </div>
                    </div>

	                <?php if (isset($_GET['turoperator']) && $_GET['turoperator'] == 'Itaka') { ?>
                    <ul id="filter-tabs" class="nav nav-tabs">
                        <li class="active"><a href="#" data-filter="">Все предложения</a></li>
                        <li><a href="#" data-filter="bestsellers">Самые популярные</a></li>
                        <?php if ($_GET['turoperator'] == 'Itaka') { ?>
                        <li><a href="#" data-filter="lastminute">Last Minute</a></li>
                        <?php 
                    } ?>
                    </ul>
	                <?php 
            } ?>
                </div>
            </div>
        </form>
    </section>
</section>
<section class="mainbottom">
    <section class="main">
        <div class="levall">
            <script type="text/html" id="rating-item-template">
                <% if ( typeof showOpinionsProjectIframe !== 'undefined' && showOpinionsProjectIframe === true && ratingHtml !== '') { %>
                <span class="hotel-list-flag">
					<span class="hotel-rank"><%= ratingHtml %></span>
					<span class="hotel-rank-max"> / <?= (isset($_GET['turoperator']) && $_GET['turoperator'] == 'Sletat') ? 10 : 6; ?></span>
				</span>
                <% } %>
            </script>

            <script type="text/html" id="results-counter-template">
                <span class="results-counter">(Результаты  <%- counter %>)</span>
            </script>
            <script type="text/html" id="assets-item-template">
                <ul class="hotel-list-description <?= strtolower(isset($_GET['turoperator']) ? $_GET['turoperator'] : ""); ?>">
                    <% _.each(list, function(text) { %>
                    <li><%= text %></li>
                    <% }); %>
                </ul>
                <?php if (isset($_GET['turoperator']) && $_GET['turoperator'] == 'Sletat') { ?>
                    <a href="#" class="read-more-list-desc">раскрыть полный список</a>
                <?php 
            } ?>
            </script>
            <script type="text/html" id="special-attr-item-template">
                <ul class="product-icon-promotion">
                    <% _.each(list, function(text) { %>
                    <li title="<%- text.icon %>"><span><strong><%- text.icon %></strong></span></li>
                    <% }); %>
                </ul>
            </script>
            <script type="text/html" id="load-more-item-template">
                <div class="btn-load-more-offers">
                    <a href="#" id="load-more-offers" class="btn load-more-offers load-more-offers_<?= strtolower(tsvet_get_query_var('turoperator', 'tsvet', $_GET)); ?>">Загрузить еще</a>
                </div>
            </script>
            <script type="text/html" id="no-results-item-template">
                <div class="results-info">
                    Ничего не найдено <br/>
                    <span> Предложений, соответствующих указанным критериям не найдено.<br/>
			<br/>
			Чтобы найти<br/>
					 тур соответствующий Вашим ожиданиям удалите один или несколько выбранных критериев.</span>
                </div>
            </script>
            <script type="text/html" id="loading-item-template">
                <div class="results-loading">загрузка</div>
            </script>

            <script type="text/html" id="variants-popover-template">

                <% if(_.size(otherDepartures)) { %>
                <div class="departure-variants">
                    <div class="popover-departure"><%- departuresLabel %>:</div>
                    <ul>
                        <% _.each(otherDepartures, function(departure) { %>
                        <li><a href="<%= departure.url %>"><%- departure.title %> <span class="price"> <strong> <%= departure.price %></strong><span
                                            class="pln">&nbsp;pln</span></span></a></li>
                        <% }); %>
                    </ul>
                </div>
                <% } %>
                <% if(_.size(otherFoodTypes)) { %>
                <div class="food-variants">
                    <div class="food-variants__tittle">Питание:</div>
                    <ul>
                        <% _.each(otherFoodTypes, function(food) { %>
                        <li><a href="<%= food.url %>"><%- food.title %> <span
                                        class="price"> <strong> <%= food.price %></strong><span
                                            class="pln">&nbsp;pln</span></span><a/></li>
                        <% }); %>
                    </ul>
                </div>
                <% } %>
            </script>

            <script type="text/html" id="list-item-template">
                <div class="hotel-list-item" data-product-code="<%= productCode %>" data-start-date="<%= startDate %>"
                     data-dep-name="<%= depName %>">
                    <a class="hidden mobile-link" href="<%= url %>"></a>
                    <div class="hotel-list-photo-preview">
                        <a href="<%= url %>" title="Показать подробности">
                            <img src="https://itaka.pl/cms/img/1/sp.gif" data-original="<%= photo %>"
                                 alt="<%- title %>"/>
                            <% if(ratingHtml !== '') { %>
                            <span class="hotel-list-rating">
								<%= ratingHtml %>
							</span>
                            <% } %>
                            <span title="Więcej zdjęć" class="hotel-list-gallery"
                                  data-search-gallery="<%= searchGallery %>"></span>
                            <span class="hotel-list-gallery-info">Посмотреть фото</span>
                        </a>
                    </div>

                    <div class="hotel-list-content <% if (isPromotionPriceAvailable){ %> ymaxPrice_search_wrapper<% } %>">
                        <div>
                            <header>
                                <h2 data-offerlink="<%= url %>">
                                    <a href="<%= url %>" title="Детали предложения - <%- title %>"><%- title %></a>
                                    <%= stars %><span class="hotel-country"><%= destination %></span>
                                </h2>
                            </header>
                            <section class="hotel-list-bottom">
                                <div class="hotel-list-description"><%= assets %></div>
                                <div class="hotel-list-description-show"></div>
                                <div class="product-promotion">
                                    <%= offerAttributes %>

                                    <% if(_.size(promotionIcon)) { %>
                                    <% _.each(promotionIcon, function(promoIcon) { %>
                                    <ul class="product-icon-promotion">
                                        <li class="icon-<%= promoIcon.icon %>" title="Акция <%= promoIcon.text %>">
                                            <span><strong><%= promoIcon.text %></strong></span></li>
                                    </ul>
                                    <% }) %>
                                    <% } %>
                                </div>

                            </section>
                        </div>
                        <div>
                            <div class="hotel-list-configuration"
                                 title="<%= dateFromFull %> - <%= dateToFull %> (<%= duration %> дней), <%= departureName %>">
                                <% if (ownDeparture){ %>
                                <span class="own-departure-label"><%= dateFrom %> - только проживание</span>
                                <% } else { %>
                                <a class="offer-variants pricing" data-variants-id="<%= offerId %>"><%= dateFrom %> (<%=
                                    duration %> дней) - <%= departure %></a>
                                <% } %>
                                <% if (isPromotionPriceAvailable){ %>
                                <div class="ymaxPrice_search">
                                    <span class="ymax_percent">-<%= percentItakaHit %>%</span>
                                    <span class="price <%= priceTypeClass %>" title="<%= priceTypeLabel %>">
											<strong><%= ymaxPriceItakaHit %><span
                                                        class="price-line_through"></span></strong>
											<span class="pln">Руб</span>
											<i class="fa fa-user hidden-xs"></i>
											<i class="fa fa-users hidden-xs"></i>
										</span>
                                </div>
                                <% } %>
                                <span class="price <%= priceTypeClass %> price-main"
                                      title="<%= priceTypeLabel %>"><strong><%= price %></strong><span
                                            class="pln">Руб</span></span>
                                <!--<span class="price old_person" title="Cena przed obniżką">2 109</span> -->
                            </div>
                            <div class="hotel-list-offer-details">
                                <a class="btn-details" title="Показать подробности" href="<%= url %>">Подробнее</a>
                                <!--<a title="" href="#" class="save add_to_clipboard" data-ofr-id="<%= offerId %>"><i class="fa fa-heart"></i> Добавить в избранное</a>-->
                            </div>
                        </div>
                    </div>
                </div>
            </script>

            <script type="text/html" id="list-item-plus7-template">
                <div class="hotel-list-item plus7" data-product-code="<%= productCode %>"
                     data-start-date="<%= startDate %>" data-dep-name="<%= depName %>">
                    <a class="hidden mobile-link" href="<%= url %>"></a>
                    <div class="first-event-data">
                        <div class="hotel-list-photo-preview">
                            <span class="hotel-event-flag"><span class="hotel-type"><span>wycieczka</span></span></span>
                            <a href="<%= url %>" title="Показать подробности">
                                <img src="/cms/img/1/sp.gif" data-original="<%= photo %>" alt="<%- title %>"/>
                                <% if(ratingHtml !== '') { %>
                                <span class="hotel-list-rating">
									<%= ratingHtml %>
								</span>
                                <% } %>
                                <span title="Więcej zdjęć" class="hotel-list-gallery"
                                      data-search-gallery="<%= searchGallery %>"></span>
                                <span class="hotel-list-gallery-info">Посмотреть фото</span>
                            </a>
                        </div>

                        <div class="hotel-list-content">
                            <header>
                                <h2 data-offerlink="<%= url %>">
                                    <%- title %><%= stars %><span class="hotel-country"><%= destination %></span>
                                </h2>
                            </header>
                            <section class="hotel-list-bottom">
                                <div class="hotel-list-description"><%= assets %></div>
                                <div class="hotel-list-description-show"></div>
                            </section>
                        </div>
                    </div>

                    <div class="second-event-data">
                        <div class="hotel-list-photo-preview">
                            <span class="hotel-event-flag"><span class="hotel-type"><span>wczasy</span></span></span>
                            <a href="<%= url %>" title="Показать подробности">
                                <img src="/cms/img/1/sp.gif" data-original="<%= additionalHotel.photo %>"
                                     alt="<%- additionalHotel.title %>"/>
                                <% if(additionalHotel.ratingHtml !== '') { %>
                                <span class="hotel-list-rating">
									<%= additionalHotel.ratingHtml %>
								</span>
                                <% } %>
                                <span title="Więcej zdjęć" class="hotel-list-gallery"
                                      data-search-gallery="<%= additionalHotel.searchGallery %>"></span>
                                <span class="hotel-list-gallery-info">Посмотреть фото</span>
                            </a>
                        </div>

                        <div class="hotel-list-content">
                            <header>
                                <h2 data-offerlink="<%= url %>"><%- additionalHotel.title %><%= additionalHotel.stars %>
                                    <span class="hotel-country"><%= additionalHotel.destination %></span>
                                </h2>

                            </header>
                            <section class="hotel-list-bottom">
                                <div class="hotel-list-description"><%= additionalHotel.assets %></div>
                                <div class="hotel-list-description-show"></div>
                                <div class="product-icon-promotion"><%= offerAttributes %><%=
                                    additionalHotel.offerAttributes %>
                                </div>
                            </section>
                        </div>
                    </div>

                    <div class="event-combination-data">
                        <div class="hotel-list-configuration"
                             title="<%= dateFromFull %> - <%= dateToFull %> (<%= duration %> дней), <%= departureName %>">
                            <span class="offer-variants" data-variants-id="<%= offerId %>"><%= dateFrom %> (<%= duration %> дней) - <%= departure %></span>
                            <span class="price <%= priceTypeClass %>"
                                  title="<%= priceTypeLabel %>"><strong><%= price %></strong><span
                                        class="pln">Руб</span></span>
                            <!--<span class="price old_person" title="Cena przed obniżką">2 109</span> -->
                        </div>

                        <div class="hotel-list-offer-details">
                            <a class="btn-details" title="Показать подробности" href="<%= url %>">Подробнее</a>
                            <!--<a href="#" class="save" class="save add_to_clipboard" data-ofr-id="<%= offerId %>"><i class="fa fa-heart"></i> Добавить в избранное</a>-->
                        </div>
                    </div>

                </div>
            </script>

            <div id="loader-wrapper"></div>
            <div id="search-results" class="as-list container search-results search-results_<?= strtolower(tsvet_get_query_var('turoperator', 'tsvet', $_GET)); ?>"></div>
        </div>
    </section>
</section>
