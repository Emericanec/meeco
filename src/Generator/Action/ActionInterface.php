<?php

declare(strict_types=1);

namespace App\Generator\Action;

interface ActionInterface
{
    public const BASE_URL = 'https://meeco.app';

    public const FORM_TYPE_NUMBER = 'number';
    public const FORM_TYPE_STRING = 'string';

    public const METHOD_POST = 'POST';
    public const METHOD_GET = 'GET';

    public function getTitle(): string;

    public function getName(): string;

    public function getDescription(): string;

    public function getForms(): array;

    public function getRequests(): array;

    public function toArray(): array;
}
