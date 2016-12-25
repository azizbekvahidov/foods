
<table class="table table-bordered">
  <thead>
    <tr>
        <th rowspan="2">Дата</th>
        <th rowspan="2">Ост на начало дня</th>
        <th colspan="8">Приход</th>
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
    </tr>
  </thead>
  <tbody>
    <?foreach ($model as $key => $value) {?>
      <tr>
        <td><?=date('d.m.Y',strtotime($value['info_date']))?></td>
        <td><?=$value['proceed']-$value['term']-$value['genDir']?></td>
        <td><?=$value['term']?></td>
        <td><?=$value['parish']?></td>
        <td><?=$value['azizTerm']?></td>
        <td><?=$value['kassa']?></td>
        <td><?=$value['tortShams']?></td>
        <td><?=$value['meat']?></td>
        <td><?=$value['other']?></td>
        <td><?=$value['gosBank']?></td>
        <td><?=$value['waitor']?></td>
        <td><?=$value['genDir']?></td>
      </tr>
    <?}?>
  </tbody>
</table>
