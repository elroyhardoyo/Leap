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
class InputFile extends Html {

	public $arrImgExt   = array ("jpg", "png", "gif", "jpeg", "bmp");
	public $arrVideoExt = array ("mp4", "mkv", "flv");

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
		$if = new \InputFileModel();
		$path = _SPPATH . $if->upload_url;
		// echo $src;
		$name = (isset($src) ? $path . $src : _SPPATH . "images/noimage.jpg");
		if ($name == $path . "foto") {
			$name = _SPPATH . "images/noimage.jpg";
		}

		return $name;
	}

	public static function makeFoto ($src, $id)
	{
		$str = '
    <div class="foto100">
    <img src="' . $src . '" id="' . $id . '" onload="OnImageLoad(event,100);">
    </div>';

		return $str;
	}

	public static function isImage ($filename)
	{
		$ip = new InputFile();
		$ext = end((explode(".", $filename)));

		return in_array($ext, $ip->arrImgExt);
	}

	public static function isVideo ($fileName)
	{
		$ip = new InputFile();
		$ext = end((explode(".", $filename)));

		return in_array($ext, $ip->arrVideoExt);
	}

	public static function generateThumbnail ($filename)
	{
		$if = new \InputFileModel();
		$path = _SPPATH . $if->upload_url;
		$video = '~/Documents/PHP' . $path . $filename;
		$outputName = InputFile::getThumbnailFilename($filename);
		$thumbnail = '~/Documents/PHP' . $path . $outputName;

		putenv("PATH=/usr/local/bin:/usr/bin:/bin");
		$command = 'ffmpeg -i ' . $video . ' -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg ' . $thumbnail . ' 2>&1';

		$result = shell_exec($command);
		if ($result) {
			return $outputName;
		}

		return null;
	}

	public static function getThumbnailFilename ($videoName)
	{
		if ($videoName) {
			// Strip out all the paths
			$arrName = explode("/", $videoName);
			$arrName = $arrName[count($arrName) - 1];

			// Strip out the file extension
			$arrName = explode(".", $arrName);

			// Take file's last name without the extension
			return $arrName[count($arrName) - 2] . '.png';
		}
	}

	public function p ()
	{
		$t = time()+rand(0,100);
		$if = new \InputFileModel();
		?>
		<div id="input_file_img_<?= $t; ?>"
		     <? if (!self::isImage($this->value) && !self::isVideo($this->value)){ ?>style="display: none;"<? } ?> >
			<?
			//if(self::isImage($this->value)){
			$src = InputFile::getFoto($this->value);
			//echo $src;
			//echo $this->value;
			$id = "holder_file_old_" . $t;
			//echo $id;
			echo InputFile::makeFoto($src, $id);
			//}
			?>
		</div>

    
<div class="input-group">
<input class="form-control" type="text" name="<?= $this->name; ?>" id="<?= $this->id; ?>_<?= $t; ?>" value="<?= $this->value; ?>">
      


        <div id="hidupload_<?= $t; ?>">
            <input class="form-control" type="file"
                       id="uploadfolder_<?= $t; ?>"
                       name="upload" />
                <i class="glyphicon glyphicon-ok"
                   id="file_ok_<?= $this->id; ?>_<?= $t; ?>"
                   style="display: none;"></i>
                <i class="glyphicon glyphicon-repeat"
                   id="file_repeat_<?= $this->id; ?>_<?= $t; ?>"
                   style="display: none;"></i>
        </div>
</div>
		<script type="text/javascript">

			// Variable to store your files
			var files;

			// Add events
			$('#hidupload_<?= $t; ?> input[type=file]').on('change', inputFiles_<?=$t;?>);

			// Grab the files and set them to our variable
			/*function prepareUpload(event)
			 {
			 files = event.target.files;
			 } */
			// Catch the form submit and upload the files
			function inputFiles_<?=$t;?>(event) {
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
					url: '<?=_SPPATH;?>Uploader/uploadfiles?t=<?=$t;?>&files=1&adafile=<?= $this->value; ?>',
					type: 'POST',
					data: data,
					cache: false,
					dataType: 'json',
					processData: false, // Don't process the files
					contentType: false, // Set content type to false as jQuery will tell the server its a query string request
					success: function (data, textStatus, jqXHR) {
						// console.log(data);

						if (data.bool) {
							// Success so call function to process the form
							//submitForm(event, data);
							console.log("success");
							// loadfolder_<?=$t;?>(activeTID_<?=$t;?>);
							$("#file_ok_<?= $this->id; ?>_<?= $t; ?>").show();
							//var slc = $('#uploadfolder_<?=$t;?>').val();
                                                        console.log(data.filename);
							$("#<?= $this->id; ?>_<?=$t;?>").val(data.filename);
                                                        console.log("yey "+$("#<?= $this->id; ?>_<?=$t;?>").val());
							if (data.isImage) {
								var imageHtml = document.getElementById("<?=$id;?>");
								imageHtml.removeAttribute("style");
								imageHtml.removeAttribute("width");
								imageHtml.removeAttribute("height");
								imageHtml.src = '<?=_SPPATH;?><?=$if->upload_url;?>' + data.filename;
								$("#input_file_img_<?=$t;?>").show();
							}else if (data.isVideo) {
                                                            generateThumbnail(data.filename);
                                                        }else {
                                                            console.log("not video");
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

			function generateThumbnail(filename) {
				$.get("<?=_SPPATH;?>Video/generateThumbnail?filename=" + filename,
					function (dataReturn) {
						console.log(dataReturn);

						var imageHtml = document.getElementById("<?=$id;?>");
						imageHtml.removeAttribute("style");
						imageHtml.removeAttribute("width");
						imageHtml.removeAttribute("height");
						imageHtml.src = '<?=_SPPATH;?><?=$if->upload_url;?>' + dataReturn;
						$("#input_file_img_<?=$t;?>").show();
						$("#video_upload_preview").val(dataReturn);
					});
			}
		</script>
	<?
	}
}
