<style media="screen">
  .table{
    font-size: 10px;
  }
</style>
<? $expense = new Expense();?>
<table class="table table-bordered">
  <thead>
    <tr>
        <th rowspan="2">Дата</th>
        <th rowspan="2">Ост на начало дня</th>
        <th colspan="8">Приход</th>
        <th rowspan="2">ИТОГО</th>
        <th colspan="9">Расход</th>
        <th rowspan="2">ИТОГО РАСХОД</th>
        <th rowspan="2">ОСТАТОК на к-ц дня</th>
    </tr>
    <tr>
        <th>Наличные</th>
        <th>торт Шамс</th>
        <th>Торты магазины</th>
        <th>Терминал</th>
        <th>Доставка нал</th>
        <th>Фирмен счет</th>
        <th>Ген. директор</th>
        <th>Долг персонала</th>
        <th>Магазины закуп</th>
        <th>Торт</th>
        <th>Г/Б</th>
        <th>Терминал</th>
        <th>Нал. касса</th>
        <th>Долг персонал</th>
        <th>Прочие расходы</th>
        <th>Закуп</th>
        <th>Офиц.</th>
    </tr>
  </thead>
  <tbody>
    <?foreach ($model as $key => $value) {
      $summ = $expense->getDebt($value['info_date']);
      $begin = $value['proceed']-$value['term']-$value['genDir'];
      ?>
      <tr>
        <td><?=date('d.m.Y',strtotime($value['info_date']))?></td>
        <td>0</td>
        <td><?=$begin?></td>
        <td><?=$summ['mag']?></td>
        <td><?=$summ['cont']?></td>
        <td><?=$value['term']+$value['azizTerm']?></td>
        <td> </td>
        <td> </td>
        <td><?=$value['genDir']?></td>
        <td><?=$summ['perDebt']?></td>
        <td><?=$begin+$summ['mag']+$summ['cont']+$value['term']+$value['azizTerm']+$value['genDir']+$summ['perDebt']?></td>
        <td><?=$summ['mag']?></td>
        <td><?=$summ['cont']?></td>
        <td><?=$value['gosBank']?></td>
        <td><?=$value['term']?></td>
        <td><?=$value['kassa']+$value['genDir']?></td>
        <td><?=$summ['perDebt']?></td>
        <td><?=$value['other']?></td>
        <td><?=$value['parish']?></td>
        <td><?=$value['waitor']?></td>
        <td><?=$summ['mag']+$summ['cont']+$value['gosBank']+$value['term']+$value['kassa']+$value['genDir']+$summ['perDebt']+$value['other']+$value['parish']+$value['waitor']?></td>
        <td>0</td>
      </tr>
    <?}?>
  </tbody>
</table>
