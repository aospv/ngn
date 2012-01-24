<?php

class MysqlImporter {
  
  /*
  
  private $importFinished = false;
  
  private $readLimit; 
  
  function __construct() {
    // We can not read all at once, otherwise we can run out of memory
    $memoryLimit = trim(@ini_get('memory_limit'));
    // 2 MB as default
    if (empty($memoryLimit)) {
      $memoryLimit = 2 * 1024 * 1024;
    }
    // In case no memory limit we work on 10MB chunks
    if ($memoryLimit == -1) {
      $memoryLimit = 10 * 1024 * 1024;
    }
    // Calculate value of the limit
    if (strtolower(substr($memoryLimit, -1)) == 'm') {
      $memoryLimit = (int)substr($memoryLimit, 0, -1) * 1024 * 1024;
    } elseif (strtolower(substr($memoryLimit, -1)) == 'k') {
      $memoryLimit = (int)substr($memoryLimit, 0, -1) * 1024;
    } elseif (strtolower(substr($memoryLimit, -1)) == 'g') {
      $memoryLimit = (int)substr($memoryLimit, 0, -1) * 1024 * 1024 * 1024;
    } else {
      $memoryLimit = (int)$memoryLimit;
    }
    $this->readLimit = $memoryLimit / 8; // Just to be sure, there might be lot of memory needed for uncompression
  }
  
  private function detectCompression($filepath) {
    $file = @fopen($filepath, 'rb');
    if (!$file) {
        return false;
    }
    $test = fread($file, 4);
    $len = strlen($test);
    fclose($file);
    if ($len >= 2 && $test[0] == chr(31) && $test[1] == chr(139)) {
        return 'application/gzip';
    }
    if ($len >= 3 && substr($test, 0, 3) == 'BZh') {
        return 'application/bzip2';
    }
    if ($len >= 4 && $test == "PK\003\004") {
        return 'application/zip';
    }
    return 'none';
  }
  
  private $read_multiply = 0;
  
  private $charset = 'utf8';
  
  private $charsetOfFile = 'utf8';
  
  private $isCharsetConversion = false;
  
  private function importGetNextChunk($size = 32768) {
    global $compression, $import_handle;

    // Add some progression while reading large amount of data
    if ($this->readMultiply <= 8) {
        $size *= $this->readMultiply;
    } else {
        $size *= 8;
    }
    $this->readMultiply++;

    // We can not read too much
    if ($size > $GLOBALS['read_limit']) {
        $size = $GLOBALS['read_limit'];
    }

    if (PMA_checkTimeout()) {
        return FALSE;
    }
    if ($GLOBALS['finished']) {
        return TRUE;
    }

    if ($GLOBALS['import_file'] == 'none') {
        // Well this is not yet supported and tested, but should return content of textarea
        if (strlen($GLOBALS['import_text']) < $size) {
            $GLOBALS['finished'] = TRUE;
            return $GLOBALS['import_text'];
        } else {
            $r = substr($GLOBALS['import_text'], 0, $size);
            $GLOBALS['offset'] += $size;
            $GLOBALS['import_text'] = substr($GLOBALS['import_text'], $size);
            return $r;
        }
    }

    switch ($compression) {
        case 'application/bzip2':
            $result = bzread($import_handle, $size);
            $GLOBALS['finished'] = feof($import_handle);
            break;
        case 'application/gzip':
            $result = gzread($import_handle, $size);
            $GLOBALS['finished'] = feof($import_handle);
            break;
        case 'application/zip':
            $result = substr($GLOBALS['import_text'], 0, $size);
            $GLOBALS['import_text'] = substr($GLOBALS['import_text'], $size);
            $GLOBALS['finished'] = empty($GLOBALS['import_text']);
            break;
        case 'none':
            $result = fread($import_handle, $size);
            $GLOBALS['finished'] = feof($import_handle);
            break;
    }
    $GLOBALS['offset'] += $size;

    if ($charset_conversion) {
        return PMA_convert_string($charset_of_file, $charset, $result);
    } else {
        if ($GLOBALS['offset'] == $size) {
            // UTF-8
            if (strncmp($result, "\xEF\xBB\xBF", 3) == 0) {
                $result = substr($result, 3);
            // UTF-16 BE, LE
            } elseif (strncmp($result, "\xFE\xFF", 2) == 0 || strncmp($result, "\xFF\xFE", 2) == 0) {
                $result = substr($result, 2);
            }
        }
        return $result;
    }
}
    
  }
  
  */
  
