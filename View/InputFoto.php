<?php
namespace Leap\View;
    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

/**
 * Description of InputFoto
 *
 * @author User
 */
class InputFoto extends Html {
    public function __construct ($id, $name, $value, $classname = 'form-control')
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
    }

    public static function getAndMakeFoto ($src, $id)
    {
        $src2 = self::getFoto($src);

        return self::makeFoto($src2, $id);
    }

    public static function getFoto ($src)
    {
        if($src == "") return "images/noimage.jpg";

        $fname = basename($src);
        $ext = end((explode(".", $fname)));
        //echo $src;
        //echo $ext;
        /*if($ext == "gif" || $ext == "png"){
            $if = new \InputFileModel();
            $src2 = $if->upload_url;
            return $src2.$fname;
        }*/
        
        $name = (isset($src) ? _PHOTOURL . $src :  "images/noimage.jpg");
        if ($name == _PHOTOURL . "foto") {
            $name =  "images/noimage.jpg";
        }

        return $name;
    }

    public static function makeFoto ($src, $id)
    {
        $str = '
    <div class="foto100">
    <img src="' ._SPPATH. $src . '" id="' . $id . '" onload="OnImageLoad(event,100);">
    </div>';

        return $str;
    }

    public function p ()
    {
        $t = time();
        $src = self::getFoto($this->value);
        $id = "holder_foto_old_" . $t;
        $if = new \InputFileModel();
        ?>
<div id="loader<?=$id;?>" style="position: absolute; margin-top: 30px; margin-left: 40px; display: none;" class="inputfotoloader">Loading...</div>
        <span id="<?= $this->id; ?>_<?= $t; ?>">
    <?
   
    echo self::makeFoto($src, $id);
    ?>
    <input type="file" name="file_<?= $this->name; ?>" id="file_<?= $this->id; ?>" value="<?= $this->value; ?>">
    <input type="hidden" name="<?= $this->name; ?>" id="<?= $this->id; ?>" value="<?= $this->value; ?>">
    
</span>
<style>
.inputfotoloader,
.inputfotoloader:before,
.inputfotoloader:after {
  background: #888;
  -webkit-animation: load1 1s infinite ease-in-out;
  animation: load1 1s infinite ease-in-out;
  width: 1em;
  height: 4em;
}
.inputfotoloader:before,
.inputfotoloader:after {
  position: absolute;
  top: 0;
  content: '';
}
.inputfotoloader:before {
  left: -1.5em;
}
.inputfotoloader {
  text-indent: -9999em;
  margin: 8em auto;
  position: relative;
  font-size: 11px;
  -webkit-animation-delay: -0.16s;
  animation-delay: -0.16s;
}
.inputfotoloader:after {
  left: 1.5em;
  -webkit-animation-delay: -0.32s;
  animation-delay: -0.32s;
}
@-webkit-keyframes load1 {
  0%,
  80%,
  100% {
    box-shadow: 0 0 #888;
    height: 4em;
  }
  40% {
    box-shadow: 0 -2em #888;
    height: 5em;
  }
}
@keyframes load1 {
  0%,
  80%,
  100% {
    box-shadow: 0 0 #888;
    height: 4em;
  }
  40% {
    box-shadow: 0 -2em #888;
    height: 5em;
  }
}

</style>
        <script type="text/javascript">
            var fileTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];  //acceptable file types
            
            document.querySelector('#<?=$this->id;?>_<?=$t;?> input[type=file]').addEventListener('change', function (event) {
                // Read files
                
                var files = event.target.files;
                //alert('in');
                //console.log(files);
                // Iterate through files
                for (var i = 0; i < files.length; i++) {
                    // alert('in2');
                    // Ensure it's an image
                    if (files[i].type.match(/image.*/)) {
                        var reader = new FileReader();
                        //alert('in3');
                        var extension = files[i].name.split('.').pop().toLowerCase();
                        var isSuccess = fileTypes.indexOf(extension) > -1; 
                        console.log(extension);
                        console.log(reader);
                        console.log(isSuccess);
                         if (isSuccess) { 
                        
                        //check size
                        if(extension == "png" || extension == "gif"){
                            
                            //kalau png atau gif, tidak di resize, krn we want to preserve the transparency and animations
                            if (typeof FileReader !== "undefined") {
                                var sizess = files[i].size;
                                // check file size
                                console.log(sizess);

                            }
                            inputFotoFiles_<?=$t;?>(event);
                               
                        }
                        else{
                            reader.onload = function (readerEvent) {
                            // alert('in3b');
                            var image = new Image();
                            //alert('in4');
                            //console.log(image);
                            image.onload = function (imageEvent) {
                                // Resize image
                                var canvas = document.createElement('canvas'),
                                    max_size = 1200,
                                    width = image.width,
                                    height = image.height;
                                if (width > height) {
                                    if (width > max_size) {
                                        height *= max_size / width;
                                        width = max_size;
                                    }
                                } else {
                                    if (height > max_size) {
                                        width *= max_size / height;
                                        height = max_size;
                                    }
                                }
                                canvas.width = width;
                                canvas.height = height;
                                canvas.getContext('2d').drawImage(image, 0, 0, width, height);

                                //alert('in');

                                // Upload image
                                var xhr = new XMLHttpRequest();
                                if (xhr.upload) {

                                    // Update progress
                                    xhr.upload.addEventListener('progress', function (event) {
                                        var percent = parseInt(event.loaded / event.total * 100);
                                        //progressElement.style.width = percent+'%';
                                        $("#loader<?=$id;?>").show();
                                        
                                    }, false);

                                    // File uploaded / failed
                                    xhr.onreadystatechange = function (event) {
                                        if (xhr.readyState == 4) {
                                            if (xhr.status == 200) {
                                                $("#loader<?=$id;?>").hide();
                                                //imageElement.classList.remove('uploading');
                                                //imageElement.classList.add('uploaded');
                                                var imageHtml = document.getElementById("holder_foto_old_<?=$t;?>");

                                                imageHtml.removeAttribute("style");
                                                imageHtml.removeAttribute("width");
                                                imageHtml.removeAttribute("height");
                                                imageHtml.src = '<?=_SPPATH;?>'+'<?=_PHOTOURL;?>' + xhr.responseText;
                                                $('#<?=$this->id;?>_<?=$t;?> #<?=$this->id;?>').val(xhr.responseText);
                                                //imageHtml.onload(function(){resizeAndJustify("holder_foto_old_<?=$t;?>",100);});
                                                //document.getElementById('progress_fotodatamurid_<?=$t;?>').style.display = 'none';
                                                //$('loadingtop').fade();
                                                //$('oktop').fade().fade();
                                                console.log('Image uploaded: ' + xhr.responseText);
                                                /*$('close_button_pop1').onClick(function(){
                                                 $('content_utama').load('
                                                <?=_SPPATH;?>datamurid/harmonica_widget?aj=1',{spinner:"loadingtop"});
                                                 $('pop1').hide();
                                                 });*/

                                            } else {
                                                //imageElement.parentNode.removeChild(imageElement);
                                            }
                                        }
                                    }

                                    // Start upload
                                    xhr.open('post', '<?=_SPPATH;?>uploader/uploadres?adafile=<?= $this->value; ?>&ext='+extension, true);
                                    xhr.send(canvas.toDataURL('image/'+extension));

                                }
                            }
                            image.src = readerEvent.target.result;

                        }
                        reader.readAsDataURL(files[i]);
                        }//else png or gif
                        
                        
                        
                        }//success
                        else{
                            alert("<?=Lang::t('Please only upload image files');?>");
                            console.log("err type");
                        }
                    }else{
                        alert("<?=Lang::t('Please only upload image files');?>");
                        console.log("err type");
                    }
                }
                
            });
            
            // Catch the form submit and upload the files
        function inputFotoFiles_<?=$t;?>(event) {
                files = event.target.files;
                event.stopPropagation(); // Stop stuff happening
                event.preventDefault(); // Totally stop stuff happening

                // START A LOADING SPINNER HERE

                // Create a formdata object and add the files
                var data = new FormData();
                $.each(files, function (key, value) {
                        data.append(key, value);
                });
                //console.log(data);
                $.ajax({
                        url: '<?=_SPPATH;?>Uploader/uploadres_ext?t=<?=$t;?>&files=1&adafile=<?= $this->value; ?>',
                        type: 'POST',
                        data: data,
                        cache: false,
                        dataType: 'json',
                        processData: false, // Don't process the files
                        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                        success: function (data, textStatus, jqXHR) {
                                // console.log(data);

                                if (data.bool) {
                                        var imageHtml = document.getElementById("holder_foto_old_<?=$t;?>");

                                        imageHtml.removeAttribute("style");
                                        imageHtml.removeAttribute("width");
                                        imageHtml.removeAttribute("height");
                                        imageHtml.src = '<?=_SPPATH;?>'+'<?=_PHOTOURL;?>' + data.filename;
                                        $('#<?=$this->id;?>_<?=$t;?> #<?=$this->id;?>').val(data.filename);
                                        imageHtml.src = '<?=_SPPATH;?><?=_PHOTOURL;?>' + data.filename;
                                        console.log('Image uploaded: ' + data.filename);
                                               
                                }
                                else {
                                        // Handle errors here
                                        console.log('ERRORS: ' + data.error);
                                        $("#file_repeat_<?= $this->id; ?>_<?= $t; ?>").show();
                                }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
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
