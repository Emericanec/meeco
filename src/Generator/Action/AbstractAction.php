<?php

declare(strict_types=1);

namespace App\Generator\Action;

abstract class AbstractAction implements ActionInterface
{
    public function toArray(): array
    {
        return [
            'title' => $this->getTitle(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'forms' => $this->getForms(),
            'requests' => $this->getRequests(),
        ];
    }
}
