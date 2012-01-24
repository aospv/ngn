<?php

# Внимание! Данный файл должен оставаться в кодировке WINDOWS-1251! #

setlocale(LC_ALL, 'ru_RU.CP1251');

/**
 * Типограф, версия 2.0.5 (PHP5)
 * ------------------------------------------------------------
 * Страничка: http://rmcreative.ru/article/programming/typograph/  
 *
 * Авторы:
 * - Оранский Максим ( http://smee-again.livejournal.com/ )
 *    Первоначальный код и правила, тестирование.
 * - Макаров Александр ( http://rmcreative.ru/ )
 *    Код, тестирование, правила, идеи, дальнейшая поддержка.
 *
 * Спасибо за тестирование:
 * - faZeful
 * - Naruvi
 *
 * При создании типографа помимо личного опыта использовались:
 * - http://philigon.ru/
 * - http://artlebedev.ru/kovodstvo/
 * - http://pvt.livejournal.com/
 * ------------------------------------------------------------
 */
class Typograph{
  public $_encoding;
  public $_sym = array(
    'nbsp'    => '&nbsp;',
    'lnowrap' => '<span style="white-space:nowrap">',
    'rnowrap' => '</span>',

    'lquote'  => '«',
    'rquote'  => '»',
    'lquote2' => '&lsquo;',
    'rquote2' => '&rsquo;',

    'mdash'   => '&mdash;',
    'ndash'   => '&ndash;',
    'minus'   => '&ndash;', // соотв. по ширине символу +, есть во всех шрифтах

    //'hellip'  => '&hellip;',
    'copy'    => '&copy;',
    'trade'   => '<sup>&trade;</sup>',
    'apos'    => '&#39;',   // см. http://fishbowl.pastiche.org/2003/07/01/the_curse_of_apos
    'reg'     => '<sup><small>&reg;</small></sup>',
    'multiply' => '&times;',
    '1/2' => '&frac12;',
    '1/4' => '&frac14;',
    '3/4' => '&frac34;',
    'plusmn' => '&plusmn;',
    'rarr' => '&rarr;',
    'larr' => '&larr;',
  );

  /**
   * Укажите кодировку для обработки текста в кодировке, отличной от WINDOWS-1251.
   * @param String $encoding
   */
  function __construct($encoding = null){
     $this->_encoding = $encoding;
  }

  public $_safeBlocks = array(
      //'<div class=\"code[^>]*>' => '<\/div><\/li><\/ol><\/div>',
    '<pre[^>]*>' => '<\/pre>',
    '<style[^>]*>' => '<\/style>',
    '<script[^>]*>' => '<\/script>',
    '<!--' => '-->',
  );

  /**
   * Добавляет безопасный блок, который не будет обрабатываться типографом.
   *
   * @param String $openTag
   * @param String $closeTag
   */
  function addSafeBlock($openTag, $closeTag){
    $this->_safeBlocks[$openTag] = $closeTag;
  }

  /**
   * Устанавливает соответствие между символом и его представлением.
   *
   * @param String $sym
   * @param String $entity
   */
  function setSym($sym, $entity){
    $this->_sym[$sym] = $entity;
  }

  /**
   * Форматирует число как телефон.
   * @param String $phone
   * @return String
   */
  function _phone($phone){
      //89109179966 => 8 (910) 917-99-66
      $result = $phone[0].' ('.$phone[1].$phone[2].$phone[3].') ';
      $count = 0;
      $buff='';
      for ($i=strlen($phone)-1; $i>3; $i--){
          $left = $i-3;
          if ($left!=3){
              if ($count<1){
                  $buff.=$phone[$i];
                  $count++;
              }
              else{
                  $buff.=$phone[$i].'-';
                  $count=0;
              }
          }
          else{
              $buff.=strrev(substr($phone, $i-2, 3));
              break;
          }
      }
      $result.=strrev($buff);
      return $result;
  }

