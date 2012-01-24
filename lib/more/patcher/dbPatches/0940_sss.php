<?php

DbText::replaceFieldText(
  'pages',
  'link',
  '/(\/[^\/]+\/)t\/(\d+)/',
  '$1t.$2'
);
