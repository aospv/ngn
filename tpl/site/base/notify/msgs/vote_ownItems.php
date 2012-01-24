<? $link = $d['item']['pagePath'].'/'.$d['item']['id'] ?>
Пользователь <a href="<?= Tt::getUserPath($d['voter']['id']) ?>"><?= $d['voter']['login'] ?></a> 
отдал <b><?= $d['votes'] ?></b> голоса за вашу
<?= empty($d['item']['title']) ?
'<a href="'.$link.'">запись</a>' :
'запись <a href="'.$link.'">'.$d['item']['title'].'</a>' ?>.