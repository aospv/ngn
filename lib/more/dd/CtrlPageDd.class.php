<?php

abstract class CtrlPageDd extends CtrlPage {

  /**
   * @var DdItemsManager
   */
  public $oManager; 

  /**
   * Имя структуры
   *
   * @var string
   */
  public $strName;
  

  /**
   * ID текущей записи
   *
   * @var integer
   */
  public $itemId;
  
  /**
   * Данные текущей записи
   *
   * @var array
   */
  public $itemData;

  /**
   * Данные пользователя текущей записи
   *
   * @var array
   */
  public $itemUser;

  public $requiredSettings = array('strName');
  
  /**
   * Тип редиректа при успешном выполнении экшена:
   * referer/self/completePage
   *
   * @var string
   */
  public $completeRedirectType;

  /**
   * Экшены, имеющие страничку "complete"
   *
   * @var array
   */
  public $completeActions = array(
    'new', 'edit', 'delete'
  );
  
  public $isParentSubAction = false;
  
  public $useDefaultTplFolder = true;
  
  /**
   * Имя структуры мастер-раздела
   *
   * @var integer
   */
  public $masterStrName;
  
  /**
   * ID мастер-раздела
   *
   * @var integer
   */
  public $masterPageId;
  
  /**
   * Имя поля мастер-раздела
   *
   * @var string
   */
  public $masterField;
  
  protected function init() {
    $this->strName = $this->page['settings']['strName'];
    $oFields = new DdFields($this->strName);
    // ----------------------------------------------------------------
    $this->itemId = isset($this->oReq->r['itemId']) ? (int)$this->oReq->r['itemId'] : 0;
    if (!$this->itemId)
      $this->itemId = isset($this->params[1]) ? (int)$this->params[1] : 0;
    
    // Перед инициализацией класса CtrlPage, в котором инициализируется объект
    // комментариев, для этого объекта нужно определить $this->id2
    $this->id2 = $this->itemId;
    $this->d['fields'] = $oFields->getFields();
    parent::init();
  }
  
  protected function getItemsManagerOptions() {
    return array();
  }
  
  protected function initItemsManager() {
    $this->oManager = DdCore::getItemsManager($this->page['id'], $this->getItemsManagerOptions());
    $this->oManager->oItems->setPriv($this->priv);
    $this->oManager->oForm->oFields->getDisallowed = $this->adminMode;
    $this->oManager->oForm->ctrl = $this;
    if ($this->userGroup)
      $this->oManager->createData = array('userGroupId' => $this->userGroup['id']);
    if (!Misc::hasSuffix('edit', $this->action)) {
      if (!empty($this->page['settings']['createBtnTitle']))
        $this->oManager->oForm->options['submitTitle'] = $this->page['settings']['createBtnTitle'];
      else
        $this->oManager->oForm->options['submitTitle'] = 'Добавить';
    }
  }
  
  public function updateCommentsDate() {
    if (!$this->itemId) throw new NgnException('$this->itemId not defined');
    $this->oManager->oItems->updateField($this->itemId, 'commentsUpdate', dbCurTime());
  }

  protected function setFormTpl($oForm) {
    if (empty($this->page['settings']['formTpl'])) return;
    if (!$tpls = Config::getVar('formTpl.'.$this->page['settings']['formTpl'], true)) 
      throw new NgnException('Config var "formTpl.'.$this->page['settings']['formTpl'].'" with form '.
            'templates not exists.');      
    foreach ($tpls as $k => $v) {
      $oForm->templates[$k] = $v;
    }
  }
    
  public function setItemData() {
    if (isset($this->itemData)) return $this->itemData;
    if (!($this->itemData = $this->oManager->oItems->getItemF($this->itemId))) return false;
    $this->d['item'] = $this->itemData;
    return $this->itemData;
  }
  
  public function action_new() {
    $this->d['tpl'] = 'edit';
    if (($id = $this->oManager->requestCreate(
    isset($this->oReq->r['default']) ? $this->oReq->r['default'] : array())) !== false) {
      $this->itemId = $id;
      $this->completeRedirect();
      return $id;
    } else {
      $this->d['form'] = $this->oManager->oForm->html();
      return false;
    }
  }
  
