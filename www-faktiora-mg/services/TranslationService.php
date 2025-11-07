<?php
//CLASS - transalation service

class TranslationService
{
    //translations files types
    private static $translations = [];
    //default lang
    private static $current_lang = 'fr';
    //supported lang
    private static $supported_lang = ['fr', 'mg', 'en'];


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
            self::$current_lang = $lang ? (in_array($lang, self::$supported_lang) ? $lang : 'fr') : self::detectLang();
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

        //replace field
        foreach ($params as $field => $val) {
            $string =  str_replace(":$field", $val, $string);
        }

        // return $strings;
        return $string;
    }
    //method - change lang
    public static function setLang($lang)
    {
        //supported
        if (in_array($lang, self::$supported_lang)) {
            //set cookie lang
            setcookie('lang', $lang, time() + (86400) * 365);
            //redirect to site url
            header('Location: ' . SITE_URL);
            return true;
        }
        return false;
    }


    //====================== PRIVATE METHODS ====================

    //method - detect lang
    private static function detectLang(): string
    {
        //browser lang
        $browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'fr', 0, 2);

        //browser lang - supported or not
        return in_array($browser_lang, self::$supported_lang) ? $browser_lang : 'fr';
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
