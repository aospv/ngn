<?php

Misc::checkEmpty($_REQUEST, array('name', 'path'));
print "Ngn.tpls.{$_REQUEST['name']} = ".Arr::jsString(Tt::getTpl($_REQUEST['path'])).";";
