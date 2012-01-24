<?php

// Rss
$this->d['isRss'] = (
  ($this->d['isItemsController'] and $this->d['settings']['rssTitleField']) or 
  $this->d['isCommentsController']
);

// Errors
$this->d['errors'] = Auth::get('errors');

// Layout
if (!isset($this->d['layoutN']))
  $this->d['layoutN'] = PageLayoutN::get($this->d['page']['id']);

if ($this->action == 'new' or $this->action == 'edit')
  $this->d['layoutN'] = 2;
