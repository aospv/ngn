<h2><?= $d['field']['title'] ?></h2>
<ul>
<? foreach ($d['tags'] as $v) { ?>
 <li <?= !empty($v['selected']) ? 'class="active"' : '' ?>><a href="<?= $v['link'] ?>"><?= $v['title'] ?></a></li>
<? } ?>
</ul>
