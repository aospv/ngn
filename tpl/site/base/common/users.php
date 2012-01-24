<? if (!$d) { print 'отсутствуют'; return; } ?>
<?= Tt::enumDddd($d, '`<a href="`.Tt::getUserPath($userId).`">`.$login.`</a>`') ?>
<div class="clear"><!-- --></div>
