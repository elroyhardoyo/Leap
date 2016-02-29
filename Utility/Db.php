<?php
namespace Leap\Utility;

    /*
     * LEAP OOP PHP
     * Each line should be prefixed with  *
     */

/**
 * Description of Db
 *
 * @author User
 */
class Db {
    //abstract variable
    protected $serverpath;
    protected $db_username;
    protected $db_password;
    protected $db_name;
    protected $db_prefix;
    protected $myConnection;

    //constructor methods
    public function __construct ($DbSetting)
    {

        $this->serverpath = $DbSetting['serverpath'];
        $this->db_username = $DbSetting['db_username'];
        $this->db_password = $DbSetting['db_password'];
        $this->db_name = $DbSetting['db_name'];
        $this->db_prefix = $DbSetting['db_prefix'];
        $this->connect();
    }
    public function getServerPath(){
        return $this->serverpath;
    }
    public function getUsername(){
        return $this->db_username;
    }
    public function getPassword(){
        return $this->db_password;
    }
    public function getDBName(){
        return $this->db_name;
    }
    /*
     *  Connect to Server
     *  TODO : throw exception
     */
    protected function connect ()
    {
        //print_r(PDO::getAvailableDrivers());


        $mysql = mysql_connect($this->serverpath, $this->db_username, $this->db_password);
        $this->myConnection = $mysql;
        if (!$mysql) {
            echo "Cannot Connect to Database";
        }
        $db = mysql_select_db($this->db_name, $mysql);
        if (!$db) {
            echo "Cannot Select Database";
        }
    }

    /*
     * Only for Insert Query, return the insert ID
     */
    function qid ($query)
    {
        mysql_query($query, $this->myConnection);
        $id = mysql_insert_id();

        return $id;
    }

    /*
     * Query Method
     * return 0 for Insert, Update, 1 for SELECT, 2 for SELECT Multiple Entries
     */
    function query ($query, $return = 0, $savelog = 0)
    {
        $result = mysql_query($query, $this->myConnection);

        if ($return > 1) {
            $arr = array ();
            while ($res = mysql_fetch_object($result)) {
                $arr[] = $res;
            }

            return $arr;
        } elseif ($return) {
            return mysql_fetch_object($result);
        } else {
            return $result;
        }
    }
}
