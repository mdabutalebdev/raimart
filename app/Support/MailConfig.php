<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Config;

/**
 * Pushes the SMTP credentials saved in Admin → SMTP Settings into Laravel's mail
 * config at runtime, so mail works without touching .env on the server.
 */
class MailConfig
{
    public static function isConfigured(): bool
    {
        return filled(SiteSetting::get('smtp_host')) && filled(SiteSetting::get('smtp_port'));
    }

    public static function apply(): void
    {
        if (! self::isConfigured()) {
            return;
        }

        $encryption = SiteSetting::get('smtp_encryption', 'tls');

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', SiteSetting::get('smtp_host'));
        Config::set('mail.mailers.smtp.port', (int) SiteSetting::get('smtp_port', '587'));
        Config::set('mail.mailers.smtp.username', SiteSetting::get('smtp_username'));
        Config::set('mail.mailers.smtp.password', SiteSetting::get('smtp_password'));
        Config::set('mail.mailers.smtp.encryption', $encryption === 'none' ? null : $encryption);

        if ($from = SiteSetting::get('smtp_from_email')) {
            Config::set('mail.from.address', $from);
        }
        if ($fromName = SiteSetting::get('smtp_from_name')) {
            Config::set('mail.from.name', $fromName);
        }
    }
}
