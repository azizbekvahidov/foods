<div class="span5 well">
    <h4>Блюда</h4>
    <ol>
    <? foreach ($model as $val) {?>
        <li><?=CHtml::link($val->name,array('dishes/update?id='.$val->dish_id),array('target'=>'_blank'))?></li>
    <?} ?>
    </ol>
</div>
<div class="span5 well">
    <h4>Полуфабрикаты</h4>
    <ol>
        <? foreach ($model2 as $val) {?>
            <li><?=CHtml::link($val->name,array('halfstaff/update?id='.$val->halfstuff_id),array('target'=>'_blank'))?></li>
        <?} ?>
    </ol>
</div>