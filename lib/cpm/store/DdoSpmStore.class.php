<?php

Ddo::addFuncByName('buyBtn', function($v) {
  return '<a href="#" class="btn" data-authorId="'.$v['authorId'].'"><span>'.$v['title'].'</span></a>';
});

class DdoSpmStore extends DdoSite {}
