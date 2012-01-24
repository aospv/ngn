<?php

class DdfmaDdCity extends Ddfma {

  public function updateCreate($name) {
    $country = include __DIR__.'/country.php';
    $region = include __DIR__.'/region.php';
    $city = include __DIR__.'/city.php';
    $largeCitys = array_flip(array_merge(
      File::lines(__DIR__.'/city1000'),
      File::lines(__DIR__.'/city500'),
      File::lines(__DIR__.'/city100')
    ));
    //$region = Arr::filter_by_value($region, 'region_id', 3160);
    $proto = array(
      'strName' => $this->strName,
      'groupName' => $name
    );
    foreach ($country as $v) {
      $r = $proto;
      $r['title'] = $v['name'];
      $r['name'] = DdTags::title2name($v['name']);
      if (isset($v['order'])) $r['oid'] = $v['order'];
      $old2newCountry[$v['country_id']] = db()->insert('tags', $r);
    }
    foreach ($region as $v) {
      $r = $proto;
      $r['title'] = $v['name'];
      $r['name'] = DdTags::title2name($v['name']);
      if (!isset($old2newCountry[$v['country_id']])) continue;
      $r['parentId'] = $old2newCountry[$v['country_id']];
      if (isset($v['order'])) $r['oid'] = $v['order'];
      else $r['oid'] = 10000;
      $old2newRegion[$v['region_id']] = db()->insert('tags', $r);
    }
    $regionTagCount = array();
    foreach ($city as $v) {
      if (!isset($largeCitys[$v['name']])) continue;
      $r = $proto;
      $r['title'] = $v['name'];
      $r['name'] = DdTags::title2name($v['name']);
      if (!isset($old2newRegion[$v['region_id']])) continue;
      $r['parentId'] = $old2newRegion[$v['region_id']];
      //if (isset($v['order'])) $r['oid'] = $v['order'];
      $r['oid'] = $largeCitys[$v['name']];
      db()->insert('tags', $r);
      // Счетчик городов в регионах
      if (!isset($regionTagCount[$r['parentId']])) {
        $regionTagCount[$r['parentId']] = 1;
      } else {
        $regionTagCount[$r['parentId']]++;
      }
    }
    // Удаляем теги пустых регионов
    foreach ($old2newRegion as $tagId) {
      if (!isset($regionTagCount[$tagId]))
        db()->query("DELETE FROM tags WHERE id=$tagId");
    }
  }

}
