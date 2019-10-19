<h2>Общая информация</h2>
<table>
    <tr><td>Имя</td><td><?= $data['info']['name'] ?></td></tr>
    <tr><td>Телефон</td><td><?= $data['info']['phone'] ?></td></tr>
    <tr><td>email</td><td><?= $data['info']['email'] ?></td></tr>
    <tr><td>Отправить ответ:</td><td><?= $data['info']['whereToSend'] ?></td></tr>
</table>

<h2>Тип отдыха, куда и когда</h2>
<table>
    <tr>
        <td>Тип отдыха</td>
        <td>
        <?php
            $rest_types = [];
            foreach ($data['rest_type'] as $type => $enabled) {
                if ( ! $enabled || $type === 'Другое') continue;
                $rest_types[] = $type;
            }
            if (trim($data['rest_type_custom_variant']) !== '') {
                $rest_types[] = trim($data['rest_type_custom_variant']);
            }
            echo implode(', ', $rest_types);
        ?>
        </td>
    </tr>
    <tr>
        <td>Страна</td>
        <td>
            <?php
            $countries = [];
            foreach ($data['country'] as $country => $enabled) {
                if ( ! $enabled ) continue;
                $countries[] = $country;
            }
            echo implode(', ', $countries);
            ?>
        </td>
    </tr>
    <tr>
        <td>Время отдыха</td>        
        <td>С&nbsp;<?= $data['time']['from'] ? date('d.m.Y', strtotime($data['time']['from'])) : '—' ?>
            по&nbsp;<?= $data['time']['to'] ? date('d.m.Y', strtotime($data['time']['to'])) : '—' ?></td>
    </tr>
    <tr><td>Кто летит</td><td><?= $data['who'] ?></td></tr>
</table>

<h2>Предпочтения по отелю</h2>
<table>
    <tr>
        <td>Звездность отеля</td>
        <td>

            <?php
            $stars_variants = [];
            foreach ($data['hotel']['stars_multiple'] as $star_variant => $enabled) {
                if ( ! $enabled ) continue;
                $stars_variants[] = $star_variant;
            }
            echo implode(', ', $stars_variants);
            ?>

        </td>
    </tr>
    <tr>
        <td>Какой тип питания предпочитаете?</td>
        <td>

            <?php
            $food_variants = [];
            foreach ($data['hotel']['food_multiple'] as $food => $enabled) {
                if ( ! $enabled ) continue;
                $food_variants[] = $food;
            }
            echo implode(', ', $food_variants);
            ?>

            <?php if (in_array('Другое', $data['hotel']['food_multiple'])): ?>
                (<?= $data['hotel']['custom'] ?>)
            <?php endif; ?>
        </td>
    </tr>
</table>

<h2>Самое важное на отдыхе</h2>
<h3>(баллы от&nbsp;1 до&nbsp;5)</h3>
<table>
    <tr>
        <td>Море и пляж</td>
        <td><?= +$data['hotel']['features']['sea']+1 ?></td>
    </tr>
    <tr>
        <td>Хороший отель с анимацией</td>
        <td><?= +$data['hotel']['features']['hotel']+1 ?></td>
    </tr>
    <tr>
        <td>Экскурсии</td>
        <td><?= +$data['hotel']['features']['excursions']+1 ?></td>
    </tr>
    <tr>
        <td>Активный регион (бары, дискотеки, шопинг)</td>
        <td><?= +$data['hotel']['features']['activities']+1 ?></td>
    </tr>
    <tr>
        <td>Спокойный отдых, уединенный регион</td>
        <td><?= +$data['hotel']['features']['calm']+1 ?></td>
    </tr>
    <tr>
        <td>Аквапарк</td>
        <td><?= +$data['hotel']['features']['aquapark']+1 ?></td>
    </tr>
    <tr>
        <td>Хорошее питание</td>
        <td><?= +$data['hotel']['features']['food']+1 ?></td>
    </tr>
    <tr>
        <td>Бассейн</td>
        <td><?= +$data['hotel']['features']['swimming']+1 ?></td>
    </tr>
    <tr>
        <td>Алкоголь</td>
        <td><?= +$data['hotel']['features']['alcohol']+1 ?></td>
    </tr>
</table>

<h2>Вылет</h2>
<p>
    <?= $data['departure'] ?>
    <?php if ($data['departure'] === 'другое'): ?>
        (<?= $data['departure_custom'] ?>)
    <?php endif; ?>
</p>

<h2>Финансовые вопросы</h2>
<table>
    <tr><td>Планируемый бюджет</td><td><?= $data['money']['budget'] ?></td></tr>
    <tr>
        <td>Траты</td>
        <td>
            <?= $data['money']['spend'] ?>
            <?php if ($data['money']['spend'] === 'другое'): ?>
                (<?= $data['money']['spend_custom'] ?>)
            <?php endif; ?>
        </td>
    </tr>
    <tr><td>Как удобнее купить</td><td><?= $data['money']['convenience'] ?></td></tr>
</table>

<h2>Дополнительная информация</h2>
<table>
    <tr><td>Пожелания</td><td><?= $data['additional_info']['wishes'] ?></td></tr>
    <tr><td>Где уже отдыхали</td><td><?= $data['additional_info']['experience'] ?></td></tr>
    <tr><td>Как часто путешествуете?</td><td><?= $data['additional_info']['frequency'] ?></td></tr>
    <tr><td>Время года</td><td><?= $data['additional_info']['time'] ?></td></tr>
</table>