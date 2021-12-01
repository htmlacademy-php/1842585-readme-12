<?php

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

require_once './vendor/autoload.php';

$transport = Transport::fromDsn("smtp://2f9a68295f0fcb:ce330b45e27fd2@smtp.mailtrap.io:2525?encryption=tls&auth_mode=login");
$mailer = new Mailer($transport);

function sendEmail(MailerInterface $mailer, string $sender, string $subject, string $recipient, string $text): Array
{
    $errors = "";

    $email = (new Email())
        ->from($sender)
        ->to($recipient)
        ->subject($subject)
        ->html($text);

    try {
        $mailer->send($email);
    } catch (TransportExceptionInterface $e) {
        $errors =  "Ошибка отправки письма";
    }

    return [
        "success" => $errors === "",
        "errors" => $errors,
    ];
}
