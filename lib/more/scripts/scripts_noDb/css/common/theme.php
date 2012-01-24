<?php

if (!StmCore::enabled()) return;
if (empty(O::get('Req')->r['location'])) {
  $o = new StmThemeCss(StmCore::getCurrentThemeData());
} else {
  $o = new StmThemeCss(
    new StmThemeData(
      new StmDataSource(O::get('Req')->r['location']),
      array('id' => O::get('Req')->rq('id'))
    )
  );
}
print $o->oCss->css;
