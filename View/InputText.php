<?php
namespace Leap\View;
    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

/**
 * Description of InputText
 *
 * @author User
 */
class InputText extends Html {

    public $type;


    public function __construct ($type, $id, $name, $value, $classname = 'form-control')
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->classname = $classname;
        $this->value = stripslashes($value);
    }

    public function p ()
    {
        $sem = $this->type;
        $idku = $this->id;
        if($this->type == "date"){
            $sem = "text";
            if($this->value != ""){
                $this->value =date("d-m-Y", strtotime($this->value));
            }
            $idku = $idku.time();
        }
        
        echo "<input type='{$sem}' name='{$this->name}' value='{$this->value}' id='{$idku}' class='{$this->classname}' {$this->readonly}>";
        
        if($this->type == "date"){
echo '
<script>
     $( "#'.$idku.'" ).datepicker({ dateFormat: \'dd-mm-yy\' });    
</script>';
             
        }
    }
}