  /**
   * Вызывает типограф, обходя html-блоки и безопасные блоки
   * Для обработки текста в кодировке, отличной от WINDOWS-1251, укажите её
   * вторым параметром.
   *
   * @param String $str
   * @return String
   */
  function process($str){
    if($this->_encoding!=null){
      $str = iconv($this->_encoding, 'WINDOWS-1251', $str);  
    }
    $pattern = '(';
    foreach ($this->_safeBlocks as $start => $end){
      $pattern .= "$start.*$end|";
    }
    $pattern .= '<[^>]*[\s][^>]*>)';
    $str = preg_replace_callback("~$pattern~isU", array('self', '_stack'), $str);
    $str = $this->typo_text($str);
    $str = strtr($str, self::_stack());
    if($this->_encoding!=null){
      $str = iconv('WINDOWS-1251', $this->_encoding, $str);
    }
    return $str;
  }

  /**
   * Накапливает исходный код безопасных блоков при использовании в качестве
   * обратного вызова. При отдельном использовании возвращает накопленный
   * массив.
   *
   * @param array $matches
   * @return array
   */
  function _stack($matches = false){
    static $safe_blocks = array();
    if ($matches !== false){
      $key = '<'.count($safe_blocks).'>';
      $safe_blocks[$key] = $matches[0];
      return $key;
    }
    else{
      $tmp = $safe_blocks;
      unset($safe_blocks);
      return $tmp;
    }
  }

