<? // $d - Field Data ?>

<script type="text/javascript" src="./i/js/ngn/Ngn.FieldSet.js"></script>
<script type="text/javascript">

Ngn.cp.Dfp = new Class({
  
  Implements: [Options],

  options: {
    form: 'ddFieldForm',
    dfp: 'dfp'
    fieldId: null,
  },
  
  initialize: function(options) {
    this.form = $(this.options.form) || this.options.form;
    this.dfp = $(this.options.dfp) || this.options.dfp;
    $$('input[name=type]').each(function(el){
      el.addEvent('change', function(){
        if (el.get('checked')) this.load(el.get('value'));
      }.bind(this));
    }.bind(this));
  },
  
  load: function(type) {
    var type = this.form.values().type;
    new Ngn.cp.Request.JSON({
      url: window.location.pathname + '?a=json_getDpHtml',
      onSuccess: function(data) {
        //if (!data) $('typeBlock').setStyle('display', 'none');
        //else $('typeBlock').setStyle('display', 'block');
        this.dfp.set('html', data.html);
        this.initDfp(data.type);
      }
    }).get({
      type: type,
      fieldId: this.options.fieldId
    });
  },

  dfpType2classes: {
    list: ['Ngn.FieldSet'],
    tagsFlat: ['Ngn.FieldSet'],
    tagsTree: ['Ngn.TreeEditTags']
  },
  
  initDfp: function(dfpType) {
    if (!this.dfpType2classes[dfpType]) return;
    this.dfpType2classes[dfpType].each(function(c){
      //if ($defined(eval(c))) 
      eval('new ' + c + '();');
    });
  }
  
});

window.addEvent('domready', function() {
  new Ngn.cp.Dfp();
});

</script>
