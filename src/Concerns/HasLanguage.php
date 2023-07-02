<?php

namespace Kpebedko22\Enum\Concerns;

trait HasLanguage
{
    /**
     * By default, null.
     * So when use translate function, it will use app() locale.
     *
     * @var string|null
     */
    protected ?string $language = null;

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }
}
