<?php

 /**
     * Regelt den Zugriff auf die jeweilige Datenbank über PDO
     *
     *
     * @author      Albin Blaschka <albin@albinblaschka.info>
     * @copyright   Albin Blaschka, February 2008
     * @version     "Consolidation"
     * @package     FoDok 2
     */


class DB_Connect {

      private $dbh;
      private static $instances = 0;
      const ONLY_ONE_INSTANCE_ALLOWED = 5000;
      const NOT_IMPLEMENTED = 5001;
      const UNDEFINED = 9999;

      public function __construct($conf) {
            // if(DB_Connect::$instances == 0){
                  switch ($_SESSION['fdksettings'][$conf]['driver']) {
                      case 'pgsql':
                           $this->dbh = new PDO("pgsql:host=".$_SESSION['fdksettings'][$conf]['server'].
                                                ";dbname=".$_SESSION['fdksettings'][$conf]['db'].
                                                ";user=".$_SESSION['fdksettings'][$conf]['usn'].
                                                ";password=".$_SESSION['fdksettings'][$conf]['pwd'].
                                                ";port=".$_SESSION['fdksettings'][$conf]['port']);
                           $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                           DB_Connect::$instances = 1;
                      break;
                      case 'mysql':
                           #throw new DB_Exception('Not implemented yet!',self::NOT_IMPLEMENTED);
                           # $dbh = new PDO('mysql:host=localhost;dbname=test', $user, $pass);
                           $this->dbh = new PDO('mysql:host='.$_SESSION['fdksettings'][$conf]['server'].';dbname='.$_SESSION['fdksettings'][$conf]['db'],
                                          $_SESSION['fdksettings'][$conf]['usn'], $_SESSION['fdksettings'][$conf]['pwd']);
                           $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                           DB_Connect::$instances = 1;
                      break;
                      default:
                           throw new DB_Exception('Error in Configuration or something strange happended...',self::UNDEFINED);
                  }
            //}else{
            //    throw new DB_Exception('Only one connection allowed',self::ONLY_ONE_INSTANCE_ALLOWED);
            //}
      }

      public function getDBConnection(){
            return $this->dbh;
      }

      public function startTrans(){
            $this->dbh->beginTransaction();
            return TRUE;
      }

      public function addQueryTrans($query) {
            $done = $this->dbh->exec($query);
            return $done;
      }

      public function commitTrans() {
            $this->dbh->commit();
            return TRUE;
      }

      public function undoTrans() {
            $this->dbh->rollBack();
            return TRUE;
      }

	public function simpleQuery($query) {
          try{
            $sth = $this->getDBConnection()->prepare($query);
            $sth->execute();
            $result = $sth->fetchall(PDO::FETCH_ASSOC);
          }
          catch(DB_Exception $e){
            echo $e;
            exit();
          }
          catch(Exception $e){
            echo '<pre>';
            print_r($e);
            echo '<br></pre>'.$e->getMessage();
            exit();
          }
          return $result;
      }

      public function prepQuery($query, $params) {
            // Prepared Statement: http://de.php.net/manual/de/ref.pdo.php
      }

      public function close(){
          DB_Connect::$instances = 0;
          if(isset($this->dbh)){
            $this->dbh = NULL;
          }
      }

}

?>
