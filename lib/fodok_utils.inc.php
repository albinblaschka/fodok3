<?php

/**
* Sammlung von Tools zur Verarbeitung von Daten im Rahmen der FoDok
*
* Die Methoden dieser Klasse sind statisch aufzurufen und abstrahieren
* grundlegende Abfragen von Daten der FoDok
*
* @package FoDok
* @author Albin Blaschka
* @version 1.0
*/

class fodok_utils {

    public static function types() {
        $query = "select distinct tabschema, aktiv_kurz, sort from ".$_SESSION['fdksettings']['postgres']['schema'].".spezi_publikation order by sort";
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
        $toReturn = array();
        foreach ($result as $select) {
            $toReturn[$select['tabschema']] = $select['aktiv_kurz'];
        }
        return $toReturn;
    }

    public static function typeList(){
        $typeList = '<ul>';
        $types = self::types();
        while (list($key, $val) = each($types)) {
            $typeList .= '<li>'.$key.' ('.$val.')</li>';
        }
        $typeList .= '</ul>';
        return $typeList;
    }

    public static function subTypes($type) {
        $query = "select id, aktivitaet, spezi, zusatz, grob
                         from ".$_SESSION['fdksettings']['postgres']['schema'].".spezi_publikation where tabschema = '".$type."'";
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
        $toReturn = array();
        foreach ($result as $select) {
            $toReturn[$select['aktivitaet']][$select['zusatz']][0] = $select['id'];
            $toReturn[$select['aktivitaet']][$select['zusatz']][1] = $select['spezi'];
            $toReturn[$select['aktivitaet']][$select['zusatz']][2] = $select['grob'];
        }
        return $toReturn;
    }

	public static function getStructure() {
        $query = "select distinct institut, bezeichnung FROM
                ".$_SESSION['fdksettings']['postgres']['schema'].".struktur  where fa = TRUE order by institut";
        try{
            $DBHandle = new DB_Connect('postgres');
            $result0 = $DBHandle->simpleQuery($query);
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
		$result = array();
		foreach($result0 as $set) {
			$result[$set['institut']] = $set['bezeichnung'];
		    
		}
        return $result;
    }

    public static function projects() {
        $query = "select institut, leistungsnummer, bezeichnung FROM
                ".$_SESSION['fdksettings']['postgres']['schema'].".projekte order by institut, bezeichnung";
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
        return $result;
    }

     public static function projectData($projectNr) {
        $checkNr = preg_match('/^[0-9]{4}$/',$projectNr);
        if ($checkNr == 0) {
            $returnMsg[0] = 'Error';
            $returnMsg[1] = 'Fehler: Ungültige Projektnummer';
            return $returnMsg;
        }
        $query = "select leistungsnummer, bezeichnung, projektleiter, projektbeginn, projektende
                        from ".$_SESSION['fdksettings']['postgres']['schema'].".projekte where leistungsnummer = '".$projectNr."'";
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
        if (empty($result) == TRUE) {
            $returnMsg[0] = 'Error';
            $returnMsg[1] = 'Fehler: Projekt mit Nummer '.$projectNr.' nicht gefunden';
            return $returnMsg;
        }
        $returnMsg[0] = 'Ok';
        $returnMsg[1] = $result[0]['bezeichnung'].' (Projektnummer '.$result[0]['leistungsnummer'].')';
        return $returnMsg;
    }
    
    public static function getProjectsforPubl($publID) {
        $dataset = array();
        $data = array();
        $query = "select publ_id, leistungsnummer, zuteilung from ".$_SESSION['fdksettings']['postgres']['schema'].".projektzuordnung where publ_id = ".$publID;
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
        
        if (empty($result) == TRUE) {
            $dataset['publID'] = NULL;
        }else{
            foreach ($result as $set) {
                $data[$set['leistungsnummer']] = $set['zuteilung'];
            }
            $dataset[] = $data;
        }
        return $dataset;  
    }


    public static function getPeople($myUnit, $what = 'code') {
        if ($myUnit == '*') {
            $query = "select nachname, vorname, persnr from ".$_SESSION['fdksettings']['postgres']['schema'].".personen
                            where rechte is not NULL
                            order by zugehoerigkeit, nachname ";
        }else{
            $query = "select nachname, vorname, persnr from ".$_SESSION['fdksettings']['postgres']['schema'].".personen
                            where zugehoerigkeit like '".$myUnit."%'
                            and rechte is not NULL
                            order by zugehoerigkeit, nachname";
        }
        try{
            $DBHandle = new DB_Connect('postgres');
            $result0 = $DBHandle->simpleQuery($query);
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
        $result = array();
        foreach ($result0 as $data) {
            switch ($what) {
                case 'fullname':
                    $result[$data['persnr']] = $data['nachname']. ' '.$data['vorname'];
                    break;
				case 'firstname':
                    $result[$data['persnr']] = $data['nachname'];
                    break;
                case 'code':
                default:
                    $result[$data['persnr']][0] = $data['nachname'];
					$result[$data['persnr']][1] = $data['vorname'];
            }

        }
        return $result;
    }

    public static function searchAPK($lastname) {

        $query = "select persnr, nachname, vorname from "
                    .$_SESSION['fdksettings']['postgres']['schema'].".personen
                    where nachname like '".$lastname."%' order by nachname, vorname";
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
        return $result;
    }

    public static function getYears() {
        $query = "select distinct jahr from ".$_SESSION['fdksettings']['postgres']['schema'].".uebersicht order by jahr desc";
        try{
            $DBHandle = new DB_Connect('postgres');
            $result0 = $DBHandle->simpleQuery($query);
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
        $result = array();
        foreach ($result0 as $data) {
           $result[] = $data['jahr'];
        }
        return $result;
    }

    public static function getVisibility($pubNr) {

        $query0 = "select quote from ".$_SESSION['fdksettings']['postgres']['schema'].".uebersicht where publid = ".$pubNr;
        $query1 = "select uri, web from ".$_SESSION['fdksettings']['postgres']['schema'].".uris where publ_id = ".$pubNr;
        try{
            $DBHandle = new DB_Connect('postgres');
            $result0 = $DBHandle->simpleQuery($query0);
            $result1 = $DBHandle->simpleQuery($query1);
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

        $toReturn = array();

        if (empty($result0) == TRUE) {
            $toReturn['quote'] = FALSE;
        }else{
            $toReturn['quote'] = $result0[0]['quote'];
        }
        if (empty($result1) == TRUE) {
            $toReturn['file'] = '';
            $toReturn['web'] = FALSE;
        }else{
            $toReturn['file'] = $result1[0]['uri'];
            $toReturn['web'] = $result1[0]['web'];
        }
        return $toReturn;
    }

    public static function removeFile($publid) {
        $queryFile = "select uri from ".$_SESSION['fdksettings']['postgres']['schema'].".uris
                         where publ_id = ".$publid;
            try{
                $DBHandle = new DB_Connect('postgres');
                $result = $DBHandle->simpleQuery($queryFile);
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
            if (empty($result[0]['uri']) == FALSE) {
                $theFile = $result[0]['uri'];
                $deleted = @unlink($_SESSION['fdksettings']['general']['absolute'].'/'.$_SESSION['fdksettings']['general']['files'].'/'.$theFile);
                if ($deleted != TRUE){
                   return 'Datei konnte nicht gelöscht werden';
                }
                $query = "delete from ".$_SESSION['fdksettings']['postgres']['schema'].".uris
                             where publ_id = ".$publid;
                try{
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
                return 'Datei aus der FoDok gelöscht!';
            }else{
                return 'Datensatz gelöscht!';
            }
    }

}
?>
