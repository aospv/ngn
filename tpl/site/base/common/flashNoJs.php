<?
if ($d['flashvars']) {
  $d['flashvars'] = http_build_query(
    is_array($d['flashvars']) ? $d['flashvars'] : array($d['flashvars']));
} 
?>
  <object id="<?= $d['id'] ?>" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="<?= $d['id'] ?>" 
  width="<?= $d['width'] ?>" height="<?= $d['height'] ?>" style="display:block;">
    <param name="movie" value="<?= $d['path'] ?>" />
    <param name="allowfullscreen" value="true" />
    <param name="allowscriptaccess" value="always" />
    <param name="flashvars" value="<?= $d['flashvars'] ?>" />
    <embed
      type="application/x-shockwave-flash"
      id="<?= $d['id'] ?>"
      name="<?= $d['id'] ?>"
      src="<?= $d['path'] ?>" 
      width="<?= $d['width'] ?>" 
      height="<?= $d['height'] ?>"
      allowscriptaccess="always" 
      allowfullscreen="true"
      flashvars="<?= $d['flashvars'] ?>" 
    />
  </object>