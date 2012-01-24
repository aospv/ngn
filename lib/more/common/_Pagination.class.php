<?php

class Pagination {
  
  static $n = 10;
  
  static $whereCond;
  
  static $sep = ' ';
  
  static $selfPage;
  
  static $type = '';
  
  static $desc;
  
  static $maxPages = 9;
  
  static $tpl = '`<a href="`.$link.`"><span>`.$title.`</span></a>`';
  
  static $tplSelected = '`<b><span>`.$title.`</span></b>`';
  
  static $page;

  static public function get($table, $joins = '', $forceShowTableStatus = false) {
    if (!empty(self::$whereCond) or $forceShowTableStatus) {
      $whereCond = isset(self::$whereCond) ? self::$whereCond : '';
      //$whereCond = isset(self::$whereCond) ? 'WHERE '.self::$whereCond : '';
      $cnt = db()->selectCell(
        "SELECT COUNT(*) AS count FROM $table $joins $whereCond");
    } else {
      $r = db()->selectRow("SHOW TABLE STATUS LIKE '$table'");
      $cnt = $r['Rows'];
    }
    $res = self::getData($cnt);
    return array($res[0], $res[1].','.$res[2], $res[3], $res[4]);
  }
  
  static public function get2($cnt) {
    $res = self::getData($cnt);
    return array($res[0], $res[1].','.$res[2], $res[3], $res[4]);
  }
  
  /**
   * Генерирует данные
   *
   * @param integer Общее число записей
   * @return unknown
   */
  static public function getData($all) {
    if (O::get('Req')->page) {
      $page = isset(O::get('Req')->page[self::$type]) ? O::get('Req')->page[self::$type] : 1;
    } else {
      $page = 1;
    }

    // Если №страницы меньше или равен 0, считаем, что это первая страница
    if ($page <= 0) $page = 1;

    $pagesN = 0; // Количество страниц всего

    if (self::$n == 0) $pagesN = 0;
    else {
      if ($all) $pagesN = ceil($all / self::$n);
      else $pagesN = 1;
    }
    
    // Если №страницы больше возможного кол-ва страниц
    if ($page > $pagesN) $page = $pagesN;
  
    if (isset(self::$desc)) {
      $page = $pagesN - $page + 1;
    }
    
    $html = "";
    
    if (!self::$page) {
      unset($_GET["page".self::$type]);
    }
    
    $links = array();
      
    if ($pagesN != 0 and $pagesN != 1) {
      $links = array();

      $descN = 0;
      
      if (O::get('Req')->page) {
        $self = Tt::getPath(count(O::get('Req')->params)-1);
      } else {
        $self = Tt::getPath();
      }
      
      for ($i = 0; $i < $pagesN; $i++) {
        $pageNumber = $i + 1;
        $descN--;
      
        if ($i <= $page - round(self::$maxPages / 2)-1 or 
            $i >= $page + round(self::$maxPages / 2)-1) continue;

        $qstr2 = $self.'/pg'.self::$type.$pageNumber;
        
        $d = array(
          'title' => $pageNumber,
          'link' => self::$selfPage.$qstr2
        );
      
        if (($i+1) == $page) $links[] = St::dddd(self::$tplSelected, $d);
        else $links[] = St::dddd(self::$tpl, $d);
      }
    }
  
    if (count($links) > 0) $html = implode(self::$sep, $links);

    if (self::$n == 0) $limit = '';
    else {
      $offset = ($page-1) * self::$n;
      $limit = self::$n;
    }
    
    return array($html, $offset, $limit, $all, count($links));
  }
  
}
