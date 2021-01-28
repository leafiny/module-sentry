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
 * Class Sentry_Log_Model_File
 */
class Sentry_Log_Model_File extends Log_Model_File
{
    /**
     * @var string $dsn
     */
    protected $dsn = null;

    /**
     * Sentry_Log_Model_File constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->dsn = $this->getDsn();

        if ($this->dsn) {
            Sentry\init(['dsn' => $this->dsn]);
        }
    }

    /**
     * Add Log
     *
     * @param mixed $message
     * @param int   $level
     *
     * @return int
     */
    public function add($message, int $level = self::INFO): int
    {
        if ($message instanceof Throwable && $this->dsn) {
            try {
                Sentry\captureException($message);
            } catch (Throwable $throwable) {
                // Do nothing if sentry fails
            }
        }

        return parent::add($message, $level);
    }

    /**
     * Retrieve DSN
     *
     * @return string|null
     */
    protected function getDsn(): ?string
    {
        return App::getConfig('app.sentry_dsn');
    }
}
