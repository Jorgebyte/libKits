<?php

namespace Jorgebyte\libKits\provider;

use Jorgebyte\libKits\Kit;

interface DatabaseProvider
{
    public function init(): void;
    public function saveKit(Kit $kit): void;
    public function loadKits(): array;
}