<?php
/**
 * Created by PhpStorm.
 * User: elroy
 * Date: 12/7/15
 * Time: 11:24 AM
 */

namespace Leap\View;


class InputAttribute extends Html {
    var $totalstock_id;
    public function __construct ($totalstock_id,$id, $name, $value, $classname = 'form-control')
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
        $this->totalstock_id = $totalstock_id;
    }

    public function p(){
        $t = time().rand(1,100);

        $id = $this->id."attrib_" . $t;

//        echo $id;
        $exp = explode(",",$this->value);
        $llimit = count($exp);
        if($llimit<1)$llimit = 0;
        ?>
        <input type="hidden" name="<?= $this->name; ?>" id="<?= $this->id; ?>_<?=$t;?>" class="<?= $this->id; ?>" value="<?= $this->value; ?>">
        <div class="attribute_adder">

            <small>Contoh : size, ingat attribute hanya bisa merupakan text, dan mempunyai stok</small><br><br>
            <div id="attbox_<?=$id;?>">


            </div>
            <div id="submitter_<?=$id;?>" style="display: none; padding-top: 20px;">
                <button id="butsave_<?=$id;?>" type="button" class="btn btn-default">Save Attributes</button>
                <button id="but_<?=$id;?>" type="button" class="btn btn-default">Add Attribute </button>
            </div>
        </div>
        <script>
            var attr_<?=$id;?> = [];
            var attrnr_<?=$id;?> = <?=$llimit;?>;
            <? if($llimit>0){?>
            //create attributes
            <? foreach($exp as $num=>$ee){
             $exp2 = explode(";",$ee);
             $text = $exp2[0];
             $stok = $exp2[1];
             ?>
            createAttr<?=$id;?>('<?=$num;?>','<?=$text;?>','<?=$stok;?>');
            <? } //foreach?>

            function createAttr<?=$id;?>(lokalid,text,stok){
                var text2 = "<div class='attr_box'>Attr Name : <input id='attrtext_<?=$id;?>_"+lokalid+"' type='text' value='"+text+"'> Stok : <input id='attrstok_<?=$id;?>_"+lokalid+"' type='number' value='"+stok+"'></div>";
                $("#attbox_<?=$id;?>").append(text2);
                $("#submitter_<?=$id;?>").show();
            }
            <? } ?>
            $("#but_<?=$id;?>").click(function(){
                var lokalid = attrnr_<?=$id;?>;
                var text = "<div class='attr_box'>Attr Name : <input id='attrtext_<?=$id;?>_"+lokalid+"' type='text'> Stok : <input id='attrstok_<?=$id;?>_"+lokalid+"' type='number'></div>";
                $("#attbox_<?=$id;?>").append(text);
                $("#submitter_<?=$id;?>").show();
                attrnr_<?=$id;?>++;
            });

            $("#butsave_<?=$id;?>").click(function(){
                var gab = [];
                var totalstok = 0;
//                alert(attrnr_<?//=$id;?>//);
                //parsing all the inputs
                for(var x = 0;x<attrnr_<?=$id;?>;x++){

//                    alert(x);

                    var text = $("#attrtext_<?=$id;?>_"+x).val();
                    var stok = parseInt($("#attrstok_<?=$id;?>_"+x).val());

                    if(text!=""&&stok==""){
                        //error1
                        alert("Attribute "+text+" belum punya stok");
                    }
                    else if (stok === parseInt(stok, 10) && text!=""){
                        gab.push(text+";"+stok);
                        totalstok += stok;
                    }else if(stok !== parseInt(stok, 10)&& text!=""){
                        alert(" Stok harus diisi dengan angka");
                    }

                }

                var energy = gab.join();
                $("#<?= $this->id; ?>_<?=$t;?>").val(energy);

//                alert(energy);

                $("#<?=$this->totalstock_id;?>").val(totalstok);
            });
        </script>
        <?
    }

} 