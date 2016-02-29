<?php
namespace Leap\View;

/*
 * LEAP OOP PHP 
 * Each line should be prefixed with  * 
 */


class Lang {

    protected $langID;
    protected $langPath;

    function __construct ($lang_id, $langpath = 'lang/')
    {
        /*
        * INCLUDE LANGUAGE YG DIPAKAI
        */
        $this->langID = $lang_id;
        $this->langPath = $langpath;
    }

    /*
     *  activate session
     */

    public static function setLang ($l)
    {
        $_SESSION['lang'] = $l;
    }

    /*
     *  activeGetSetLang
     */

    public static function t ($str, $return = 1)
    {
        //disini bisa ditambah fungsi insert ke DB sehingga bs tau apa aja yang harus di replace !!
        global $_lang;
        //@include ("lang/".strtolower($_SESSION['lang']).".php");
        //pr($_lang);
        //
        //save ke db utk spy mudah di benarkan translationsnya
        global $db;
        $q = "INSERT INTO ry_lang SET lang_id = '$str',lang_ts = now()";
        $db->query($q, 0);

        if (isset($_lang[$str])) {
            if ($return) {
                return $_lang[$str];
            } else {
                echo $_lang[$str];
            }
        } else {
            $new = $str; // baru supaya mudah dicari variablenya
            // $new = ucwords(str_replace("_"," ",$str));
            if ($return) {
                return "$new";
            } else {
                echo "$new";
            }
        }
    }

    /*
     *  load Lang
     */

    public static function setCookie ()
    {
        setcookie("lang", self::getLang(), time() + 60 * 60 * 24 * 365, '/');
    }

    public static function getLang ()
    {
        $lang = (isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en');

        return $lang;
    }

    public static function getCookie ()
    {
        return (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : self::getLang());
    }

    public function activateLangSession ()
    {
        if (!isset($_SESSION['lang'])) {
            $_SESSION['lang'] = $this->langID;
        }
    }

    public function activateGetSetLang ()
    {
        if (isset($_GET['setlang'])) {
            $_SESSION['lang'] = $_GET['setlang'];
        }
    }

    public function loadLang ()
    {
        if (!isset($_SESSION['lang'])) {
            die("Activate Session To Use This Function");
        }

        $filelang = strtolower($_SESSION['lang']);
        try {
            if (!@include_once($this->langPath . $filelang . ".php")) {
                throw new \Exception("Failed to include language file");
            }
        } catch (\Exception $e) {
            //pr($e);
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}
 