  /**
   * Главная функция типографа.
   * @param String $str
   * @return String
   */
  function typo_text($str){
    $sym = $this->_sym;
    if (trim($str) == '') return '';
    
    $str = str_replace('"-', '" -', $str);
    $str = str_replace('".', '" .', $str);
    $str = str_replace('"?', '" ?', $str);
    $str = str_replace('"!', '" !', $str);

    $html_tag = '(?:(?U)<.*>)';
    //$hellip = '\.{2,5}';
    $phrase_end   = '(?:[)!?.:#*\\\]|$|\w)';
    $phrase_begin = "(?:$hellip|\w)";
    $any_quote    = "(?:$sym[lquote]|$sym[rquote]|$sym[lquote2]|$sym[rquote2]|&quot;|\")";
    
    $replace = array(
      // Много пробелов или табуляций -> один пробел
      '~( |\t)+~' => ' ',

      // Разносим неправильные кавычки
      '~([^"]\w+)"(\w+)"~' => '$1 "$2"',
      '~"(\w+)"(\w+)~' => '"$1" $2',
      

      //Слепляем скобки со словами
      //'~\(\s~s' => '(',
      //'~\s\)~s' => ')',

         //Неразрывные названия организаций и абревиатуры форм собственности
      '~(ООО|ОАО|ЗАО|ЧП|ИП|НПФ|НИИ)\s+"(.*)"~' => $sym['lnowrap'].'$1 "$2"'.$sym['rnowrap'],

       //Нельзя отрывать имя собственное от относящегося к нему сокращения.
       //Например: тов. Сталин, г. Воронеж
       //Ставит пробел, если его нет.
       '~([гГ]|гр|тов|пос)\.\s*([А-Я]+)~s' => '$1.'.$sym['nbsp'].'$2',

       //Не отделять стр. и с. от номера странцы.
       '~([с|С](?:тр)?)\.\s*(\d+)~s' => '$1.'.$sym['nbsp'].'$2',

       //Не разделять 2007 г., ставить пробел, если его нет
       '~([0-9]+)\s*([гГ])\.~s' => '$1&nbsp;$2.',

       
       /*
       @todo
       // Превращаем кавычки в ёлочки. Двойные кавычки склеиваем
//       "~(?<=\s|^|>)($html_tag*)($any_quote+)($html_tag*$phrase_begin$html_tag*)~"  => '$1'.$sym['lquote'].'$3',
       "~(?<=\s|^|>)($html_tag*)($any_quote+)($html_tag*$phrase_begin$html_tag*)~"  => '$1'.$sym['lquote'].'$3',
       
       
       "~($html_tag*$phrase_end$html_tag*)($any_quote+)($html_tag*$phrase_end$html_tag*|\s|,|<)~" => '$1'.$sym['rquote'].'$3',
       
      */


      // Знак дефиса или два знака дефиса подряд - на знак длинного тире.
      // + Нельзя разрывать строку перед тире, например: Знание - сила, Курить - здоровью вредить.
      '/(\s+|^)(--?)(?=\s)/' => ' —',

      // Знак дефиса, ограниченный с обоих сторон цифрами - на знак короткого тире.
      '/(?<=\d)-(?=\d)/' => ' —',

      // Нельзя оставлять в конце строки предлоги и союзы а, в, и, к, о, с, у и т.д.
      '/(?<=\s|^|\W)(а|в|и|к|о|с|у|я|о|со|об|от|то|не|ни|но|из|за|уж|на|по|под|пред|предо|про|над|как|без|что|во|да|для|до|там|ещё|их|или|ко|меж|между|перед|передо)(\s+)/i' => '$1'.$sym['nbsp'],

      // Нельзя отрывать частицы бы, ли, же от предшествующего слова, например: как бы, вряд ли, так же.
      "/(?<=\S)(\s+)(ж|бы|б|же|ли|ль|либо|или)(?=$html_tag*[\s)!?.])/i" => $sym['nbsp'].'$2',

      // Неразрывный пробел после инициалов.
      //'~([А-ЯA-Z]\.)\s?([А-ЯA-Z]\.)\s?([А-Яа-яA-Za-z]+)~s' => '$1$2'.$sym['nbsp'].'$3',
      // Работает неправильно в подобном случае "F.P.G."  -> "F.P. G."

      // Русские денежные суммы, расставляя пробелы в нужных местах.
      '~(\d+)\s?(руб.)~s' =>  '$1&nbsp;$2',
      '~(\d+)\s?(млн.|тыс.)?\s?(руб.)~s'  =>  '$1&nbsp;$2&nbsp;$3',

      // Неразрывные пробелы в кавычках
      //"/($sym[lquote]\S*)(\s+)(\S*$sym[rquote])/U" => '$1'.$sym["nbsp"].'$3',

      // От 2 до 5 знака точки подряд - на знак многоточия (больше - мб авторской задумкой).
      //"~$hellip~" => $sym['hellip'],

      // Знаки (c), (r), (tm)
      '~\((c|с)\)~i'  => $sym['copy'],
      '~\(r\)~i'  =>  $sym['reg'],
      '~\(tm\)~i' =>  $sym['trade'],

      // Спецсимволы для 1/2 1/4 3/4
      '~\b1/2\b~' => $sym['1/2'],
      '~\b1/4\b~' => $sym['1/4'],
      '~\b3/4\b~' => $sym['3/4'],

      "~'~"   =>  $sym['apos'],
      // Размеры 10x10, правильный знак
      '~(\d+)[x|X|х|Х|*](\d+)~' => '$1'.$sym['multiply'].'$2',
      //Неправильная расстановка запятых
      '~(\w+|'.$sym['rquote'].'|'.$sym['rquote2'].'|'.$sym['trade'].'|'.$sym['apos'].'|'.$sym['reg'].') ?, ?(\w+|'.$sym['lquote'].'|'.$sym['lquote2'].'|'.$sym['apos'].')~'       => '$1, $2',
      //Восклицательный знак с предшествующим пробелом... нехорошо!
      '~(\w+) +!~' => '$1!', 
      //Телефоны
      '~тел[:.] ?(\d+)~ie' => "'<span style=\"white-space:nowrap\">тел: '.self::_phone('$1').'</span>'",
      //+-
      '~([^\+]|^)\+-~' => '$1'.$sym['plusmn'],
      //arrows
      //'~([^-]|^)->~' => '$1'.$sym['rarr'],
      //'~<-([^-]|$)~' => $sym['larr'].'$1',
    );
    
    
    //print $str; die();
    

    $str = preg_replace(array_keys($replace), array_values($replace), $str);
    return $str;
  }
}

?>