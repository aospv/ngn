<?php

class TextParsing {

  static function stripComments($script) {
    $cleaned = '';
    $sc = $mc = $sq = $mq = false;
    
    for ($i = 0; $i < strlen($script); $i++) {
      if ($mc) {
        if ($script{$i} == '*') {
          $i++;
          if ($script{$i} == '/') {
            $mc = false;
          }
        }
        continue;
      } else 
        if ($sc) {
          if ($script{$i} == "\n") {
            $cleaned .= "\n";
            $sc = false;
          }
          continue;
        } else {
          // this stuff is to avoid error with regular expressions and escaped slashed mostly:
          if ($script{$i} ==
             '\'' &&
             (($script{$i -
             1} ==
             '\\' &&
             $script{$i -
             2} ==
             '\\') ||
             $script{$i -
             1} !=
             '\\') &&
             ! $mq) {
              $sq = ! $sq;
          } else 
            if ($script{$i} ==
               '"' &&
               (($script{$i -
               1} ==
               '\\' &&
               $script{$i -
               2} ==
               '\\') ||
               $script{$i -
               1} !=
               '\\') &&
               ! $sq) {
                $mq = ! $mq;
            } else 
              if (! $sq && ! $mq) {
                if ($script{$i} == '/') {
                  $i++;
                  if ($script{$i} ==
                     '*') {
                      $mc = true;
                    continue;
                  } else 
                    if ($script{$i} ==
                       '/' &&
                       $script{$i -
                       2} !=
                       '\\') {
                        $sc = true;
                      continue;
                    } else {
                      $i--;
                    }
                }
              }
        }
      $cleaned .= $script{$i};
    }
    
    return $cleaned;
  
  }

  static public function stripComments3($script) {
    $cleaned = "";
    $sc = $mc = $sq = $mq = false;
    
    for ($i = 0; $i < strlen($script); $i++) {
      if ($mc) {
        if ($script{$i} == '*') {
          $i++;
          if ($script{$i} == '/') {
            $mc = false;
          }
        }
        continue;
      } else 
        if ($sc) {
          if ($script{$i} == n) {
            $cleaned .= n;
            $sc = false;
          }
          continue;
        } else {
          if ($script{$i} == '\'') {
            $sq = ! $sq;
          } else 
            if ($script{$i} == '"') {
              $mq = ! $mq;
            } else 
              if (! $sq && ! $mq) {
                if ($script{$i} == '/') {
                  $i++;
                  if ($script{$i} ==
                     '*') {
                      $mc = true;
                    continue;
                  } else 
                    if ($script{$i} ==
                       '/' &&
                       $script{$i -
                       2} !=
                       '\\') {
                        $sc = true;
                      continue;
                    } else {
                      $i;
                    }
                }
              }
        }
      $cleaned .= $script{$i};
    }
    
    $lines = explode(n, $cleaned);
    $nonBlankLines = array();
    foreach ($lines as $li) {
      if (trim($li) != "") {
        $nonBlankLines[] = $li;
      }
    }
    $cleaned = implode(n, $nonBlankLines);
    return $cleaned;
  }

  static public function stripComments2($script) {
    $cleaned = '';
    $sc = $mc = $sq = $mq = false;
    
    for ($i = 0; $i < strlen($script); $i++) {
      if ($mc) {
        if ($script{$i} == '*') {
          $i++;
          if ($script{$i} == '/') {
            $mc = false;
          }
        }
        continue;
      } else 
        if ($sc) {
          if ($script{$i} == "\n") {
            $sc = false;
          }
          continue;
        } else {
          if ($script{$i} == '\'') {
            $sq = ! $sq;
          } else 
            if ($script{$i} == '"') {
              $mq = ! $mq;
            } else 
              if (! $sq && ! $mq) {
                if ($script{$i} == '/') {
                  $i++;
                  if ($script{$i} ==
                     '*') {
                      $mc = true;
                    continue;
                  } else 
                    if ($script{$i} ==
                       '/') {
                        $sc = true;
                      continue;
                    }
                }
              }
        }
      $cleaned .= $script{$i};
    }
    return $cleaned;
  }

  static public function stripComments_($str) {
    $str = '__' . $str . '__';
    $mode = array(
      'singleQuote' => false, 
      'doubleQuote' => false, 
      'regex' => false, 
      'blockComment' => false, 
      'lineComment' => false, 
      'condComp' => false
    );
    for ($i = 0, $l = strlen($str); $i < $l; $i++) {
      if ($mode['regex']) {
        if ($str[$i] === '/' and $str[$i - 1] !== '\\') {
          $mode['regex'] = false;
        }
        continue;
      }
      if ($mode['singleQuote']) {
        if ($str[$i] === "'" and $str[$i - 1] !== '\\') {
          $mode['singleQuote'] = false;
        }
        continue;
      }
      if ($mode['doubleQuote']) {
        if ($str[$i] === '"' and $str[$i - 1] !== '\\') {
          $mode['doubleQuote'] = false;
        }
        continue;
      }
      if ($mode['blockComment']) {
        if ($str[$i] === '*' and $str[$i + 1] === '/') {
          $str[$i + 1] = '';
          $mode['blockComment'] = false;
        }
        $str[$i] = '';
        continue;
      }
      if ($mode['lineComment']) {
        if ($str[$i + 1] === "\n" or $str[$i + 1] === "\r") {
          $mode['lineComment'] = false;
        }
        $str[$i] = '';
        continue;
      }
      if ($mode['condComp']) {
        if ($str[$i - 2] === '@' and $str[$i - 1] === '*' and $str[$i] === '/') {
          $mode['condComp'] = false;
      }
      continue;
    }
    $mode['doubleQuote'] = $str[$i] === '"';
    $mode['singleQuote'] = $str[$i] === "'";
    if ($str[$i] === '/') {
      if ($str[$i + 1] === '*' and $str[$i + 2] === '@') {
        $mode['condComp'] = true;
        continue;
      }
      if ($str[$i + 1] === '*') {
        $str[$i] = '';
        $mode['blockComment'] = true;
        continue;
      }
      if ($str[$i + 1] === '/') {
        $str[$i] = '';
        $mode['lineComment'] = true;
        continue;
      }
      $mode['regex'] = true;
    }
  }
  return substr($str, 2, strlen($str) - 4);
}

}
