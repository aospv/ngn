<?php

class GrabberSourceRss extends GrabberSourceHtmlAbstract {
  
  static public $title = 'RSS';
  
  protected function _getChannelItems() {
    $channel = $this->channelData;
    $feedReader = new FeedReader('d.m.Y H:i:s');
    $feedReader->setFeedUrl($channel['url']);
    if (!$feedReader->parseFeed()) {
      die2($feedReader->errors);
      //throw new NgnException($feedReader->errors, true);
    }
    //$n = $feedReader->getFeedNumberOfNodes();
    $data = $feedReader->getFeedOutputData();
    for ($i=0; $i<$this->itemsLimit; $i++) {
      if (!empty($channel['contentBegin'])) {
        $text = $this->getHTMLContent(
          $data['item']['link'][$i],
          $channel
        );
      } else {
        $text = $this->formatFromRssItem($data['item'], $i);
      }
      // Убираем ссылки на тот же ресурс, с которого берём новость
      $text = $this->stripLinks($text, $channel['url']);
      $items[] = array(
        'title' => $data['item']['title'][$i],
        'text' => $text,
        'dateCreate' => $data['item']['pubdate'][$i],
        'datePublish' => $data['item']['pubdate'][$i],
        //'guid' => $data['item']['guid'][$i] ? $data['item']['guid'][$i] : $data['item']['link'][$i],
        //'subjects' => $channel['subjects']
      );
    }
    return $items;
  }
  
  private function formatFromRssItem($item, $i) {
    return $item['description'][$i].'<hr />'.
           '<a href="'.$item['link'][$i].'" target="_blank">'.$item['link'][$i].'</a>';
  }
  
  public function process() {
    foreach (RssChannels::getSubscribes() as $v) {
      self::import($v['pageId'], $v['url']);
    }
  }
  
  public function getLinks($page) {}
  
}
