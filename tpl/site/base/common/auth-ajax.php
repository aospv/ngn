<? /* Разметка, поддерживающая табы */ ?>

<? foreach ($d['forms'] as $v) { ?>
<h2 class="tab" data-id="<?= $v['id'] ?>"><?= $v['title'] ?></h2>
<div class="apeform">
  <?= $v['html'] ?>
  <div class="clear"><!-- --></div>
</div>
<? } ?>

<? if (Config::getVarVar('userReg', 'vkAuthEnable')) { ?>
<h2 class="tab" title="Войти с помощью «Вконтакте»" data-name="vk">
  <img src="/i/img/icons/vk.png" />
</h2>
<div id="vkAuth"></div>
<? } ?>
