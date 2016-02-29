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
class InputFilter extends Html {




    public function __construct ($id, $name, $value, $classname = 'form-control')
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->classname = $classname;
        $this->value = stripslashes($value);
    }

    public function p ()
    {

        $idku = $this->id.time().rand(0,10);


        ?>
        <div class="col-md-2">
        <select class="form-control" id="filter_<?=$idku;?>">
            <option value="">none</option>
            <option>=</option>
            <option>!=</option>
            <option>&lt;</option>
            <option>&lt;=</option>
            <option>&gt;</option>
            <option>&gt;=</option>
        </select>
            </div>
        <div class="col-md-10">
            <input type="number" class="form-control" id="val_<?=$idku;?>">
        </div>
        <?
        echo "<input type='hidden' name='{$this->name}' value='{$this->value}' id='{$idku}' class='{$this->classname}' {$this->readonly}>";

        ?>

        <script>
            $('#filter_<?=$idku;?>').change(function(){
                var val1 = $('#filter_<?=$idku;?>').val();
                var val2 = $('#val_<?=$idku;?>').val();

                var gabung = val1+"__"+val2;

                $('#<?=$idku;?>').val(gabung);


                if(val1==""){
                    $('#<?=$idku;?>').val("");
                }
            });

            $('#val_<?=$idku;?>').blur(function(){
                var val1 = $('#filter_<?=$idku;?>').val();
                var val2 = $('#val_<?=$idku;?>').val();

                var gabung = val1+"__"+val2;

                $('#<?=$idku;?>').val(gabung);

                if(val1==""){
                    $('#<?=$idku;?>').val("");
                }
            });
        </script>
        <?


    }
}
