<?php

class Pagination extends Options2 {

  public $options = array(
    'n' => 10,
    'sep' => ' ',
    'maxPages' => 9,
    'dddd' => '`<a href="`.$link.`"><span>`.$title.`</span></a>`',
    'ddddSelected' => '`<b><span>`.$title.`</span></b>`',
    'forceShowTableStatus' => false,
    'oReq' => null,
    'type' => '',
    'desc' => false,
    'page' => null,
    'selfPage' => null
  );
  
  /**
   * @var Req
   */
  protected $req;
  
  protected function init() {
    $this->req = $this->options['oReq'] ?: O::get('Req');
  }
  
  public function get($table, DbCond $cond = null) {
    if ($cond or $this->options['forceShowTableStatus']) {
      $cnt = db()->selectCell("SELECT COUNT(*) AS count FROM $table".$cond->all());
    } else {
      $r = db()->selectRow("SHOW TABLE STATUS LIKE '$table'");
      $cnt = $r['Rows'];
    }
    $res = $this->data($cnt);
    return array($res[0], $res[1].','.($res[1]+$res[2]), $res[3], $res[4]);
  }
  
  public function get2($cnt) {
    $res = $this->data($cnt);
    return array($res[0], $res[1].','.$res[2], $res[3], $res[4]);
  }
  
  public function data($all) {
    if ($this->req->page) {
      $page = isset($this->req->page[$this->options['type']]) ?
        $this->req->page[$this->options['type']] : 1;
    } else {
      $page = 1;
    }

    // Если №страницы меньше или равен 0, считаем, что это первая страница
    if ($page <= 0) $page = 1;

    $pagesN = 0; // Количество страниц всего

    if ($this->options['n'] == 0) $pagesN = 0;
    else {
      if ($all) $pagesN = ceil($all / $this->options['n']);
      else $pagesN = 1;
    }
    
    // Если №страницы больше возможного кол-ва страниц
    if ($page > $pagesN) $page = $pagesN;
  
    if ($this->options['desc']) {
      $page = $pagesN - $page + 1;
    }
    
    $html = "";
    
    if (!$this->options['page']) {
      unset($this->req->r["page".$this->options['type']]);
    }
    
    $links = array();
      
    if ($pagesN != 0 and $pagesN != 1) {
      $links = array();

      $descN = 0;
      
      if ($this->req->page) {
        $self = Tt::getPath(count($this->req->params)-1);
      } else {
        $self = Tt::getPath();
      }
      
      for ($i = 0; $i < $pagesN; $i++) {
        $pageNumber = $i + 1;
        $descN--;
      
        if ($i <= $page - round($this->options['maxPages'] / 2)-1 or 
            $i >= $page + round($this->options['maxPages'] / 2)-1) continue;

        $qstr2 = $self.'/pg'.$this->options['type'].$pageNumber;
        
        $d = array(
          'title' => $pageNumber,
          'link' => $this->options['selfPage'].$qstr2
        );
      
        if (($i+1) == $page) $links[] = St::dddd($this->options['ddddSelected'], $d);
        else $links[] = St::dddd($this->options['dddd'], $d);
      }
    }
    if (count($links) > 0) $html = implode($this->options['sep'], $links);

    if ($this->options['n'] == 0) $limit = '';
    else {
      $offset = ($page-1) * $this->options['n'];
      $limit = $this->options['n'];
    }
    return array($html, $offset, $limit, $all, count($links));
  }

}
