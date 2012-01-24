<?php

define('TTF_DIR', NGN_PATH.'/fonts/ttf');

class Reporter {
  
  public $colors = array('red', 'blue', 'darkgreen', 'magenta', 'gray');
  
  private function setGraph() {
    // Create the graph. These two calls are always required
    $this->graph = new Graph(600, 300, 'auto');
    $this->graph->SetScale('textlin');
  }  

  public function _graph($rows, $dataBarX = null, $title = '', $xTitle = '', $yTitle = '') {
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
    if ($dataBarX) $graph->xaxis->SetTickLabels($dataBarX);
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
    if (is_array($rows[0]['report'])) {
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
        //$p->SetLegend(Misc::cut($row['data']['title'], 30));
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
      //$lineplot = new LinePlot($rows);
      $lineplot = new LinePlot(array(11,3,8,12,5,1,9,13,5,7));
      
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
