<?php

class Tgz extends Options2 {
  
  protected $requiredOptions = array(
    'tempFolder'
  );
    
  public function extract($archive) {
    $tarArchive = str_replace('.tgz', '.tar', $archive);
    File::delete($tarArchive); // 7za не поддерживает перезапись без вопросов
    sys("7za x -o{$this->options['tempFolder']} $archive", true);
    if (!file_exists($tarArchive))
      throw new NgnException('Tar is not extracted');
    $subfolder = $this->options['tempFolder'].'/'.Misc::randString(8);
    sys("7za x -o$subfolder $tarArchive", true);
    return Dir::getFlat($subfolder);
  }
  
}
