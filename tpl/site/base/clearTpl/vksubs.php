<?php


$o = new VkontakteAuth('masted@bk.ru', 'enotherpass');
print $o->auth(true);