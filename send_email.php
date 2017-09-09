<?php 
	if($_POST['name-id'] == "" || $_POST['surname-id'] == "" || $_POST['email-id'] == "" || $_POST['message'] == ""){
		header('location: contact.php');
		exit();
	} else {
		$to = "eltehmo@bih.net.ba";

		$headers = "From: " . strip_tags($_POST['email-id']) . "\r\n";
		$headers .= "Reply-To: ". strip_tags($_POST['email-id']) . "\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$subject = 'ETS - Mostar Podrska korisnicima';

		$message = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<meta name="viewport" content="width=device-width"/>';

		$message .= '<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<meta name="viewport" content="width=device-width"/>
						<style>
					body{ 
					  width:100% !important; 
					  min-width: 100%;
					  -webkit-text-size-adjust:100%; 
					  -ms-text-size-adjust:100%; 
					  margin:0; 
					  padding:0;
					}

					#backgroundTable { 
					  margin:0; 
					  padding:0; 
					  width:100% !important; 
					  line-height: 100% !important; 
					}
					img { 
					  outline:none; 
					  text-decoration:none; 
					  -ms-interpolation-mode: bicubic;
					  width: auto;
					  max-width: 100%; 
					  float: left; 
					  clear: both; 
					  display: block;
					}

					p {
					  margin: 0 0 0 10px;
					}
					table {
					  border-spacing: 0;
					  border-collapse: collapse;
					}
					td { 
					  word-break: break-word;
					  -webkit-hyphens: auto;
					  -moz-hyphens: auto;
					  hyphens: auto;
					  border-collapse: collapse !important; 
					}

					table, tr, td {
					  padding: 0;
					  vertical-align: top;
					  text-align: left;
					}

					hr {
					  color: #d9d9d9; 
					  background-color: #d9d9d9; 
					  height: 1px; 
					  border: none;
					}

					/* Responsive Grid */

					table.body {
					  height: 100%;
					  width: 100%;
					}

					table.container {
					  width: 580px;
					  margin: 0 auto;
					  text-align: inherit;
					}

					table.row { 
					  padding: 0px; 
					  width: 100%;
					  position: relative;
					}

					table.container table.row {
					  display: block;
					}

					td.wrapper {
					  padding: 10px 20px 0px 0px;
					  position: relative;
					}

					table.columns,
					table.column {
					  margin: 0 auto;
					}

					table.columns td,
					table.column td {
					  padding: 0px 0px 10px; 
					}


					table.one { width: 30px; }
					table.two { width: 80px; }
					table.three { width: 130px; }
					table.four { width: 180px; }
					table.five { width: 230px; }
					table.six { width: 280px; }
					table.seven { width: 330px; }
					table.eight { width: 380px; }
					table.nine { width: 430px; }
					table.ten { width: 480px; }
					table.eleven { width: 530px; }
					table.twelve { width: 580px; }


					/* Alignment & Visibility Classes */

					table.center, td.center {
					  text-align: center;
					}


					/* Typography */

					body, table.body, h1, h2, h3, h4, h5, h6, p, td { 
					  color: #666;
					  font-family: "Helvetica", "Arial", sans-serif; 
					  font-weight: normal; 
					  padding:0; 
					  margin: 0;
					  text-align: left; 
					  line-height: 1.3;
					}

					h1, h2, h3, h4, h5, h6 {
					  word-break: normal;
					}

					h1 {font-size: 30px;}
					h2 {font-size: 26px;}
					h3 {font-size: 20px;}
					h4 {font-size: 17px;}
					h5 {font-size: 14px;}
					h6 {font-size: 13px;}

					h1, h2, h3, h4, h5, h6, p {margin-bottom: 10px;}

					body, table.body, p, td {font-size: 14px;line-height:1.42;}
					p { 
					  margin-bottom: 10px;
					}
					small {
					  font-size: 10px;
					}
					a {
					  color: #3498db; 
					  text-decoration: none;
					}
					a:hover { 
					  color: #2795b6 !important;
					}
					a:active { 
					  color: #2795b6 !important;
					}
					a:visited { 
					  color: #2ba6cb !important;
					}
					    .template-label {
					      color: #666666;
					      font-weight: bold;
					      font-size: 16px;
					    }
					    .callout .wrapper {
					      padding-bottom: 20px;
					    }
					    .callout .panel {
					      background: #ECF8FF;
					      border-color: #b9e5ff;
					    }
					    .header {
					      background: #ecf0f1;
					    }
					    .footer .wrapper {
					      background: #E3E3E3;
					    }
					    .footer h5 {
					      padding-bottom: 10px;
					    }
						</style>
					</head>';

	$message .= '<body>
					<table class="body">
						<tr>
							<td class="center" align="center" valign="top">
				        <center>

				          <table class="row header">
				            <tr>
				              <td class="center" align="center">
				                <center>

				                  <table class="container">
				                    <tr>
				                      <td class="wrapper last">

				                        <table class="twelve columns">
				                          <tr>
				                          
				                            <td class="six sub-columns last" style="text-align:right; vertical-align:middle;">
				                              <span class="template-label">ETS Mostar</span>
				                            </td>
				                            <td class="expander"></td>
				                          </tr>
				                        </table>

				                      </td>
				                    </tr>
				                  </table>

				                </center>
				              </td>
				            </tr>
				          </table>

				          <table class="container">
				            <tr>
				              <td>

				                <table class="row">
				                  <tr>
				                    <td class="wrapper last">

				                      <table class="twelve columns">
				                        <tr>
				                          <td>
				                            <h1>'.$_POST['name-id'].' i '.$_POST['surname-id'].'</h1><h4>Email: '.strip_tags($_POST['email-id']).'</h4><br>
				                						<p class="lead">'.strip_tags($_POST['message']).'</p>
				                          </td>
				                        
				                        </tr>
				                      </table>

				                    </td>
				                  </tr>
				                </table>
				                <table class="row footer">
				                  <tr>
				                    <td class="wrapper">

				                      <table class="six columns">
				                        <tr>
				                    <td class="wrapper last">

				                <table class="row">
				                  <tr>
				                    <td class="wrapper last">

				                      <table class="twelve columns">
				                        <tr>
				                          <td align="center">
				                            <center>
				                              <p style="text-align:center;"><a href="#">Pravila emaila</a></p>
				                            </center>
				                          </td>
				                          <td class="expander"></td>
				                        </tr>
				                      </table>

				                    </td>
				                  </tr>
				                </table>
				              </td>
				            </tr>
				          </table>

				        </center>
							</td>
						</tr>
					</table>
				</body>
				</html>';
	
	
require_once ("./PHPMailer/class.phpmailer.php");

$mail = new PHPMailer;
$mail->isSMTP();/*Set mailer to use SMTP*/
$mail->Host = 'mail.etsmostar.edu.ba';/*Specify main and backup SMTP servers*/
$mail->Port = 587;
$mail->SMTPAuth = true;/*Enable SMTP authentication*/
$mail->Username = "etsmo@etsmostar.edu.ba";/*SMTP username*/
$mail->Password = "Ail757q#";/*SMTP password*/
$mail->Secure = "ssl";
$mail->From = 'etsmo@etsmostar.edu.ba';
$mail->FromName = "ETS Web Podrška korisnicima";
$mail->addAddress($to, 'Recipients Name');/*Add a emails*/
$mail->addReplyTo($to, "bb");
$mail->Subject = $subject;
$mail->Body    = $message;
$mail->AltBody = $message;
$mail->isHTML(true);
if(!$mail->send()) {
    echo 'Poruka nije poslata.';
    echo 'Greska: ' . $mail->ErrorInfo;
} else {
  header('location: kontakt.php');
  exit();
}
	}


?>