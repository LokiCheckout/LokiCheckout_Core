<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\Behaviour;

trait JsNameBehaviour
{
    public function getJsName(): string
    {
        $name = ucwords($this->getParent()->getNameInLayout(), '_.-');

        return preg_replace('#[_.-]+#', '', $name);
    }
}
