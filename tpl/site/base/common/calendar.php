<? $d['calendar']['pagePath'] = $d['params'] ? Tt::getPath() : $d['page']['path'] ?>

<div id="ddCalendar">
  <? Tt::tpl('common/calendarInner', $d['calendar']) ?>
</div>
