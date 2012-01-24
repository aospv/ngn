<?php

if ($this->d['action'] == 'new') {
  $this->d['pageTitle'] = 'Подача заявки на дружбу';
}

/*
if ($this->d['action'] == 'list') {
  if (in_array(Auth::get('id'), array_unique(Arr::get($this->d['items'], 'userId')))) {
    $this->d['topBtns'][] = array(
      'title' => 'Панель друга',
      'link' => Tt::getPath(1).'?a=friendsBar'
    );  
  }
}
*/