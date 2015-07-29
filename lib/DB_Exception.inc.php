<?php

 /**
     * Regelt die Exceptions bei Auftreten von Fehlern bei der Datenbankverbindung
     *
     * Requires PHP 5 or above
     *
     * @author    Albin Blaschka <albin@albinblaschka.info>
     * @copyright Albin Blaschka, February 2008
     * @version   "Consolidation"
     * @package   FoDok 2
     */

class DB_Exception extends Exception
{
    //no new data members

    // private $myExeptions;

    public function __construct($message, $errorno)
    {

        $myExeptions[] = 5000;
        $myExeptions[] = 5001;
        $myExeptions[] = 9999;

        //check for programmer error
        if(in_array($errorno, $myExeptions) == true) {
            $message = __CLASS__  .": ". $message;
        }else{
            $message = __CLASS__  . ": ". $message;
        }

        //call the Exception constructor
        parent::__construct($message, $errorno);
    }
    //override __tostring
    public function __toString()
    {
        return ("Error: $this->code - $this->message");
    }
}
?>
