<?php

/**
 * Получает данных их Excel-файлов
 *
 */
class ExcelTableData {
  
  /**
   * Возвращает данные в виде массива полученного из таблицы excel-файла
   *
   * @param   string  Имя файла
   * @return  array   Массив данных из таблицы
   */
  static public function getData($file) {
    // Excel
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding(CHARSET);
    $data->read($file);
    self::normalize($data->sheets[0]['cells']);
    return $data->sheets[0]['cells'];
  }
  
  static private function normalize(&$data, $delete = true) {
    // Находим какое кол-во колонок встречается чаще всего
    $counts = array();
    foreach ($data as $k => &$v) {
      if (!$v) continue;
      // № колонки - кол-во колонок
      isset($counts[count($v)]) ? $counts[count($v)]++ : $counts[count($v)] = 1;
    }
    $counts2 = array_flip($counts);
    // Находим № столбца с максимальным кол-вом ячеек
    foreach ($data as &$v)
      if (!$v) continue;
  }
  
}
