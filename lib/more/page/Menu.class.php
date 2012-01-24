<?php

class Menu {

  /**
   * Возвращает HTML дерева разделов в виде маркерованого списка.
   * Пример:
   * <ul>
   *   <li><a href="/o-nas">О нас</a></li>
   *   <li>
   *     <a href="/reshenija">Решения</a>
   *     <ul>
   *       <li><a href="/reshenija.galerei">Галереи</a></li>
   *     </ul>
   *   <li><a href="/platforma">Платформа</a></li>
   *   <li><a href="/servisi">Сервисы</a></li>
   *   </li>
   * </ul>
   *
   * @param   string    Имя раздела
   * @param   string    Шаблон тэга ссылки
   * @param   integer   Глубина
   */
  static public function ul(
  $name,
  $depthLimit = 1,
  $linkTpl = '`<a href="`.$link.`"><span>`.$title.`</span></a>`'
  ) {
    return self::getUlObj($name, $depthLimit, $linkTpl)->html();
  }
  
  /**
   * @return PagesTreeTpl
   */
  static public function getUlObj(
  $name,
  $depthLimit = 1,
  $linkTpl = '`<a href="`.$link.`"><span>`.$title.`</span></a>`'
  ) {
    if (empty($linkTpl)) throw new NgnException('$linkTpl can not be empty');
    return self::getUlObjById(DbModelCore::get('pages', $name, 'name')->r['id'], $depthLimit, $linkTpl);
  }
  
  static public function getUlObjById($pageId,
  $depthLimit = 1,
  $linkTpl = '`<a href="`.$link.`"><span>`.$title.`</span></a>`'
  ) {
    $oPagesTreeTpl = PagesTreeTpl::getObjCached($pageId);
    $oPagesTreeTpl->setNodesBeginTpl('`<ul>`');
    $oPagesTreeTpl->setNodesEndTpl('`</ul></li>`');
    $oPagesTreeTpl->setNodeTpl('`<li id="mi_`.Misc::name2id($name).`"`.(!empty($class) ? ` class="`.$class.`"` : ``).`>`.'.$linkTpl);
    $oPagesTreeTpl->setLeafTpl('`<li id="mi_`.Misc::name2id($name).`"`.(!empty($class) ? ` class="`.$class.`"` : ``).`>`.'.$linkTpl.'.`</li>`');
    $oPagesTreeTpl->setDepthLimit($depthLimit);
    if (($currentPageId = R::get('currentPageId')) !== false)
      $oPagesTreeTpl->setCurrentId($currentPageId);
    $oPagesTreeTpl->setBreadcrumbsIds(R::get('breadcrumbsPageIds'));
    return $oPagesTreeTpl;
  }

  static public function flatByPageId($pageId, $linkTpl, $sep = '') {
    $oPagesTreeTpl = PagesTreeTpl::getObjCached($pageId);
    $oPagesTreeTpl->setDepthLimit(1);
    $oPagesTreeTpl->setNodesBeginTpl('');
    $oPagesTreeTpl->setNodesEndTpl('');
    $oPagesTreeTpl->setTpl($linkTpl);
    $oPagesTreeTpl->setSeparator($sep);
    $oPagesTreeTpl->setCurrentId(R::get('currentPageId'));
    $oPagesTreeTpl->setBreadcrumbsIds(R::get('breadcrumbsPageIds'));
    return $oPagesTreeTpl->html();
  }
  
  static public function flat($name, $linkTpl, $sep = '') {
    return self::flatByPageId(
      DbModelCore::get('pages', $name, 'name')->r['id'], $linkTpl, $sep);
  }

  static public function flatLevel2($curPageId, $linkTpl, $sep = '') {
    $parents = DbModelPages::getTree()->getParentsReverse($curPageId);
    return self::flatByPageId(
      $parents[1]['id'],
      $linkTpl,
      $sep
    );
  }

  static public function simple($name) {
    return self::flat($name, '`<a href="`.$link.`"><span>`.$title.`<span></a>`', ' | ');
  }
  
}
