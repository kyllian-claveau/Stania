<?php

namespace App\Business;

class UpgradeBusiness
{
    public function __construct(
        private readonly DatabaseBusiness $databaseBusiness,
        private readonly ?string $updateCode
    )
    {

    }

    public function getUpdateCode(): ?string
    {
        return empty($this->updateCode) ? null : $this->updateCode;
    }

    public function needUpdate(): bool
    {
        return !$this->databaseBusiness->upToDate();
    }

    public function update(): void
    {
        if (!$this->needUpdate()) {
            return;
        }

        $this->databaseBusiness->update();
    }
}