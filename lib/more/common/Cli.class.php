<?php

class Cli {
  static public function storeCommand($dir) {
    $argv = $_SERVER['argv'];
    $argv[0] = str_replace('.php', '', $argv[0]);
    LogWriter::str('commands', implode(' ', $argv), $dir);
  }
}