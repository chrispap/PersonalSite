<?php

class Site
{
    private $con = NULL;
    private $config = array();
    private $admin = false;
    private $localaccess = false;
    private $PATH = "";
    private $FULL_PATH = "";
	private $PUBLIC_PATH = "";
	private $IMG_PATH = "";
    private $content = "";
    private $title = "";
    private $p1 = "";
    private $p2 = "";
    private $p3 = "";

    function __construct()
    {
        $this->config = include('config.php.local');
        $this->authenticate();
        $this->frontController();
    }

    public function mySqlConnect()
    {
        if (!isset($this->con))
        {
            $user = $this->config['mysql']['user'];
            $pass = $this->config['mysql']['password'];
            $this->con = mysql_connect("localhost",$user, $pass);

            if (!$this->con) {
                die('Could not connect: ' . mysql_error());
            }

            mysql_set_charset("utf8",$this->con);
            mysql_select_db("chris", $this->con);
        }
    }

    private function authenticate()
    {
        session_start();

        $CLIENT_PUBLIC_IP   = $_SERVER['REMOTE_ADDR'];
        $SERVER_PUBLIC_IP = file_get_contents("http://dns.loopia.se/checkip/checkip.php");

        if ($SERVER_PUBLIC_IP )
            $SERVER_PUBLIC_IP = trim( substr( $SERVER_PUBLIC_IP, strpos($SERVER_PUBLIC_IP, ':')+1 , 30));

        $SERVER_PUBLIC_IP = strip_tags( $SERVER_PUBLIC_IP );
        $this->localaccess = $SERVER_PUBLIC_IP == $CLIENT_PUBLIC_IP ? true: false;

        /** LOGED_OUT -> LOGIN ( ATTEMT TO LOGIN ) */
        if ( !isset($_SESSION['username']) && isset($_POST['username'])) {
            $this->mysqlConnect();

            $sql = 'SELECT * from users where username = "'.$_POST['username'].'";';

            if ($res = mysql_query($sql, $this->con)) {
                if ($row =  mysql_fetch_array($res)) {
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['email']    = $row['email'];
                    $_SESSION['userid']   = $row['userid'];
                }
            }
        }

        /** LOGED_IN -> LOGED_OUT ( REQUEST TO LOGOUT ) */
        else if (isset($_POST['logout']) && $_POST['logout']=="true" && !isset($_POST['username'])) {
            unset($_SESSION['userid']);
            unset($_SESSION['username']);
            unset($_SESSION['email']);
            unset($_SESSION['userid']);
            session_destroy();
        }

        /** username == admin-username? ( administrator Verification ) */
        if (isset($_SESSION['userid']) && $_SESSION['userid'] === "1")
            $this->admin=true;
        else
            $this->admin=false;
    }

    public function frontController ()
    {
        $this->PATH = 'http://' . $_SERVER['SERVER_NAME'] . '/';
		$this->PUBLIC_PATH 	 = $this->PATH . $this->config['paths']['public'];
		$this->IMAGE_PATH    = $this->PATH . $this->config['paths']['image'];
		$this->FULL_PATH 	 = $this->PATH;
		
        /** Extract the case parameter from url */
        $this->p1 = isset($_GET["p1"])? $_GET["p1"] : "home";
        $this->p2 = isset($_GET["p2"])? $_GET["p2"] : "";
        $this->p3 = isset($_GET["p3"])? $_GET["p3"] : "";
        
        if (strlen($this->p1)) $this->FULL_PATH = $this->FULL_PATH . $this->p1 . '/';
        if (strlen($this->p2)) $this->FULL_PATH = $this->FULL_PATH . $this->p2 . '/';
        if (strlen($this->p3)) $this->FULL_PATH = $this->FULL_PATH . $this->p3 . '/';

        $this->title = "Chris Papapavlou";
        $this->content = "pages/pages_$this->p1.php";
    }

    public function render()
    {
        include('pages/layout.html');
    }

}
