<?php

define('TTF_DIR', CORE_PATH.'/fonts/ttf');

class Reporter2 {
	
	public $table;
	
	public $tablePrefix;
	
	public $dateField;
	
	public $imageLink;
  
  public $rows;
  
  public $graph;
	
	public $groupBy;
	
  /*
   * rotates/clicks/ctr
   */
  public $mode;
  
  protected $extraCond;
  
  private $title;
  
	function __construct($tablePrefix, $dateField) {
		$this->tablePrefix = $tablePrefix;
		$this->dateField = $dateField;
		$this->setMode();
	}
	
	public function setMode($mode = 'rotates') {
		if (!in_array($mode, array('clicks', 'rotates', 'ctr')))  
		  Err::warning("Unknown mode $mode");
		$this->mode = $mode;
		$this->setTable($this->tablePrefix.'_'.$mode);
	}
	
	public function setTable($table) {
    $this->table = $table;
	}
	
	public function setDateField($dateField) {
    $this->dateField = $dateField;		
	}
	
  public function setExtraCond($cond) {
    $this->extraCond .= "\n".$cond;
  }

  public function resetExtraCond($cond) {
    $this->extraCond = "\n".$cond;
  }

  public function setGroup($groupBy = null) {
    $this->groupBy = $groupBy;		
	}
  
