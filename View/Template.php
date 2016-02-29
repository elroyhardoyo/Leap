<?php
namespace Leap\View;

    /*
     * LEAP OOP PHP
     * Each line should be prefixed with  *
     */

/**
 * Description of Template
 *
 * @author User
 */
class Template {
    //6.12.2014 roy ubah protected menjadi public
    public $domainMenu = array ();
    public $adminMenu  = array (); // dipake
    public $charset           = "utf-8"; // dipake
    public $title; // dipake
    public $metakey; // dipake
    public $metades; // dipake
    public $content           = array (); // dipake
    public $onload; // dipake
    public $headText          = array (); // dipake
    public $headPhpFiles      = array (); // dipake
    public $bodyLastText      = array ();
    public $bodyPhpFilesLast  = array ();
    public $breadcrumbs       = array ();
    public $bodyPhpFilesFirst = array ();
    public $bodyFirstText     = array ();
    public $admin             = 0;
    public $init;
    public $namaFileTemplate  = "skeleton";
    public $WebSetting;

    function __construct ($WebSetting)
    {
        $this->WebSetting = $WebSetting;
    }

    /*
    * Print to template
    */
    public function printHTML ($str)
    {
        $location = $this->namaFileTemplate;

        //getContent
        $this->content[] = $str;
        $content = implode('', $this->content);
        //page title
        $title = $this->getTitle();
        //get metakey
        $metaKey = $this->getMetaKey();
        //get metakey
        $metaDescription = $this->getMetaDesc();
        //getHead

        //onLoad
        $onLoad = $this->onload;

        //getBodyfirst dan last didalam template aja, kalau perlu

        $exp = explode("/", $_GET["url"]);
        $class = $exp[0];

        if (!@include _THEMEPATH . "/{$location}--" . $class . ".php") {
            include _THEMEPATH . "/{$location}.php";
        }

    }

    /*
     * namaFileTemplate
     */

    function getTitle ()
    {
        if ($this->title == "") {
            return $this->WebSetting["title"];
        } else {
            return $this->title;
        }
    }

    function setTitle ($title)
    {
        $this->title = $title;
    }
    /*
     *              HEAD
     */
    /*
     * Title
     */

    function getMetaKey ()
    {
        if ($this->metakey == "") {
            return "<meta name=\"Keywords\" content=\"" . $this->WebSetting["metakey"] . "\" />";
        } else {
            return "<meta name=\"Keywords\" content=\"" . $this->metakey . "\" />";
        }
    }

    function setMetaKey ($metakey)
    {
        $this->metakey = $metakey;
    }

    /*
     * Meta Key
     */

    function getMetaDesc ()
    {
        $metades = $this->metades;
        if ($this->metades == "") {
            $metades = $this->WebSetting["metadescription"];
        }

        return "<meta name=\"Description\" content=\"" . $metades . "\" />
    <meta http-equiv=\"pragma\" content=\"no-cache\" />
    <meta http-equiv=\"cache-control\" content=\"no-cache\" />";
    }

    function getNamaFileTemplate ()
    {
        return $this->namaFileTemplate;
    }

    /*
     * Meta Des
     */

    function setNamaFileTemplate ($skeleton)
    {
        $this->namaFileTemplate = $skeleton;
    }

    function setMetaDesc ($metades)
    {
        $this->metades = $metades;
    }

    /*
     *  Head Files
     */

    function printHead ()
    {
        foreach ($this->headPhpFiles as $j) {
            @include $j;
        }
        foreach ($this->headText as $hd) {
            echo $hd;
        }
    }

    /*
     * Add files to <head>
     */
    function addFilesToHead ($file)
    {
        $this->headPhpFiles[] = $file;
    }

    /*
     * @return array of path to files
     */
    function getFilesHead ()
    {
        return $this->headPhpFiles;
    }

    /*
     * Add text to <head>
     */
    function addTextToHead ($text)
    {
        $this->headText[] = $text;
    }

    /*
     *  @return string 
     */
    function getTextHead ()
    {
        return $this->headText;
    }

    function getHeadfiles ()
    {
        
        foreach ($this->headPhpFiles as $j) {
            @include $j;
        }
        
        foreach ($this->headText as $hd) {
            //echo htmlentities($hd)."<br><br>";
            echo $hd;
        }
    }
    /*
     * add Text before close </body>
     */
    function addTextBeforeCloseBody($text){
        $this->bodyLastText[] = $text;
    }
    function addFilesBeforeCloseBody ($file)
    {
        $this->bodyPhpFilesLast[] = $file;
    }
    /*
     * load last text
     */
    function getLastBodyFiles ()
    {
        
        foreach ($this->bodyPhpFilesLast as $j) {
            @include $j;
        }
        
        foreach ($this->bodyLastText as $hd) {
            //echo htmlentities($hd)."<br><br>";
            echo $hd;
        }
    } 
    /*
     * print path2js
     */
    public function printPaths2JS(){
        ?>
<script type="text/javascript">
    var _sppath = '<?=_SPPATH;?>';
    var _themepath = '<?=_THEMEPATH;?>';
    var _photopath = '<?=_PHOTOPATH;?>';
    var _photourl = '<?=_PHOTOURL;?>';
</script>    
        <?
    }
}
