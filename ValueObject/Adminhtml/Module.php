<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ValueObject\Adminhtml;

class Module
{
    public function __construct(
        private string $moduleName,
        private bool $moduleStatus,
        private bool $lokiCheckoutRegistered,
        private string $composerName,
        private string $composerVersion,
        private string $latestComposerVersion,
    ) {
    }

    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    public function isModuleStatus(): bool
    {
        return $this->moduleStatus;
    }

    public function isLokiCheckoutRegistered(): bool
    {
        return $this->lokiCheckoutRegistered;
    }

    public function getComposerName(): string
    {
        return $this->composerName;
    }

    public function getComposerVersion(): string
    {
        return $this->composerVersion;
    }

    public function getLatestComposerVersion(): string
    {
        return $this->latestComposerVersion;
    }
}
