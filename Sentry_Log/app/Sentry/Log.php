<?php
/**
 * This file is part of Leafiny.
 *
 * Copyright (C) Magentix SARL
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

/**
 * Class Sentry_Log
 */
final class Sentry_Log
{
    /**
     * Init Sentry
     *
     * @return bool
     */
    public static function init(): bool
    {
        if (!self::sdkExists()) {
            return false;
        }

        if (!self::getDsn()) {
            return false;
        }

        if (self::getClient()) {
            return false;
        }

        Sentry\init(['dsn' => self::getDsn(), 'environment' => App::getEnvironment()]);

        return true;
    }

    /**
     * Capture Exception
     *
     * @param Throwable $exception
     *
     * @return string
     */
    public static function exception(Throwable $exception): string
    {
        self::init();

        return (string)Sentry\captureException($exception);
    }

    /**
     * Capture Message
     *
     * @param string $message
     * @param int    $level
     *
     * @return string
     */
    public static function message(string $message, int $level = Core_Interface_Log::INFO): string
    {
        self::init();

        return (string)Sentry\captureMessage($message, self::severity($level));
    }

    /**
     * Retrieve Sentry severity
     *
     * @param int $level
     *
     * @return Sentry\Severity
     */
    protected static function severity(int $level): Sentry\Severity
    {
        switch ($level) {
            case Core_Interface_Log::DEBUG:
                return Sentry\Severity::debug();
            case Core_Interface_Log::WARN:
                return Sentry\Severity::warning();
            case Core_Interface_Log::ERR:
                return Sentry\Severity::error();
            case Core_Interface_Log::EMERG:
            case Core_Interface_Log::ALERT:
            case Core_Interface_Log::CRIT:
                return Sentry\Severity::fatal();
            case Core_Interface_Log::INFO:
            case Core_Interface_Log::NOTICE:
            default:
                return Sentry\Severity::info();
        }
    }

    /**
     * Check if Sentry sdk exists
     *
     * @return bool
     */
    protected static function sdkExists(): bool
    {
        return class_exists('Sentry\SentrySdk');
    }

    /**
     * Retrieve Sentry current client
     *
     * @return Sentry\ClientInterface|null
     */
    protected static function getClient(): ?Sentry\ClientInterface
    {
        return Sentry\SentrySdk::getCurrentHub()->getClient();
    }

    /**
     * Retrieve Sentry DSN
     *
     * @return string|null
     */
    protected static function getDsn(): ?string
    {
        return App::getConfig('app.sentry_dsn');
    }
}
