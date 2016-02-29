<?php
/**
 * Created by PhpStorm.
 * User: elroy
 * Date: 2/4/16
 * Time: 11:50 PM
 */

namespace Leap\View;


class CPRCondition extends InputText{

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

        <input type="text" name="<?= $this->name; ?>" id="<?= $this->id; ?>_<?=$t;?>" class="<?= $this->id; ?>" value="<?= $this->value; ?>">
        <div class="attribute_adder">
            <div id="submitter_<?=$id;?>" style="padding: 10px; text-align: right; float: right;">
                <!--                <button id="butsave_--><?//=$id;?><!--" type="button" class="btn btn-default">Save Attributes</button>-->
                <button id="but_<?=$id;?>" type="button" class="btn btn-default">Add Attribute </button>
            </div>

            <div id="attbox_<?=$id;?>">
            </div>

        </div>
        <div id="allprod" style="display: none; background-color: #FFFFFF; padding: 10px;">
            <div style="float: right; width: 10px; height: 10px;" onclick="$('#allprod').hide();">x</div>
            <?
            $n = new \MProdModel();
            $arrN = $n->getOrderBy("VariantID ASC");
            self::printer($arrN);
            ?>
        </div>
        <script>
            var featSudah = [];
            var opt_<?=$id;?> = [
                '<option value="all">all</option>',
//                '<option value="price">Price</option>',
                '<option value="variantID">VariantID</option>',
                '<option value="baseID">BaseID</option>',
                '<option value="category">Category</option>'
            ];

            var optcommand_<?=$id;?> = [
                'variantID',
                'baseID',
                'category'
            ];

            var opt2a_<?=$id;?> = [
                '<option value=""></option>',
                '<option value="=">=</option>',
                '<option value="&gt;">&gt;</option>',
                '<option value="&gt;=<">&gt;=</option>',
                '<option value="=&lt;">=&lt;</option>',
                '<option value="&lt;">&lt;</option>'
            ];

            var opt2b_<?=$id;?> = [
                '<option value=""></option>',
                '<option value="is">is one of</option>',
                '<option value="is_not">is not one of</option>'
            ];

            var opt3a_<?=$id;?> = '<input type="number" id="span3">';

            var attr_<?=$id;?> = [];
            var attrnr_<?=$id;?> = <?=$llimit;?>;
            <? if($llimit>0){?>
            //create attributes
            <? foreach($exp as $num=>$ee){
             $exp2 = explode(";",$ee);
             $option1 = $exp2[0];
             $option2 = $exp2[1];
             $option3 = $exp2[1];
             ?>
            createAttr<?=$id;?>('<?=$num;?>','<?=$option1;?>','<?=$option2;?>','<?=$option3;?>');
            <? } //foreach?>

            function createAttr<?=$id;?>(lokalid,option1,option2,option3){

                var selecttext = '';
                for(var x=0;x<opt_<?=$id;?>.length;x++){
                    selecttext += opt_<?=$id;?>[x];
                }
                var text2 = "<div class='condition form-inline'><select class='form-control' id='select1_"+lokalid+"' onchange='updateSpan2(\""+lokalid+"\");'>"+selecttext+"</select><span id='span2_"+lokalid+"'></span><span id='span3_"+lokalid+"'></span></div>";
                $("#attbox_<?=$id;?>").append(text2);
                $("#submitter_<?=$id;?>").show();
            }
            <? } ?>

            function updateSpan2(lokalid){
                var slc = $('#select1_'+lokalid).val();

                activeLokalID = lokalid;


                var selecttext = '';
                if(slc == "all"){
                    $('#span2_'+lokalid).html('');
                    $('#span3_'+lokalid).html('');
                    return "";
                }
                else if(slc == "price"){
                    for(var x=0;x<opt2a_<?=$id;?>.length;x++){
                        selecttext += opt2a_<?=$id;?>[x];
                    }
                    featSudah.push(slc);
                }else{
                    for(var x=0;x<opt2b_<?=$id;?>.length;x++){
                        selecttext += opt2b_<?=$id;?>[x];
                    }
                    featSudah.push(slc);
                }
                $('#span3_'+lokalid).html('');
                $('#span2_'+lokalid).html("<select class='form-control' id='select2_"+lokalid+"' onchange='updateSpan3(\""+lokalid+"\");'>"+selecttext+"</select>");
            }

