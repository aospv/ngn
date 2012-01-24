<?

$d['height'] += 19;
if (!$d['id']) $d['id'] = 'video'.rand(1, 10000);
Tt::tpl('common/flash', $d);

/*
?>
  <object id="<?= $id ?>" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="<?= $id ?>" 
  width="<?= $d['w'] ?>" height="<?= $d['h'] ?>" style="display:block;">
    <param name="movie" value="./i/swf/mp/player.swf" />
    <param name="allowfullscreen" value="true" />
    <param name="allowscriptaccess" value="always" />
    <param name="flashvars" value="file=../../..<?= $d['path'] ?>&image=<?= $preview ?><?= $flashvars ?>" />
    <embed
      type="application/x-shockwave-flash"
      id="<?= $id ?>2"
      name="<?= $id ?>2"
      src="./i/swf/mp/player.swf" 
      width="<?= $d['w'] ?>" 
      height="<?= $d['h'] ?>"
      allowscriptaccess="always" 
      allowfullscreen="true"
      flashvars="file=../../../../u/<?= $d['path'] ?>&image=<?= $preview ?><?= $flashvars ?>" 
    />
  </object>

*/
?>
