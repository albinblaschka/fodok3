<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of fodokAnalyze
 *
 * @author BLASCHKA
 */
class fodokAnalyze {

    public static function countItems($type, $period = '*') {

		$query = "select uebersicht.tabschema, count(uebersicht.publid) as anzahl, spezi_publikation.sort
					from ".$_SESSION['fdksettings']['postgres']['schema'].".uebersicht, ".$_SESSION['fdksettings']['postgres']['schema'].".spezi_publikation
					where
					spezi_publikation.tabschema = uebersicht.tabschema ";

        if ($period != '*' and $type == '*') {
            $query .="and uebersicht.jahr = ".$period;
        }
        if ($type != '*' and $period == '*') {
            $query .="and uebersicht.tabschema = '".$type."'";
        }
        if ($type != '*' and $period != '*') {
            $query .="and uebersicht.tabschema = '".$type."' and uebersicht.jahr=".$period;
        }

        $query .= " group by uebersicht.tabschema, spezi_publikation.sort
					order by spezi_publikation.sort;";

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
            $returnMsg[1] = 'Fehler: fodokAnalyze::countItems';
            return $returnMsg;
        }

        $items = array();
        foreach ($result as $record) {
              $items[$record['tabschema']] = $record['anzahl'];
        }
        return $items;
    }

    public static function totalPoints($person, $type, $period) {

        if ($person == '*' and $type == '*' and $period == '*') {
            $query = "select summe from ".$_SESSION['fdksettings']['postgres']['schema'].".punkte_summe_hblfa";
            $typeOfResult = 1;
        }
        if ($person == '*' and $type == '*' and $period != '*') {
            $query = "select summe from ".$_SESSION['fdksettings']['postgres']['schema'].".punkte_summe_hblfa_jahr where jahr=".$period;
            $typeOfResult = 1;
        }

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
            $returnMsg[1] = 'Fehler: fodokAnalyze::totalPoints';
            return $returnMsg;
        }

        return $result;
    }

    public static function groupedPersPoints($type, $period) {

        if ($type == '*' and $period == '*') {
            $query = "select gruppe, sum(punkte) as punkte
                        from ".$_SESSION['fdksettings']['postgres']['schema'].".persgruppe_punkte
                        where (gruppe = 'A' or gruppe = 'B' or gruppe = 'C')
                        group by gruppe order by gruppe";
            $typeOfResult = 2;
        }
        if ($type == '*' and $period != '*') {
            $query = "select jahr, gruppe, sum(punkte) as punkte
                             from ".$_SESSION['fdksettings']['postgres']['schema'].".persgruppe_punkte
                             where jahr=".$period."
                             and (gruppe = 'A' or gruppe = 'B' or gruppe = 'C')
                             group by jahr, gruppe
                             order by jahr, gruppe";


            $typeOfResult = 2;
        }

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
            $returnMsg[1] = 'Fehler: fodokAnalyze::groupedPersPoints';
            return $returnMsg;
        }

        switch ($typeOfResult) {
            case 1:
                $resultset = $result;
            break;
            case 2:
                $resultset = array();
                foreach ($result as $record) {
                    $resultset[$record['gruppe']] = $record['punkte'];
                }
            break;
        }

        return $resultset;
    }
}
?>
