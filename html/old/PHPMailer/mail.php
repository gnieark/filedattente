<?php
require("class.phpmailer.php");

$mail = new phpmailer();

$mail->From     = "david.malo@agriculture.gouv.fr";
$mail->FromName = "David";
$mail->Host     = "smtp.agricoll.drdaf14-bas-normandie.agri";
$mail->Mailer   = "smtp";

@MYSQL_CONNECT("ddsv-intranet","utilisateur","conges");
@mysql_select_db("db_conges");
$query  = "SELECT u_email FROM cones_users";
$result = @MYSQL_QUERY($query);

while ($row = mysql_fetch_array ($result))
{
    // HTML body
    $body  = "Hello <font size=\"4\">" . $row["full_name"] . "</font>, <p>";
    $body .= "<i>Your</i> personal photograph to this message.<p>";
    $body .= "Sincerely, <br>";
    $body .= "phpmailer List manager";

    // Plain text body (for mail clients that cannot read HTML)
    $text_body  = "Hello " . $row["full_name"] . ", \n\n";
    $text_body .= "Your personal photograph to this message.\n\n";
    $text_body .= "Sincerely, \n";
    $text_body .= "phpmailer List manager";

    $mail->Body    = $body;
    $mail->AltBody = $text_body;
    $mail->AddAddress($row["email"], $row["full_name"]);
    $mail->AddStringAttachment($row["photo"], "YourPhoto.jpg");

    if(!$mail->Send())
        echo "There has been a mail error sending to " . $row["email"] . "<br>";

    // Clear all addresses and attachments for next loop
    $mail->ClearAddresses();
    $mail->ClearAttachments();
}
?>