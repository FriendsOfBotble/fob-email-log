<?php

declare(strict_types=1);

namespace FriendsOfBotble\EmailLog\Listeners;

use Exception;
use FriendsOfBotble\EmailLog\Models\EmailLog;
use Illuminate\Mail\Events\MessageSent;
use Symfony\Component\Mime\Address;

class EmailLogger
{
    public function handle(MessageSent $event): void
    {
        $message = $event->message;
        $sent = $event->sent->getSymfonySentMessage();

        try {
            EmailLog::query()->create([
                'from' => $this->addressesToString($message->getFrom()),
                'to' => $this->addressesToString($message->getTo()),
                'cc' => $this->addressesToString($message->getCc()),
                'bcc' => $this->addressesToString($message->getBcc()),
                'subject' => $message->getSubject(),
                'html_body' => $message->getHtmlBody(),
                'text_body' => $message->getTextBody(),
                'raw_body' => $sent->getMessage()->toString(),
                'debug_info' => $sent->getDebug(),
            ]);
        } catch (Exception $exception) {
            logger()->error($exception->getMessage());
        }
    }

    protected function addressesToString(array $addresses): string
    {
        return collect($addresses)->map(function (Address $address) {
            return $address->toString();
        })->implode(', ');
    }
}
