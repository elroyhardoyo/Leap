<?php
namespace Leap\Utility;
    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

/**
 * Description of Hook
 *
 * @author Elroy Hardoyo<elroy.hardoyo@leap-systems.com>
 */
class Hook {

    public static function processHook ($array = array ())
    {

        foreach ($array as $classname => $fkt) {
            if (class_exists($classname)) {
                //echo "exist".$nsClassname;
                $code = "\$new = new " . $classname . "();";
                eval($code);

                if (method_exists($new, $fkt)) {
                    $code = "\$str = \$new->" . $fkt . "();";
                    eval($code);
                }
            }
        }

    }
}
