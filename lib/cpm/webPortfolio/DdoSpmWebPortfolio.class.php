<?php

class DdoSpmWebPortfolio extends DdoSite {}

Ddo::addFuncByName('title', function($v) { return '<a href="'.$v['o']->items[$v['id']]['url'].'" target="_blank">'.$v['v'].'</a>'; });

