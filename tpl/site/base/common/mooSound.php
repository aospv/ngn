<div class="soundPlayer" id="<?= $d['id'] ?>"></div>
<script type="text/javascript">
window.addEvent('domready', function() {
  new Ngn.Sound.Player(
    $('<?= $d['id'] ?>'),
    ['<?= $d['flashvars']['file'] ?>'],
    '<?= $d['strName'] ?>',
    <?= (int)$d['itemId'] ?>,
    '<?= $_COOKIE['PHPSESSID'] ?>'
  );
});
</script>
