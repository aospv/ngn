<?php

class StandAloneNgnMigration {
  
  static private $patchesDirName = 'standAlonePatches';

  /**
   * @param   string  Путь к вебруту
   * @param   string  Путь к старому NGN каталогу
   * @param   string  Путь к новому NGN каталогу
   */
  static public function migrate($webrootFolder, $fromNgnFolder, $toNgnFolder) {
    if (!file_exists($fromNgnFolder))
      throw new NgnException("Can not migrate NGN. 'From' folder '$fromNgnFolder' does not exists");
    self::patch($webrootFolder, $fromNgnFolder, $toNgnFolder);
    Dir::remove($fromNgnFolder);
    output("MOVE: '$fromNgnFolder' ---> '$toNgnFolder'");
    Dir::move($toNgnFolder, $fromNgnFolder);
    output("REMOVE: '$webrootFolder/i'");
    Dir::remove($webrootFolder.'/i');
    output("COPY: '$fromNgnFolder/i' ---> '$webrootFolder/i'");
    Dir::copy($fromNgnFolder.'/i', $webrootFolder.'/i');
    // эти методы не подходят т.к. работают с константами
    //SFLM::clearJsCssCache();
    //NgnCache::clean();
  }
  
  /**
   * Запускает патчи из папки с ngn, которые имеют более старый номер сборки, чем
   * указанный номер текущей сборки.
   * 
   * В этот метод передается значение папки в ngn, а не папки с патчами по той причине, что
   * папка ngn в любом случае должна существовать и передать её было бы проще.
   * Почему нельзя обработать папку с патчами отдельно? Почему она должна существовать?
   * Потому что после применение патчей, переход на эту версию ngn обязателен.
   *
   * @param  integer  Номер текущего 
   * @param  string   $ngnFolder
   * @return bool
   */
  static private function patch($webrootFolder, $fromNgnFolder, $toNgnFolder) {
    $curBuild = Config::getConstant($fromNgnFolder.'/config/version.php', 'BUILD');
    if (!($patches = self::getActualPatches($curBuild, $toNgnFolder))) return false;
    output("Current build: $curBuild.");
    foreach ($patches as $patch) {
      $func = self::getFuncName($patch['build'], $patch['file']);
      if (!function_exists($func))
        include $patch['file'];
      output("Call $func. For build: {$patch['build']}");
      eval($func.'($webrootFolder, $fromNgnFolder, $toNgnFolder);');
    }
    return true;
  }
  
  static public function getFuncName($buildN, $filepath) {
    return 'patch_'.$buildN.'_'.str_replace('.php', '', basename($filepath));
  }
  
  /**
   * Возвращает массив stand-alone-патчей актуальных для заданной сборки
   *
   * @param   integer   Номер сборки
   * @param   string    Путь  NGN-каталогу из которго необходимо брать патчи
   * @return  array
   */
  static public function getActualPatches($curBuild, $ngnFolder) {
    $patches = array();
    foreach (Dir::getFilesR($ngnFolder.'/lib/more/patcher/'.self::$patchesDirName) as $file) {
      $patchBuild = preg_replace('/^.*\/(\d+)\/\w+\.php$/', '$1', $file);
      if ($patchBuild > $curBuild) {
        $patches[] = array(
          'build' => $patchBuild,
          'file' => $file
        );
      }
    }
    return $patches;
  }
  
  /**
   * Перемещает патчи из корня папки с патчами в подпапку сборки $buildN 
   *
   * @param string  Папка c NGN
   * @param integer Номер сборки
   */
  static public function buildPatches($ngnFolder, $buildN) {
    $patchesDir = $ngnFolder.'/lib/more/patcher/'.self::$patchesDirName;
    if (!($files = Dir::files($patchesDir))) return;
    $oLibStorage = new LibStorage();
    Dir::make($patchesDir.'/'.$buildN);
    foreach ($files as $file) {
      file_put_contents($patchesDir.'/'.$file,
        "<?php\n\nfunction ".StandAloneNgnMigration::getFuncName($buildN, $file).
        '($webrootFolder, $fromNgnFolder, $toNgnFolder)'.
        " {\n".$oLibStorage->getCode($patchesDir.'/'.$file)."\n}\n");
      output("$patchesDir/$file ---> $patchesDir/$buildN/$file");
      rename($patchesDir.'/'.$file, $patchesDir.'/'.$buildN.'/'.$file);
    }
  }
  
}
