<?php
namespace Leap\View;

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

/**
 * Description of Html
 *
 * @author User
 */
class Html implements View {

    public $id;
    public $name;
    public $value;
    public $constraints = array ();
    public $classname;

    public $readonly = "";

    public function setReadOnly ($bool = 1)
    {
        if ($bool) {
            $this->readonly = "readonly";
        } else {
            $this->readonly = "";
        }
    }

    /*
     * print as HTML
     */
    public function p ()
    {
        \pr($this);
    }

    public function makeDiv ($id, $innerHtml, $classname)
    {
        echo "<div id='{$id}' class='{$classname}'>{$innerHtml}</div>";
    }
}
