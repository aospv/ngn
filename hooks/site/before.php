<?php

// Rss
$this->d['isRss'] = (
  (DdCore::isDdController($this->page['controller']) and !empty($this->d['settings']['rssTitleField'])) or 
  !empty($this->d['isCommentsController'])
);

// Errors
$this->d['errors'] = Auth::get('errors');