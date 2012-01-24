<? $level2stars = Arr::get(Config::getVar('levelStars'), 'level', 'maxStarsN'); ?>
<p>В связи с вашими особыми заслугами вам был назначен <b><?= $d['level'] ?></b> уровень.</p>
<p>Теперь вы можете отдавать или забирать по <b><?= $level2stars[$d['level']] ?></b> 
<?= Misc::wordEnd($level2stars[$d['level']], 'звезде', 'звезды', 'звезд') ?> 
для каждой записи, учавствующей в рейтинге</p>
