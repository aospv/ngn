<?php 

class FeedReader_Common {

  public $feedReader;   // parser
  public $feedUrl;
  public $node;
  public $channelFlag;
  public $currentTag;
  public $outputData;
  public $itemFlag;
  public $imageFlag;
  public $feedVersion;
  public $charsetOut;
  public $errors;

  function __construct() {
    $this->feedReader='';
    $this->feedUrl='';
    $this->node=0;
    $this->channelFlag=false;
    $this->currentTag='';
    $this->outputData=array();
    $this->itemFlag=false;
    $this->imageFlag=false;
    $this->feedVersion='';
  }

  function setFeedUrl($url) {
    $this->feedUrl=$url;
  }

  function getFeedOutputData() {
    //$this->outputData = Misc::iconvR('UTF-8', $this->charsetOut, $this->outputData);
    return $this->outputData;
  }

  function getFeedNumberOfNodes() {
    return $this->node;
  }

  function parseFeed() {
    //if (!$data = @file_get_contents_ie($this->feedUrl)) return false;
    if (!$data = @file_get_contents($this->feedUrl)) return false;
    $this->feedReader = xml_parser_create();
    xml_set_object($this->feedReader, $this);
    xml_parser_set_option($this->feedReader, XML_OPTION_CASE_FOLDING, true);
    xml_set_element_handler($this->feedReader, 'openTag', 'closeTag');
    xml_set_character_data_handler($this->feedReader, 'dataHandling');
    if (!@xml_parse($this->feedReader, $data)) {
      $this->errors[] = xml_error_string(xml_get_error_code($this->feedReader));
    }
    xml_parser_free($this->feedReader);
    return $this->errors ? false : true;
  }

  function openTag(&$parser, &$name, &$attribs) {
    if ($name) {
      switch (strtolower($name)) {
        case 'channel':
          $this->channelFlag = true;
          break;
        case 'image':
          $this->channelFlag = false;
          $this->imageFlag = true;
          break;
        case 'item':
          $this->channelFlag = false;
          $this->imageFlag = false;
          $this->itemFlag = true;
          $this->node++;
          break;
        default:
          $this->currentTag = strtolower($name);
          break;
      }
    }
  }

  function closeTag(&$parser, &$name){
    $this->currentTag = '';
  }

  function dataHandling(&$parser, &$data) {
    if (!$this->currentTag) return;
    if ($this->channelFlag) {
      if (isset($this->outputData['channel'][$this->currentTag])) {
        $this->outputData['channel'][$this->currentTag] .= $data;
      } else {
        $this->outputData['channel'][$this->currentTag] = $data;
      }
    }
    if ($this->itemFlag) {
      if (isset($this->outputData['item'][$this->currentTag][$this->node-1])) {
        $this->outputData['item'][$this->currentTag][$this->node-1] .= $data;
      } else{
        $this->outputData['item'][$this->currentTag][$this->node-1] = $data;
      }
    }
    if ($this->imageFlag) {
      if(isset($this->outputData['image'][$this->currentTag])){
        $this->outputData['image'][$this->currentTag].=$data;
      }else{
        $this->outputData['image'][$this->currentTag]=$data;
      }
    }
  }

}

class FeedReader extends FeedReader_Common {
  
  public $dateFormat;
  
  function __construct($dateFormat = 'Y-m-d H:i:s') {
    $this->dateFormat = $dateFormat;
    $this->charsetOut = CHARSET;
    parent::__construct();
  }
  
  function parseFeed() {
    if (!$r = parent::parseFeed()) return false;
    if ($this->outputData['item']['pubdate']) {
      foreach ($this->outputData['item']['pubdate'] as &$v) {
        $v = $this->formatDate($v);
      }
    }
    return $r;
  }
  
  function formatDate($s) {
    $months = array(
      'Jan' => 1,
      'Feb' => 2,
      'Mar' => 3,
      'Apr' => 4,
      'May' => 5,
      'Jun' => 6,
      'Jul' => 7,
      'Aug' => 8,
      'Sep' => 9,
      'Oct' => 10,
      'Nov' => 11,
      'Dec' => 12,
    );
    $r = preg_match('/(\w{3}), (\d{2}) (\w{3}) (\d{4}) (\d{2}):(\d{2})(:(\d{2}))* (.*)/', $s, $m);
    return date($this->dateFormat, mktime($m[5], $m[6], (int)$m[8], $months[$m[3]], (int)$m[2], $m[4]));
  }
  
}