            var activeLokalID = 0;

            function updateSpan3(lokalid){
                var slc2 = $('#select1_'+lokalid).val();

                var slc = $('#select2_'+lokalid).val();

                activeLokalID = lokalid;
//                $('#span3_'+lokalid).html(slc);

                var selecttext = '';
                if(slc2 == "price"){

                    $('#span3_'+lokalid).html('<input onchange="updateHiddenCondition();" class="form-control" type="number" id="isi_span3_'+lokalid+'">');

                }else{
                    /// load from database....
                    if(slc2 == "variantID"){
                        //uncheck all checkbox
                        $('.variant_check').prop("checked",false);
                        checkedVariant = [];
                        $('#allprod').show();
                        html = "<input type='text' class='form-control' id='isi_span3_"+lokalid+"'>";

                    }
                    if(slc2 == "baseID"){
                        <?
                        $n = new \MProdModel();
                        $arrN = $n->getWhere("ArticleType = 'Base' ORDER BY BaseArticleID ASC");



                        $cc = '';
                        foreach($arrN as $num=>$nn){
                            $cc .= "<input type='checkbox'>".$num.". ".$nn->BaseArticleID." ".$nn->BaseArticleNameENG."<br>";
                        }
                        ?>
                        var html = "<?=$cc;?>";
                    }
                    if(slc2 == "category"){
                        <?
                        $n = new \MProdCat();
                        $arrN = $n->getWhere("cat_parent_id != '-1' ORDER BY cat_name ASC");
                        $cc = '';
                        foreach($arrN as $num=>$nn){
                            $cc .= "<input type='checkbox'>".$num.". ".$nn->cat_id." ".$nn->cat_name."<br>";
                        }
                        ?>
                        var html = "<?=$cc;?>";
                    }
                    $('#span3_'+lokalid).html(html);

                }


            }


            $("#but_<?=$id;?>").click(function(){
                var lokalid = attrnr_<?=$id;?>;
//                var text = "<div class='attr_box'>Attr Name : <input id='attrtext_<?//=$id;?>//_"+lokalid+"' type='text'> Stok : <input id='attrstok_<?//=$id;?>//_"+lokalid+"' type='number'></div>";


                var selecttext = '';
                for(var x=0;x<opt_<?=$id;?>.length;x++){

                    if(jQuery.inArray( optcommand_<?=$id;?>[x], featSudah ) !== -1){
                        continue;
                    }


                    selecttext += opt_<?=$id;?>[x];
                }
                var text2 = "<div class='condition form-inline'><select class='form-control' id='select1_"+lokalid+"' onchange='updateSpan2(\""+lokalid+"\");'>"+selecttext+"</select><span id='span2_"+lokalid+"'></span><span id='span3_"+lokalid+"'></span></div>";
                $("#attbox_<?=$id;?>").append(text2);

                $("#submitter_<?=$id;?>").show();
                attrnr_<?=$id;?>++;
            });

//            $("#butsave_<?//=$id;?>//").click(function(){
//
//                updateHiddenCondition();
////                alert(energy);
//
////                $("#<?////=$this->totalstock_id;?>////").val(totalstok);
//            });

