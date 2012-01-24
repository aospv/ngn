TextareaSelection = function(Input) {
  this.insField = Input;
  this.brkL = "<";
  this.brkR = ">";
  this.insBeg = 1;
  this.defa = '';
  this.selted = '';
}

lenTR = function(oTR,oTa) {
  var ltr;
  var tR=document.body.createTextRange();
  tR.moveToElementText(oTa);

  var tR1=tR.duplicate();  tR1.setEndPoint('StartToEnd',oTR);
  var tR2=tR.duplicate();  tR2.setEndPoint('StartToStart',oTR);

  var lenSel=tR2.text.length-tR1.text.length;
  if (tR1.text.length > 0 || oTa.value.length <= tR.text.length)
    return lenSel;
  else{
    var lenCorr=0;
    var lenCorr2=0;
    var tR3=oTR.duplicate();
    var i,i0;  i=i0=tR3.text.length;
    while (i>=i0 && tR1.text.length == 0) {
      if(i==0) {
        tR3.moveStart('character',-1);
        lenCorr--;
        lenCorr2=1;
      }
      tR3.moveEnd('character',-1);
      i=tR3.text.length;
      tR1.setEndPoint('StartToEnd',tR3);
      lenCorr++; //чис. переносов в конце
    };
    return lenSel+(lenCorr+lenCorr2-1)*2;
  };
};


TextareaSelection.prototype.getInsText = function() {
  this.insField.focus();

  var s=this.insField.value;
  if (this.insField.selectionEnd==null) {
    this.ch = 0;
    if (document.selection && document.selection.createRange) {
      this.tR=document.selection.createRange();
      this.ch='character';
      this.tR1=document.body.createTextRange();
    }
    if (!this.ch || this.tR.parentElement && this.tR.parentElement()!=this.insField) {
      this.insPosL=this.insPosR=s.length;
    }
    else {
      this.insPosL=this.tR.text.length;
      this.insPosR=-1;

      if (this.insField.type=='textarea') {
        this.tR1.moveToElementText(this.insField);

        var dNR = 0;
        if (window.navigator.appName == "Microsoft Internet Explorer") {
          var tR1=this.tR1.duplicate();
          tR1.setEndPoint('StartToEnd', this.tR);
          var tR2=this.tR1.duplicate();
          tR2.setEndPoint('StartToStart', this.tR);

          if (tR1.text == tR2.text) {
            if (tR1.text == "") {
              this.insPosR = s.length;
            }
            else {
              var tR3 = tR1.duplicate();
              do {
                tR3.moveStart('character',-1);
                if (tR3.text.toString().charCodeAt(0) == 13)
                  dNR += 2;
                else
                  break;
              } while (1);
            }
          }
        }

        this.tR.setEndPoint('StartToStart',this.tR1);
        if (this.insPosR == -1)
          this.insPosR=this.tR.text.length + dNR;
      }
      else {
        this.tR.moveStart('textedit',-1);
        this.insPosR=this.tR.text.length;
      }
      this.insPosL=this.insPosR - this.insPosL;
    }
  }
  else {
    this.insPosL=this.insField.selectionStart;
    this.insPosR=this.insField.selectionEnd;
    if (this.insBeg && self.opera && !this.insPosL && !this.insPosR) {
      this.insPosL=this.insPosR=s.length;
      this.insBeg=0;
    }
  }

  return s.substring(this.insPosL,this.insPosR);
}

