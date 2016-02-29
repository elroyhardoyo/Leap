<?php
/**
 * Created by PhpStorm.
 * User: elroy
 * Date: 12/8/15
 * Time: 9:18 AM
 */

namespace Leap\View;


class InputPrice extends Html {

    public $formatterArray = array();
    //formatnya prefix. , thousand separator, cent separator

    public function __construct ($id, $name, $value, $formatterArray = array("Rp.",".","",0), $classname = 'form-control')
    {
        $this->id = $id;
        $this->name = $name;
        $this->formatterArray = $formatterArray;
        $this->classname = $classname;
        $this->value = stripslashes($value);
    }
    public function p(){

        $id = $this->id.rand(1,100);

        echo "<input style='text-align: right;' type='text' name='sem_{$this->name}' value='{$this->value}' id='format_{$id}' class='{$this->classname}' >";
        ?>
        <input type="hidden" name="<?=$this->name;?>" id="<?=$id;?>" value="<?=$this->value;?>" >
<script>
    $('#format_<?=$id;?>').priceFormat({
        prefix: '<?=$this->formatterArray[0];?>',
        thousandsSeparator: '<?=$this->formatterArray[1];?>',
        centsSeparator: '<?=$this->formatterArray[2];?>',
        centsLimit: <?=$this->formatterArray[3];?>
    });
    $('#format_<?=$id;?>').keyup(function(){
        //remove prefix and separator
        var curr = $('#format_<?=$id;?>').val();
        var number = Number(curr.replace(/[^0-9\,]+/g,""));
        $('#<?=$id;?>').val(number);
    });

</script>
        <?
    }

} 