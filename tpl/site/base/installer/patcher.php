<? $actualPatches = $d['oPatcher']->getActualPatches() ?>

<p>Номер последнего патча примененного для сайта: <b><?= $d['oPatcher']->getSiteLastPatchN() ?></b></p>
<p>Номер последнего актуального патча: <b><?= $d['oPatcher']->getNgnLastPatchN() ?></b></p>

<? if ($actualPatches) { ?>
<p><b>Актуальные патчи:</b></p>
<ol>
<? foreach ($actualPatches as $v) { ?>
  <li value="<?= $v['patchN'] ?>"><pre><?= $v['descr'] ?></pre></li>
<? } ?>
</ul>
<input type="button" value="Применить" 
  onclick="window.location = '<?= Tt::getPath() ?>?a=patch'" />
<? } ?>

<hr />
<a href="/"><?= SITE_TITLE ?></a>