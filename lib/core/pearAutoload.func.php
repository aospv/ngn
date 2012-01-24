<?php

function pearAutoload($class) {
  require_once str_replace('_', '/', $class).'.php';
}
