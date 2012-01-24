<div id="video_<?= $d['$id'] ?>"></div>
<script type="text/javascript">
Ngn.video({
"container": $("video_<?= $d['$id'] ?>"),
"width": "<?= $d['o']->w+250 ?>",
"height": "<?= $d['o']->h ?>"
},{
"file": "../../../<?= str_replace('./', '', $d['v']) ?>",
"image": "../../../<?= str_replace('./', '', File::reext($d['v'], 'jpg')) ?>",
"provider": "http",
"playlistfile": "/c/videoPlaylist",
"playlist.position": "right",
"playlist.size": "250",
"autostart": true,
"repeat": "always"
});
</script>
