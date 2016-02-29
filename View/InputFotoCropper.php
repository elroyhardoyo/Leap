<?php
/**
 * Created by PhpStorm.
 * User: elroy
 * Date: 11/2/15
 * Time: 6:36 PM
 */

namespace Leap\View;


class InputFotoCropper extends InputFoto{

    public $ratio;

    public function __construct ($ratio, $id, $name, $value, $classname = 'form-control',$hidden = 0)
    {
        $this->id = $id;
        $this->name = $name;
//        $this->value = $value;
        $this->hidden = $hidden;
        $this->value = htmlentities(stripslashes($value));
        $this->ratio = $ratio;
    }

    public function p ()
    {
        $t = time();
        $src = self::getFoto($this->value);
        $id = $this->id."holder_foto_old_" . $t;
        $if = new \InputFileModel();

        $exps = explode(":",$this->ratio);
        $ratio_width = (int) $exps[0];
        $ratio_height = (int) $exps[1];
        ?>

        <i><?=Lang::t("Proportion of the picture must be");?> <?=$this->ratio;?></i>
        <div id="loader<?=$id;?>" style="position: absolute; margin-top: 30px; margin-left: 40px; display: none;" class="inputfotoloader">Loading...</div>
        <span id="<?= $this->id; ?>_<?= $t; ?>">
            <table >
                <tr>
                    <td><?

                        echo self::makeFoto($src, $id);
                        ?>

                        <b id="ratioOK" style="display: none;"><br><?=Lang::t('Image Ratio OK');?></b>
                    </td>
                    </tr>
                <tr>
                    <td>
                        <input class="<?=$this->classname;?>" type="file" name="file_<?= $this->name; ?>" id="file_<?= $this->id; ?>" value="<?= $this->value; ?>">

                        <?
                        if($this->hidden)$tipe = "hidden";
                        else $tipe = "text";
                        ?>
                        <input type="<?=$tipe;?>" name="<?= $this->name; ?>" id="<?= $this->id; ?>" value="<?= $this->value; ?>">
                    </td>
                </tr>
            </table>
        </span>
        <style>

            .cropper-example-3 {
                width: 100%;
            }

            .cropper-example-3 > img {
                max-width: 100%;
            }

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
            var ratio_width = <?=$ratio_width;?>;
            var ratio_height = <?=$ratio_height;?>;
            var isItOK = 0;
            var NeedToCroppedfileID = 0;

            document.querySelector('#<?=$this->id;?>_<?=$t;?> input[type=file]').addEventListener('change', function (event) {
                // Read files
                isItOK = 0;
                $("#ratioOK").hide();
                var files = event.target.files;
                //alert('in');
                //console.log(files);
                // Iterate through files
                for (var i = 0; i < files.length; i++) {

                    var mywidth = 0;
                    var myheight = 0;

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

                            //kalau png atau gif, tidak di resize, krn we want to preserve the transparency and animations
                            if (typeof FileReader !== "undefined") {
                                var sizess = files[i].size;
                                // check file size
                                console.log("sizes");
                                console.log(sizess);
                                var _URL = window.URL || window.webkitURL;
                                var file, img;
                                if ((file = this.files[i])) {
                                    img = new Image();
                                    img.onload = function () {
                                        console.log(this.width + " " + this.height);
                                        mywidth = this.width;
                                        myheight = this.height;

                                        if(ratio_width/ratio_height == mywidth/myheight){
                                            isItOK = 1;
                                            console.log("Ratio OKE");
                                            $("#ratioOK").show();
                                        }
                                    };
                                    img.src = _URL.createObjectURL(file);
                                }
                            }

                            inputFotoFiles_<?=$id;?>_<?=$t;?>(event);




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
            function inputFotoFiles_<?=$id;?>_<?=$t;?>(event) {
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
                    url: '<?=_LANGPATH;?>Uploader/uploadres_ext?t=<?=$t;?>&files=1&adafile=<?= $this->value; ?>',
                    type: 'POST',
                    data: data,
                    cache: false,
                    dataType: 'json',
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                    success: function (data, textStatus, jqXHR) {
                        //console.log(data);

                        if (data.bool) {
                            //console.log("#<?=$id;?>");
                            var imageHtml = $("#<?=$id;?>");
                            imageHtml.attr('src', '<?=_SPPATH;?>'+'<?=_PHOTOURL;?>' + data.filename);
                            /*imageHtml.removeAttribute("style");
                             imageHtml.removeAttribute("width");
                             imageHtml.removeAttribute("height");
                             imageHtml.src = '<?=_SPPATH;?>'+'<?=_PHOTOURL;?>' + data.filename;*/
                            $('#a_<?=$id;?>').attr("href",'<?=_SPPATH;?>'+'<?=_PHOTOURL;?>' + data.filename);
                            $('#<?=$this->id;?>_<?=$t;?> #<?=$this->id;?>').val(data.filename);
                            //imageHtml.src = '<?=_SPPATH;?><?=_PHOTOURL;?>' + data.filename;
                            console.log('Image uploaded: ' + data.filename);

                            $('.cropper-example-3 > img').attr("src",'<?=_SPPATH;?>'+'<?=_PHOTOURL;?>' + data.filename);

                            $('.cropper-example-3 > img').cropper("destroy");
                            $('.cropper-example-3 > img').cropper({
                                aspectRatio: <?=$ratio_width;?> / <?=$ratio_height;?>,
                                autoCropArea: 1,
                                crop: function(e) {
                                    // Output the result data for cropping image.
//                                    console.log(e.x);
//                                    console.log(e.y);
//                                    console.log(e.width);
//                                    console.log(e.height);
//                                    console.log(e.rotate);
//                                    console.log(e.scaleX);
//                                    console.log(e.scaleY);


                                }
                            });

                            if(!isItOK){
                                $(".cropTool").show();
                            }
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
        <button id="saveCropButton" style="display: none;" class="btn btn-default cropTool" onclick="ups(event);">Crop Picture</button>
        <script>
            $('.cropper-example-3 > img').cropper({
                aspectRatio: <?=$ratio_width;?> / <?=$ratio_height;?>,
                autoCropArea: 1,
                crop: function(e) {
//                    // Output the result data for cropping image.
//                    console.log(e.x);
//                    console.log(e.y);
//                    console.log(e.width);
//                    console.log(e.height);
//                    console.log(e.rotate);
//                    console.log(e.scaleX);
//                    console.log(e.scaleY);
//
//                    var cropBoxData = $('.cropper-example-3 > img').cropper('getCropBoxData');
//                    var canvasData = $('.cropper-example-3 > img').cropper('getCanvasData');
//
//                    $("#canvasdata").val(canvasData);
//                    $("#cropboxdata").val(cropBoxData);
//
//                    console.log(canvasData);
//                    console.log(cropBoxData);
                }
            });


            function ups(event){
                event.preventDefault();
                // Upload cropped image to server
                var canvas = $('.cropper-example-3 > img').cropper('getCroppedCanvas');

                var fullQuality = canvas.toDataURL("image/jpeg", 1.0);
                console.log(fullQuality);

//                $('.cropper-example-3 > img').cropper('getCroppedCanvas').toBlob(function (blob) {
                    var formData = new FormData();

                    var old_filename = $('#<?=$this->id;?>_<?=$t;?> #<?=$this->id;?>').val();

//                    alert(old_filename);

                    formData.append('croppedImage', fullQuality);

                    $.ajax('<?=_LANGPATH;?>UploaderCrop/upload?adafile=<?= $this->value; ?>&ext=jpg&fname='+old_filename, {
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            console.log(data);

                            var imageHtml = $("#<?=$id;?>");
                            imageHtml.attr('src', '<?=_SPPATH;?>'+'<?=_PHOTOURL;?>' + data+'?t='+$.now());

                            if(data != old_filename){

                                $('#a_<?=$id;?>').attr("href",'<?=_SPPATH;?>'+'<?=_PHOTOURL;?>' + data);
                                $('#<?=$this->id;?>_<?=$t;?> #<?=$this->id;?>').val(data);
                            }
                            console.log('Upload success');
//                            alert("Success");
                            asuccess("Image Cropped Successfully");
                        },
                        error: function () {
                            alert("Upload Failed");
                            console.log('Upload error');
                        }
                    });
//                });
            }

        </script>



    <?
    }

    public static function makeFoto ($src, $id)
    {
//        if($src == ""){
//            $url = _SPPATH."images/noimage.jpg";
//        }else{
//            $path =  $src;
//
//            if (file_exists($path)) {
//                $url = _SPPATH. $src;
//            } else {
//                $url = _SPPATH."images/noimage.jpg";
//            }
//        }
        $str = '
    <div class="foto100">
    <a id="a_' . $id . '" target="_blank" href="' .$src . '">
    <img  src="' . $src . '" id="' . $id . '" onload="OnImageLoad(event,100);">
    </a>
    </div>';
        $str .= '

    <div class="cropper-example-3 cropTool" style="display: none;">

    <img  src="' .$src . '" id="' . $id . '_cropper" >

    </div>';

        return $str;
    }
}