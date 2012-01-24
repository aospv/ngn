<?

/**
 * Пример массива $d:
 * array(
 *   'id' => 'flash_id',
 *   'path' => 'path/to/file.swf',
 *   'bgcolor' => '#FFFFFF',
 *   'width' => 100,
 *   'height' => 100,
 *   'flashvars' => array(
 *     'var1' => '123'
 *   )
 * )
 * 
 */

if (!isset($d['wmode'])) $d['wmode'] = 'opaque';

/*
if (!empty($d['flashvars'])) {
  foreach ($d['flashvars'] as $k => $v) {
    $flashvars[] = "$k=$v";
  }
  $d['flashvars'] = implode('&', $flashvars);
}
*/
if ($d['flashvars']) {
  $d['flashvars'] = http_build_query(
    is_array($d['flashvars']) ? $d['flashvars'] : array($d['flashvars']));
} 
?>

<p id="preview<?= $d['id'] ?>">The player will show in this paragraph</p>
<script type="text/javascript">
  var s<?= $d['id'] ?> = new SWFObject('<?= $d['path'] ?>','<?= $d['id'] ?>','<?= $d['width'] ?>','<?= $d['height'] ?>','9');
  s<?= $d['id'] ?>.addParam('allowfullscreen','true');
  s<?= $d['id'] ?>.addParam('wmode','<?= $d['wmode'] ?>');
  s<?= $d['id'] ?>.addParam('allowscriptaccess','always');
  s<?= $d['id'] ?>.addParam('flashvars','<?= $d['flashvars'] ?>');
  s<?= $d['id'] ?>.write('preview<?= $d['id'] ?>');
</script>
