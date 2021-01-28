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
     * Add Log
     *
     * @param mixed $message
     * @param int   $level
     *
     * @return int
     */
    public function add($message, int $level = self::INFO): int
    {
        if ($message instanceof Throwable) {
            try {
                Sentry_Log::exception($message);
            } catch (Throwable $throwable) {
                // Do nothing if sentry fails
            }
        }

        return parent::add($message, $level);
    }
}
