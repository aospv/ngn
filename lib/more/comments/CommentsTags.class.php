<?php

class CommentsTags {
  
  static public function getTags($level) {
    if ($level >= Config::getVarVar('level', 'commentsTagsLayer2Level'))
      $config = Config::getVar('comments.allowedTags.layer2');
    if (!$config)
      $config = Config::getVar('comments.allowedTags');
    return $config;
  }
  
  static public function getToolbarTags($level) {
    $tags = array(
      'b' => array(
        'title' => 'Жирный',
        'class' => 'bold',
      ),
      'i' => array(
        'title' => 'Наклонный',
        'class' => 'italic',
      ),
      'u' => array(
        'title' => 'Подчёркнутый',
        'class' => 'underline',
      ),
      's' => array(
        'title' => 'Перечёркнутый',
        'class' => 'strike',
      ),
      'quote' => array(
        'title' => 'Цитировать',
        'class' => 'quote',
      ),
      'attention' => array(
        'title' => 'Выделить',
        'class' => 'attention',
      ),
    );
    return Arr::filter_by_keys($tags, self::getTags($level));
  }
  
}
