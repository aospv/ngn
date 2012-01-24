  <? if (!isset($d['pagePath'])) $d['pagePath'] = Tt::getPath(); ?>
  <? if ($d['prevMonthDate'] or $d['nextMonthDate']) { ?>
  <div class="ddCalendarBtns">
    <table cellpadding="0" cellspacing="0">
    <tr>
      <td class="prev">
        <a href="<?= Tt::getPathWithoutDate($d['pagePath'], 'd.'.$d['prevMonthDate']) ?>" 
          title="<?= $d['prevMonthDate'] ?>">« <?= $d['prevMonth'] ?></a></td>
      <td class="next">
        <a href="<?= Tt::getPathWithoutDate($d['pagePath'], 'd.'.$d['nextMonthDate']) ?>" 
          title="<?= $d['nextMonthDate'] ?>"><?= $d['nextMonth'] ?> »</a></td>
    </tr>
    </table>
  </div>
  <? } ?>
  <div class="ddCalendarTable">
    <?= $d['table'] ?>
  </div>