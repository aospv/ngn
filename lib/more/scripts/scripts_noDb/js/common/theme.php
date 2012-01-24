<?php

if (!StmCore::enabled()) return;
print O::get('StmThemeJs', StmCore::getCurrentThemeData())->js;
