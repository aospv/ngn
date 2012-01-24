<?php

interface UpdatableItems {

  public function getItem($id);
  public function create(array $data);
  public function event($name, $id);
  public function update($id, array $data);
  public function updateField($id, $k, $v);
  public function getItemNonFormat($id);
  public function delete($id);

}
