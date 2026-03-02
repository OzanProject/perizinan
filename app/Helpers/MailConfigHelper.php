<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;

class MailConfigHelper
{
  /**
   * Dynamically set mail configuration from Dinas model.
   *
   * @param \App\Models\Dinas $dinas
   * @return void
   */
  public static function set($dinas)
  {
    if (!$dinas || !$dinas->mail_host) {
      return;
    }

    $config = [
      'transport' => 'smtp',
      'host' => $dinas->mail_host,
      'port' => $dinas->mail_port,
      'encryption' => $dinas->mail_encryption,
      'username' => $dinas->mail_username,
      'password' => $dinas->mail_password,
      'timeout' => null,
      'local_domain' => env('MAIL_EHLO_DOMAIN'),
      'stream' => [
        'ssl' => [
          'allow_self_signed' => true,
          'verify_peer' => false,
          'verify_peer_name' => false,
        ],
      ],
    ];

    Config::set('mail.default', 'smtp');
    Config::set('mail.mailers.smtp', array_merge(config('mail.mailers.smtp', []), $config));
    Config::set('mail.from.address', $dinas->mail_from_address);
    Config::set('mail.from.name', $dinas->mail_from_name);
  }
}
