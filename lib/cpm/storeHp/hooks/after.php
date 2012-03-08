<?php

if (!Auth::get('id')) {
  $this->d['topBtns'][] = array(
    'title' => 'Узнать оптовые цены',
    'id' => 'btnShowHiddenPrices'
  );
}
