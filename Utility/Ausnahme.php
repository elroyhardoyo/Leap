<?php
namespace Leap\Utility;

use Leap\View\Lang;

/*
 * LEAP OOP PHP 
 * Each line should be prefixed with  * 
 */

/**
 * Description of Ausnahme
 *
 * @author User
 */
class Ausnahme {

    public static function notFound ()
    {
        //die("Not Available on Demo Version");        
        header("Location:" . _SPPATH . "p404?msg=" . Lang::t('Not Found'));
    }

    public static function notAuth ()
    {
        header("Location:" . _SPPATH . "index?msg=" . Lang::t('Please Sign In'));
        die("Not Authorize");
    }

    public static function loginFirst ()
    {
        die("Login First");
    }

    public static function switchToMobileView ()
    {
        $_SESSION['isMobile'] = 1;
        die("switchToMobileView");
    }

    public static function notAuthToView ()
    {
        die("Not Authorize To View");
    }
}
