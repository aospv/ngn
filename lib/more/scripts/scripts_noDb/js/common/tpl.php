<?php

if (!empty(O::get('Req')->r['name'])) $d = O::get('Req')->r;
print "Ngn.tpls.{$d['name']} = ".Arr::jsString(Tt::getTpl($d['path']), $d['d']).";";
