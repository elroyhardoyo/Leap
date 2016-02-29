<?php
/**
 * Created by PhpStorm.
 * User: elroy
 * Date: 9/14/15
 * Time: 5:11 PM
 *  INGAT harus ada jquery UI
 */

namespace Leap\View;


class InputTextOnKeyUp extends Html{

    public $arrValue;

    public function __construct ($arrValue, $id, $name, $value, $classname = 'form-control')
    {
        $this->id = $id;
        $this->name = $name;
        $this->arrValue = $arrValue;
        $this->classname = $classname;
        $this->value = stripslashes($value);
    }

    public function p ()
    {
        $sem = $this->type;
        $idku = $this->id."_".time()."_".rand(0,100);

        ?>

        <script>
            $(function() {
                var availableTags = [
                    <?
                    $arrBaru = array();
                    foreach($this->arrValue as $num=>$val){
                        $key = '"'.ucwords(strtolower($val)).'"';
                        if(!in_array($key,$arrBaru))
                        $arrBaru[] = $key;
                    }
                    $imp = implode(",",$arrBaru);
                    echo $imp;
        ?>

                ];
                $( "#<?=$idku;?>" ).autocomplete({
                    source: availableTags
                });
            });
        </script>
        <div class="ui-widget">

            <input id="<?=$idku;?>"  placeholder='<?=Lang::t($this->name);?>' name='<?=$this->name;?>' value='<?=$this->value;?>' class="form-control">
        </div>
        <?


    }
} 