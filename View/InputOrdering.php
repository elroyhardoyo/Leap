<?php
/**
 * Created by PhpStorm.
 * User: elroy
 * Date: 9/18/15
 * Time: 8:59 PM
 */

namespace Leap\View;


class InputOrdering extends Html{

//    public $arrValue;

    public function __construct ($id, $name, $value, $classname = 'form-control')
    {
        $this->id = $id;
        $this->name = $name;
//        $this->arrValue = $arrValue;
        $this->classname = $classname;
//        $this->value = stripslashes($value);
        $this->value = htmlentities(stripslashes($value));
    }

    public function p ()
    {
//        $sem = $this->type;
        $idku = $this->id."_".time()."_".rand(0,100);

        ?>
        <style>
            ul.sorttbl{list-style-type: none; padding: 0px;}
            .sorttbl li{
                line-height: 30px; padding-left: 20px;
            }
            #sortable { list-style-type: none; margin: 0; padding: 0; width: 50%; }
            #sortable li { margin: 0 3px 3px 3px; padding: 10px; padding-left: 20px; font-size: 15px; height: 28px; }
            #sortable li span { position: absolute; margin-left: -10px; }
        </style>
        <script>
//            $(function() {
//                $( "#sortable" ).sortable();
//                $( "#sortable" ).disableSelection();
//            });
        </script>
        <div class="ui-widget" style="display: none;">

            <input id="<?=$idku;?>" type="hidden"  placeholder='<?=Lang::t($this->name);?>' name='<?=$this->name;?>' value='<?=$this->value;?>' class="form-control">
        </div>
        <?

        $exp = explode(",",$this->value);

        ?>
        <ul id="sortable<?=$idku;?>" class="sorttbl">
            <? foreach($exp as $ex){?>
            <li id="<?=$ex;?>" class="ui-state-default"><?=$ex;?></li>
            <? }?>
        </ul>
        <script>
            $('#sortable<?=$idku;?>').sortable({
                axis: 'y',
                update: function (event, ui) {
//                    var data = $(this).sortable('serialize');
//                    alert(data);
                    // POST to server using $.post or $.ajax
//                    $.ajax({
//                        data: data,
//                        type: 'POST',
//                        url: '/your/url/here'
//                    });

                    //create the array that hold the positions...
                    var order = [];
                    //loop trought each li...
                    $('#sortable<?=$idku;?> li').each( function(e) {

                        //add each li position to the array...
                        // the +1 is for make it start from 1 instead of 0
//                        order.push( $(this).attr('id')  + '=' + ( $(this).index() + 1 ) );
                        order.push( $(this).attr('id'));
                    });

                    // join the array as single variable...
                    var positions = order.join(',')
                    //use the variable as you need!
//                    alert( positions );
                    $('#<?=$idku;?>').val(positions);
                }
            });
        </script>
    <?


    }
} 