  static function import($buffer) {
    // Defaults for parser
    $sql = '';
    $start_pos = 0;
    $i = 0;
    $len = 0;
    $big_value = 2147483647;

    /*
    while (!($finished && $i >= $len) && !$error && !$timeout_passed) {
    $data = self::importGetNextChunk();
    if ($data === FALSE) {
        // subtract data we didn't handle yet and stop processing
        $offset -= strlen($buffer);
        break;
    } elseif ($data === TRUE) {
        // Handle rest of buffer
    } else {
        // Append new data to buffer
        $buffer .= $data;
        // free memory
        unset($data);
        // Do not parse string when we're not at the end and don't have ; inside
        if ((strpos($buffer, $sql_delimiter, $i) === FALSE) && !$GLOBALS['finished'])  {
            continue;
        }
    }
    */
    // Current length of our buffer
    $len = strlen($buffer);

    // Grab some SQL queries out of it
    while ($i < $len) {
        $found_delimiter = false;
        // Find first interesting character
        $old_i = $i;
        // this is about 7 times faster that looking for each sequence i
        // one by one with strpos()
        if (preg_match('/(\'|"|#|-- |\/\*|`|(?i)DELIMITER)/', $buffer, $matches, PREG_OFFSET_CAPTURE, $i)) {
            // in $matches, index 0 contains the match for the complete 
            // expression but we don't use it
            $first_position = $matches[1][1];
        } else {
            $first_position = $big_value;
        }
        /**
         * @todo we should not look for a delimiter that might be
         *       inside quotes (or even double-quotes)
         */
        // the cost of doing this one with preg_match() would be too high
        $first_sql_delimiter = strpos($buffer, $sql_delimiter, $i);
        if ($first_sql_delimiter === FALSE) {
            $first_sql_delimiter = $big_value;
        } else {
            $found_delimiter = true;
        }

        // set $i to the position of the first quote, comment.start or delimiter found
        $i = min($first_position, $first_sql_delimiter);

        if ($i == $big_value) {
            // none of the above was found in the string

            $i = $old_i;
            if (!$GLOBALS['finished']) {
                break;
            }
            // at the end there might be some whitespace...
            if (trim($buffer) == '') {
                $buffer = '';
                $len = 0;
                break;
            }
            // We hit end of query, go there!
            $i = strlen($buffer) - 1;
        }

        // Grab current character
        $ch = $buffer[$i];

        // Quotes
        if (strpos('\'"`', $ch) !== FALSE) {
            $quote = $ch;
            $endq = FALSE;
            while (!$endq) {
                // Find next quote
                $pos = strpos($buffer, $quote, $i + 1);
                // No quote? Too short string
                if ($pos === FALSE) {
                    $found_delimiter = false;
                    break;
                }
                // Was not the quote escaped?
                $j = $pos - 1;
                while ($buffer[$j] == '\\') $j--;
                // Even count means it was not escaped
                $endq = (((($pos - 1) - $j) % 2) == 0);
                // Skip the string
                $i = $pos;

                if ($first_sql_delimiter < $pos) {
                    $found_delimiter = false;
                }
            }
            if (!$endq) {
                break;
            }
            $i++;
            // Aren't we at the end?
            if (/*$GLOBALS['finished'] && */$i == $len) {
                $i--;
            } else {
                continue;
            }
        }

        // Not enough data to decide
        if ((($i == ($len - 1) && ($ch == '-' || $ch == '/'))
          || ($i == ($len - 2) && (($ch == '-' && $buffer[$i + 1] == '-')
            || ($ch == '/' && $buffer[$i + 1] == '*'))))/* && !$GLOBALS['finished']*/) {
            break;
        }

        // Comments
        if ($ch == '#'
         || ($i < ($len - 1) && $ch == '-' && $buffer[$i + 1] == '-'
          && (($i < ($len - 2) && $buffer[$i + 2] <= ' ')
           || ($i == ($len - 1)/*  && $GLOBALS['finished']*/)))
         || ($i < ($len - 1) && $ch == '/' && $buffer[$i + 1] == '*')
                ) {
            // Copy current string to SQL
            if ($start_pos != $i) {
                $sql .= substr($buffer, $start_pos, $i - $start_pos);
            }
            // Skip the rest
            $j = $i;
            $i = strpos($buffer, $ch == '/' ? '*/' : "\n", $i);
            // Skip *
            if ($ch == '/') {
                // Check for MySQL conditional comments and include them as-is
                if ($buffer[$j + 2] == '!') {
                    $comment = substr($buffer, $j + 3, $i - $j - 3);
                    if (preg_match('/^[0-9]{5}/', $comment, $version)) {
                        if ($version[0] <= PMA_MYSQL_INT_VERSION) {
                            $sql .= substr($comment, 5);
                        }
                    } else {
                        $sql .= $comment;
                    }
                }
                $i++;
            }
            
            // Skip last char
            $i++;
            // Next query part will start here
            $start_pos = $i;
            // Aren't we at the end?
      print '+';
            if ($i == $len) {
                $i--;
            } else {
                continue;
            }
        }
        
        // Change delimiter, if redefined, and skip it (don't send to server!)
        if (strtoupper(substr($buffer, $i, 9)) == "DELIMITER"
         && ($buffer[$i + 9] <= ' ')
         && ($i < $len - 11)
         && strpos($buffer, "\n", $i + 11) !== FALSE) {
           $new_line_pos = strpos($buffer, "\n", $i + 10);
           $sql_delimiter = substr($buffer, $i + 10, $new_line_pos - $i - 10);
           $i = $new_line_pos + 1;
           // Next query part will start here
           $start_pos = $i;
           continue;
        }

        // End of SQL
        if ($found_delimiter/* || ($GLOBALS['finished'] && ($i == $len - 1))*/) {
            $tmp_sql = $sql;
            if ($start_pos < $len) {
                $length_to_grab = $i - $start_pos;

                if (! $found_delimiter) {
                    $length_to_grab++;
                }
                $tmp_sql .= substr($buffer, $start_pos, $length_to_grab);
                unset($length_to_grab);
            }
            // Do not try to execute empty SQL
            if (! preg_match('/^([\s]*;)*$/', trim($tmp_sql))) {
                $sql = $tmp_sql;
                die($sql);
                //PMA_importRunQuery($sql, substr($buffer, 0, $i + strlen($sql_delimiter)));
                $buffer = substr($buffer, $i + strlen($sql_delimiter));
                // Reset parser:
                $len = strlen($buffer);
                $sql = '';
                $i = 0;
                $start_pos = 0;
                // Any chance we will get a complete query?
                //if ((strpos($buffer, ';') === FALSE) && !$GLOBALS['finished']) {
                if ((strpos($buffer, $sql_delimiter) === FALSE)/* && !$GLOBALS['finished']*/) {
                    break;
                }
            } else {
                $i++;
                $start_pos = $i;
            }
        }
    } // End of parser loop
} // End of import loop
// Commit any possible data in buffers
      }
