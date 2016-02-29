<?php
namespace Leap\Utility;


use Leap\View\Lang;

/*
 * LEAP OOP PHP 
 * Each line should be prefixed with  * 
 */

/**
 * Description of Crud
 * Kelas untuk semua yang perlu database, table , sort, dll , create read update delete,
 * selainnya bisa pakai Utility yg perlu fungsi, Container yang perlu struktur nya e.g. for games
 *
 * @author User
 */
class Crud {
    var $table_name;
    var $main_id;
    var $default_read_coloms;
    //allowed colom in database
    var $coloumlist;

    /*
     * CRUD READ includes read, getByID, fill, save, export As Excel
     */
    public function read ($perpage = 20)
    {
        global $db;
        $page = (isset($_GET['page']) ? addslashes($_GET['page']) : 1);
        $all = 0;
        if ($page == "all") {
            $page = 1;
            $all = 1;
        }
        $begin = ($page - 1) * $perpage;
        $limit = $perpage;
        // get columnlist filter
        $clms = (isset($_GET['clms']) ? addslashes($_GET['clms']) : '');
        if ($clms == "") {
            $clms = $this->default_read_coloms;
        }
        $clmsPlaceholder = $clms;
        $clms = $this->main_id . "," . $clms; // add main id to the filter
        $arrClms = explode(",", $clms);
        // searchh
        $searchdb = " ";
        $search = (isset($_GET['search']) ? addslashes($_GET['search']) : 0);

        $w = (isset($_GET['word']) ? addslashes($_GET['word']) : '');
        if ($search == 1 && $w != '') {
            $searchdb .= " WHERE ";
            foreach ($arrClms as $col) {
                $imp[] = " $col LIKE '%{$w}%' ";

            }
            $searchdb .= implode(" OR ", $imp);
            $searchdb .= " ";

        }
        // get placeholder
        $placeholder = "";
        $p = array ();
        foreach ($arrClms as $col) {
            $p[] = Lang::t($col);
        }
        $placeholder = implode(",", $p);


        $t = time();
        $q = "SELECT count(*) as nr FROM {$this->table_name} $searchdb";
        $nr = $db->query($q, 1);
        //echo $q;echo "<br>";
        $sortdb = $this->main_id . " ASC";
        $sort = (isset($_GET['sort']) ? addslashes($_GET['sort']) : $sortdb);
        $sortdb = $sort;

        $beginlimit = "LIMIT $begin,$limit";
        if ($all) {
            $beginlimit = "";
            $perpage = $nr->nr;
        }
        $q = "SELECT $clms FROM {$this->table_name} $searchdb ORDER BY $sortdb $beginlimit";
        //echo $q;

        //create return array
        $return['arr'] = $db->query($q, 2);
        $return['totalpage'] = ceil($nr->nr / $perpage);
        $return['perpage'] = $perpage;
        $return['page'] = $page;
        $return['sort'] = $sort;
        $return['search'] = $placeholder;
        $return['search_keyword'] = $w;
        $return['search_triger'] = $search;
        $return['coloms'] = $clmsPlaceholder;
        $return['colomslist'] = $this->coloumlist;
        $return['main_id'] = $this->main_id;
        $return['classname'] = get_class($this);

        $export = (isset($_GET['export']) ? addslashes($_GET['export']) : 0);
        if ($export) {
            $this->exportIt($return);
        }

        return $return;
    }

    public function exportIt ($return)
    {
        $filename = $return['classname'] . "_" . date('Ymd') . ".xls";

        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        $flag = false;

        foreach ($return['arr'] as $key => $obj) {

            foreach ($obj as $name => $value) {
                echo Lang::t($name) . "\t";
            }
            break;
        }
        print("\n");
        foreach ($return['arr'] as $key => $obj) {

            foreach ($obj as $name => $value) {
                echo $value . "\t";
            }
            print("\n");
        }
        exit;
    }


    /*
     * CRUD createForm and save , fungsi pembantu columnlist
     */
    function createForm ()
    {
        $return = array ();
        //load data kalau ada id yang dikirim..
        $return['load'] = 0;
        $id = (isset($_GET['id']) ? addslashes($_GET['id']) : 0);
        if ($id) {
            $this->getByID($id);
            $return['load'] = 1;
        }
        $return['classname'] = get_class($this);
        $return['id'] = $this;
        $return ['colomlist'] = $this->getColumnlist();

        return $return;
    }

}