  /**
   * Производит редактирование
   * Возвращает true при удачном апдейте
   *
   * @return bool
   */
  public function action_edit() {
    if (empty($this->itemId)) {
      $this->error404('Редактирование невозможно');
      return false;
    }
    $this->oManager->oForm->setActionFieldValue('edit');
    $this->d['itemId'] = $this->itemId;
    $this->d['tpl'] = 'edit';
    if ($this->oManager->requestUpdate($this->itemId)) {
      $this->completeRedirect();
      return true;
    } else {
      $this->initDeleteFileUrl();
      $this->d['form'] = $this->oManager->oForm->html();
    }
    return false;
  }
  
  protected function initDeleteFileUrl() {
    $this->oManager->oForm->options['deleteFileUrl'] =
      Tt::getPath(1).'/'.$this->itemId.'/?a=deleteFile';
  }
  
  public function action_json_new() {
    if ($this->oManager->requestCreate(
    isset($this->oReq->r['default']) ? $this->oReq->r['default'] : array())) {
      $this->json = $this->oManager->data;
      return;
    }
    return $this->oManager->oForm;
  }
  
  public function action_json_edit() {
    $this->initDeleteFileUrl();
    if ($this->oManager->requestUpdate($this->itemId)) {
      $this->json = $this->oManager->data;
      return;
    }
    return $this->oManager->oForm;
  }
  
  public function action_updateDirect() {
    $this->action_ajax_updateDirect();
    $this->redirect();
  }
  
  public function action_ajax_updateDirect() {
    if (!isset($this->oReq->r['k']))
      throw new NgnException("\$this->oReq->r['k'] not defined");
    $k = mysql_escape_string($this->oReq->r['k']);
    db()->query("UPDATE dd_i_{$this->strName} SET $k=? WHERE id=?d",
      $this->oReq->r['v'], $this->oReq->r['itemId']);
  }
  
  public function action_complete() {
    if (!isset($this->oReq->r['completeAction']))
      throw new NgnException("\$this->oReq->r['completeAction'] not defined");
    $completeAction = $this->oReq->r['completeAction'];
    if ($completeAction != 'delete') {
      if (!$this->itemId) {
        $this->error404();
        return;
      }
      $this->d['moders'] = Moder::getModers($this->page['id']);
    }
    if (Tt::exists('complete.'.$completeAction)) {
      $this->d['tpl'] = 'complete.'.$completeAction;
    } else {
      $this->d['tpl'] = 'complete';
    }
  }
  
  public function action_delete() {
    $this->oManager->delete($this->itemId);
    $this->completeRedirect();
  }
  
  public function action_ajax_delete() {
    LogWriter::v('deleeeee', $this->itemId);
    $this->oManager->delete($this->itemId);
  }
  
  protected $disableCompleteRedirect = false;
  
  public function completeRedirect() {
    if ($this->disableCompleteRedirect) return;
    if (!isset($this->completeRedirectType) and 
        !empty($this->page['settings']['completeRedirectType'])) {
      $this->completeRedirectType = $this->page['settings']['completeRedirectType'];
    }
    if ($this->completeRedirectType == 'referer') {
      // referer
      $this->redirect('referer');
    } elseif ($this->completeRedirectType == 'referer_item') {
      // referer_item
      if ($this->action == 'edit' or $this->action == 'new') {
        $this->redirect(Tt::getPath(1).'/'.$this->itemId);
      } else {
        $this->redirect('referer');
      }      
    } elseif ($this->completeRedirectType == 'self') {
      // self
      $this->redirect();
    } elseif ($this->completeRedirectType == 'edit') {
      // edit
      $this->redirect(Tt::getPath(1).
        ((!isset($this->static_id) and isset($this->itemId)) ? '/'.$this->itemId : '').
        '?a=edit');
    } elseif ($this->completeRedirectType == 'fullpath') {
      // fullpath
      $this->redirect('fullpath');
    } else {
      // complete
      $this->redirect(Tt::getPath().'?a=complete&completeAction='.
        $this->action.($this->itemId ? '&itemId='.$this->itemId : ''));
    }
  }
  
  public function action_ajax_deleteFile() {
    $this->deleteFile();
  }
  
  public function action_deleteFile() {
    $this->deleteFile();
    $this->completeRedirect();
  }
  
