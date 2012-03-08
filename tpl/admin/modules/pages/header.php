<?

$links = array();
$curPageModulePath = Tt::getPath(1).'/pages/'.($d['page']['id'] ? $d['page']['id'] : 0);

$isItems = ClassCore::hasAncestor('Ctrl'.$d['page']['controller'], 'CtrlDdItems');
$isMasterItems = ClassCore::hasAncestor('Ctrl'.$d['page']['controller'], 'CtrlItemsMaster');

  if ($d['action'] != 'editContent' and ($d['page']['folder'] or !$d['page'])) {
    $t = $d['page']['id'] ? LANG_CREATE_SECTION : 'Создать корневой раздел';
    $links[] = array(
      'title' => $t,
      'class' => 'add createModulePage',
      'link' => $curPageModulePath.'/newModulePage'
    );
    if ($d['params'][0] == 'god') {
      $links[] = array(
        'title' => $t.' (ручное)',
        'class' => 'add2 createPage',
        'link' => $curPageModulePath.'/newPage'
      );
    }
  }
  /*
  if ($d['params'][0] == 'god') {
    $links[] = array(
      'title' => 'Создать slave-раздел',
      'class' => 'add',
      'link' => Tt::getPath(3).'?a=newSlavePage'
    );
  }
  */  
  if ($d['page']) {
    $editContentPath = $curPageModulePath.'/editContent';
      
    if ($d['page']['settings']['strName']) {
      if ($d['action'] == 'editContent' and $isItems) {
        $editContentFullPath = $curPageModulePath.'/editContent'.
        (
          !empty($d['oSPC']->page['slave']) ?
          '/v.'.DdCore::masterFieldName.'.'.$d['oSPC']->d['masterItem']['id'] :
          ''
        );
        $links[] = array(
          'title' => '<b>Добавить '.NgnMorph::cast($d['oSPC']->settings['itemTitle'], array('ЕД', 'ВН')).'</b>',
          'descr' => 'Создать новую запись в разделе «<b>'.$d['page']['title'].'</b>»',
          'class' => 'add',
          'link' => $editContentFullPath.'?a=new'.
            (
            !empty($d['oSPC']->page['slave']) ?
              '&default['.DdCore::masterFieldName.']='.
                $d['oSPC']->d['masterItem']['id'] :
              ''
            )
        );
        if ($d['pcd']['items']) {
          if ($d['pcd']['action'] == 'list') {
            $links[] = array(
              'title' => 'Выделить все',
              'class' => 'select'
            );
          }
          /*
          $links[] = array(
            'title' => 'Переместить выделенные',
            'class' => 'move',
            'link' => Tt::getPath().'?a=moveGroupForm'
          );
          */
          $links[] = array(
            'title' => 'Удалить выделенные',
            'class' => 'deleteGroup',
            'link' => Tt::getPath().'?a=deleteGroup'
          );
          $links[] = array(
            'title' => 'Сменить автора',
            'class' => 'users',
            'link' => Tt::getPath().'?a=changeAuthorGroupForm'
          );
        }
      }
      if ($isItems) {
        $links[] = array(
          'title' => '<b>'.ucfirst(
            NgnMorph::singular2plural(
              $d['oSPC']->settings['itemTitle']
            )
          ).'</b>',
          'descr' => 'Список записей раздела «<b>'.$d['page']['title'].'</b>»',
          'class' => 'list',
          'link' => $editContentPath
        );
      } else {
        $links[] = array(
          'title' => '<b>Содержание</b>',
          'descr' => 'Редактировать содержание раздела «<b>'.$d['page']['title'].'</b>»',
          'class' => 'edit',
          'link' => $editContentPath
        );
      }
    }
    $links[] = array(
      'title' => '<b>Открыть</b>',
      'descr' => 'Открыть раздел «<b>'.$d['page']['title'].'</b>» на сайте',
      'class' => 'link',
      'link' => Tt::getPath(0).'/'.$d['page']['path'].($d['page']['slave'] ? Tt::getPathLast(1) : ''),
      'target' => '_blank'
    );
    if (AdminModule::_isAllowed('slices') or $d['params'][0] == 'god') {
      $r = db()->query("SELECT id, title FROM slices WHERE pageId=?d", $d['page']['id']);
      foreach ($r as $v) {
        $links[] = array(
          'title' => '<b>'.$v['title'].'</b>',
          'descr' => 'Редактировать: '.$v['title'],
          'class' => 'slices',
          'link' => Tt::getPath(1).'/slices/'.$d['page']['id'].'/'.$v['id'],
          //'target' => '_blank'
        );
      }
    }
    if ((AdminModule::_isAllowed('pageMeta') or $d['params'][0] == 'god') and !$d['page']['slave']) {
      $links[] = array(
        'title' => 'Мета-теги',
        'class' => 'meta',
        'link' => Tt::getPath(1).'/pageMeta/'.$d['page']['id']
      );
    }
    $links[] = array(
      'title' => LANG_PAGE_PROPERTIES,
      'class' => 'editProp',
      'link' => $curPageModulePath.'/editPage'
    );
    if ($d['params'][0] == 'god') {
      if ($d['page']['controller']) {
        $links[] = array(
          'title' => LANG_CONTROLLER_OPTIONS,
          'class' => 'editOptions',
          'link' => $curPageModulePath.'/editControllerSettings'
        );
      }
      if ($isMasterItems) {
        $links[] = array(
          'title' => 'Параметры slave-контроллера',
          'class' => 'editOptions',
          'link' => Tt::getPath(1).'/pages/'.$d['page']['settings']['slavePageId'].'/editControllerSettings'
        );
      }
      /*
      $links[] = array(
        'title' => 'Параметры главного шаблона',
        'class' => 'editOptions',
        'link' => Tt::getPath(1).'/mainTplSettings'
      );
      $links[] = array(
        'title' => 'Параметры главного шаблона раздела <b>'.$d['page']['title'].'</b>',
        'class' => 'editOptions',
        'link' => Tt::getPath(1).'/mainTplSettings/'.$d['page']['id']
      );
      */
    }
    /*
     * Глючит перенаправление
    $links[] = array(
      'title' => LANG_DELETE,
      'class' => 'confirm delete',
      'link' => Tt::getPath(3).'?a=deletePage&id='.$d['page']['id']
    );
    */
    if ((AdminModule::_isAllowed('privileges') or $d['params'][0] == 'god') and !$d['page']['slave']) {
      $links[] = array(
        'title' => LANG_PRIVILEGES,
        'class' => 'privileges',
        'link' => Tt::getPath(1).'/privileges/'.$d['page']['id'].'/pagePrivileges'
      );
    }
    if ($d['extraButtons']) {
      foreach ($d['extraButtons'] as $v) {
        $links[] = array(
          'title' => $v['title'],
          'class' => $v['privileges'],
          'link' => $v['link']
        );
      }
    }
    if (AdminModule::_isAllowed('slices') or $d['params'][0] == 'god') {
      // Slices 
      $links[] = array(
        'title' => 'Создать слайс',
        'class' => 'add',
        'link' => Tt::getPath(1).'/slices/'.$d['page']['id'].'/new'
      );
    }
    if ((AdminModule::_isAllowed('pageLayout') or $d['params'][0] == 'god')) {
      $links[] = array(
        'title' => 'Формат страницы',
        'descr' => 'Задать особый формат страницы для раздела «<b>'.$d['page']['title'].'</b>»',
        'class' => 'layout',
        'link' => Tt::getPath(1).'/pageLayout/'.$d['page']['id'],
        'onclick' => "if (confirm('Вы действительно хотите создать особый формат страницы для раздела «{$d['page']['title']}»?')) window.location = this.href; return false;"
      );
    }
    if (DdCore::isDdController($d['page']['controller']) and (AdminModule::_isAllowed('ddOutput') or $d['params'][0] == 'god')) {
      $links[] = array(
        'title' => 'Управление выводом полей',
        'class' => 'list',
        'link' => Tt::getPath(1).'/ddOutput/'.$d['page']['id'],
      );
    }
    if (AdminModule::_isAllowed('pageBlocks') or $d['params'][0] == 'god') {
      $blocksCount = PageBlockCore::getDynamicBlocksCount($d['page']['id']);
      $links[] = array(
        'title' => 'Блоки'.($blocksCount ? " (<b>$blocksCount</b>)" : ''),
        'class' => 'pageBlocks',
        'link' => Tt::getPath(1).'/pageBlocks/'.$d['page']['id']
      );
    }
      /*
      if ($d['params'][0] == 'god') {
        $links[] = array(
          'title' => 'Параметры блоков',
          'descr' => 'Параметры блоков раздела «<b>'.$d['page']['title'].'</b>»',
          'class' => 'editOptions',
          'link' => Tt::getPath(3).'/editBlocksSettings'
        );
        $links[] = array(
          'title' => 'Параметры блоков по умолчанию',
          'class' => 'editOptions',
          'link' => Tt::getPath(3).'/editBlocksDefaultSettings'
        );
      }
      */

    if ($d['pageBlocks']) {
      foreach ($d['pageBlocks'] as $v) {
        $links[] = array(
          'title' => $v['title'],
          'class' => 'edit',
          'link' => Tt::getPath(3).'?a=editBlock&blockId='.$v['id']
        );
      }
    }
  }
  if ($d['page']) {
    if (AdminModule::_isAllowed('importPhoto') or $d['params'][0] == 'god') {
      if ($d['page']['module'] == 'photoalbumSlave' and $d['page']['slave'] and isset($d['params'][6])) {
        $links[] = array(
          'title' => 'Загрузить архив фотографий',
          'class' => 'import',
          'link' => Tt::getPath(1).'/importPhoto/'.$d['page']['id'].'/'.$d['params'][6],
        );
      } elseif ($d['page']['module'] == 'photo') {
        $links[] = array(
          'title' => 'Загрузить архив фотографий',
          'class' => 'import',
          'link' => Tt::getPath(1).'/importPhoto/'.$d['page']['id'],
        );
      }
    } 
    /*
    if ($isItems and AdminModule::_isAllowed('ddImport') or $d['params'][0] == 'god') {
      $links[] = array(
        'title' => 'Импортировать данные',
        'class' => 'import',
        'link' => Tt::getPath(1).'/ddImport/'.$d['page']['id'],
      );
    }
    if ($isItems and AdminModule::_isAllowed('grabber') or $d['params'][0] == 'god') {
      $links[] = array(
        'title' => 'Граббер',
        'class' => 'grabber',
        'link' => Tt::getPath(1).'/grabber/'.$d['page']['id'],
      );
    }
    */
    if (!empty($d['page']['settings']['tagField'])) {
      $links[] = array(
        'title' => 'Теги',
        'class' => 'tags',
        'link' => Tt::getPath(1).'/tags/'.
          db()->getCell('tags_groups', 'id', 'name', $d['page']['settings']['tagField']).
          '/list',
      );
    }
  }
  
  /*
  if ($d['page']['folder']) {
    $links[] = array(
      'title' => 'Открыть папку',
      'descr' => 'Открыть папку «<b>'.$d['page']['title'].'</b>»',
      'class' => 'folder',
      'link' => Tt::getPath(2).'/'.$d['page']['id']
    );
  }
  */
  if ($d['action'] == 'editControllerSettings' and $isItems) {
    if ($d['page']['settings']['order'] != 'oid' and $d['page']['settings']['order'] != 'oid DESC') {
      $links[] = array(
        'title' => 'Включить ручную сортировку',
        'class' => 'turnOn',
        'link' => Tt::getPath().'?a=setOidPageOrder',
        'onclick' => "if (confirm('Включение ручной сортировки изменит сортировку записей этого раздела. Вы действительно уверены, что хотите этого?')) window.location = this.href; return false;"
      );
    } else {
      $links[] = array(
        'title' => 'Выключить ручную сортировку',
        'class' => 'turnOff',
        'link' => Tt::getPath().'?a=resetOidPageOrder',
        'onclick' => "if (confirm('Выключение ручной сортировки удалит сортировку записей этого раздела. Вы действительно уверены, что хотите этого?')) window.location = this.href; return false;"
      );
    }
    if (empty($d['page']['settings']['showRating'])) {
      $links[] = array(
        'title' => 'Включить рейтинг',
        'class' => 'turnOn confirm',
        'link' => Tt::getPath().'?a=setRatingOn'
      );
    } else {
      $links[] = array(
        'title' => 'Выключить рейтинг',
        'class' => 'turnOff confirm',
        'link' => Tt::getPath().'?a=setRatingOff'
      );
    }
  }
  if (0 and $d['pcd']['itemId']) {
    $links[] = array(
      'title' => 'Версии',
      'class' => 'versions',
      'link' => Tt::getPath(3).'/versions/'.$d['pcd']['itemId'],
    );
    $links[] = array(
      'title' => 'Автосохранения',
      'class' => 'autosave',
      'link' => Tt::getPath(3).'/autosaves/'.$d['pcd']['itemId']
    );
  }
  if ($d['page']['controller'] == 'albums') {
    if (AdminModule::_isAllowed('importAlbums') or $d['params'][0] == 'god') {
      $links[] = array(
        'title' => 'Импортировать альбомы',
        'class' => 'import',
        'link' => Tt::getPath(1).'/importAlbums/'.$d['page']['id']
      );
    }
  }

?>

<style>
.navSub a.move, .navSub a.deleteGroup, .navSub a.users {
display: none;
}
</style>

<?
Tt::tpl('admin/common/module-header', array(
  //'title' => $d['page']['title'],
  'links' => $links
))
?>

<script type="text/javascript">
// Добавляем подтверждение к кнопке удаления раздела
var eSubNav = $('subNav');
if (eSubNav) {
  eDelete = $('subNav').getElement('a[class=delete]');
  if (eDelete)
    eDelete.addEvent('click', function(e){
      e.preventDefault();
      if (confirm('Вы уверены?')) console.debug(this);
    });
}
</script>
