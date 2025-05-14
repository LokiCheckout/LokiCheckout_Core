<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Logger;

use Psr\Log\LoggerInterface;
use Yireo\LokiCheckout\Config\Config;

class Debugger
{
    public function __construct(
        private Config $config,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @param string $msg
     * @param mixed $data
     *
     * @return bool
     */
    public function debug(string $msg, $data = null): bool
    {
        if ($this->config->isDebug() === false) {
            return false;
        }

        if (!empty($data)) {
            $msg .= ': ' . var_export($data, true);
        }

        $this->logger->notice($msg);
        return true;
    }
}
