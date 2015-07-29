
<?php
function doQuery($query) 
{
    
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
    return $result;
}

?>