            function updateHiddenCondition(){
                var gab = [];
                var totalstok = 0;
//                alert(attrnr_<?//=$id;?>//);
                //parsing all the inputs
                for(var x = 0;x<attrnr_<?=$id;?>;x++){

//                    alert(x);

                    var isi3 = $("#isi_span3_"+x).val();
                    var isi2 = $("#select2_"+x).val();
                    var isi1 = $("#select1_"+x).val();

                    if(isi1=="" || isi2 =="" || isi3 == ""){
                        //error1
                        alert("Missing Attributes ");
                    }
                    else {
                        gab.push(isi1+";"+isi2+";"+isi3);
                    }

                }

                var energy = gab.join("|");

                $("#<?= $this->id; ?>_<?=$t;?>").val(energy);
            }
        </script>
        <style>
            .condition{
                padding: 5px;
            }
        </style>
    <?
    }
    public static function printer($arr){
        $pc = new \ProductAtCategory();
        ?>

        <script>

        //untuk product management
        var catKey = [];


        var page = 0;
        var limit = 12;
        var total = 0;
        var jmlpage = 0;

        function preloadImg(id){
//                console.log('preload '+id);
            $('#imgloader_'+id).hide();
            $('#imgasli_'+id).show();
        }

        function removeA(arr) {
            var what, a = arguments, L = a.length, ax;
            while (L > 1 && arr.length) {
                what = a[--L];
                while ((ax= arr.indexOf(what)) !== -1) {
                    arr.splice(ax, 1);
                }
            }
            return arr;
        }

        function moveToPage(x){
            page = x;
            printProduct({});
        }

        var arahPrice = "desc";
        function sortByPrice(){
            if(arahPrice == "desc") {
                homes.sort(function (a, b) {
                    return parseFloat(a.SellingPrice) - parseFloat(b.SellingPrice);
                });
                arahPrice = "asc";
            }else{
                arahPrice = "desc";
                homes.sort(function (a, b) {
                    return parseFloat(b.SellingPrice) - parseFloat(a.SellingPrice);
                });
            }

            printProduct({});
        }
        var arahName = "desc";

        function sortByName(){
            if(arahName == "desc") {
                homes.sort(sort_by('BaseArticleNameENG', false, function(a){return a.toUpperCase()}));
                arahName = "asc";
            }else{
                arahName = "desc";
                homes.sort(sort_by('BaseArticleNameENG', true, function(a){return a.toUpperCase()}));
            }
            printProduct({});
        }

        var sort_by = function(field, reverse, primer){
            var key = function (x) {return primer ? primer(x[field]) : x[field]};

            return function (a,b) {
                var A = key(a), B = key(b);
                return ( (A < B) ? -1 : ((A > B) ? 1 : 0) ) * [-1,1][+!!reverse];
            }
        }

        function updateArrSize(){
            var arrSem = homes.slice();
            var arrFiltered = [];
            var range = {
                minprice : $( "#slider" ).slider( "values", 0 ),
                maxprice : $( "#slider" ).slider( "values", 1 )
            };
            var yangMasukRange = 0;
            for(var x=0;x<arrSem.length;x++) {
                var attr = arrSem[x];

                if (range.hasOwnProperty('minprice')) {
                    if (attr['SellingPrice'] < range.minprice)
                        continue;
                }
                if (range.hasOwnProperty('maxprice')) {
                    if (attr['SellingPrice'] > range.maxprice)
                        continue;
                }
                if(catKey.length>0){
                    if(jQuery.inArray( attr['TaggingLevel3ID'], catKey ) == -1){
                        continue;
                    }
                }

                //search filter
                var search = $("#searchTextVariant").val().toLowerCase();
                if(search != '') {
//                    console.log(search);
//                    console.log(attr['VariantID']+ " "+attr['VariantNameENG']);
                    if (attr['VariantID'].toLowerCase().indexOf(search) === -1 && attr['VariantNameENG'].toLowerCase().indexOf(search) === -1) {
                        continue;
                    }
                }


                arrFiltered.push(attr);
                yangMasukRange++;
            }
            total = yangMasukRange;
            return arrFiltered;
        }
        function printProduct(option){
            $('#loadingtop').show().fadeOut();
//                console.log(option);

            var arrSem = updateArrSize();

            var range = {
                minprice : $( "#slider" ).slider( "values", 0 ),
                maxprice : $( "#slider" ).slider( "values", 1 )
            };
            var html = '';
            var printed  = 0;

//                var arrSem = homes.slice();

            var end = Math.min(limit,arrSem.length);
            console.log(arrSem);

            var t =$.now();

            if(page>1) {
                var anzahlremove = 0 - ((page - 1) * limit);
                arrSem.splice(anzahlremove);
            }
//                console.log("anzahlremove "+anzahlremove);
//                console.log(arrSem.length);


            html += createPagination();

            while(printed < end && arrSem.length > 0){
//                for(var x=0;x<12;x++){
                var attr = arrSem.pop();
                var rand = Math.floor((Math.random() * 100) + 1);
                t = t+rand;

                if(range.hasOwnProperty('minprice')){
                    if(attr['SellingPrice']<range.minprice)
                        continue;
                }
                if(range.hasOwnProperty('maxprice')){
                    if(attr['SellingPrice']>range.maxprice)
                        continue;
                }
                if(catKey.length>0){
                    if(jQuery.inArray( attr['TaggingLevel3ID'], catKey ) == -1){
                        continue;
                    }
                }

                html += '<div class="product_list_item">';
                html += '<div class="product_list_item_dalaman">';
                html += '<div id="imgloader_'+attr['VariantID']+'_'+t+'" class="img_loader" >';
                html += '<img onload="" src="<?=_SPPATH;?>images/tbs-hor-ajax-loader.gif">';
                html += '</div>';

                var imgurl = '<?=$pc->imgURL;?>'+attr['BaseArticleImageFile'];
                if(attr['BaseArticleImageFile'] == ''){
                    imgurl = '<?=$pc->noimage;?>';
                }

                html += '<div  id="imgasli_'+attr['VariantID']+'_'+t+'" class="product_list_item_img" style="display:none;">';
//                html += '<a title="'+attr['BaseArticleNameENG']+'" href="<?//=_SPPATH;?>//pr/p/'+attr['VariantID']+'/'+encodeURIComponent(attr['BaseArticleNameENG'])+'">';
                html += '<img onload="preloadImg(\''+attr['VariantID']+'_'+t+'\');"  src="'+imgurl+'" >';
//                html += '</a>';
                html += '</div>';



                html += '<div class="product_list_item_text">';

                html += '<div class="name">';
                html += '<input class="variant_check" type="checkbox" id="check_'+attr['VariantID']+'" onchange="checkMe(this,'+attr['VariantID']+');">';
                html += ' ID .'+attr['VariantID']+' <br>';
                html += attr['VariantNameENG'];
                html += '</div>';


                html += '<div class="item_price">IDR '+toRp(attr['SellingPrice'])+'</div>';

                html += '<div class="clearfix"></div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';



                if(printed%3 == 2)html += "<div class='clearfix'></div><hr class='dotted'/>";

                printed++;
            }
            html += '<div class="clearfix"></div>';
            html += createPagination();


            $('#product_data').html(html);
//                $('#loadingtop').hide();
        }

        function createPagination(){

            var html = '';
//                $begin = (($page-1)*$limit)+1;
//                $end = $page+$limit-1;
//
//                $total = count($arr);
//                $jmlhpage = ceil($total/$limit);
//
//                $minpage = max(1,$page-3);
//                $maxpage = min($jmlhpage,$page+3);

            var begin = ((page-1)*limit)+1;
            var end = Math.min(begin+limit-1,total);

            var jmltotal = total;
            var jmlpage = Math.ceil(jmltotal/limit);

            var minpage = Math.max(1,page-3);
            var maxpage = Math.min(jmlpage,page+3);


            html += '<div class="product_pagination">';
            html += '<div class="showing">SHOWING <b>'+begin+'</b>-<b>'+end+'</b> OF <b>'+jmltotal+'</b></div>';
            html += '<div class="pages">Pages';
            if(page>1) {
                var mundur = page-1;
                html += '<span onclick="moveToPage('+mundur+');" class="page_nr">«</span>';
            }
            for(var x=minpage;x<=maxpage;x++){
                var sel = '';
                if(page == x)sel = 'page_nr_sel';
                html += '<span onclick="moveToPage('+x+');" class="page_nr '+sel+'">'+x+'</span>';
            }
            if(page<jmlpage){
                var maju = page+1;
                html += '<span onclick="moveToPage('+maju+');" class="page_nr">»</span>';
            }

            html += '&nbsp;&nbsp; &nbsp; <b>'+page+'</b> of <b>'+jmlpage+'</b> displayed </div>';
            html += '</div>';

            return html;
        }

        function toRp(angka){
            var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
            var rev2    = '';
            for(var i = 0; i < rev.length; i++){
                rev2  += rev[i];
                if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
                    rev2 += '.';
                }
            }
            return rev2.split('').reverse().join('');
        }
        var checkedVariant = [];

        function checkMe(obj,key){
            console.log('checked');

            var jo = $(obj);


            if(obj.checked) {
                //Do stuff

                checkedVariant.push(key);
            }
            else{
                //remove element from array
                removeA(checkedVariant, key);
            }
            $('#isi_span3_'+activeLokalID).val(checkedVariant.join());

            updateHiddenCondition();
        }

        </script>

        <div class="clearfix"></div>

        <div class="col-md-12" style="margin-top: 20px;">





        <?