TextareaSelection.prototype.insPicCore = function(s1, s2, s3) {
  var isPic=s2==null;
  var s=this.insField.value;
  var scrl=this.insField.scrollTop;

  this.insPosL = 0;
  this.insPosR = 0;

  var insText=this.getInsText();

  if ((isInSel=this.selted==insText)&&s3==3) {
    isInSel=insText.length;insText='';
  }
  if (document.all)
    this.insField.defaultValue=s;
  else
    this.defa=s;

  if (isPic && !(s3==2&&insText!='')) {
    s2=s1;s1=''; 
  } //for addressing&picture code
  this.insField.value=s.substring(0,this.insPosL)+s1+insText+s2+s.substring(this.insPosR,s.length);
  if(isInSel&&s3==3)
    this.insPosR-=isInSel;
  var insCursor=this.insPosR+s1.length+(isPic||this.insPosL!=this.insPosR?s2.length:0);
  var insCursorL=insCursor;
  if(s3==1) {
    insCursorL=this.insPosL+s1.length;insCursor=s1.length+this.insPosR;
  }
  var a1=s.substr(0,s3!=3?this.insPosR:this.insPosR+isInSel).match(/\r\n/g);

  if (document.body.createTextRange) {
    var TS = this;

    setTimeout(function() {
      var t=TS.insField.createTextRange();
      t.collapse();
      t.moveEnd(TS.ch,(insCursor-(a1?a1.length:0)));
      t.moveStart(TS.ch,(insCursorL-((a1=s3!=3?s.substr(0,s3==1?TS.insPosL:TS.insPosR).match(/\r\n/g):a1)?a1.length:0)));
      t.select();
    }, 1);
  }
  else {
    if (document.all)
      this.insField.focus();
    if (this.insField.selectionEnd!=null) {
      this.insField.selectionStart=insCursorL;this.insField.selectionEnd=insCursor+(document.all?1:0);

      var insField = this.insField;
      setTimeout(function() {
        insField.focus();
        if (document.all)
          insField.selectionEnd--;
      }, 50);
      if (document.all) {
        var tR=document.selection.createRange();
        if (insCursorL==insCursor)
          tR.collapse();
        tR.select();
      }
      else if (scrl>0)
        this.insField.scrollTop=scrl;
    }
  }
}

TextareaSelection.prototype.insPic = function(s1,s2,s3) {
  if (!document.all && this.selted=='' && s3==3) {
    s1=s2;
    s2=this.brkL+'/'+s2+this.brkR;
    s3='';
  }
  if (s3==3) {
    s1+=s2;
    s2='';
  }
  s1=this.brkL+s1+(s2==this.brkR?'=':this.brkR); //'[b]' or '[b='
  if (s3==2) {
    s1+=s2;
    s2='';
  }
  this.insPicCore(s1, s2, s3);
}

TextareaSelection.prototype.insTag = function(s,c) {   //'b','[/b]' | 'c[/b], '
  this.insPic(s, (c ? c : '') + this.brkL + '/' + s + this.brkR + (c ? ', ' : ''), c ? 2 : null);
}

TextareaSelection.prototype.insTagNL = function(s,c) {
  this.insPic(s, (c ? c : '') + '\n' + this.brkL + '/' + s + this.brkR + (c ? ', ' : ''), c ? 2 : null);
}

TextareaSelection.prototype.insTagSel = function(s) {   //'b','[/b]',1
  this.insPic(s,this.brkL+'/'+s+this.brkR,1);
}

TextareaSelection.prototype.insTagArg = function(s) {  //'b',']'
  this.insPic(s,this.brkR);
}

TextareaSelection.prototype.insBack = function() {
  with (this.insField) {
    var s=document.all ? value : this.defa;
    value=document.all ? defaultValue : this.defa;
    if (document.all)
      defaultValue=s;
    else
      this.defa=s;
  }
}

TextareaSelection.prototype.insUrl = function() {
  if (href = prompt('Введите ссылку', 'http://'))
    if (this.getInsText())
      this.insPicCore('<a href="' + href + '">', '</a>', 2);
    else
      TSel.insPicCore(href);
}

TextareaSelection.prototype.insCapt = function(s){  //'b]selection[/',b,3 /*конец функций для цитаты*/
  this.insPic(s + this.brkR + (this.selted=(document.getSelection?(self.str?str:(document.all?(document.getSelection()
    ?document.getSelection():document.selection.createRange().text):getSelection()))
    :(document.selection?document.selection.createRange().text:'')))
    +this.brkL+'/',s,3);
}