  /**
   * @param string Тип: day/month/year
   */
  public function getData_($type, $date) {
    //list($y, $m, $d) = explode('-', $date);
    list($d, $m, $y) = explode('.', $date);
    $y = (int)$y;
    $d = (int)$d;
    $m = (int)$m;
    if ($this->groupBy) $groupByCond = "GROUP BY {$this->groupBy}"; 
    if ($type == 'day') {
    	$cond = "
      DAY({$this->dateField})=$d AND
      MONTH({$this->dateField})=$m AND
      YEAR({$this->dateField})=$y
    	";
      $selF = 'HOUR';
      //for ($i=0; $i<=23; $i++) $rows[$i] = 0;
    } elseif ($type == 'month') {
      $cond = "
      MONTH({$this->dateField})=$m AND
      YEAR({$this->dateField})=$y
      ";
      $selF = 'DAY';
      //for ($i=0; $i<=31; $i++) $rows[$i] = 0;
    } elseif ($type == 'year') {
      $cond = "
    	YEAR({$this->dateField})=$y
      ";
    	$selF = 'MONTH';
    	//for ($i=2007; $i<=2009; $i++) $rows[$i] = 0;
    }
    
    $t = strtolower(substr($selF, 0, 1));
  //--      UNIX_TIMESTAMP({$this->dateField}) AS tStamp_{$this->dateField}
    $r = q("
    SELECT
      adsId,
      $selF({$this->dateField}) AS $t
    FROM {$this->table}
    WHERE $cond
    {$this->extraCond}
    $groupByCond
    ");
    if (!$r) return array();
    foreach ($r as $v) {
      $rows[$v[$t]] ? $rows[$v[$t]]++ : $rows[$v[$t]] = 1;       
    }
    //pr($rows); die();
    asort($rows);
    return $rows;
  }

  public function getData($type, $date) {
  	if ($this->mode == 'ctr') {
      $this->setMode('rotates');
      $rowsR = $this->getData_($type, $date);
      $this->setMode('clicks');
      $rowsC = $this->getData_($type, $date);
      foreach ($rowsC as $k => $v) {
        if ($rowsR[$k]) {
          $rows[$k] = round($rowsC[$k] / $rowsR[$k] * 100, 2);
        }  
      }
      $this->setMode('ctr');
      return $rows;
  	} else return $this->getData_($type, $date);
  }

  protected  function graph____($rows, $title, $xTitle, $yTitle) {
    includeLib('common/PowerGraphic.class.php');
    $PG = new PowerGraphic;
    $PG->integer_y = true;
    $PG->title     = $title;
    $PG->axis_x    = $xTitle;
    $PG->axis_y    = $yTitle;
    $PG->graphic_1 = 'Year 2004';
    $PG->graphic_2 = 'Year 2003';
    $PG->type      = 4;
    $PG->skin      = 4;
    $PG->credits   = 0;
    $k = 0;
    foreach ($rows as $h => $n) {
      $PG->x[$k] = $h;
      $PG->y[$k] = $n;
      $k++;
    }
    return  '/common/graph?'.$PG->create_query_string();
  }
  
  protected function graph($rows, $dataBarX, $title, $xTitle, $yTitle) {
  	$this->setGraph();
  	$graph = $this->graph;
    $graph->SetMarginColor('white');
    $graph->SetFrame(false);
    $graph->SetMargin(55, 155, 30, 45);
    //$graph->img->SetMargin(55, 125, 20, 40);
    $graph->img->SetAntiAliasing();     
    $graph->legend->SetFont(FF_ARIAL, FS_NORMAL, 8);
    $graph->title->Set($title);
    $graph->title->SetFont(FF_ARIAL, FS_BOLD, 10);
    $graph->xaxis->title->Set($xTitle);
    $graph->xaxis->SetTickLabels($dataBarX);
    $graph->xaxis->title->SetFont(FF_ARIAL, FS_NORMAL, 9);
    $graph->xaxis->SetPos('min');
    $graph->xaxis->SetLabelMargin(4);
    $graph->xaxis->SetTitleMargin(10); 
    $graph->xaxis->SetFont(FF_ARIAL, FS_NORMAL, 7);
    $graph->yaxis->SetFont(FF_ARIAL, FS_NORMAL, 8);
    $graph->yaxis->title->Set($yTitle);
    $graph->yaxis->title->SetFont(FF_ARIAL, FS_NORMAL, 9);    
    $graph->yaxis->SetTitleMargin(40);
    $graph->legend->SetShadow('white', 0);
    $graph->legend->Pos(0.08, 0.1, 'right', '');
    //$graph->legend->SetAbsPos(10,10,'right','top');
    $graph->yaxis->SetWeight(2);
    $graph->xaxis->SetWeight(2);
    $colors =& $this->colors;
    if (is_array($rows[0])) {
    	for ($i=0; $i<count($rows); $i++) {
    		$row =& $rows[$i];
    		$isMark = count($rows[$i]['report']) == 1 ? true : false;
    		if ($i==0) $rows[$i]['report'][count($dataBarX)-1] = "-"; // Заглушка, что бы не стягивалось
    		for ($j=0; $j<count($row['report']); $j++)
    		  if (!$row['report'][$j])
    		  	$row['report'][$j] = "-";
        // Create the linear plot
        $p = new LinePlot($row['report']); 
        $p->SetColor($colors[$i]);
        //$lineplots[$i]->mark->SetFont(FF_ARIAL, FS_NORMAL, 9);
        $p->SetLegend(Misc::cut($row['data']['title'], 30));
        //$p->ShowBorder(false);
        if ($isMark) {
          $p->mark->SetType(MARK_FILLEDCIRCLE);
          $p->mark->SetFillColor($colors[$i]);
          $p->mark->SetWidth(3);        
        }
        $graph->Add($p); // Add the plot to the graph
    	}
    } else {
      // Create the linear plot 
      $lineplot = new LinePlot($rows); 
      $lineplot->SetColor('red');
      // Add the plot to the graph 
      $graph->Add($lineplot);
    }
    // Display the graph
    Dir::make(UPLOAD_PATH.'/graph'); 
    //$name = md5(serialize($rows));
    $name = rand(0, 100000);
    $graph->Stroke(UPLOAD_PATH.'/graph/'.$name.'.jpg');
    return '/'.UPLOAD_DIR.'/graph/'.$name.'.jpg';
  }    
  
}
