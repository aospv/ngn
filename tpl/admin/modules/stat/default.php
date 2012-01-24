<style>
.am_stat td {
vertical-align: top;
}
.am_stat h3 {
margin-bottom: 0px;
margin-left: 7px;
} 
</style>

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
  <td width="33%">
<h3>График последних посещений</h3>
<iframe width="100%" height="230" src="<?= Config::getVarVar('piwik', 'url') ?>/index.php?module=Widgetize&action=iframe&columns[]=nb_visits&moduleToWidgetize=VisitsSummary&actionToWidgetize=getEvolutionGraph&idSite=<?= Config::getVarVar('stat', 'siteId') ?>&period=day&date=today&disableLink=1&widget=1" scrolling="no" frameborder="0" marginheight="0" marginwidth="0"></iframe></div>
<h3>Ключевые слова</h3>
<div id="widgetIframe"><iframe width="100%" height="250" src="http://stat.majexa.ru/index.php?module=Widgetize&action=iframe&moduleToWidgetize=Referers&actionToWidgetize=getKeywords&idSite=2&period=day&date=today&disableLink=1&widget=1" scrolling="no" frameborder="0" marginheight="0" marginwidth="0"></iframe></div>  </td>
  </td>
  <td width="33%">
<h3>Посещения по времени</h3>
<div id="widgetIframe"><iframe width="100%" height="350" src="http://stat.majexa.ru/index.php?module=Widgetize&action=iframe&moduleToWidgetize=VisitTime&actionToWidgetize=getVisitInformationPerLocalTime&idSite=2&period=day&date=today&disableLink=1&widget=1" scrolling="no" frameborder="0" marginheight="0" marginwidth="0"></iframe></div>  
  <td width="33%">
<h3>Посетители в реальном времени</h3>
<div id="widgetIframe"><iframe width="100%" height="500" src="http://stat.majexa.ru/index.php?module=Widgetize&action=iframe&moduleToWidgetize=Live&actionToWidgetize=widget&idSite=2&period=day&date=today&disableLink=1&widget=1" scrolling="auto" frameborder="0" marginheight="0" marginwidth="0"></iframe></div>  
  </td>
</tr>
</table>


