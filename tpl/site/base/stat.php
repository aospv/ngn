<!-- Piwik -->
<? $siteId = Config::getVarVar('stat', 'siteId') ?> 
<script type="text/javascript">
var pkBaseURL = "<?= Config::getVarVar('piwik', 'url') ?>/";
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", <?= $siteId ?>);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://dev.wandaland.ru/piwik/piwik.php?idsite=<?= $siteId ?>" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Tracking Code -->
