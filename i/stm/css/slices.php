<?php

if ($d['slices']) foreach ($d['slices'] as $v)
print "
#slice_{$v['id']} {
left: {$v['x']};
top: {$v['y']};
}
";

if ($d['homeTopOffset']) foreach ($d['slices'] as $v)
print "
.home #slice_{$v['id']} {
left: {$v['x']};
top: ".($v['y']+$d['homeTopOffset'])."px;
}
";
