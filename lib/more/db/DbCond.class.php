<?php

class DbCond {

  protected $nullFilters = array();
  
  public $filters;
  
  public $filterCond = '';

  public $rangeFilterCond = '';
  
  public $range2rangeFilterCond = '';
  
  protected $filterMode = 'AND';
  
  public $orderCond;

  protected $orderKey;

  /**
   * @var bool
   */
  protected $orderAsc;

  public $limitCond;
  
  protected $tablePrefix;
  
  protected $table;
  
  public function __construct($table = null) {
    $this->table = $table;
    $this->tablePrefix = $table ? $table.'.' : '';
  }
  
  public function all(array $except = array()) {
    $conds = $this->getConditions();
    return $conds ? $this->getJoinCond()." WHERE 1\n".implode("\n", $conds)."\n" : '';
  }
  
  protected function _getConditions(array $except = array()) {
    $conds = array();
    foreach (get_object_vars($this) as $k => $v) {
      if (!is_string($v) or !Misc::hasSuffix('Cond', $k)) continue;
      $name = Misc::removeSuffix('Cond', $k);
      if (in_array($name, $except)) continue;
      $conds[$name] = $v;
    }
    return $conds;
  }
  
  protected function getConditions(array $except = array()) {
    $conds = $this->_getConditions($except);
    return array_merge(
      Arr::filterExceptKeys($conds, array('limit', 'order')),
      Arr::filter_by_keys($conds, array('limit', 'order')) // эти должны быть в конце
    );
  }
  
  protected function getWhereConditions() {
    return Arr::filterExceptKeys($this->_getConditions(), array('limit', 'order'));
  }
  
  public function where() {
    return " WHERE 1\n".implode("\n", $this->getWhereConditions())."\n";
  }
  
  protected function getJoinCond() {
    if (!$this->table) return '';
    $r = '';
    foreach ($this->joins as $table => $v)
      $r .= "\nLEFT JOIN {$table} ON {$table}.{$v[0]}={$this->table}.$v[1]\n";
    return $r;
  }
  
  public function removeFilter($key) {
    $this->_removeFilter('filter', $key);
    return $this;
  }
  
  public function setFilterMode($mode = 'AND') {
    $this->filterMode = ($mode == 'AND') ? 'AND' : 'OR';
    return $this;
  }
  
  /**
   * @param   string  filterCond/itemFilterCond
   * @param   array   array(
   *                    'key' => 'asd',
   *                    'value' => 123,
   *                    'table' => 'tt',
   *                    'func' => ...
   *                  )
   */
  public function addFilter(array $filter) {
    $this->_addFilter('filter', $filter);
    return $this;
  }
  
  public function addF($key, $value, $func = null) {
    return $this->addFilter(array(
      'key' => $key,
      'value' => $value,
      'func' => $func
    ));
  }

  protected function _addFilter($type, array $filter) {
    if (is_array($filter['value'])) {
      foreach ($filter['value'] as &$v) {
        if (!is_numeric($v))
          $v = "'".mysql_real_escape_string($v)."'";
      }
      $filter['value'] = implode(', ', $filter['value']);
    } else {
      if (!is_numeric($filter['value']))
        $filter['value'] = "'".mysql_real_escape_string($filter['value'])."'";
    }
    $n = isset($this->filters[$type]) ? count($this->filters[$type]) : 0;
    $this->filters[$type][$n] = $filter;
    if (empty($filter['mode']))
      $this->filters[$type][$n]['mode'] = $this->filterMode;
    $this->setFiltersCond($type);
  }
  
  public function _removeFilter($type, $key) {
    foreach ($this->filters[$type] as $n => $filter) {
      if ($filter['key'] == $key)
        Arr::dropN($this->filters[$type], $n);
    }
    $this->setFiltersCond($type);
    return $this;
  }
  
