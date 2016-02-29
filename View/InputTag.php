<?php
/**
 * Created by PhpStorm.
 * User: elroy
 * Date: 9/18/15
 * Time: 3:38
 * INGAT : Harus ada bootstrap-tagsinput dan typeahead.js
 */

namespace Leap\View;


class InputTag extends Html{

    public $prefetchLocation;

    public function __construct ($prefetchLocation, $id, $name, $value, $classname = 'form-control')
    {
        $this->id = $id;
        $this->name = $name;
        $this->prefetchLocation = $prefetchLocation;
        $this->classname = $classname;
//        $this->value = stripslashes($value);
        $this->value = htmlentities(stripslashes($value));
    }

    public function p ()
    {

        $idku = $this->id."_".time()."_".rand(0,100);
        $prefetchLocation = $this->prefetchLocation;
        ?>
    <style>
        .bootstrap-tagsinput{
            width: 100%;
        }
    </style>
        <input id="<?=$idku;?>" name="<?=$this->name;?>" type="text" value="<?=$this->value;?>" data-role="tagsinput" class="form-control" />
        <script>
            var citynames<?=$idku;?> = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: '<?=_LANGPATH;?><?=$prefetchLocation;?>?t='+$.now(),
                    filter: function(list) {
                        return $.map(list, function(cityname) {
                            return { name: cityname }; });
                    }
                }
            });
            citynames<?=$idku;?>.initialize();

            $('#<?=$idku;?>').tagsinput({
                typeaheadjs: {
                    name: '<?=$this->name;?>',
                    displayKey: 'name',
                    valueKey: 'name',
                    source: citynames<?=$idku;?>.ttAdapter()
                }
            });
        </script>
    <?


    }
} 