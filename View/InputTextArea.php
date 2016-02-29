<?php
namespace Leap\View;
    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

/**
 * Description of InputTextArea
 *
 * @author User
 */
class InputTextArea extends Html {
    public function __construct ($id, $name, $value, $classname = 'form-control')
    {
        $this->id = $id;
        $this->name = $name;
        $this->classname = $classname;
        $this->value = stripslashes($value);
    }

    public function p ()
    {
        ?>
        <textarea rows="4" class='<?= $this->classname; ?>' name="<?= $this->name; ?>"
                  id="<?= $this->id; ?>" <?= $this->readonly; ?>><?= $this->value; ?></textarea>
    <?
    }
}
