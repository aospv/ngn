<?php

class Js {

  static public function jsNewObjFirstLibLoad($lib, $class) {
    print "
Asset.javascript('".SFLM::getJsCachedUrlLib($lib)."', {
  onLoad: function() {
    new Ngn.$class();
  }
});
";
  }

}