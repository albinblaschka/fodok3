<?php

/**
* HandleCredential - Bearbeiten der Authorisierungsfunktionen und
* User-Informationen auf Basis der APK
*
* Die Klasse HandleCredentials regelt die Authentifizierung auf Basis einer PostgreSQL-Tabelle. Im Falle
* der Forschungsdokumentation werden die Rechte entsprechend der Daten in der APK (Personalverwaltung und
* Stundenaufzeichnung) vergeben.
*
* @package FoDok
* @author Albin Blaschka
* @version 1.0
*/

class FoDok_HandleCredential {

    protected $status = '';
    protected $username = '';
    protected $rights = '';
    protected $unit;
    protected $dep;
    public $persnr;
    public $vorname;

    public function __construct() {
        if (isset($_SESSION) == FALSE) {
            die('Interner Fehler: Session nicht initialisiert!');
        }else{
            $this->status = FALSE;
            $this->username = '';
            $this->rights = '';
            $this->unit = '';
        }
    }

    public function GetStatus() {
      if (empty($this->username) == TRUE or empty($this->rights)){
         $this->status = FALSE;
      }else{
        $this->status = TRUE;
      }
      return $this->status;
   }

    public function SetStatus($un, $creds) {
      $this->username = $un;
      $this->rights = strlen($creds);
      if (empty($un) == FALSE and empty($creds) == FALSE) {
         $this->status = TRUE;
      }else{
         $this->status = FALSE;
      }
   }

    public function GetUnit() {
        return $this->unit;
    }

    public static function GetDep($APKNr) {
        $query = "select struktur.institut from "
                    .$_SESSION['fdksettings']['postgres']['schema'].".struktur, ".$_SESSION['fdksettings']['postgres']['schema'].".personen
				  where
                    personen.persnr = ".$APKNr." and
                    personen.zugehoerigkeit=struktur.kostenstellennr";

        try{
            $DBConnect = new DB_Connect('postgres');
            $result = $DBConnect->simpleQuery($query);
            $DBConnect->close();
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
        $dummy = $result[0]['institut'];
        return $dummy;
    }

   public function SetUnit($newUnit) {
      /* Variable prüfen! */
      $this->unit = $newUnit;
   }

   public function GetUser() {
      if ($this->GetStatus() == TRUE) {
         return $this->username;
      }else{
         return 'Nicht Authentifiziert!';
      }
   }

   public function authenticate($user, $pwd) {
      $query = "select persnr, nachname, vorname, passwort, zugehoerigkeit, rechte
					from ".$_SESSION['fdksettings']['postgres']['schema'].".personen where persnr='".trim($user)."'";
      try{
         $DBConnect = new DB_Connect('postgres');
         $result0 = $DBConnect->simpleQuery($query);
         $DBConnect->close();
         $result = $result0[0];
         unset($result0);
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
      if (empty($result) == TRUE) {
         $this->SetStatus('','');
      }
      if ($result['passwort'] != $pwd) {
         $this->SetStatus($result['nachname'],FALSE);
         $this->unit = '';
      }else{
		 $this->SetStatus($result['nachname'],$result['rechte']);
         $this->unit = $result['zugehoerigkeit'];
         $this->persnr = $result['persnr'];
         $this->vorname = $result['vorname'];
      }
   }

   public function GetCredential() {
      return $this->rights;
   }

   public function getFullName($persnr) {
      $query = "select  vorname, nachname from ".$_SESSION['fdksettings']['postgres']['schema'].".personen where persnr=".$persnr;
      try{
         $DBHandle = new DB_Connect('postgres');
         $result = $DBHandle->simpleQuery($query);
         $DBHandle->close();
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
      $fullName['nachname'] = ucfirst(strtolower($result[0]['nachname']));
      $fullName['vorname'] = $result[0]['vorname'];
      return $fullName;
   }


   public function testUnit() {
      $arrReturn = array();
      $code = substr($this->unit,-3);
      if ($code <> '000') {
         /* wir sind maximal Abteilungsleiter */
         $arrReturn['code'] = '00';
         $arrReturn['unitCode'] = $this->unit;
      }else{
         /* wir sind Institutsleiter */
         $arrReturn['code'] = '000';
         $arrReturn['unitCode'] = substr($this->unit,0,-3);
      }
      $query = "select kostenstelle from ".$_SESSION['fdksettings']['postgres']['schema'].".personen where persnr = '".$this->persnr."'";
      try{
         $DBHandle = new DB_Connect('postgres');
         $result = $DBHandle->simpleQuery($query);
         $DBHandle->close();
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
      $arrReturn['unitName'] = $result[0]['kostenstelle'];
      return $arrReturn;
   }

}
?>
