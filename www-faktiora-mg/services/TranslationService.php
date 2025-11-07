<?php
//CLASS- transalation service

class TranslationService
{
    //translations files types
    private static $translations = [];
    //default lang
    private static $current_lang = 'fr';


    //==================== PUBLIC METHODS ======================

    //method - initialize transalation service
    public static function init($lang = null)
    {
        //get || set language by cookie
        //__cookie exist - cookie mang
        if (isset($_COOKIE['lang'])) {
            self::$current_lang = trim($_COOKIE['lang']);
        }
        //__cookie !exist
        else {
            //browser lang
            self::$current_lang = $lang ?: self::detectLang();
        }

        //load lang files
        self::loadFiles();
    }
    //method - translate string
    public static function translate($key, $params = [])
    {
        //explode by . (type + messages)
        $keys = explode('.', $key);
        //type (messages , errors, forms)
        $type = $keys[0];
        //message = titles, succes, auth ...
        $messages = array_slice($keys, 1);

        //type !exist
        if (!isset(self::$translations[$type])) {
            return "![MISSING MESSAGE TYPE : $type]";
        }

        //messages tab
        $string = self::$translations[$type];

        //get string
        foreach ($messages as $message) {
            //message !found
            if (!isset($string[$message])) {
                return "![MISSING MESSAGE : $key]";
            }

            $string = $string[$message];
        }

        // return $strings;
        return $string;
    }


    //====================== PRIVATE METHODS ====================

    //method - detect lang
    private static function detectLang(): string
    {
        //browser lang
        $browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'fr', 0, 2);
        //supported lang
        $supported = ['fr', 'mg', 'en'];

        //browser lang - supported or not
        return in_array($browser_lang, $supported) ? $browser_lang : 'fr';
    }
    //method - load transalation folder/files
    private static function loadFiles()
    {
        //lang path
        $lang_path = I18N_PATH . '/locales/' . self::$current_lang . '/';
        //files
        $files = ['errors', 'forms', 'messages'];

        //require files
        foreach ($files as $file) {
            //file path
            $file_path = $lang_path . $file . '.php';

            //file !exist
            if (!file_exists($file_path)) {
                error_log("File not found : " . $file_path);
            }
            //file exist - require
            else {
                self::$translations[$file] = require $file_path;
            }
        }
    }
}
