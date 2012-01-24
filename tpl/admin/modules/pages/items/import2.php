<h2>Импортирование данных (шаг 2)</h2>

<style>
#sampleData {
position: relative;
height: 30px;
padding: 5px;
border: 1px solid #CCCCCC;
background: #FFD204;
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
.droppable {
padding: 10px 20px 10px 20px;
border: 1px solid #00FF00;
float: left;
width: 110px;
margin: 0px 5px 5px 0px;
}
.dropped {
background: #CCCCCC;
}
.enter {
border: 1px solid #FF0000;
}
#results {
margin-top: 10px;
}
</style>

<div id="sampleData">
<? $l = current($d['items']) ?>
<? $i=0; foreach ($l as $k => $v) { ?>
  <div id="data<?= $k ?>" class="dragData" title="<?= $v ?>" style="left:<?= $i*115+5 ?>px"><?= Misc::cut($v, 8) ?></div>
<? $i++; } ?>
  <div class="clear"><!-- --></div>
</div>


<table cellpadding="0" cellspacing="0" id="itemsTable">
<? foreach ($d['items'] as $l) { ?>
<tr>
  <? foreach ($l as $v) { ?>
    <td><?= $v ?></td>
  <? } ?>
</tr>
<? } ?>
</table>

<hr />

<div class="fields">
<? foreach ($d['fields'] as $f) { ?>
  <div class="droppable" id="<?= $f['name'] ?>"><?= $f['name'] ?></div>
<? } ?>
<div class="clear"><!-- --></div>
</div>

<div id="results"></div>

<input type="button" value="Импортировать" id="btnImport" /> 

<script type="text/javascript">

var buildResults = function() {
  var s = '<h3>Результат переноса:</h3><ul>';
  asd.each(function(dataN, field){
    s += '<li>' + $('data' + dataN).get('title') + ' → ' + field + '</li>';
  });
  s += '</ul>';
  $('results').set('html', s);
};

var opt = {
    snap: 0,
    droppables: '.droppable',
    onSnap: function(el){
      el.addClass('dragging');
    },
    onComplete: function(el){
      el.removeClass('dragging');
    },
    onDrop: function(el, droppable){
      if (!droppable) return;
      var fieldName = droppable.get('id');
      if (asd[fieldName]) {
        alert('Это поле уже занято. Перенесите в другое');
        return;
      }
      droppable.addClass('dropped');
      asd[fieldName] = el.get('id').replace('data', '');
      buildResults();
    },
    onEnter: function(el, droppable){
      if (!droppable) return;
      var fieldName = droppable.get('id');
      if (asd[fieldName]) {
        return;
      }
      droppable.addClass('enter');
    },    
    onLeave: function(el, droppable){
      if (!droppable) return;
      var fieldName = droppable.get('id');
      if (!asd[fieldName]) {
        droppable.removeClass('enter');
      }
      if (asd[fieldName] == el.get('id').replace('data', '')) {
        droppable.removeClass('dropped');
        asd[fieldName] = null;
      }
      buildResults();
    }
  };

var asd = new Hash({});

$('sampleData').getElements('.dragData').each(function(el, n) {
  new Drag.Move(el, opt);
});

$('btnImport').addEvent('click', function(e){
  new Request({
    method: 'post',
    url: '<?= Tt::getPath() ?>?a=ajax_import',
    data: {importData: asd},
    onComplete: function(data) {
      //alert(data);
    }
  }).send();  
});

</script>