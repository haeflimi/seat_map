<?php
defined('C5_EXECUTE') or die("Access Denied.");

$subject = t("Einladung zur Turicane 22 Anmeldung");
$body = t("

Hallo %s,

Du wurdest von %s eingeladen, dich für die Turicane 22 LAN Party anzumelden. Der Sitzplatz
mit der Nummer %s ist bereits für dich vorgemerkt.

Bitte folge diesem Link um den Anmeldeprozess abzuschliessen und dir deinen Platz definitiv
zu sichern:
<a href='%s'>%s</a>

See you @LAN
Das Turicane LAN Orga Team

", $uName, $inviteeName, $seatNumber, $uName, $signupPageURL, $signupPageURL);

$parsedBody = nl2br($body);
$html = file_get_contents(DIR_BASE.'/application/mail/mail_template.html');
$html = str_replace('{subject}', $subject, $html);
$html = str_replace('{body}', $parsedBody, $html);

$cssToInlineStyles = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles();
$bodyHTML = $cssToInlineStyles->convert($html);
