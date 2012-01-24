<p class="info">Отправлено успешно. Спасибо.</p>
<? if (!empty($d['results'])) { ?>
  <h2>Результаты тестирования</h2>
  <? Tt::tpl('dd/test_choice_slave/results', $d['results']) ?>
<? } ?>
