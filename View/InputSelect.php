<?php
namespace Leap\View;
    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

/**
 * Description of InputSelect
 *
 * @author User
 */
class InputSelect extends Html {
    public $options = array ();
    public $selected;

    public function __construct ($arr, $id, $name, $selected, $classname = 'form-control')
    {
        $this->options = $arr;
        $this->id = $id;
        $this->name = $name;
        $this->selected = $selected;
        $this->classname = $classname;
    }

    public function p ()
    {
        ?>
        <select class='<?= $this->classname; ?>' name="<?= $this->name; ?>"
                id="<?= $this->id; ?>" <?= $this->readonly; ?>>
            <? foreach ($this->options as $val => $text) {
                if ($this->selected == $val) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }
                ?>
                <option value="<?= $val; ?>" <?= $selected; ?>><?= stripslashes($text); ?></option>
            <? } ?>
        </select>
    <?
    }
}
