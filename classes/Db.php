<?php
class Db{
    private static $singleton,
        $con;
    # save connection to singleton and return itself (the full object)
    public function __construct()
    {
        # If your singleton is not set
        if(!isset(self::$singleton))
            # assign it this class
            self::$singleton = $this;
        # return this class
        return self::$singleton;
    }
    # This is a connection method because your __construct
    # is not able to return the $pdo connection
    public function connection($host='localhost',$username='root',$password='',$database='social_network')
    {
        # In the connection, you can assign the PDO to a static
        # variable to send it back if it's already set
        if(self::$con instanceof \PDO)
            return self::$con;
        # If not already a PDO connection, try and make one
        try {
            # PDO settings you can apply at connection time
            $opts = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES => false);
            # Assign your PDO Conneciton here.
            self::$con =  new PDO("mysql:host={$host};dbname={$database}",$username,$password,$opts);
            # Return the connection
            return self::$con;
        }
        catch (PDOException $e){
            exit('Database error');
        }
    }

}
?>