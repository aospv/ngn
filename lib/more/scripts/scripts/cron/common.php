<?php

set_time_limit(600);
$o = new CronManager();
$o->run(O::get('Req')->rq('period'));
