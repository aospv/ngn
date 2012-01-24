<?php

$f = function($v, $method) {
  return
    '<b class="title">'.$v['title'].':</b>'.
    '<ul>'.
    Tt::enumSsss2(
      UserStoreCore::$method($v['authorId']),
      '<li>$v</li>',
      ''
    ).
    '</ul>';
};
Ddo::addFuncByName('deliveryWays', function($v) use ($f) { return $f($v, 'getDeliveryWays'); });
Ddo::addFuncByName('paymentWays', function($v) use ($f) { return $f($v, 'getPaymentWays'); });
Ddo::addFuncByName('rules', function($v) {
  if (!DbModelCore::get('userStoreSettings', $v['authorId'])->r['settings']['rules']) return '';
  return '
<a href="#" id="userStoreRules" class="pseudoLink">'.$v['title'].'</a>
<script type="text/javascript">
Ngn.addBtnAction("#userStoreRules", function() {
  new Ngn.Dialog.HtmlPage({
    title: "'.$v['title'].'",
    url: "/c/userStore/ajax_rules/'.$v['authorId'].'"
  });
});
</script>
';
});
Ddo::addFuncByName('buyBtn', function($v) {
  return '<a href="#" class="btn" data-authorId="'.$v['authorId'].'"><span>'.$v['title'].'</span></a>';
});


function colorPalette($imageFile, $numColors) {
  $granularity = 5;
  $cache = NgnCache::c();
  $cacheKey = 'color'.$numColors.sha1($imageFile);
  if (($r = $cache->load($cacheKey)) !== false) return $r;
  $granularity = max(1, abs((int)$granularity));
  $colors = array(); 
  $size = @getimagesize($imageFile); 
  if ($size === false) { 
    user_error("Unable to get image size data"); 
    return false; 
  }
  $img = @imagecreatefromjpeg($imageFile); 
  if (!$img) { 
    user_error("Unable to open image file"); 
    return false; 
  }
  for ($x = 0; $x < $size[0]; $x += $granularity) {
    for($y = 0; $y < $size[1]; $y += $granularity) {
      $thisColor = imagecolorat($img, $x, $y); 
      $rgb = imagecolorsforindex($img, $thisColor); 
      $red = round(round(($rgb['red'] / 0x33)) * 0x33); 
      $green = round(round(($rgb['green'] / 0x33)) * 0x33); 
      $blue = round(round(($rgb['blue'] / 0x33)) * 0x33); 
      $thisRGB = sprintf('%02X%02X%02X', $red, $green, $blue); 
      if (array_key_exists($thisRGB, $colors)) { 
        $colors[$thisRGB]++;
      } else {
        $colors[$thisRGB] = 1; 
      }
    }
  } 
  arsort($colors); 
  $r = array_slice(array_keys($colors), 0, $numColors);
  $cache->save($r, $cacheKey);
  return $r;
}

Ddo::addFuncByName('colors', function($v) {
  $colors = colorPalette(WEBROOT_PATH.$v['o']->items[$v['id']]['image'], 5);
  $t = <<<EOT
<script src="/i/js/ntc.js"></script>
<style>
.f_colors table {
margin-top: 10px;
margin-bottom: 0px;
}
.f_colors .colorItem {
width: 30px;
height: 2px;
}
</style>
<table class="noborder" id="colors"><tr>
EOT;
  foreach ($colors as $c) $t .= '<td style="background-color:#'.$c.'" data-color="#'.$c.'" class="tooltip"><div class="colorItem"></div></td>';
  $t .= <<<EOT
</table>
<script>
$('colors').getElements('td').each(function(el) {
  el.set('title', ntc.name(el.get('data-color'))[1]);
});
</script>
EOT;
  return $t;
});

class DdoSpmUserStore extends DdoSite {}