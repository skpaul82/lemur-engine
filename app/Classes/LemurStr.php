<?php

namespace App\Classes;

class LemurStr
{

    /**
     * @param $str
     * @return array[]|false|string[]
     */
    public static function splitIntoSentences($str)
    {


        return preg_split('/(\s*,*\s*)*[.?!]+(\s*,*\s*)*/', $str, -1, PREG_SPLIT_NO_EMPTY);
    }


    /**
     * prepare the input
     * @param $str
     * @param bool $uppercase
     * @return string|string[]|null
     */
    public static function normalize($str, $uppercase = true)
    {

        //replace everything but numbers
        $str = preg_replace('/[^a-z0-9]+/i', ' ', $str);
        //remove multiple whitespaces
        $str = preg_replace('/\s+/', ' ', $str);
        //trim
        $str = trim($str);
        if ($uppercase) {
            //convert to upper
            $str = mb_strtoupper($str);
        }
        return $str;
    }


    /**
     * remove non alphanumeric char from end of string
     *
     * @param $str
     * @return mixed
     */
    public static function removeSentenceEnders($str)
    {
        $str = preg_replace('/[^a-z0-9]+\Z/i', '', $str);
        //replace everything
        //$str = rtrim($str, '?!.');
        return $str;
    }


    public static function mbUcfirst($str, $encoding = "UTF-8", $lower_str_end = false)
    {
        $str = trim($str);
        $first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
        if ($lower_str_end) {
            $str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
        } else {
            $str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
        }
        $str = $first_letter . $str_end;
        return $str;
    }


    /**
     * 1. the values come in denormalized
     *
     * @param $string - the user value - could be input, that, topic
     * @param $regExpItem - the value from the db - could be regexp_input, regexp_that, regexp_topic
     * @return array
     */
    public static function extractWildcardFromString($string, $regExpItem)
    {


        //but ultimately we saved the DENORMALIZED value as well minus sentence-enders.
        //EXAMPLE OF CLIENT INPUT
        //is this jon's sandwhich?
        //EXAMPLE OF THE PATTERN THIS MATCHES
        //IS THIS *
        //EXAMPLE OF A DENORMALIZED INPUT
        //is this jon's sandwich?
        //EXAMPLE OF A NORMALIZED INPUT
        //is this jon s sandwich
        //EXAMPLE OF A DENORMALIZED STAR
        //jon's sandwich (<- notice no closing punctuation)
        //EXAMPLE OF A NORMALIZED STAR
        //sandwich jon s sandwich


        //remember we may have a pattern which contains things like this
        //i like the color (\bblue\b|\bgreen\b|\bred\b)
        //in this case the entire reg exp bit should be replaced with star as it needs to be treated as a wildcard...
        $regExpItem = self::convertToRegExpPattern($regExpItem);



        $match=self::normalize($string, false);



        //we perform all our checks against the normalised value as these are the values stored in t h
        //if $regExpItem = $match then just return its a straight match with no extractable wildcards .. return blank
        if ($regExpItem == $match) {
            return [];
        }

        preg_match($regExpItem, $match, $matches);
        if (isset($matches)) {
            array_shift($matches); //remove the first result
            return $matches;
        }
    }

    public static function convertToRegExpPattern($regexp)
    {

        //handle consecutive wildcards first
        //if replacing direct from db
        $regexp = str_replace('% % ', '(\w+)\s(\w+)\s', $regexp);

        //if replacing after some other processing has taken place
        $regexp = str_replace('(.*) (.*) ', '(\w+)\s(\w+)\s', $regexp);

        $regexp = str_replace('%', '(.*)', $regexp);
        $regexp = '#'.$regexp.'#i';
        return $regexp;
    }






