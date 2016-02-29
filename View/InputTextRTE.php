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
class InputTextRTE extends Html {
    public function __construct ($id, $name, $value, $classname = 'form-control')
    {
        $this->id = $id;
        $this->name = $name;
        $this->classname = $classname;
        $this->value = stripslashes($value);
    }

    public function p ()
    {
        $t = time();
        ?>
        <textarea rows="4" class='<?= $this->classname; ?>' name="example_<?= $this->name; ?>"
                  id="<?= $this->id."_".$t; ?>" <?= $this->readonly; ?>><?= $this->value; ?></textarea>
<input type="hidden" id="hidden_<?= $this->id."_".$t; ?>" name="<?= $this->name; ?>" value='<?=$this->value; ?>'>
 <script>
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace( '<?= $this->id."_".$t; ?>' );
    array_rte.push('<?= $this->id."_".$t; ?>');
    
  </script>  
    <?
    }
}
