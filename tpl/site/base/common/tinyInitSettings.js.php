<?php 

if (!$d) throw new NgnException('Theme type ($d) is not defined');

$oTI = new TinyInit($d);

?>

Ngn.TinySettings = new Class({

  getSettings: function(_settings) {
    return {
      language : "ru",
      //paste_strip_class_attributes : "all",
      //theme_advanced_statu  sbar_location : "bottom",
      theme_advanced_toolbar_location : "top",
      theme_advanced_toolbar_align : "left",
      theme_advanced_source_editor_height : 400,
      //cleanup : true,
      verify_css_classes : true, // удаляет существующие в "content_css" классы... так что нах
      //paste_use_dialog : false,
      <? if ($oTI->cssFile) { ?>
        content_css: "<?= $oTI->cssFile.'?r='.rand(1,10000) ?>",
      <? } ?>
      inlinepopups_skin: 'ngnPopup',
      width: '100%',
      mode: 'exact',
      theme : '<?= $oTI->getTheme() ?>',
      relative_urls : false,
      remove_script_host : true,
      document_base_url : '//' + window.location.host + "/",
      popup_css: '/i/css/common/screen.css',
      paste_auto_cleanup_on_paste : true,
      valid_elements : "<?= $oTI->getValidElements() ?>",
      
      formats : {
        alignleft : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'left'},
        aligncenter : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'center'},
        alignright : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'right'}
      },
      
      cleanup_on_startup : true,
      remove_trailing_nbsp : true,
      theme_advanced_blockformats : "<?= $oTI->getThemeAdvancedBlockformats() ?>",
      plugins: "<?= $oTI->getPlugins() ?>",
      theme_advanced_disable: "<?= $oTI->getThemeAdvancedDisable() ?>",
      // это plugin: ,paste
      //theme_advanced_buttons1_add_before : "pastetext,pasteword",
      // ***** ,
      theme_advanced_buttons2_add : "<?= $oTI->getTableButtons().(Misc::isGod() ? ',code' : '') ?>",
      <? if (($themeAdvancedStyles = $oTI->getThemeAdvancedStyles())) { ?>
        theme_advanced_styles : "<?= $themeAdvancedStyles ?>",
      <? } ?>
      setup: function(ed) {
        // Если текст состоит из пустого параграфа, удаляем его
        ed.onPostProcess.add(function(ed, o) {
          if (o.get) {
            if (Ngn.clearParagraphs(o.content) == '') o.content = '';
            o.content = o.content.replace('http://mailto:', 'mailto:');
            o.content = o.content.replace('<table>', '<table cellspacing="0">');
          }
        });
        (function() {
          ed.onBeforeSetContent.add(function(ed, o){
            try {
              //ed.execCommand('mceCleanup');
            } catch(e) {};
          });
        }).delay(1000);
      }
    };
  }
  
});
