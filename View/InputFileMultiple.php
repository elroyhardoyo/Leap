<?php
namespace Leap\View;
use Leap\View\InputFile;
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
class InputFileMultiple extends InputFile{
   
    public function p(){
        $t = time() + rand(0,1000);
        $startTID = rand(0,10);
        $this->id = $this->id."_".$t;
        // get asli
        $text = '';
        $exp = explode(",",trim(rtrim($this->value)));
        $arrNames = array();
        foreach ($exp as $fil){
           // echo $fil."<br>";
            if($fil=="")continue;
            $exp2 = explode(".",$fil);
            $if = new \InputFileModel();
           // echo $exp2[0]."<br>";
            $if->getByID($exp2[0]);
            $arrNames[] = $if;
            $text .= "<div id='file_".$exp2[0]."_{$t}' class='mlt_item'>".$if->file_url." <i onclick=\"deleteFromList_{$t}('".$fil."');\" class='glyphicon glyphicon-remove'></i></div>";
        }
        ?>
<input type="hidden" name="<?=$this->name;?>" value="<?=$this->value;?>" id="<?=$this->id;?>" class="form-control">
<div id="multfile_<?=$startTID;?>_<?=$t;?>" ><?=$text;?></div>
<div id="hidupload_<?=$t;?>" style="padding-top: 10px;">
    <input class="form-control" type="file" id="uploadfolder_<?=$t;?>" name="upl_<?=$this->name;?>" value="<?=$this->value;?>"  multiple  />
</div>
<script>
// Variable to store your files
var files;
 
// Add events
$('#hidupload_<?=$t;?> input[type=file]').on('change', uploadFiles_<?=$t;?>);
 
// Grab the files and set them to our variable
function deleteFromList_<?=$t;?>(id)
{
  //alert('in'+id);
  var bagi = id.split(".");
  var id_asli = bagi[0];
  var id_ext = bagi[1];
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
      $.post("<?=_SPPATH;?>UploaderMultiples/del",{id:id});
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
        url: '<?=_SPPATH;?>UploaderMultiples/uploadfiles?t=<?=$t;?>&files=1',
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
    
    
}
