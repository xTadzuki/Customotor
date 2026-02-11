<?php



function send_mail(string $to, string $subject, string $message): bool
{
    $config = $GLOBALS['config'] ?? [];
    $mailCfg = $config['mail'] ?? [];

    $enabled = (bool)($mailCfg['enabled'] ?? true);

    
    if (!$enabled) {
        return true;
    }

    $fromEmail = (string)($mailCfg['from_email'] ?? 'no-reply@customotor.local');
    $fromName  = (string)($mailCfg['from_name'] ?? 'customotor');

    // Encodage du sujet (UTF-8)
    $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

    $headers = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';
    $headers[] = 'From: ' . $fromName . ' <' . $fromEmail . '>';
    $headers[] = 'Reply-To: ' . $fromEmail;

    // mail() retourne true/false
    return mail($to, $encodedSubject, $message, implode("\r\n", $headers));
}