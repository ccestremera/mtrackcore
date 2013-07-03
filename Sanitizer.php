<?php

namespace mTrack\System\Utilities;

class Sanitizer
{
    static public function keywords($data) {
//        $query_search   = array('*', '"', '(', ')', 'upper:', 'ANDNOT', 'AND', 'OR', 'NEAR');
//        $query_replace  = array('', '', '', '', '', '|', '|', '|', '|');
//
//        $query_data = explode('|', str_replace($query_search, $query_replace, $data));
//
//        $keywords = array();
//        foreach ($query_data as $query_data_value) {
//            $keyword = trim($query_data_value);
//
//            if(!in_array($keyword, $keywords)) {
//                $keywords[] = $keyword;
//            }
//        }
//        
//        return $keywords;
    }
    
    static public function keywords2($data) {
        $client_keyword_value   = trim(str_replace(array('*', '"', '(', ')'), '', $data));
        $keywords_data          = explode('ANDNOT', $client_keyword_value);

        if(count($keywords_data) > 1) {
            $keywords_incl = explode('OR', $keywords_data[0]);

            $keyword_content = array();
            foreach ($keywords_incl as $keywords_incl_value) {
                $keywords_incl_data     = explode(':', $keywords_incl_value);
                $keywords_incl_counter  = count($keywords_incl_data);

                if($keywords_incl_counter > 1) {
                    $keyword_content[] = trim($keywords_incl_data[1]);
                } else {
                    $keyword_content[] = trim($keywords_incl_value);
                }
            }

            $keywords_excl = explode('OR', $keywords_data[1]);

            $keywords_excl_data     = ' EXCL ';
            $keywords_incl_counter  = count($keywords_excl);
            $x                      = 1;

            foreach ($keywords_excl as $keywords_excl_value) {
                if($x == $keywords_incl_counter) {
                    $keywords_excl_data .= trim($keywords_excl_value);
                } else {
                    $keywords_excl_data .= trim($keywords_excl_value) . ', ';
                }

                $x++;
            }
        } else {
            $keywords_data = explode('AND', $client_keyword_value);

            if(count($keywords_data) > 1) {
                foreach ($keywords_data as $keywords_data_value) {
                    $keyword_content[] = trim($keywords_data_value);
                }
                
                $keywords_excl_data = '';
            } else {
                $keyword_content[] = $keywords_data[0];
                $keywords_excl_data = '';
            }
        }
        
            
        $response_data = array(
            'keywords'  => $keyword_content,
            'option'    => $keywords_excl_data
        );

        return $response_data;
    }
    
    static public function getMultiChar ( $_P ) {

        if ( gettype ( $_P ) == 'array' ) {

                $output = array ();

                while ( list ( $key, $value ) = each ( $_P ) ) $output[self::getMultiChar ( $key )] = self::getMultiChar ( $value );

                return $output;

        }
        else if ( gettype ( $_P ) == 'string' ) {
                $alpha = array ( 'å', 'ø', 'æ', 'Å', 'Ø', 'Æ', 
                                                 'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 
                                                 'ä', 'ë', 'ï', 'ö', 'ü', 'Ä', 'Ë', 'Ï', 'Ö', 'Ü', 
                                                 'â', 'ê', 'î', 'ô', 'û', 'Â', 'Ê', 'Î', 'Ô', 'Û',
                                                 '❤','«', 'ß', '£', 'í'
                                           );

                $_P    = html_entity_decode ( $_P );

//                $temp1 = utf8_encode ( $_P );
                $temp1 = $_P;

                $temp2 = utf8_decode ( $_P );

                if ( self::contains ( $temp1, $alpha ) ) {
//echo 1;
                        $text = utf8_encode($temp1);

                }
                else if (self::contains ( $temp2, $alpha ) ) {

//echo 2;
                        $text = $temp2;

                }
                else {
//echo 3;

                        $text = $_P;

                }
                
                
                return $text;

        }
        else return stripslashes ( $_P );
    }
    
    static public function contains($data, $keys) {
	if (gettype($data) == 'string') {
            foreach ($keys as $key) {
                if (strpos($data, $key) !== false) {
                    return true;
                }
            }
	} elseif(gettype($data) == 'array') {
            foreach ($keys as $key) {
                if (!isset($data[$key])) { 
                    return false;
                }
            }
	}
	
	return true;
    }

    static public function getEncodedData($data) {
        $alpha = array (
            'å', 'ø', 'æ', 'Å', 'Ø', 'Æ', 
            'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 
            'ä', 'ë', 'ï', 'ö', 'ü', 'Ä', 'Ë', 'Ï', 'Ö', 'Ü', 
            'â', 'ê', 'î', 'ô', 'û', 'Â', 'Ê', 'Î', 'Ô', 'Û',
            '❤','«', 'ß', '£', 'í'
        );

        if(self::contains($data, $alpha )) {
            $data_char =  utf8_decode($data);

            if(mb_check_encoding($data_char, 'UTF-8')) {
               $char = utf8_encode($data); 
            } else {
                $char = utf8_encode($data_char);
            }
        }

        return $char;
    }
}