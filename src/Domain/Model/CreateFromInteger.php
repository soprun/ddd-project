<?php

declare(strict_types=1);

namespace App\Domain\Model;

interface CreateFromInteger
{
    public static function createFromInteger(int $value): self;

    public static function sda(): void ;
}
