/**
 * editor_plugin_src.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function() {
  var DOM = tinymce.DOM, Element = tinymce.dom.Element, Event = tinymce.dom.Event, each = tinymce.each, is = tinymce.is;

  tinymce.create('tinymce.plugins.InlinePopups', {
    init : function(ed, url) {
      // Replace window manager
      ed.onBeforeRenderUI.add(function() {
        ed.windowManager = new tinymce.InlineWindowManager(ed);
        DOM.loadCSS(url + '/skins/' + (ed.settings.inlinepopups_skin || 'clearlooks2') + "/window.css");
      });
    },

    getInfo : function() {
      return {
        longname : 'InlinePopups',
        author : 'Moxiecode Systems AB',
        authorurl : 'http://tinymce.moxiecode.com',
        infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/inlinepopups',
        version : tinymce.majorVersion + "." + tinymce.minorVersion
      };
    }
  });

  tinymce.create('tinymce.InlineWindowManager:tinymce.WindowManager', {
    InlineWindowManager : function(ed) {
      var t = this;

      t.parent(ed);
      t.zIndex = 300000;
      t.count = 0;
      t.windows = {};
    },

    open : function(f, p) {
      var t = this, id, opt = '', ed = t.editor, parentWindow;

      f = f || {};
      p = p || {};

      // Run native windows
      if (!f.inline)
        return t.parent(f, p);

      parentWindow = t._frontWindow();
      if (parentWindow && DOM.get(parentWindow.id + '_ifr')) {
        parentWindow.focussedElement = DOM.get(parentWindow.id + '_ifr').contentWindow.document.activeElement;
      }
      
      this.dialog = new Ngn.Dialog.Iframe({
        width: f.width,
        height: f.height,
        parent: DOM.doc.body,
        message: 'asd',
        iframeUrl: f.url || f.file
      });
      return;
    },

    focus : function(id) {
      var t = this, w;

      if (w = t.windows[id]) {
        w.zIndex = this.zIndex++;
        w.element.setStyle('zIndex', w.zIndex);
        w.element.update();

        id = id + '_wrapper';
        DOM.removeClass(t.lastId, 'mceFocus');
        DOM.addClass(id, 'mceFocus');
        t.lastId = id;
        
        if (w.focussedElement) {
          w.focussedElement.focus();
        } else if (DOM.get(id + '_ok')) {
          DOM.get(w.id + '_ok').focus();
        } else if (DOM.get(w.id + '_ifr')) {
          DOM.get(w.id + '_ifr').focus();
        }
      }
    },

    close : function(win, id) {
      this.dialog.close();
      return;
      
      
      
      var t = this, w, d = DOM.doc, fw, id;

      id = t._findId(id || win);
      
      
      
      // Probably not inline
      if (!t.windows[id]) {
        t.parent(win);
        return;
      }

      t.count--;

      if (t.count == 0) {
        DOM.remove('mceModalBlocker');
        DOM.setAttrib(DOM.doc.body, 'aria-hidden', 'false');
        t.editor.focus();
      }

      if (w = t.windows[id]) {
        t.onClose.dispatch(t);
        Event.remove(d, 'mousedown', w.mousedownFunc);
        Event.remove(d, 'click', w.clickFunc);
        Event.clear(id);
        Event.clear(id + '_ifr');

        DOM.setAttrib(id + '_ifr', 'src', 'javascript:""'); // Prevent leak
        w.element.remove();
        delete t.windows[id];

        fw = t._frontWindow();

        if (fw)
          t.focus(fw.id);
      }
    },
    
    // Find front most window
    _frontWindow : function() {
      var fw, ix = 0;
      // Find front most window and focus that
      each (this.windows, function(w) {
        if (w.zIndex > ix) {
          fw = w;
          ix = w.zIndex;
        }
      });
      return fw;
    },

    setTitle : function(w, ti) {
      
      var e;

      w = this._findId(w);

      if (e = DOM.get(w + '_title'))
        e.innerHTML = DOM.encode(ti);
    },

    alert : function(txt, cb, s) {
      alert('not realized');
    },

    confirm : function(txt, cb, s) {
      alert('not realized');
    },

    // Internal functions

    _findId : function(w) {
      var t = this;

      if (typeof(w) == 'string')
        return w;

      each(t.windows, function(wo) {
        var ifr = DOM.get(wo.id + '_ifr');

        if (ifr && w == ifr.contentWindow) {
          w = wo.id;
          return false;
        }
      });

      return w;
    }
    
  });

  // Register plugin
  tinymce.PluginManager.add('inlinepopups', tinymce.plugins.InlinePopups);
})();

