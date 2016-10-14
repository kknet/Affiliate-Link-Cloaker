<?php
if ($_POST['dl1'] AND $_POST['dl2'] AND $_POST['dl3'] AND $_POST['dl4'] AND $_POST['dl5']) 
{

$desktop = "'". htmlspecialchars($_POST["dl1"]) . "','" . htmlspecialchars($_POST["dl2"]) . "','" . htmlspecialchars($_POST["dl3"]) . "','" . htmlspecialchars($_POST["dl4"]) . "','" . htmlspecialchars($_POST["dl5"]) . "'";
}  
elseif ($_POST['dl1'] AND $_POST['dl2'] AND $_POST['dl3'] AND $_POST['dl4']) 
{
  
$desktop = "'". htmlspecialchars($_POST["dl1"]) . "','" . htmlspecialchars($_POST["dl2"]) . "','" . htmlspecialchars($_POST["dl3"]) . "','" . htmlspecialchars($_POST["dl4"]) . "'";
}  
elseif ($_POST['dl1'] AND $_POST['dl2'] AND $_POST['dl3'])
{

$desktop = "'". htmlspecialchars($_POST["dl1"]) . "','" . htmlspecialchars($_POST["dl2"]) . "','" . htmlspecialchars($_POST["dl3"]) . "'";

}  

elseif ($_POST['dl1'] AND $_POST['dl2'])
{

$desktop = "'". htmlspecialchars($_POST["dl1"]) . "','" . htmlspecialchars($_POST["dl2"]) . "'";
}  

elseif ($_POST['dl1'])
{

$desktop = "'". htmlspecialchars($_POST["dl1"]) . "'";
}  
else 
{
    
}




if ($_POST['ml1'] AND $_POST['ml2'] AND $_POST['ml3'] AND $_POST['ml4'] AND $_POST['ml5']) 
{

$mobile = "'". htmlspecialchars($_POST["ml1"]) . "','" . htmlspecialchars($_POST["ml2"]) . "','" . htmlspecialchars($_POST["ml3"]) . "','" . htmlspecialchars($_POST["ml4"]) . "','" . htmlspecialchars($_POST["ml5"]) . "'";
}  
elseif ($_POST['ml1'] AND $_POST['ml2'] AND $_POST['ml3'] AND $_POST['ml4']) 
{
  
$mobile = "'". htmlspecialchars($_POST["ml1"]) . "','" . htmlspecialchars($_POST["ml2"]) . "','" . htmlspecialchars($_POST["ml3"]) . "','" . htmlspecialchars($_POST["ml4"]) . "'";
}  
elseif ($_POST['ml1'] AND $_POST['ml2'] AND $_POST['ml3'])
{

$mobile = "'". htmlspecialchars($_POST["ml1"]) . "','" . htmlspecialchars($_POST["ml2"]) . "','" . htmlspecialchars($_POST["ml3"]) . "'";

}  

elseif ($_POST['ml1'] AND $_POST['ml2'])
{

$mobile = "'". htmlspecialchars($_POST["ml1"]) . "','" . htmlspecialchars($_POST["ml2"]) . "'";
}  

elseif ($_POST['ml1'])
{

$mobile = "'". htmlspecialchars($_POST["ml1"]) . "'";
}  
else 
{
    
}




if ($_POST['dp1'] AND $_POST['dp2'] AND $_POST['dp3'] AND $_POST['dp4'] AND $_POST['dp5']) 
{

$decoy = "'". htmlspecialchars($_POST["dp1"]) . "','" . htmlspecialchars($_POST["dp2"]) . "','" . htmlspecialchars($_POST["dp3"]) . "','" . htmlspecialchars($_POST["dp4"]) . "','" . htmlspecialchars($_POST["dp5"]) . "'";
}  
elseif ($_POST['dp1'] AND $_POST['dp2'] AND $_POST['dp3'] AND $_POST['dp4']) 
{
  
$decoy = "'". htmlspecialchars($_POST["dp1"]) . "','" . htmlspecialchars($_POST["dp2"]) . "','" . htmlspecialchars($_POST["dp3"]) . "','" . htmlspecialchars($_POST["dp4"]) . "'";
}  
elseif ($_POST['dp1'] AND $_POST['dp2'] AND $_POST['dp3'])
{

$decoy = "'". htmlspecialchars($_POST["dp1"]) . "','" . htmlspecialchars($_POST["dp2"]) . "','" . htmlspecialchars($_POST["dp3"]) . "'";

}  

