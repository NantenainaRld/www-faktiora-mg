<?php
//CLASS- transalation service

class TranslationService
{
    //translations files types
    private static $translations = [];
    //default lang
    private static $curren_lang = 'fr';

    //method - initialize transalation service
    public static function init($lang = null)
    {
        //get || set language by cookie
        //__cookie exist - cookie mang
        if (isset($_COOKIE['lang'])) {
            self::$curren_lang = trim($_COOKIE['lang']);
        }
        //__cookie !exist
        else {
            //browser lang
            self::$curren_lang = $lang ?: self::detectLang();
        }
    }
    //mehod - detect lang
    public static function detectLang(): string
    {
        //browser lang
        $browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'fr', 0, 2);
        //supported lang
        $supported = ['fr', 'mg', 'en'];

        //browser lang - supported or not
        return in_array($browser_lang, $supported) ? $browser_lang : 'fr';
    }
}
