<?php
/**
 * Created by PhpStorm.
 * User: elroy
 * Date: 2/4/16
 * Time: 11:50 PM
 */

namespace Leap\View;


class InputConditionTBS extends InputText{


    public function __construct ($id, $name, $value)
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
    }

    public function p(){
        $t = time().rand(1,100);
        ?>

        <div id="show_<?= $this->id; ?>_<?=$t;?>" style="width: 100%; overflow: auto; padding-bottom: 10px;  height: 100px; display: none;"></div>
        <input type="hidden" name="<?= $this->name; ?>" id="<?= $this->id; ?>_<?=$t;?>" class="<?= $this->id; ?>" value="<?= $this->value; ?>">
        <button onclick="resetReloadAndShow_<?=$t;?>();" type="button">edit</button>

        <script>
        <?
        if($this->value!=""){
        ?>
        $('#show_<?= $this->id; ?>_<?=$t;?>').show();
        isiKanValue('<?= $this->id; ?>_<?=$t;?>');
        showHowManySelected();
            <?
        }
        ?>
        function resetReloadAndShow_<?=$t;?>(){

            $('#allprod').show();
            $('.wrapper').hide();

            //reset
            //apa yg direset..
            //check button, check categories, search, filter, page, posisi slider

            page = 1;
//            unCheckedAll();
            uncheckCategories();
            unsetSearch();
            resetSliderPos();


            $('.variant_check').prop("checked",false);

            //atau reload jika ada isi
            isiKanValue('<?= $this->id; ?>_<?=$t;?>');

            printProduct({});
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

        <script>

        //untuk product management
        var catKey = [];
        var page = <?=$page;?>;
        var limit = <?=$limit;?>;
        var total = <?=$total;?>;
        var jmlpage = <?=$jmlhpage;?>;
        var homes = [];

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
                    var lanjut = 0;
                    var cats = attr['TaggingLevel3ID'].split(' ');
//                    console.log(cats);
                    for(var z=0;z<cats.length;z++) {
                        if (jQuery.inArray(cats[z], catKey) > -1) {
                            lanjut = 1;
//                            console.log("lanjut"+cats[z]+attr['VariantID']);
                        }
                    }
                    if(lanjut === 0)
                        continue;
                }

                //search filter
                var search = $("#searchTextVariant").val().toLowerCase();
                if(search != '') {
//                    console.log(search);
//                    console.log(attr['VariantID']+ " "+attr['VariantNameENG']);BaseArticleNameENG
                    if (attr['VariantID'].toLowerCase().indexOf(search) === -1 && attr['VariantNameENG'].toLowerCase().indexOf(search) === -1 && attr['BaseArticleNameENG'].toLowerCase().indexOf(search) === -1) {
                        continue;
                    }
                }


                arrFiltered.push(attr);
                yangMasukRange++;
            }
            total = yangMasukRange;
//            console.log("yangMasukRange "+yangMasukRange);
//            console.log(arrFiltered);
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
//            console.log(arrSem);

            var t =$.now();

            if(page>1) {
                var anzahlremove = 0 - ((page - 1) * limit);
                arrSem.splice(anzahlremove);
            }
//                console.log("anzahlremove "+anzahlremove);
//                console.log(arrSem.length);


            html += createPagination();

