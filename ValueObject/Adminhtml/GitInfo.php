<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ValueObject\Adminhtml;

class GitInfo
{
    public function __construct(
        private readonly string $path = ''
    ) {
    }

    public function hasInfo(): bool
    {
        return file_exists($this->getHeadFile());
    }

    public function getBranch(): string
    {
        $contents = $this->getHeadContents();
        if (!preg_match('/branch \'([^\']+)\'/', $contents, $match)) {
            return '';
        }

        return $match[1];
    }

    public function getCommit(): string
    {
        $contents = $this->getHeadContents();
        if (!preg_match('/^([a-z0-9]+)\s/', $contents, $match)) {
            return '';
        }

        return $match[1];
    }

    public function getHeadContents(): string
    {
        $file = $this->getHeadFile();
        if (false === file_exists($file)) {
            return '';
        }

        return file_get_contents($file);
    }

    private function getHeadFile(): string
    {
        $path = $this->path;
        if (file_exists($path . '/.git/FETCH_HEAD')) {
            return $path . '/.git/FETCH_HEAD';
        }

        return dirname($path) . '/.git/FETCH_HEAD';
    }
}
