<?php

require_once 'Csv/Exception.php';
require_once 'Csv/Exception/FileNotFound.php';
require_once 'Csv/Exception/CannotDetermineDialect.php';
require_once 'Csv/AutoDetect.php';
require_once 'Csv/Dialect.php';
require_once 'Csv/Reader/Abstract.php';
require_once 'Csv/Reader.php';
require_once 'Csv/Reader/String.php';

class Csv extends Csv_Reader {
}
