<?PHP
include_once 'database.php';

try{
    $conn = new PDO($server, $root, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "connected";
}catch (PDOException $ex){
    echo "Connection failed".$ex->getMessage();
}
    
?>