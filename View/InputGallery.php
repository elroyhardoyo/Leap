<?php
namespace Leap\View;
use Leap\View\InputFileMultiple;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InputFileMultiple
 *
 * @author Elroy Hardoyo<elroy.hardoyo@leap-systems.com>
 */
class InputGallery extends InputFileMultiple{
   
    public function p(){
        $t = time() + rand(0,1000);
        $startTID = rand(0,10);
        $this->id = $this->id."_".$t;
        // get asli
        $text = '';
        $exp = explode(",",trim(rtrim($this->value)));
        $arrNames = array();
        $sudah = array();
        foreach ($exp as $fil){
            if(in_array($fil, $sudah))
                                continue;
            $sudah[] = $fil;
            $fil=trim(rtrim($fil));
           // echo $fil."<br>";
            if($fil=="")continue;
            $exp2 = explode(".",$fil);
            $if = new \InputFileModel();
           // echo $exp2[0]."<br>";
            $if->getByID($exp2[0]);
            $fil2 = explode(".",$fil);
            $fil3 = $fil2[0];
            $arrNames[] = $if;
          //  $text .= "<div id='file_".$exp2[0]."_{$t}' class='mlt_item'>".$if->file_url." <i onclick=\"deleteFromList_{$t}('".$fil."');\" class='glyphicon glyphicon-remove'></i></div>";
            $text .= "<div id='file_".$exp2[0]."_{$t}' class='mlt_item_gal'><div id='gal_$fil3' class='foto100gal'><span class='helper'></span><img ondblclick='makeasmainpic_".$t."(\"$fil\");' src='"._SPPATH.$if->upload_url.$if->file_filename."'></div>".$if->file_url." <i onclick=\"deleteFromList_{$t}('".$if->file_filename."');\" class='glyphicon glyphicon-remove'></i></div>";
                        
        }
        ?>
<input type="hidden" name="<?=$this->name;?>"  value="<?=$this->value;?>" id="<?=$this->id;?>" class="form-control">
<div id="multfile_<?=$startTID;?>_<?=$t;?>" ><?=$text;?></div>
<div id="hidupload_<?=$t;?>" style="padding-top: 10px;clear:both;">
    <input class="form-control" type="file" id="uploadfolder_<?=$t;?>" name="upl_<?=$this->name;?>" value="<?=$this->value;?>"  multiple  />
</div>
<small>double click on the thumbnail to make it a mainpic</small>
<style>
    
.helper {
    display: inline-block;
    height: 100%;
    vertical-align: middle;
}

    .mlt_item_gal{
        float:left;
        width: 100px;
        
    }
    .foto100gal{
        mwidth: 100px;
        height: 100px;
        overflow: hidden;
        border: 1px solid #efefef;
        white-space: nowrap;
        text-align: center; 
        margin: 1em 0;
    }
    .foto100gal img{
        max-width: 100px;
        vertical-align: middle;
        cursor: pointer;
    }
    .gal_mainpic{
        border:1px solid red;
    }
</style>

<script>
// Variable to store your files
var files;
$(document).ready(function(){
   setMainPic_<?=$t;?>(); 
}); 
// Add events
$('#hidupload_<?=$t;?> input[type=file]').on('change', uploadFiles_<?=$t;?>);

//make as mainpic
/*
 * fungsi mainpic 
 * dipakai dengan cara memakai dua kali untuk di fieldnya
 */
function makeasmainpic_<?=$t;?>(id){
    /*
     * getmain pic first, cari yang dobel
     * abis itu hapus, dan tambahkan di depan
     */
    var dulunya2 = $('#<?=$this->id;?>').val();
    var arr =dulunya2.split(',');
    var adalahthumb;
    console.log(arr);
    arr = jQuery.unique( arr );
    //hilangkan thumb yang lama
    /*for(var key in arr){
       var nex = key;
       console.log("pos : "+pos+" |  key : "+key);
       var pos = jQuery.inArray( arr[key], arr, nex+1 );
       
       if(pos !== -1){
           adalahthumb = arr[key];
           console.log(pos);
           arr.splice(pos,1);
        }
    }*/
    arr.push(id);
    mainpic_inputgallery = id;
    $('#<?=$this->id;?>').val(arr.join());
    console.log(arr);
    setMainPic_<?=$t;?>();
}
var mainpic_inputgallery;
function setMainPic_<?=$t;?>(){
    var dulunya2 = $('#<?=$this->id;?>').val();
    var arr =dulunya2.split(',');
    //var arr = [9, 9, 111, 2, 3, 4, 4, 5, 7];
    var sorted_arr = arr.sort(); // You can define the comparing function here. 
                                 // JS by default uses a crappy string compare.
    var results = [];
    var pilihan;
    
    for (var i = 0; i < arr.length; i++) {
        var xxx = arr[i].split('.');
        $('#gal_'+xxx[0]).removeClass('gal_mainpic');
    }
    
    for (var i = 0; i < arr.length - 1; i++) {
        var xxx = sorted_arr[i].split('.');
        if (sorted_arr[i + 1] == sorted_arr[i]) {
            results.push(sorted_arr[i]);
            pilihan = xxx[0];
        }        
    }    
    $('#gal_'+pilihan).addClass('gal_mainpic');
    //alert(results);
}

// Grab the files and set them to our variable
function deleteFromList_<?=$t;?>(id)
{
  //alert('in'+id);
  var bagi = id.split(".");
  var id_asli = bagi[0];
  var id_ext = bagi[1];
  //kalau yang dihapus mainpic
  if(id == mainpic_inputgallery){
        var dulunya2 = $('#<?=$this->id;?>').val();
        var arr =dulunya2.split(',');
        var adalahthumb;
        console.log(arr);
        arr = jQuery.unique( arr );
        $('#<?=$this->id;?>').val(arr.join());
  }
  if(confirm('<?=Lang::t('Are You Sure? This will delete the file');?>')){
      var str = $('#<?=$this->id;?>').val();
      var res = str.split(",");
      for(var key in res){
          if(res[key] == id){
              res.splice(key,1);
          }
      }
      $('#<?=$this->id;?>').val(res.join());
      $('#file_'+id_asli+'_<?=$t;?>').hide();
      //delete on server .. later
      $.post("<?=_LANGPATH;?>UploaderMultiples/del",{id:id});
  }
}  
// Catch the form submit and upload the files
function uploadFiles_<?=$t;?>(event)
{
    files = event.target.files;
    event.stopPropagation(); // Stop stuff happening
    event.preventDefault(); // Totally stop stuff happening
 
    // START A LOADING SPINNER HERE
 
    // Create a formdata object and add the files
	var data = new FormData();
	$.each(files, function(key, value)
	{
		data.append(key, value);
	});
    
    $.ajax({
        url: '<?=_LANGPATH;?>UploaderMultiples/uploadfiles_gallery?t=<?=$t;?>&files=1',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function(data, textStatus, jqXHR)
        {
                console.log(data);
        	if(typeof data.error === 'undefined')
        	{
        		// Success so call function to process the form
        		//submitForm(event, data);
                        console.log("success");
                        //loadfolder_<?=$t;?>(activeTID_<?=$t;?>);
                       /* var fs = data.files;
                        var fstext = fs.split();
                        $('#<?=$this->id;?>').val(fstext);
                        */
                           var dulunya = $('#<?=$this->id;?>').val();
                           if(dulunya != "")
                               $('#<?=$this->id;?>').val(dulunya+","+data.ftext);
                           else
                               $('#<?=$this->id;?>').val(data.ftext);
                           
                           var dulunya2 = $('#multfile_<?=$startTID;?>_<?=$t;?>').html();
                           if(dulunya2 != "")
                               $('#multfile_<?=$startTID;?>_<?=$t;?>').html(dulunya2+" "+data.ftextAsli);
                           else
                               $('#multfile_<?=$startTID;?>_<?=$t;?>').html(data.ftextAsli);
                           //$('#<?=$this->id;?>').val($('#multfile_<?=$startTID;?>_<?=$t;?>').text());
        	}
        	else
        	{
        		// Handle errors here
        		console.log('ERRORS: ' + data.error);
        	}
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
        	// Handle errors here
        	console.log('ERRORS: ' + textStatus);
        	// STOP LOADING SPINNER
        }
    });
}
</script>            
        <?
    }
    
    public static function printMainPic($src,$id){

//        echo "halo";
//        echo $src;
        if($src == ""){
            $sel = "";
        }else{

            //explode
            $exp = explode(",",$src);

            $arr = array();
            foreach($exp as $e){
                $arr[$e]++;
            }

            $sels = array_keys($arr, max($arr));
            $sel = $sels[0];
//            pr($arr);
//            asort($arr);
//            pr($arr);
//            $sel = array_pop($arr);
//
//            pr($sel);
        }
        $foto = self::getFoto($sel);
        return self::makeFoto($foto,$id);

    }
}
