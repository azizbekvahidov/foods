<style>
    .infoDiv30{
        width: 30%;
    }
</style>
<div id="container"></div>
<div class="span12">
    <div class="span4 infoDiv30">
        <h5 style="text-align: center; color: rgb(124, 181, 236)">Без процента</h5>
        <label><strong>Среднедневная выручка по месяцу:</strong> <?=number_format($averSum/$count,0,',',' ')?></label>
        <label><strong>Выручка с нарастающим за месяц:</strong> <?=number_format($averSum,0,',',' ')?></label>
    </div>
    <div class="span4 infoDiv30">
        <h5 style="text-align: center; color: rgb(67, 67, 72);">С процентом</h5>
        <label><strong>Среднедневная выручка по месяцу:</strong> <?=number_format($averProcSum/$count,0,',',' ')?></label>
        <label><strong>Выручка с нарастающим за месяц:</strong> <?=number_format($averProcSum,0,',',' ')?></label>
    </div>

    <div class="span4 infoDiv30">
        <h5 style="text-align: center; color: rgb(144, 237, 125)    ;">Факт. выручка</h5>
        <label><strong>Среднедневная выручка по месяцу:</strong> <?=number_format($curAverSum/$count,0,',',' ')?></label>
        <label><strong>Выручка с нарастающим за месяц:</strong> <?=number_format($curAverSum,0,',',' ')?></label>
    </div>
</div>
<div class="span12" style="height: 1px; background-color: red"></div>
<div id="realized"></div>
<div class="span12">
    <div class="span4 infoDiv30">
        <h5 style="text-align: center; color: rgb(124, 181, 236)">Приход</h5>
        <label><strong>Среднедневный приход по месяцу:</strong> <?=number_format($realizeSum/$count,0,',',' ')?></label>
        <label><strong>Приход с нарастающим за месяц:</strong> <?=number_format($realizeSum,0,',',' ')?></label>
    </div>
    <div class="span4 infoDiv30">
        <h5 style="text-align: center; color: rgb(67, 67, 72);">Фактический</h5>
        <label><strong>Среднедневный приход по месяцу:</strong> <?=number_format($curRealizeSum/$count,0,',',' ')?></label>
        <label><strong>Приход с нарастающим за месяц:</strong> <?=number_format($curRealizeSum,0,',',' ')?></label>
    </div>
</div>
<script>
    $(function () {
        $('#container').highcharts({
            title: {
                text: 'Выручка ежедневная',
                x: -20 //center
            },
            subtitle: {
                //text: 'Source: WorldClimate.com',
                x: -20
            },
            zoneAxis: 'x',

            xAxis: {
                categories: [<?=$dateList?>]
            },
            yAxis: {
                title: {
                    text: 'Сумма'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: 'сум'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'Выручка без %',
                data: [<?=$summ?>]
            },{
                name: 'Выручка c %',
                data: [<?=$summP?>]
            },{
                name: 'Факт. выручка',
                data: [<?=$curProceed?>]
            }, ]
        });
    });
    $(function () {
        $('#realized').highcharts({
            title: {
                text: 'Приход Ежедневный',
                x: -20 //center
            },
            subtitle: {
                //text: 'Source: WorldClimate.com',
                x: -20
            },
            zoneAxis: 'x',

            xAxis: {
                categories: [<?=$dateList?>]
            },
            yAxis: {
                title: {
                    text: 'Сумма'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: 'сум'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'Приход',
                data: [<?=$realize?>]
            },{
                name: 'Факт.',
                data: [<?=$curRealize?>]
            }, ]
        });
    });
</script>