<? if (!$d['params'][1]) { ?>
<style>
.forum thead {
background: #555555;
font-weight: bold;
color: #FFFFFF;
}
.forum thead td {
padding: 5px 10px;
}
.forum .title {
font-size: 20px;
}
.forum td {
padding: 7px 10px 0px 10px;
}
.forum {
width: 100%;
}
</style>

<? if ($d['tags']) { ?>
<table class="forum">
<thead>
<tr>
  <td>Название форума</td>
  <td>Количество тем</td>
</tr>
</thead>
<tbody>
<? foreach ($d['tags']['forum'] as $v) { ?>
  <tr>
    <td class="title"><a href="<?= Tt::getPath(1).'/t2.forum.'.$v['id'] ?>"><?= $v['title'] ?></a></td>
    <td><?= $v['cnt'] ?></td>
  </tr>
<? } ?>
</tbody>
</table>
<? } else { ?>
  Нет форумов
<? } ?>
<? } else Tt::directTpl('site/base/dd/default', $d) ?>