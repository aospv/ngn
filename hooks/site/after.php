<?php

// Create Privileges
$this->d['canCreateAuth'] = (
  DdCore::isDdController($this->page['controller']) and
  (
    !empty($this->d['privAuth']['create']) or
    in_array('new', $this->d['allowedActions'])
  ) and
  $this->d['action'] != 'new'
);

// Create Button

$this->d['authToCreate'] =
  (!$this->d['priv']['create'] and !Auth::get('id') and $this->d['canCreateAuth']);

if (
$this->d['action'] == 'list' and 
$this->d['canCreateAuth'] and 
!$this->page['settings']['showFormOnDefault']) {
  $defaultParam = '';
  if (!$this->d['params']) {
    $listPath = $this->d['page']['path'];
  } elseif ($this->d['page']['slave']) {
    $defaultParam = '&default['.DdCore::masterFieldName.']='.$this->d['masterItem']['id'];
    $listPath = $this->d['pathData'][count($this->d['pathData'])-1]['link'];
  } elseif (!empty($this->d['tagsSelected'][0])) {
    $defaultParam = '&default['.$this->d['tagsSelected'][0]['groupName'].']='.
      $this->d['tagsSelected'][0]['id'];
    $listPath = Tt::getPath(2);
  } else {
    $listPath = Tt::getPath(1);
  }
  $this->d['topBtns'][] = array(
    'title' => !empty($this->d['createBtnTitle']) ?
      $this->d['createBtnTitle'] : 'Добавить '.
        NgnMorph::cast($this->page['settings']['itemTitle'], array('ЕД', 'ВН')), 
    'link' => $listPath . '?a=new'.$defaultParam,
    'class' => $this->d['authToCreate'] ? 'auth' : '',
    'id' => 'btnCreate'
  );
}
$this->d['bodyClass'] = ($this->page['module'] ? ' module_'.$this->page['module'] : '').
(!empty($this->page['settings']['ddItemsLayout']) ?
  ' ddil_'.$this->page['settings']['ddItemsLayout'] : '');

if (isset($_SERVER['HTTP_REFERER'])) {
  if (strstr($_SERVER['HTTP_REFERER'],'vkontakte.ru') or strstr($_SERVER['HTTP_REFERER'],'vk.com')) {
    $_SESSION['fromVk'] = 1;
  }
}