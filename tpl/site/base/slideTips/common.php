<div id="topSlideTip" class="<?= $d['class'] ?>">
  <div class="asdasd">
    <div class="smIcons bordered">
      <a href="#" class="sm-delete" title="Закрыть"><i></i></a>
    </div>
  </div>
  <div class="arrow">
    <div class="smIcons bordered">
      <a href="#" class="sm-prev"><i></i></a>
      <div class="nums"></div>
      <a href="#" class="sm-next"><i></i></a>
    </div>
  </div>
  <div class="body">
    <div class="slides smIcons bordered">
      <? $n = 0; foreach ($d['items'] as $v) { $n++ ?>
      <div class="slide" id="tSlide<?= $n ?>">
        <?= $v ?>
      </div>
      <? } ?>
    </div>
  </div>
</div>