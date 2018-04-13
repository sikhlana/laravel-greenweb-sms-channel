# Greenweb SMS notifications channel for Laravel 5

This package makes it easy to send SMS notifications via [Greenweb](https://bdbulksms.net/index.php) for Laravel 5.
Greenweb only provides SMS service for Bangladeshi mobile operators.

## Contents

- [Installation](#installation)
	- [Setting up the Greenweb service](#setting-up-the-greenweb-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

``` bash
composer require sikhlana/laravel-greenweb-sms-channel
```

First you must install the service provider (skip for Laravel >= 5.5):

``` php
// config/app.php
'providers' => [
    ...
    Sikhlana\GreenwebSmsChannel\ServiceProvider::class,
],
```

### Setting up the Greenweb service

Add your generated Greenweb SMS API key in your `.env` file:

``` dotenv
...
GREENWEB_SMS_TOKEN=
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use Sikhlana\GreenwebSmsChannel\GreenwebChannel;
use Sikhlana\GreenwebSmsChannel\GreenwebMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [GreenwebChannel::class];
    }

    public function toGreenweb($notifiable)
    {
        return (new GreenwebMessage())
            ->content("Your {$notifiable->service} account was approved!");
    }
}
```

In order to let your Notification know which phone are you sending/calling to, the channel will look for the `phone_number` attribute of the Notifiable model. If you want to override this behaviour, add the `routeNotificationForGreenweb` method to your Notifiable model.

``` php
public function routeNotificationForGreenweb()
{
    return '01765432109';
}
```

### Available Message methods

#### GreenwebMessage

- `content(string)`: Sets the message content.
- `line(string)`: Adds a line of text to the notification.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email xoxo@saifmahmud.name instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Saif Mahmud](https://github.com/sikhlana)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
