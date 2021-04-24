<?php

namespace App\Classes;

use Illuminate\Support\Facades\Log;

class LemurLog
{

    public static function sql($sql, $bindings, $time)
    {

        $calling=debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 5);

        $contextArr['message']=$sql;
        $contextArr['bindings']=$bindings;
        $contextArr['time']=$time;
        $contextArr['info']=self::extractInfo($calling);

        Log::info('', $contextArr);

        self::display($contextArr);
    }


    public static function info($message, $context = [])
    {

        $calling=debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 5);

        $contextArr['message']=$message;
        $contextArr['context']=$context;
        $contextArr['info']=self::extractInfo($calling);

        Log::info('', $contextArr);

        self::display($contextArr);
    }

    public static function debug($message, $context = [])
    {


        $calling=debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 5);

        $contextArr['message']=$message;
        $contextArr['context']=$context;
        $contextArr['info']=self::extractInfo($calling);

        Log::debug('', $contextArr);

        self::display($contextArr);
    }

    public static function warn($message, $context = [])
    {


        $calling=debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 5);

        $contextArr['message']=$message;
        $contextArr['context']=$context;
        $contextArr['info']=self::extractInfo($calling);

        Log::warning('', $contextArr);

        self::display($contextArr);
    }

    public static function error($message, $context = [])
    {


        $calling=debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 5);

        $contextArr['message']=$message;
        $contextArr['context']=$context;
        $contextArr['info']=self::extractInfo($calling);

        Log::error('', $contextArr);

        self::display($contextArr);
    }


    public static function extractInfo($calling)
    {

        $i=0;
        if (isset($calling[$i]['file'])) {
            $d[$i]['file']=basename($calling[$i]['file']);
            $d[$i]['line']=$calling[$i]['line'];
        } elseif (isset($calling[$i]['function'])) {
            $d[$i]['function']=$calling[$i]['function'];
            $d[$i]['class']=$calling[$i]['class'];
        }
        $i=1;
        if (isset($calling[$i]['file'])) {
            $d[$i]['file']=basename($calling[$i]['file']);
            $d[$i]['line']=$calling[$i]['line'];
        } elseif (isset($calling[$i]['function'])) {
            $d[$i]['function']=$calling[$i]['function'];
            $d[$i]['class']=$calling[$i]['class'];
        }

        return $d;
    }


    public static function display($contextArr)
    {

        /*echo "<pre>";
        print_r($contextArr);
        echo "</pre>";
*/
    }
}
