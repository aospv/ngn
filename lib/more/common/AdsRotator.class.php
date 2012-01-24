<?php

class AdsRotator {
	
	static $strName = 'ads';
	
	static function clearRodeoCache($place) {
    Settings::delete('rodeoBanners'.$place);
	}
	
	static private function canShowIds($pageId) {
		return $GLOBALS['reg']->db->selectCol(
		  'SELECT adsId FROM adsPages WHERE pageId=?d', $pageId);
	}
	
	static public function html($place, $pageId, $tpl = 'img') {
		// Если текущая страница определена и для неё нет ниодного ID рекламы 
		if ($pageId and !$showIds = self::canShowIds($pageId)) return false;
		// Если ID рекламы определены
		elseif ($showIds) $idsCond = "AND id IN (".implode(',', $showIds).")";

		static $shownIds;
		
		//pr($shownIds);
		
		if ($shownIds) {
			// Эти ID уже были показаны
			$idsCond2 = "AND id NOT IN (".implode(',', $shownIds).")";
		}
		
    includeLib('dd/DdItems_Extended.class.php');
    $oItems = new DdItems_Extended(self::$strName);
    if (!$rodeoBannerIds = Settings::get('rodeoBanners'.$place) or 1) {
    	/*
    	print "
        SELECT id, rodeo FROM {$oItems->table} WHERE
        place='$place' AND active=1
        $idsCond $idsCond2";
      */
      $r = $GLOBALS['reg']->db->select("
        SELECT id, rodeo FROM {$oItems->table} WHERE
        place='$place' AND active=1
        $idsCond $idsCond2");
      $totalRodeo = 0;
      
      //die2('=='.count($r));
      
      $rodeoBannerIds = array();
      foreach ($r as $v) {
      	for ($i=0; $i < $v['rodeo']; $i++) {
          $rodeoBannerIds[] = $v['id'];
          //print '* ';
        }
      }
      //die2($rodeoBannerIds);
      Settings::set('rodeoBanners'.$place, $rodeoBannerIds);
    }
    $bannerId = $rodeoBannerIds[rand(0, count($rodeoBannerIds)-1)];
    if ($banner = $oItems->getItemF($bannerId)) {
      $oItems->update($banner['id'], array('rotates' => $banner['rotates']+1));
      self::log($banner, 'adslog_rotates');
      $shownIds[] = $bannerId;
    }
    $banner['link'] = '/c/b/click?strName='.self::$strName.'&id='.$banner['id'];
    return Tt::getTpl('ads/'.$tpl, $banner);
	}
	
	static private function log($banner, $table) {
		if (!headers_sent()) session_start();
		db()->query("INSERT INTO $table SET ?a", array(
      'adsId' => $banner['id'],
      'dateEvent' => dbCurTime(),
      'url' => $_SERVER['REQUEST_URI'],
      'referer' => $_SERVER['HTTP_REFERER'],
      'pageTitle' => @$GLOBALS['reg']->page['title'],
      'pagePathLinks' => @$GLOBALS['reg']->page['title'],
		  'ip' => $_SERVER['REMOTE_ADDR'],
		  'session' => session_id()
    ));
  }
  
  static public function click($id) {
    /* @var $oDdItems DdItems */
    $oDdItems = O::get('DdItems', 'ads');
    if ($banner = $oDdItems->getItem($id)) {
      $oDdItems->update($banner['id'], array('clicks' => $banner['clicks']+1));
      self::log($banner, 'adslog_clicks');
      header('Location: '.$banner['url']);
    }
  }

}