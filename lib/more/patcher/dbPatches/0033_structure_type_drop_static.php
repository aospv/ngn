<?php

  q("ALTER TABLE `dd_structures` CHANGE `static` `type` 
  ENUM( 'static', 'dynamic', 'variant' ) NULL DEFAULT 'dynamic'");

  print 'Выставите типы структур';