//            $arr = $data->results;
            $page = 1;
            $limit = 12;

            $begin = (($page-1)*$limit)+1;
            $end = $begin+$limit-1;

            $total = count($arr);
            $jmlhpage = ceil($total/$limit);

            $minpage = max(1,$page-3);
            $maxpage = min($jmlhpage,$page+3);
            ?>
            <div class="sort">

                <span style="margin-right: 20px;">SORT BY</span>
                <span onclick="sortByName();"  class="sort_item">NAME</span>
                <span onclick="sortByPrice();" class="sort_item">PRICE</span>
                <span class="sort_item" ><input id="searchTextVariant" onkeyup="page=1;printProduct({});" type="text"></span>
                <div style="float: right; width: 200px;">
                    <div id="filter_subcategory_price">
                        <div id="slider"></div>
                        <div id="price"></div>
                        <input type="hidden" id="val_min">
                        <input type="hidden" id="val_max">
                    </div>
                    <script>
                        $(function() {
                            $( "#slider" ).slider({
                                range: true,
                                values: [ 0, 3000000 ],
                                step : 10000,
                                min: 0,
                                max: 1000000,
                                slide: function( event, ui ) {
                                    $( "#price" ).html( "IDR " + toRp(ui.values[ 0 ]) + " - IDR " + toRp(ui.values[ 1 ]) );
//                                $('#val_min').val(ui.values[ 0 ]);
//                                $('#val_max').val(ui.values[ 1 ]);
                                },
                                stop: function( event, ui ) {
//                                homes.sort(function (a, b) {
//                                    return parseFloat(a.SellingPrice) - parseFloat(b.SellingPrice);
//                                });
//                                printProduct({
//                                    minprice : ui.values[ 0 ],
//                                    maxprice : ui.values[ 1 ]
//                                });

                                    homes.sort(function (a, b) {
                                        return parseFloat(a.SellingPrice) - parseFloat(b.SellingPrice);
                                    });
                                    arahPrice = "asc";
                                    page = 1;
                                    printProduct({});
                                }
                            });

                            $( "#price" ).html( "IDR " + toRp($( "#slider" ).slider( "values", 0 )) +
                            " - IDR " + toRp($( "#slider" ).slider( "values", 1 )) );
//                        $('#val_min').val($( "#slider" ).slider( "values", 0 ));
//                        $('#val_max').val($( "#slider" ).slider( "values", 1 ));
                        });

                        function filterin(){
//                        console.log('filterin');
                            homes.sort(function (a, b) {
                                return parseFloat(a.SellingPrice) - parseFloat(b.SellingPrice);
                            });
                            printProduct({});
                        }
                    </script>
                </div>

            </div>
            <style>
                .showing{
                    float: left;
                    width: 200px;

                }
                .pages{
                    text-align: right;
                }
                .page_nr{
                    cursor: pointer;
                    padding-left: 5px;
                    padding-right: 5px;
                }
                .page_nr:hover{
                    text-decoration: underline;
                }
                .page_nr_sel{
                    font-weight: bold;
                    color: #7fb719;
                }
            </style>
            <div id="product_data">
                <div class="product_pagination">
                    <div class="showing">SHOWING <b><?=$begin;?></b>-<b><?=$end;?></b> OF <b><?=count($arr);?></b></div>
                    <div class="pages">Pages
                        <? if($page>1){?>
                            <span onclick="moveToPage(<?=$page-1;?>);" class="page_nr">«</span>
                        <?}?>
                        <? for($x=$minpage;$x<=$maxpage;$x++){?>
                            <span onclick="moveToPage(<?=$x;?>);" class="page_nr <?if($page==$x)echo "page_nr_sel";?>"><?=$x;?></span>
                        <?}?>
                        <? if($page<$jmlhpage){?>
                            <span onclick="moveToPage(<?=$page+1;?>);" class="page_nr">»</span>
                        <?} ?>
                        &nbsp;&nbsp; &nbsp; <b><?=$page;?></b> of <b><?=$jmlhpage;?></b> displayed </div>
                </div>
                <?
                $t = time();
                foreach($arr as $key=>$obj){
                    $t = $t.rand(0,100);


                    $imgurl = $pc->imgURL.$obj->BaseArticleImageFile;
                    if($obj->BaseArticleImageFile == "")$imgurl = $pc->noimage;
                    ?>
                    <div class="product_list_item">
                        <div class="product_list_item_dalaman">
                            <!--                    <div id="imgloader_--><?//=$obj->VariantID;?><!--_--><?//=$t;?><!--" class="img_loader" >-->
                            <!--                        <img src="--><?//=_SPPATH;?><!--images/tbs-hor-ajax-loader.gif">-->
                            <!--                    </div>-->
                            <div id="imgasli_<?=$obj->VariantID;?>_<?=$t;?>" class="product_list_item_img">
                                <a title="<?=$obj->BaseArticleNameENG;?>" href="<?=_SPPATH;?>pr/p/<?=$obj->VariantID;?>/<?=urlencode($obj->BaseArticleNameENG);?>">
                                    <img  src="<?=$imgurl;?>" >
                                </a>
                            </div>

                            <div class="product_list_item_text">
                                <div class="name">
                                    <input class="variant_check" type="checkbox" id="check_<?=$obj->VariantID;?>" onchange="checkMe(this,'<?=$obj->VariantID;?>');">
                                    ID . <?=$obj->VariantID;?> <br>
                                        <?=$obj->VariantNameENG;?>
                                </div>

                                <div class="item_price">IDR <?=idr($obj->SellingPrice);?></div>

                            </div>
                        </div>
                    </div>
                    <?
                    if($key%3 == 2)echo "<div class='clearfix'></div><hr class='dotted'/>";
                    if($key>10)break;
                }
                ?>
                <div class="clearfix"></div>
                <div class="product_pagination">
                    <div class="showing">SHOWING <b><?=$begin;?></b>-<b><?=$end;?></b> OF <b><?=count($arr);?></b></div>
                    <div class="pages">Pages
                        <? if($page>1){?>
                            <span onclick="moveToPage(<?=$page-1;?>);" class="page_nr">«</span>
                        <?}?>
                        <? for($x=$minpage;$x<=$maxpage;$x++){?>
                            <span onclick="moveToPage(<?=$x;?>);" class="page_nr <?if($page==$x)echo "page_nr_sel";?>"><?=$x;?></span>
                        <?}?>
                        <? if($page<$jmlhpage){?>
                            <span onclick="moveToPage(<?=$page+1;?>);" class="page_nr">»</span>
                        <?} ?>
                        &nbsp;&nbsp; &nbsp; <b><?=$page;?></b> of <b><?=$jmlhpage;?></b> displayed </div>
                </div>
            </div>
            <div class="clearfix"></div>


            <script>

                var page = <?=$page;?>;
                var limit = <?=$limit;?>;
                var total = <?=$total;?>;
                var jmlpage = <?=$jmlhpage;?>;
                var homes = [];

            $(document).ready(function(){
                $( "#product_data img" )
                    .error(function() {
                        $( this ).attr( "src", "<?=$pc->noimage;?>" );
                    });
                homes.reverse();
            });


            <?
            //create javascript objects
            foreach($arr as $key=>$obj){

//                unset($obj->VariantNameINA);
//                unset($obj->VariantNameENG);
//                unset($obj->VariantINACode);
                unset($obj->HowToUseINA);
                unset($obj->HowToUseENG);
                unset($obj->ArticleInfoINA);
                unset($obj->ProductTipsINA);
                unset($obj->ProductTipsENG);
                unset($obj->ArticleInfoENG);
                unset($obj->IngredientINA);
                unset($obj->IngredientENG);
//                unset($obj->VariantEAN);
                unset($obj->WhatInsideINA);
                unset($obj->WhatInsideENG);


                ?>
            var el = jQuery.parseJSON('<?=addslashes(json_encode($obj));?>');
            homes.push(el);
            <?
        }


    ?>

            </script><?

        ?>
        <style>
            .sort{
                border: 1px solid #cccccc;
                padding: 10px;

                font-size: 12px;
                font-weight: bold;
            }
            .product_pagination{
                padding: 10px;
                margin-bottom: 30px;
                color: #666666;
            }
            .sort_item{
                cursor: pointer;
                padding-left: 20px;
                padding-right: 20px;
                border-left: 1px dashed #cccccc;
                font-weight: normal;
            }
            .product_list_item{
                float: left;
                width: 33%;
                height: 300px;
                border-right: 1px dashed #cccccc;
            }
            .product_list_item_dalaman{
                padding: 10px;
            }
            .product_list_item_img,.img_loader{
                width: 180px;
                height: 180px;
                overflow: hidden;
                margin: auto;
                text-align: center;
            }
            .product_list_item_img img,.img_loader img{
                max-width:100%;
                max-height:100%;
            }
            .img_loader{
                line-height: 180px;
            }

            .product_list_item_text .name{
                font-weight: bold;
                color: #777777;
                height: 40px;
                text-overflow: ellipsis;
                margin-top: 20px;
                overflow:hidden;
                /*white-space:nowrap;*/
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;

            }
            .product_list_item_text .name a{
                color:#666666;
            }
            .product_list_item_text .name a:hover{
                color: #7fb719;
            }

            /*
            use text ellipsis always with overflow:hidden; and white-space:nowrap;

            for multiple line ..line clamps use
            display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                and overflow:hidden;
            */
            .rating{
                font-size: 20px;
                margin-top: 10px;
                color: #999999;
            }
            .rating > span:hover:before {
                content: "\2605";
                position: absolute;
            }
            .item_price{
                color: #888888;

            }
            .buy{
                padding: 5px;
                border: 1px solid #cccccc;
                margin-top: 10px;
                height: 40px;

            }
            a.more{
                font-size: 11px;
                text-decoration: underline;
                color: #444444;
                height: 30px;
                line-height: 30px;
            }
            a.add{
                background-color: #e2007a;
                color: white;
                padding: 5px;
                height: 30px;
                line-height: 30px;
            }
            hr.dotted{
                border-top: 1px dashed #cccccc;
                margin-top: 10px;
                margin-bottom: 10px;
                margin-left: 10px;
                margin-right: 10px;
            }

            .breadcrumbs span{
                font-weight: bold;
            }
            .breadcrumbs a{
                color: #666666;
                font-style: italic;
            }

        </style>
        </div>
        <div class="clearfix"></div>
<?
//        pr($data);
    }
} 