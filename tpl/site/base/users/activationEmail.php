Кто-то, возможно Вы, запросили письмо для подтверждения авторизации на сайте 
<a href="<?= SITE_WWW ?>" target="_blank"><?= SITE_TITLE ?></a><br /><br />
    
Для подтверждения перейдите по следующей ссылке:
<? $actLink = SITE_WWW.'/'.Tt::getControllerPath('userReg').'?a=activation&code='.$d['confirmCode'] ?>
<a href="<?= $actLink ?>" target="_blank"><?= $actLink ?></a>