//            console.log(arrSem);

            while(printed < end && arrSem.length > 0){
//                for(var x=0;x<12;x++){
                var attr = arrSem.pop();

//                console.log(attr);

                var rand = Math.floor((Math.random() * 100) + 1);
                t = t+rand;

//                if(range.hasOwnProperty('minprice')){
//                    if(attr['SellingPrice']<range.minprice)
//                        continue;
//                }
//                if(range.hasOwnProperty('maxprice')){
//                    if(attr['SellingPrice']>range.maxprice)
//                        continue;
//                }
//                if(catKey.length>0){
//                    if(jQuery.inArray( attr['TaggingLevel3ID'], catKey ) == -1){
//                        continue;
//                    }
//                }

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

                var key = attr['VariantID'];
                var checked = '';
                if(jQuery.inArray( key, checkedVariant ) > -1) {
                    checked = "checked ='true'";
                }


                html += '<input class="variant_check" type="checkbox" id="check_'+attr['VariantID']+'" '+checked+' onchange="checkMe(this,\''+attr['VariantID']+'\');">';
                html += ' ID .'+attr['VariantID']+' <br>';
                html += attr['BaseArticleNameENG'];
                html += '</div>';
                html += '<div class="item_price">'+attr['TaggingLevel3ID']+'</div>';

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
//            console.log('checked');

            var jo = $(obj);


            if(obj.checked) {
                //Do stuff

                checkedVariant.push(key);
            }
            else{
//                console.log('in checkme');
//                console.log(checkedVariant);
                //remove element from array
                removeA(checkedVariant, key);
            }
            showHowManySelected();
//            $('#isi_span3_'+activeLokalID).val(checkedVariant.join());

//            updateHiddenCondition();
        }

        function checkedAll(){
            //pick which item is displayed
            //check_variantID
            var arrSem = updateArrSize();
            for(var x=0;x<arrSem.length;x++){
                var attr = arrSem[x];
                var key = attr['VariantID'];
                $('#check_'+attr['VariantID']).prop("checked",true);

                if(jQuery.inArray( key, checkedVariant ) == -1) {
                    checkedVariant.push(key);
                }
            }
            showHowManySelected();
        }

        function unCheckedAll(){
            $('.variant_check').prop("checked",false);
            checkedVariant = [];
            showHowManySelected();
        }
        function unCheckedFiltered(){
            //pick which item is displayed
            //check_variantID
            var arrSem = updateArrSize();
            for(var x=0;x<arrSem.length;x++){
                var attr = arrSem[x];
                var key = attr['VariantID'];
                $('#check_'+attr['VariantID']).prop("checked",false);

                removeA(checkedVariant, key);
            }
            showHowManySelected();
        }

        function showHowManySelected(){
            var len = checkedVariant.length;
            asuccess(len+' selected');
            $('#anzahlSelected').html(len+' selected');
            $('#theSelection').val(checkedVariant.join());

            if(to_update_id!='') {
                $('#' + to_update_id).val(checkedVariant.join());

                //create visual change
                var html = '';
                var ende = checkedVariant.length;
                for(var x=0;x<ende;x++){
                    var attr = checkedVariant[x];
                    var obj = homesWithKey[attr];
                    var cnt = x+1;
                    html += '<div class="condition_item">';
                    html += cnt+". ["+obj['VariantID']+'] '+obj['BaseArticleNameENG'];
                    html += '</div>';
                }
                $('#show_'+to_update_id).html(html);

                if(ende<1){
                    $('#show_'+to_update_id).hide();
                }else{
                    $('#show_'+to_update_id).show();
                }
            }
        }

        function uncheckCategories(){

            //category_check
            $('.category_check').prop("checked",false);
            catKey = [];
        }

        function unsetSearch(){
            $('#searchTextVariant').val('');
        }

        function resetSliderPos(){
            $("#slider").slider('values',0,0); // sets first handle (index 0) to 50
            $("#slider").slider('values',1,1000000); // sets second handle (index 1) to 80
        }

        var to_update_id = '';
        function isiKanValue(update_id){
            to_update_id = update_id;
            var slc = $('#'+update_id).val();
//            console.log("slc "+slc);
            if(slc!='') {

//                var sem  = slc.split(",");
//                var semarr = [];
//                for(var x=0;x<sem.length;x++){
//                    var isikan = parseInt(sem[x]);
//                    semarr.push(isikan);
//                }
//                checkedVariant = semarr;

                checkedVariant = slc.split(",");
//                console.log(checkedVariant);
                printProduct({});
            }

        }

        </script>
        <input type="hidden" id="theSelection">
        <div id="anzahlSelected" style="position: absolute; font-size: 13px; padding: 20px; z-index: 1000000; left: 0px; top: 0px; width: 200px; height: 20px;"></div>
        <div class="col-md-3 wadah_filter"  >
            <div class="filter">
                <h3>Categories</h3>

                <div class="filter_item" id="filter_subcategory" >

                    <div id="filter_subcategory_isi" >
                        <?

                        \ProductCategoryService::printCategoryFilterAll();

                        ?>
                    </div>
                </div>


            </div>
            <style>
                .filter{
                    margin-top: 40px;
                    border: 1px solid #cccccc;
                    color: #666666;
                    padding: 10px;
                }
                .filter h3{
                    padding: 0;
                    margin: 0;
                }
                .filter h5{
                    color: #333333;
                    cursor: pointer;
                }
                .filter_item{
                    border-top: 1px dashed #cccccc;
                    margin-top: 10px;
                }
                .key2{
                    font-size: 14px;
                    margin-top: 7px;
                    margin-bottom: 7px;


                }
                .key2 span{
                    cursor: pointer;
                }
                .key3{
                    font-size: 13px;
                    margin-top: 3px;
                    margin-bottom: 3px;
                }
            </style>
        </div>

        <div class="col-md-9" style="margin-top: 20px;">

        <div class="sort">

            <span style="margin-right: 10px;">SORT</span>
            <span onclick="sortByName();"  class="sort_item">NAME</span>
            <span onclick="sortByPrice();" class="sort_item">PRICE</span>
            <span class="sort_item" ><input id="searchTextVariant" onkeyup="page=1;printProduct({});" type="text" placeholder="Search"></span>
            <span class="sort_item"><button onclick="checkedAll();" class="btn btn-default">Check Filtered</button>
                <button onclick="unCheckedFiltered();" class="btn btn-default">UnCheck Filtered</button>
                <button onclick="unCheckedAll();" class="btn btn-default">UnCheck All</button> </span>
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
//            pr($arr);
            $num = 0;
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
<!--                            <a title="--><?//=$obj->BaseArticleNameENG;?><!--" href="--><?//=_SPPATH;?><!--pr/p/--><?//=$obj->VariantID;?><!--/--><?//=urlencode($obj->BaseArticleNameENG);?><!--">-->
                                <img  src="<?=$imgurl;?>" >
<!--                            </a>-->
                        </div>

                        <div class="product_list_item_text">
                            <div class="name">
                                <input class="variant_check" type="checkbox" id="check_<?=$obj->VariantID;?>" onchange="checkMe(this,'<?=$obj->VariantID;?>');">
                                ID . <?=$obj->VariantID;?> <br>
                                <?=$obj->BaseArticleNameENG;?>

                            </div>
                            <div class="item_price"><?=$obj->TaggingLevel3ID;?></div>
                            <div class="item_price">IDR <?=idr($obj->SellingPrice);?></div>

                        </div>
                    </div>
                </div>
                <?
//                echo $key;

                if($num%3 == 2)echo "<div class='clearfix'></div><hr class='dotted'/>";
                if($num>10)break;
                $num++;
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



            $(document).ready(function(){
                $( "#product_data img" )
                    .error(function() {
                        $( this ).attr( "src", "<?=$pc->noimage;?>" );
                    });
                homes.reverse();

                printProduct({});
            });

            var homesWithKey = {};
            var homes_attr;

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

            homes_attr = el['VariantID'];
            homesWithKey[homes_attr] = el;
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
                padding-left: 10px;
                padding-right: 10px;
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