<?php
namespace Leap\View;


    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

/**
 * Description of Form
 *
 * @author User
 */
class Form extends Html {

    public    $inputs     = array ();
    public    $inputsByID = array ();
    public $action;
    public $method;
    public $ajax;
    protected $useTable;
    protected $formTable;

    public function __construct ($id, $name, $action, $class = '', $method = "post", $ajax = 1)
    {
        $this->action = $action;
        $this->id = $id;
        $this->name = $name;
        $this->class = $class;
        $this->method = $method;
        $this->ajax = $ajax;
    }

    public function setUseTable ($id, $class)
    {
        $this->useTable = 1;
        $this->formTable = new Table($id . "_table", $class);
    }

    public function addInput ($input)
    {
        if (!isset($input)) {
            die('ID for Input must be set');
        }
        $this->inputs[] = $input;
        $this->inputsByID[$input->id] = $input;
    }

    public function createAjaxSend ($buttonId, $formID, $errDivID)
    {
        $aj = new Ajax();
        $aj->createSend($buttonId, $formID, $errDivID);
    }

    public function p ()
    {

        echo "<form action='{$this->action}' method='{$this->method}' id='{$this->id}' name='{$this->name}'>";

        if ($this->useTable) {
            echo "<table id='{$this->formTable->id}' class='{$this->formTable->class}'>";
        }

        foreach ($this->inputs as $i) {
            if ($this->useTable) {
                echo "<tr>";
            }
            if ($this->useTable) {
                $i->pWithTable();
            } else {
                $i->p();
            }
            if ($this->useTable) {
                echo "</tr>";
            }
        }

        if ($this->useTable) {
            echo "</table>";
        }
        echo "</form>";
    }

}
