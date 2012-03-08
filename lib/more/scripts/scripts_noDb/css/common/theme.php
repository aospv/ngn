<?php

if (!StmCore::enabled()) return;
if (empty(O::get('Req')->r['location']) and ($data = StmCore::getCurrentThemeData()) !== false) {
  $o = new StmThemeCss($data);
} elseif (!empty(O::get('Req')->r['location'])) {
  $o = new StmThemeCss(
    new StmThemeData(
      new StmDataSource(O::get('Req')->r['location']),
      array('id' => O::get('Req')->rq('id'))
    )
  );
}
print $o->oCss->css;
