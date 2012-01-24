var fieldName2colN = new Hash({});

Ngn.DndImport = new Class({

  initialize: function(url) {
    this.url = url; // URL для ajax-запросов
    this.eSampleData = $('sampleData'); // Родительский элемент для элементов с исходными колонками
    this.initPos = new Hash({}); // Исходные позиции
    //this.fieldName2colN = new Hash({}); // Соответствия колонок
    this.opt = { // Параметры для объекта Drag.Move
      snap: 0,
      droppables: '.droppable',
      onSnap: function(el){ // Когда зажимается мышка
        console.debug('!!!'); 
        el.addClass('dragging');
      },
      onComplete: function(el){ // Когда падение было успешно
        el.removeClass('dragging');
      },
      /*
      onDrop: function(el, droppable){ // когда отпускается мышка (бросок)
        var elN = el.get('id').replace('data', ''); // получаем номер элемента
        var droppableId = console.debug(droppable.get('id'));
        var effect = new Fx.Morph(el, {
          duration: 100,
          transition: Fx.Transitions.Sine.easeOut
        });
        ////////////////////////////////////////////////////////////////////////
        if (!droppable) { // Блок упал не на нужный элемент
          effect.start({
            'top': initPos[elN]['y'],
            'left': initPos[elN]['x']
          });
          return;
        }
        if (droppableId == 'sampleData') { // упал на Базу
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
        
        //if (asd[fieldName]) {
        //  alert('<?= LANG_FIELD_OCCUPIED_MOVE_TO_ANOTHER ?>');
        //  return;
        //}
        
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
        // Класс, который добавляется к области в которую бросили элемент
        droppable.addClass('entered');
      },
      // Когда элемент покидает область допущенную для бросания
      onLeave: function(el, droppable){
        if (!droppable) return;
        // Определить тип элемента droppable
        // Если он является Ячейкой Ловящей Таблицы
        // 1) определить заполнена ли ячейка
        // 2) бросить
        // 3) заполнить массив заполненных
        // 4) 

        //console.debug(droppable.get('id'));
        droppable.removeClass('entered');


        
        var fieldName = droppable.get('id');

        // if (!asd[fieldName]) {
        // }

        if (el.get('id').replace('data', '')) {
          droppable.removeClass('dropped');
          this.asd[fieldName] = null;
        }
        buildResults();
      }
      */
    };
    
    
    this.eSampleData.getElements('.dragData').each(function(el, n) {
      //new Drag.Move(el, this.opt);
      //this.initPos[el.get('id').replace('data', '')] = el.getPosition(this.eSampleData);
    });

    $('btnImport').addEvent('click', function(e){
      new Ngn.Request.JSON({
        method: 'post',
        url: this.url,
        data: {
          action: ajax_import,
          importData: JSON.encode(this.asd)
        },
        onComplete: function(data) {
          //alert(data);
        }
      }).send();  
    });
    
  },

});

var buildResults = function() {
  fieldName2colN.each(function(dataN, field) {
    console.debug(dataN + ', ' + field);
    s += '<li>' + $('data' + dataN).get('title') + ' - ' + field + '</li>';
  });
  s += '</ul>';
  $('results').set('html', s);
};