  /**
   * Добавляет фильтр по заданому диапозону значений
   *
   * @param   string  Имя поля таблицы
   * @param   mixed   Значение начала диапозона
   * @param   mixed   Значение конца диапозона
   * @param   string  Имя ф-ии, которую необходимо применить при вычислении значения диапозона
   * @param   bool    Строгое (>) или нестрогое (>=) неравенство
   */
  public function addRangeFilter($key, $from, $to, $func = null, $strict = false) {
    if ($from !== false and !is_numeric($from))
      $from = "'".mysql_real_escape_string($from)."'";
    if ($to !== false and !is_numeric($to))
      $to = "'".mysql_real_escape_string($to)."'";
    $this->rangeFilterCond = $this->filterMode." ".
      ($from ?
        ($func ? $func."(" : "")."{$this->tablePrefix}$key".($func ? ")" : "").
        ($strict ? ' > ' : ' >= ').$from
        :
        ''
      ).
      ($to ? (' AND '.
        ($func ? $func."(" : "")."{$this->tablePrefix}$key".
        ($func ? ")" : "").($strict ? ' < ' : ' <= ').$to)
        :
        ''
      );
    return $this;
  }
  
  public function addNullFilter($key, $isNull = false) {
    $this->filters['null'][$key] = $isNull;
    $this->setNullCond();
  }
  
  public function addFromFilter($key, $from, $func = null, $strict = false) {
    $this->addRangeFilter($key, $from, false, $func, $strict);
  }
  
  public function addToFilter($key, $to, $func = null, $strict = false) {
    $this->addRangeFilter($key, false, $to, $func, $strict);
  }
  
  protected function setFiltersCond($type) {
    $this->$type = ''; // Очищаем текущий фильтр
    $typeCond = $type.'Cond';
    $this->$typeCond = '';
    foreach ($this->filters[$type] as $v) {
      $tablePrefix = !empty($v['table']) ? $v['table'].'.' : $this->tablePrefix;
      $this->$typeCond .= 
         $v['mode']." ".(!empty($v['func']) ? $v['func']."(" : "").
         "$tablePrefix{$v['key']}".(!empty($v['func']) ? ")" : "")." IN (" . $v['value'].
         ")\n";
    }
    return $this;
  }
  
  protected function setNullCond() {
    $type = 'null';
    foreach ($this->nullFilters as $k => $isNull) {
      $typeCond = $type.'Cond';
      $this->$typeCond .= ' AND '.
         ' '.
         "{$this->tablePrefix}{$k}" .
         ($isNull ? ' = ' : ' != ').
         "''\n";
    }
  }
  
  public function addRange2RangeFilter($keyBegin, $keyEnd, $from, $to, $func = null, $strict = false) {
    if (!is_numeric($from))
      $from = "'" . mysql_real_escape_string($from) . "'";
    if (!is_numeric($to))
      $to = "'" . mysql_real_escape_string($to) . "'";
    $this->range2rangeFilterCond = $this->filterMode .
       " ".($func ?
         $func."(" : "")."{$this->tablePrefix}$keyEnd".($func ? ")" : "").
         ($strict ? ' > ' : ' >= ').$from.' AND '.
         ($func ? $func."(" : "")."{$this->tablePrefix}$keyBegin".
         ($func ? ")" : "").($strict ? ' < ' : ' <= ').$to;
    return $this;
  }

  public function setOrder($order = 'id DESC') {
    if (!$order) return;
    $this->orderKey = preg_replace('/(.*) (DESC|ASC)/', '$1', $order);
    $this->orderAsc = !strstr($order, 'DESC');
    $this->orderCond = "ORDER BY ".(strstr($order, '(') ? '' : $this->tablePrefix).$order;
    return $this;
  }
  
  public function setLimit($limit) {
    if (!$limit) return $this;
    $this->limitCond = 'LIMIT '.mysql_real_escape_string($limit);
    return $this;
  }
  
  protected $joins = array();
  
  public function addJoinF($table, $key, $value) {
    return $this->addJoinFilter(array(
      'table' => $table,
      'key' => $key,
      'value' => $value
    ));
  }
  
  public function addJoinFilter(array $filter) {
    Arr::checkEmpty($filter, 'table');
    if (!isset($this->joins[$filter['table']]))
      throw new NgnException("There is no join '{$filter['table']}'");
    $this->_addFilter('join', $filter);
    return $this;
  }
  
  public function addJoin($table, $id2, $id1 = 'id') {
    if (isset($this->joins[$table])) return;
    $this->joins[$table] = array($id1, $id2);
    return $this;
  }
  
  static public function get($table = null) {
    return new self($table);
  }

}
