<?php

namespace Sikhlana\GreenwebSmsChannel;

use GuzzleHttp\Client;
use Illuminate\Log\Logger;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Propaganistas\LaravelPhone\PhoneNumber;

class GreenwebChannel
{
    const API_URL = 'http://sms.greenweb.com.bd/api.php';

    /**
     * The Guzzle HTTP implementation.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Creates a new Greenweb SMS channel instance.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('greenweb', $notification)) {
            if (! isset($notifiable->phone_number)) {
                return;
            }

            $to = $notifiable->phone_number;
        }

        $message = $notification->toGreenweb($notifiable);

        if (is_string($message)) {
            $message = new GreenwebMessage($message);
        }

        $to = Arr::wrap($to);

        foreach ($to as &$recipient) {
            $recipient = PhoneNumber::make($recipient, 'BD')->formatNational();
        }

        $response = $this->client->post(static::API_URL, [
            'query' => [
                'token' => config('services.greenweb.token'),
                'to' => implode(',', $to),
                'message' => urlencode($message->buildMessage()),
            ],
        ]);

        \Log::debug(sprintf(
            'Greenweb SMS Response: %s',
            $response->getBody()->getContents()
        ));
    }
}