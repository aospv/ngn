<?php

class DdTextReplacer {
	
	public $from;
	
	public $to;
  
  public $resultsCount;
  
  public $isRegexp = false;
  
  static public function replace {
    $oS = new DdStructure();
    foreach ($oS->getStructures() as $v) {
      $textFields = DdFields::getTextNames($v['name']);
      foreach (db()->query(
      "SELECT * FROM {$v['table']}") as $vv) {
        $vv2 = array();
        foreach ($textFields as $field) {
          $vv2[$field] = self::_replace($vv[$field]);
        }
        db()->query(
          "UPDATE {$v['table']} SET ?a WHERE id=?d",
          $vv2, $vv['id']);
      }
    }
    foreach (db()->query(
    "SELECT * FROM comments") as $v) {
      $v2['text'] = self::_replace($v['text']);
      $v2['text_f'] = self::_replace($v['text_f']);
      db()->query(
        "UPDATE comments SET ?a WHERE id=?d",
        $v2, $v['id']);
    }    
  }
  
  static public function _replace($s) {
    //$from = prepareRegexp($this->from);
    if ($this->isRegexp) {
    	$s = preg_replace("/{$this->from}/", $this->to, $s);
    } else {
    	$s = str_replace($this->from, $this->to, $s);
    }    
    return $s;
  }
  
  function setReplaceText($from, $to) {
  	$this->from = $from;
  	$this->to = $to;
  }
  
  /**
   * Обрабатывает записи (возвращает, либо заменяет текст)
   *
   * @param   string  Тип обработки (get/replace)
   * @return  mixed
   */
  private function processDDItems($type = 'get') {
  	if (!$this->from) throw new NgnException('$this->from not defined. Use setReplaceText()');
  	$this->resultsCount = 0;
    $oS = new DdStructure();
    $oSD = new SearchDescriber(array($this->from));
    $oSD->stripHtml = false;
    $oSD2 = new SearchDescriber(array($this->to));
    $oSD2->stripHtml = false;
    foreach ($oS->getStructures() as $v) {
      $strName = $v['name'];
      $textFields = DdFields::getTextNames($strName);
      foreach (db()->query(
      "SELECT * FROM {$v['table']}") as $vv) {
        $itemId = $vv['id'];
        $pageId = $vv['pageId'];
        $replacedRow = null;
        foreach ($textFields as $field) {
        	// Проверяем наличие ключевой фразы в текущем поле
        	//$from = prepareRegexp($this->from);
        	
        	//print '+ '.$this->from;
        	
        	
        	if ($this->strExists($this->from, $vv[$field])) {
        		
        		
        		
            $text = $vv[$field]; // Найденный текст
            if ($type == 'replace') {
              $replacedRow[$field] = $this->_replace($vv[$field]);
              $this->resultsCount++;
            } else {
            	$this->resultsCount++;
              // Если не можем получить части с искомыми словами
            	if (!$r = $oSD->getParts($text)) {
            		//$r = array(Misc::cut($text, 400));
            		$r = array("[can't find parts]");
            		$replacedParts = $r;
            	} else {
                $replacedParts = array();
                foreach ($r as $h) {
                  $replacedParts[] = $this->_replace($h);
                }
              }
              $items[$strName][$itemId]['pageData'] = Pages::getNodeStint($pageId);
              $items[$strName][$itemId]['itemData'] = 
                @db()->selectRow(
                "SELECT title FROM {$v['table']} WHERE id=?d", $itemId);
            	$items[$strName][$itemId]['fields'][$field]['found'] = $r;              
              $items[$strName][$itemId]['fields'][$field]['replaced'] = $replacedParts;              
            }
        	}
        }
        if ($type == 'replace' and $replacedRow) {        	
          db()->query(
            "UPDATE {$v['table']} SET ?a WHERE id=?d",
            $replacedRow, $vv['id']);
        }
      }
    }
    if ($type == 'get') return $items;
  }
  
  function strExists($what, $where) {
  	if ($this->isRegexp) {
  		//print "preg_match(\"/$what/\", $where)<hr>";
    	return preg_match("/$what/", $where);
    } else {
    	return strstr($where, $what);
    }
  }
  
  function getDDItems() {
  	return $this->processDDItems('get');
  }
  
  function replaceDDItems() {
  	$this->processDDItems('replace');
  }
  
}

