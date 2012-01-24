<? Tt::tpl('admin/modules/pages/header', $d) ?>

<style>
#sampleData {
position: relative;
height: 30px;
padding: 5px;
border: 1px solid #CCCCCC;
background: url(./i/img/black/drag-bg.gif);
margin-bottom: 10px;
}
.dragData {
padding: 3px 5px 3px 5px;
border: 1px solid #CCCCCC;
cursor: hand;
background: #FFFFFF;
float: left;
margin-right: 5px;
width: 60px;
height: 15px;
font-size: 9px;
position: absolute;
cursor: move;
}
.dragData:hover {
border: 1px solid #555555;
}
.dragging {
border: 1px solid #FF0000;
}
.fields {
margin-top: 10px;
}
.fields .droppable {
padding: 10px 20px 10px 20px;
border: 1px solid #00FF00;
float: left;
width: 110px;
margin: 0px 5px 5px 0px;
}
.dropped {
background: #CCCCCC;
}
.entered {
border: 1px solid #FF0000 !important;
}
#results {
margin-top: 10px;
}
</style>

<div id="sampleData" class="droppable">
<? $l = current($d['items']) ?>
<? $i=0; foreach ($l as $k => $v) { ?>
  <div id="data<?= $k ?>" class="dragData" title="<?= $v ?>" style="left:<?= $i*115+5 ?>px"><?= Misc::cut($v, 8) ?></div>
<? $i++; } ?>
  <div class="clear"><!-- --></div>
</div>

<h2>Пример данных</h2>
<table cellpadding="0" cellspacing="0" id="itemsTable">
<? foreach ($d['items'] as $l) { ?>
<tr>
  <? foreach ($l as $v) { ?>
    <td><?= Misc::cut($v, 20) ?></td>
  <? } ?>
</tr>
<? } ?>
</table>

<hr />

<div class="fields">
<? foreach ($d['fields'] as $f) { ?>
  <div class="droppable" id="<?= $f['name'] ?>" title="<?= $f['title'] ?>"><?= Misc::cut($f['title'], 20) ?></div>
<? } ?>
<div class="clear"><!-- --></div>
</div>

<div id="results"></div>

<input type="button" value="<?= LANG_IMPORT ?>" id="btnImport" /> 

<script type="text/javascript">

var initPos = new Hash({});

var fieldName2colN = new Hash({});

var buildResults = function() {
  var s = '<h3><?= LANG_IMPORT_RESULT ?></h3><ul>';
  asd.each(function(dataN, field){

console.debug(dataN+', '+field);
    
    s += '<li>' + $('data' + dataN).get('title') + ' → ' + field + '</li>';
  });
  s += '</ul>';
  $('results').set('html', s);
};

var opt = {
    snap: 0,
    droppables: '.droppable',
    // Когда зажимается мышка
    onSnap: function(el){
      el.addClass('dragging');
    },
    // Когда падение было успешно
    onComplete: function(el){
      el.removeClass('dragging');
    },
    // когда отпускается мышка (бросок)
    onDrop: function(el, droppable){
      
      //var fieldName = droppable.get('id');

      

      var elN = el.get('id').replace('data', '');

      // Блок упал не на нужный элемент
      if (!droppable) {
        var effect = new Fx.Morph(el, {
          duration: 'short',
          transition: Fx.Transitions.Sine.easeOut
        });
        effect.start({
          'top': initPos[elN]['y'],
          'left': initPos[elN]['x']
        });
        return;
      }

      var droppableId = console.debug(droppable.get('id'));

      var effect = new Fx.Morph(el, {
        duration: 100,
        transition: Fx.Transitions.Sine.easeOut
      });
      
      //console.debug(droppableId + '==' + 'sampleData');
      
      if (droppableId == 'sampleData') {
        // упал на Базу
        alert('!');
        var effect = new Fx.Morph(el, {
          duration: 'short',
          transition: Fx.Transitions.Sine.easeOut
        });
        effect.start({
          'top': initPos[elN]['y'],
          'left': initPos[elN]['x']
        });
        return;
        
      } else {
        // упал на Ячейку Таблицы
        var droppableSize = droppable.getSize();
        var droppablePos = droppable.getPosition(this.oSampleData);
        var elSize = el.getSize();
        effect.start({
          'top': droppablePos['y'] + 
            Math.round(droppableSize['y'] / 2) - 
            Math.round(elSize['y'] / 2),
          'left': droppablePos['x'] + 
            5
        });
        
        fieldName2colN[elN] = droppableId;
        
      }

      
      
      /*
      if (asd[fieldName]) {
        alert('<?= LANG_FIELD_OCCUPIED_MOVE_TO_ANOTHER ?>');
        return;
      }
      */
      //this.fieldName2colN
      
      //droppable.addClass('dropped');
      //asd[fieldName] = el.get('id').replace('data', '');
      //asd[fieldName]['id'] = el.get('id').replace('data', '');
      //asd[fieldName]['title'] = el.get('title');
      //this.buildResults();
    },
    // Когда элемент проносится над областью, допущенной для бросания
    onEnter: function(el, droppable){
      // droppable - облать допущенная до бросания
      if (!droppable) return;


      // В случае с областью-столбцом таблицы id = имени столбца
      var fieldName = droppable.get('id');

      //if (asd[fieldName]) return;
      
      // Класс, которых добавляется к области в которую бросили элемент
      droppable.addClass('entered');
    },    
    // Когда элемент покидает область допущенной для бросания
    onLeave: function(el, droppable){
      if (!droppable) return;
      // Определить тип элемента droppable
      // Если он является Ячейкой Ловящей Таблицы
      // 1) определить заполнена ли ячейка
      // 2) бросить
      // 3) заполнить массив заполненных
      // 4) 
      var fieldName = droppable.get('id');
      //if (!asd[fieldName]) {
        droppable.removeClass('enter');
      //}
      if (el.get('id').replace('data', '')) {
        droppable.removeClass('dropped');
        asd[fieldName] = null;
      }
      //buildResults();
    }
  };

// Массив с соответствиями
var asd = new Hash({});

$('sampleData').getElements('.dragData').each(function(el, n) {
  new Drag.Move(el, opt);
  initPos[el.get('id').replace('data', '')] = el.getPosition($('sampleData'));
});

$('btnImport').addEvent('click', function(e){
  new Request.JSON({
    method: 'post',
    url: '<?= Tt::getPath() ?>?a=ajax_import',
    data: {importData: JSON.encode(asd)},
    onComplete: function(data) {
      //alert(data);
    }
  }).send();  
});

</script>