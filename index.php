<?php
/*
  Yi Xu  08/17/2010  Ver 1.0 
  This is a simple script for creating cpanel email accounts using cPanel XML API Client Class
  I got the idea from http://www.zubrag.com/scripts/ cpanel email creator script. 
  However, their script uses fopen access cpanel directly and it doesn't work anymore.
  Cpanel updated their version. It is better to use their client class to access cpanel functions externally.
  Got any suggestions. please email me at andy3dfx@yahoo.com
*/
include("./xmlapi.php");   //XMLAPI cpanel client class

// Default whm/cpanel account info

$ip = "127.0.0.1";           // should be WHM ip address
$account = "cpanel_username";        // cpanel user account name
$passwd ="cpanel_password";        // cpanel user password
$port =2083;                 // cpanel secure authentication port unsecure port# 2082

$email_domain = $_REQUEST['domain']; // email domain (usually same as cPanel domain)
$email_quota = 10000; // default amount of space in megabytes


/*************End of Setting***********************/

function getVar($name, $def = '') {
  if (isset($_REQUEST[$name]))
    return $_REQUEST[$name];
  else 
    return $def;
}
// check if overrides passed
$email_user = getVar('user', '');
$email_pass = getVar('pass', $passwd);
$email_vpass = getVar('vpass', $vpasswd);
$email_domain = getVar('domain', $email_domain);
$email_quota = getVar('quota', $email_quota);

$msg = '';
$color = '';
$status = '';
if (!empty($email_user))
while(true) {


if ($email_pass !== $email_vpass){       //check password
$msg = "Email password does not match";
break;
}

$xmlapi = new xmlapi($ip);

$xmlapi->set_port($port);  //set port number. cpanel client class allow you to access WHM as well using WHM port.

$xmlapi->password_auth($account, $passwd);   // authorization with password. not as secure as hash.

// cpanel email addpop function Parameters
$call = array(domain=>$email_domain, email=>$email_user, password=>$email_pass, quota=>$email_quota);

$xmlapi->set_debug(0);      //output to error file  set to 1 to see error_log.

$result = $xmlapi->api2_query($account, "Email", "addpop", $call ); // making call to cpanel api

//for debugging purposes. uncomment to see output
//echo 'Result\n<pre>';
//print_r($result);  
//echo '</pre>';

if ($result->data->result == 1){
$status = 'success';
$msg = '<b>'.$email_user.'@'.$email_domain.'</b> account created';

} else {
$status = 'danger';
$msg = $result->data->reason;
  break;
}

break;
}

?>
<!--
IKAW PUTANG INA MO KA PAG IKAW NAG LEECH NG INFO GALING RSG O KAYA NAMAN NAG SPAM KA SA EMAIL CREATOR NA TO SASAMPALIN KO MAMA MO NG LAPTOP GAGO KA 
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<meta name=description content="A free email creator. Just use it wisely!" />
  	<meta name=author content="Roldhan Dasalla(iNew Works)" />
  	<meta property=og:url content=http://www.uyemailoh.io/ />
  	<meta property=og:type content="website" />
  	<meta property=og:title content="UyEMailOh" />
  	<meta property=og:image content="http://www.uyemailoh.io/smtp-logo.jpg" />
  	<meta property=og:description content="A free email creator. Just use it wisely!" />
  	<meta property=profile:first_name content="Roldhan Dasalla(iNew Works)" />
    <title>UyEMailOh</title>
    <link rel="stylesheet" href="bootstrap.min.css"/>
    <script type="text/javascript">
      if( !/\.?www./g.test(location.host) ) location.href = location.href.replace("://","://www.");
      if (window.location.protocol != "https:")
        window.location.href = "https:" + window.location.href.substring(window.location.protocol.length);
    </script>
</head>
<body>
  <div class="container">
    <div class="col-md-4 col-md-offset-4">
      <center>
        <h1>
          EMAIL CREATOR
        </h1>
      </center>
        <form name="frmEmail" method="post">
      <div class="panel panel-primary">
        <div class="panel-heading">Create email Form</div>
        <div class="panel-body">
          <?php echo '<div class="alert alert-'.$status.'">'.$msg.'</div>'; ?>
          <div class="form-group">
            <label>Username : </label>
            <input name="user" size="20" type="text" class="form-control" required />
          </div>
          <div class="form-group">
            <label>Domain : </label>
            <select name="domain" class="form-control">
              <option value="uyemailoh.io">uyemailoh.io</option>
              <option value="inputoutputisreal.io">inputoutputisreal.io</option>
              <option value="leechers.io">leechers.io</option>
              <option value="whyyoudothis.io">whyyoudothis.io</option>
              <option value="pogingmailer.io">pogingmailer.io</option>
              <option value="pogingphdejavu.io">pogingphdejavu.io</option>
              <option value="rsg-gwapogi.io">rsg-gwapogi.io</option>
              <option value="rsgmailer.io">rsgmailer.io</option>
            </select>
          </div>
          <div class="form-group">
            <label>Password : </label>
            <input name="pass" size="20" type="password" class="form-control" required />
          </div>
          <div class="form-group">
            <label>Varify Password : </label>
            <input name="vpass" size="20" type="password" class="form-control" required/>
          </div>
          <input name="submit" class="btn btn-success pull-left" type="submit" value="Create Email" required/>
          <a href="https://ano.anotherme.io:2096/" target="_blank" class="btn btn-primary pull-right">Login to Webmail</a>
          <div style="clear:both"></div>
        </div>
      </div>
      </form>
    </div>
  </div>
  </div>
</body>
</html>
