<?php

namespace Oip\MszeBundle;

class OipHelpers
{
    /*
    public static function jquery2iso($in)
    {
      $CONV = array();
      $CONV['c4']['85'] = 'ą';
      $CONV['c4']['84'] = 'Ą';
      $CONV['c4']['87'] = 'ć';
      $CONV['c4']['86'] = 'Ć';
      $CONV['c4']['99'] = 'ę';
      $CONV['c4']['98'] = 'Ę';
      $CONV['c5']['82'] = 'ł';
      $CONV['c5']['81'] = 'Ł';
      $CONV['c4']['84'] = 'ń';
      $CONV['c4']['83'] = 'Ń';
      $CONV['c3']['b3'] = 'ó';
      $CONV['c3']['93'] = 'Ó';
      $CONV['c5']['9b'] = 'ś';
      $CONV['c5']['9a'] = 'Ś';
      $CONV['c5']['ba'] = 'ź';
      $CONV['c5']['b9'] = 'Ź';
      $CONV['c5']['bc'] = 'ż';
      $CONV['c5']['bb'] = 'Ż';

      $i=0;
      $out = '';
      while($i<strlen($in))
      {
        if(array_key_exists(bin2hex($in[$i]), $CONV))
        {
          $out .= $CONV[bin2hex($in[$i])][bin2hex($in[$i+1])];
          $i += 2;
        }
        else
        {
          $out .= $in[$i];
          $i += 1;
        }
      }

      return $out;
    }*/
    
    /*
      Funkcja do usuwania polskich znakow z tekstu o dowolnym kodowaniu
      Autor: Marius Ma\ximus
      Inspiracja:  http://4programmers.net/PHP/FAQ/Jak_zmieni%C4%87_kodowanie_tekstu_nie_maj%C4%85c_dost%C4%99pu_do_funkcji_iconv_%5C

      Sposob uzycia:
      $zmienna = _no_pl("jakiś tekst z PL-znakami np. ŻÓŁĆ");

    */
    public static function _no_pl($tekst)
    {
       $tabela = Array(
       //WIN
            "\xb9" => "a", "\xa5" => "A", "\xe6" => "c", "\xc6" => "C",
            "\xea" => "e", "\xca" => "E", "\xb3" => "l", "\xa3" => "L",
            "\xf3" => "o", "\xd3" => "O", "\x9c" => "s", "\x8c" => "S",
            "\x9f" => "z", "\xaf" => "Z", "\xbf" => "z", "\xac" => "Z",
            "\xf1" => "n", "\xd1" => "N",
       //UTF
            "\xc4\x85" => "a", "\xc4\x84" => "A", "\xc4\x87" => "c", "\xc4\x86" => "C",
            "\xc4\x99" => "e", "\xc4\x98" => "E", "\xc5\x82" => "l", "\xc5\x81" => "L",
            "\xc3\xb3" => "o", "\xc3\x93" => "O", "\xc5\x9b" => "s", "\xc5\x9a" => "S",
            "\xc5\xbc" => "z", "\xc5\xbb" => "Z", "\xc5\xba" => "z", "\xc5\xb9" => "Z",
            "\xc5\x84" => "n", "\xc5\x83" => "N",
       //ISO
            "\xb1" => "a", "\xa1" => "A", "\xe6" => "c", "\xc6" => "C",
            "\xea" => "e", "\xca" => "E", "\xb3" => "l", "\xa3" => "L",
            "\xf3" => "o", "\xd3" => "O", "\xb6" => "s", "\xa6" => "S",
            "\xbc" => "z", "\xac" => "Z", "\xbf" => "z", "\xaf" => "Z",
            "\xf1" => "n", "\xd1" => "N");

       return strtr($tekst,$tabela);
    }
    
    
    public static function makeSlug($txt) {
       return strtolower(OipHelpers::_no_pl($txt));
       
    }
}


?>