  protected function deleteFile() {
    $this->oManager->deleteFile($this->itemId, $this->oReq->rq('fieldName'));
  }

  public function action_ajax_import() {
    DdImporter::import($this->strName, $this->page['id'], $_POST['importData']);
  }
  
  public function action_json_tagsSearch() {
    $this->json = array();
    foreach (db()->select('
      SELECT id, title FROM tags WHERE
      strName=? AND groupName=? AND title LIKE ?',
      $this->strName, $this->oReq->r['fieldName'], $this->oReq->r['search'].'%'
    ) as $v) {
      $this->json[] = array($v['title'], $v['title'], null, $v['title']);
    }
  }
  
  public function action_changeUserForm() {
    $this->d['tpl'] = 'dd/changeUser';
  }
  
  public function action_changeUser() {
    if (!$this->itemId) return;
    $this->oManager->items->update(
      $this->itemId,
      array('userId' => $_POST['userId'])
    );
    $this->redirect();
  }
  
  public function action_ajax_subscribeNewComments() {
    if (!$this->userId) throw new NgnException('No user ID');
    if (!$this->itemId) throw new NgnException('No item ID');
    Notify_SubscribeItems::update(
      $this->userId, 'comments_newMsgs', $this->page['id'], $this->itemId);
  }
  
  public function action_ajax_unsubscribeNewComments() {
    if (!$this->userId) throw new NgnException('No user ID');
    if (!$this->itemId) throw new NgnException('No item ID');
    Notify_SubscribeItems::delete(
      $this->userId, 'comments_newMsgs', $this->page['id'], $this->itemId);
  }
  
  public function action_ajax_subscribeNewItems() {
    if (!$this->userId) throw new NgnException('No user ID');
    Notify_SubscribePages::update(
      $this->userId, 'items_new', $this->page['id']);
  }
  
  public function action_ajax_unsubscribeNewItems() {
    if (!$this->userId) throw new NgnException('No user ID');
    Notify_SubscribePages::delete(
      $this->userId, 'items_new', $this->page['id']);
  }
  
  protected function afterAction() {
    parent::afterAction();
    if (($l = $this->getPageLabel()) !== null)
      $this->setPageTitle($this->d['pageTitle'].$l);
  }
  
  protected function initSubPriv() {
    $this->setPriv('sub_view', true);
  }
  
  protected function click() {
    $this->oManager->oItems->click($this->itemId);
  }
  
  protected function rate() {
    $this->oManager->oItems->click($this->itemId);
  }
  
  protected function initMeta() {
    parent::initMeta();
    if ($this->action == 'showItem') {
      if (($r = db()->selectRow('SELECT * FROM dd_meta WHERE id=?d AND strName=?',
      $this->itemId, $this->page['strName']))) {
        $this->d['pageMeta'] = $r;
      } else {
        if (isset($this->itemData['text']))
          $this->d['pageMeta']['description'] = Misc::cut($this->itemData['text'], 255);
      }
    }
  }

  protected function prepareTplPath() {
    parent::prepareTplPath();
    $tpl = $this->d['tpl'];
    if ($this->useDefaultTplFolder and
    Tt::exists($this->tplFolder.'/'.$this->strName.'/'.$tpl)) {
      $this->d['tpl'] = $this->tplFolder.'/'.$this->strName.'/'.$tpl;
    } elseif (!empty($this->page['settings']['tplName']) and 
    Tt::exists($this->tplFolder.'/'.$this->page['settings']['tplName'].'/'.$tpl)) {
      // Если задан каталог с шаблонами и необходимый фаблон в нем существует
      $this->d['tpl'] = $this->tplFolder.'/'.$this->page['settings']['tplName'].'/'.$tpl;
    } elseif (Tt::exists($this->tplFolder.'/'.$tpl)) {
      $this->d['tpl'] = $this->tplFolder.'/'.$tpl;
    }
  }
  
  public function processDynamicBlockModels(array &$blockModels) {
    if (in_array($this->action, array('new', 'edit'))) $blockModels = array();
    /*
    $blockModels[] = new DbModelVirtual(array(
      'type' => 'text',
      'colN' => 1,
      'settings' => array(
        'text' => 'System edit message'
      )
    ));
    */
  }

}
