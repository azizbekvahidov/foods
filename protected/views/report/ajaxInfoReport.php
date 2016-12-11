
<table class="table table-bordered">
  <thead>
    <tr>
      <th>Дата</th>
      <th>Наличные</th>
      <th>Терминал</th>
      <th>Приход</th>
      <th>Азиз терминал</th>
      <th>Касса</th>
      <th>торт Шамс</th>
      <th>Мясо</th>
      <th>Другие</th>
      <th>Гос. банк</th>
      <th>Официанты</th>
      <th>Ген. Дир.</th>
    </tr>
  </thead>
  <tbody>
    <?foreach ($model as $key => $value) {?>
      <tr>
        <td><?=date('d.m.Y',strtotime($value['info_date']))?></td>
        <td><?=$value['proceed']-$value['term']?></td>
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
