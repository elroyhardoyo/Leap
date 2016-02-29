<?php
namespace Leap\View;


    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

/**
 * Description of Table
 *
 * @author User
 */
class Table extends Html {

    public function __construct ($id, $class = '')
    {
        $this->id = $id;
        $this->class = $class;
    }
}