elseif ($_POST['dp1'] AND $_POST['dp2'])
{

$decoy = "'". htmlspecialchars($_POST["dp1"]) . "','" . htmlspecialchars($_POST["dp2"]) . "'";
}  

elseif ($_POST['dp1'])
{

$decoy = "'". htmlspecialchars($_POST["dp1"]) . "'";
}  
else 
{
    
}

if ($_POST['rtf1'] AND $_POST['rtf2'] AND $_POST['rtf3'] AND $_POST['rtf4'] AND $_POST['rtf5']) 
{

$reffrom =  htmlspecialchars($_POST["rtf1"]) . "','" . htmlspecialchars($_POST["rtf2"]) . "','" . htmlspecialchars($_POST["rtf3"]) . "','" . htmlspecialchars($_POST["rtf4"]) . "','" . htmlspecialchars($_POST["rtf5"]);
}  
elseif ($_POST['rtf1'] AND $_POST['rtf2'] AND $_POST['rtf3'] AND $_POST['rtf4']) 
{
  
$reffrom = htmlspecialchars($_POST["rtf1"]) . "','" . htmlspecialchars($_POST["rtf2"]) . "','" . htmlspecialchars($_POST["rtf3"]) . "','" . htmlspecialchars($_POST["rtf4"]);
}  
elseif ($_POST['rtf1'] AND $_POST['rtf2'] AND $_POST['rtf3'])
{

$reffrom = htmlspecialchars($_POST["rtf1"]) . "','" . htmlspecialchars($_POST["rtf2"]) . "','" . htmlspecialchars($_POST["rtf3"]) ;

}  

elseif ($_POST['rtf1'] AND $_POST['rtf2'])
{

$reffrom =  htmlspecialchars($_POST["rtf1"]) . "','" . htmlspecialchars($_POST["rtf2"]);
}  

elseif ($_POST['rtf1'])
{

$reffrom = htmlspecialchars($_POST["rtf1"]) ;
}  
else 
{
    
}

$emailx = htmlspecialchars($_POST["x5"]);
$refto = htmlspecialchars($_POST["rtt"]);
$emaillogs = htmlspecialchars($_POST["x8"]);
$logs = htmlspecialchars($_POST["x10"]);
$tor = htmlspecialchars($_POST["x11"]);
$proxy = htmlspecialchars($_POST["x12"]);
$fetch = htmlspecialchars($_POST["x13"]);
$ref = htmlspecialchars($_POST["x14"]);


$dirr = date("l");
$dit = strtolower($dirr);
if (!file_exists($dit)) {
    mkdir($dit);
}
$f_contents = file("random.txt"); 
$line = $f_contents[rand(0, count($f_contents) - 1)];
$line2 = $f_contents[rand(0, count($f_contents) - 1)];
$dir = $dit ."/". trim($line) ."X" . trim($line2)  ;
mkdir($dir);




$file = $dir ."/index.php";

$body = "

paapblock_links  = array(". $decoy .");
paapuser_links    = array(". $desktop .");
paapmobile_links  = array(". $mobile .");

paapreferrer_redirect = ". $ref ."; 
paapfetch_page        = ". $fetch ."; 
paapdeny_tor          = ". $tor .";  
paapdeny_proxy        = ". $proxy .";  
paapenable_logs       = ". $logs .";  
paapreferrer_list = array('". $reffrom ."' => array('". $refto ."'));
paapenable_mobile     = true; 
paapjavascript_data   = true;  

";



$ek = file_get_contents('1.php');
file_put_contents($file, $ek, FILE_APPEND | LOCK_EX);

$newdata = str_replace("paap", "$", $body);
file_put_contents($file, $newdata, FILE_APPEND | LOCK_EX);


$do = file_get_contents('3.php');
file_put_contents($file, $do, FILE_APPEND | LOCK_EX);

echo 'You Cloaked URL Is site /' . $dir ;



?>