    public static function normalizeForCategoryTable($str = '', $allowedTags = [])
    {

        //if empty return
        if (trim($str)=='') {
            return trim($str);
        }


        //some common replacements
        foreach (config('lemur_tag.commonNormalizations') as $in => $out) {
            $str = str_replace($in, $out, $str);
        }


        //remove sentence splitters
        foreach (config('lemur_tag.sentenceSplitters') as $splitter) {
            $str = str_replace($splitter, '', $str);

        }

        //strip any tags... except the allowed tags
        $str = strip_tags($str,$allowedTags);


        preg_match_all("~<[^/|^>]+>(.*?)</[^>]+>|<[^/>]+/>|[a-z0-9\^\*#\$_]+~i",$str, $m);

        //trim
        $str = trim(implode(' ',$m[0]));

        return mb_strtoupper($str);
    }


    public static function replaceWildCardsInPattern($str = '')
    {

        //if empty return
        if (trim($str)=='') {
            return trim($str);
        }

        //these are the zero or more wildcards
        //so this "I want ^ noodles" will be replaced to "I want%noodles"
        //so that "i want spicy noodles" and "I want noodles" will be matched
        $str = str_replace(" ^ ", "%", $str);
        $str = str_replace(" # ", "%", $str);
        $str = str_replace("^ ", "%", $str);
        $str = str_replace("# ", "%", $str);
        $str = str_replace(" ^", "%", $str);
        $str = str_replace(" #", "%", $str);
        $str = str_replace("^", "%", $str);
        $str = str_replace("#", "%", $str);

        //these are one or more wildcards
        $str = str_replace("*", "%", $str);
        $str = str_replace("_", "%", $str);

        //this is for an exact match
        $str = str_replace("$", "", $str);

        //replace <tag>xxxx</tag> with a %
        $str = preg_replace("~<[^/|^>]+>(.*?)</[^>]+>~i",'%',$str);
        //replace <tag /> with a %
        $str = preg_replace("~<[^/>]+/>~i",'%',$str);

        return $str;
    }



    public static function getFirstCharFromStr($str = '')
    {

        //if empty return
        if (trim($str) == '') {
            return trim($str);
        }

        return (string)$str[0];
    }


    //todo liz potenitally remove
    public static function createRegExpFromString($str)
    {

        //replace with one or more
        $str = str_replace("*", "(.*)", $str);
        $str = str_replace("_", "(.*)", $str);

        //replace with zero or more
        $str = str_replace("^", "(*+)", $str);
        $str = str_replace("#", "(*+)", $str);

        //remove
        $str = str_replace("$", "", $str);


        return $str;
    }

    public static function convertStrToRegExp($str)
    {

        //these are one or more wildcards
        $str = str_replace("*", "(.*)", $str);
        $str = str_replace("_", "(.*)", $str);

        $str = str_replace(" ^ ", "(.*)?", $str);
        $str = str_replace(" # ", "(.*)?", $str);
        $str = str_replace("^ ", "(.*)?", $str);
        $str = str_replace("# ", "(.*)?", $str);
        $str = str_replace(" ^", "(.*)?", $str);
        $str = str_replace(" #", "(.*)?", $str);
        $str = str_replace("^", "(.*)?", $str);
        $str = str_replace("#", "(.*)?", $str);




        $str = str_replace(" (\\s", "(\\s", $str);


        return $str;
    }

    public static function cleanAndImplode($arr)
    {
        if (is_array($arr) && !empty($arr)) {
            $str = implode(" ", $arr);
        } elseif (is_string($arr)) {
            $str = $arr;
        } else {
            $str = '';
        }
        return self::cleanOutPutForResponse($str);
    }

    /**
     * remove multiple white
     *
     * @param $str
     * @return mixed
     */
    public static function cleanOutPutForResponse($str)
    {
        $str = str_replace(" !", "!", $str);
        $str = str_replace(" ?", "?", $str);
        $str = str_replace(" .", ".", $str);
        $str = str_replace(" ,", ",", $str);
        $str = str_replace(" :", ":", $str);
        $str = str_replace(" ;", ";", $str);
        $str = preg_replace('/\s+/', ' ', $str);
        $str = trim($str);
        return $str;
    }

    public static function replaceForSlug($str)
    {
        $str = str_replace("*", "star", $str);
        $str = str_replace("#", "hash", $str);
        $str = str_replace("_", "uscore", $str);
        $str = str_replace("^", "hat", $str);
        $str = str_replace("$", "dollar", $str);
        return $str;
    }
}
