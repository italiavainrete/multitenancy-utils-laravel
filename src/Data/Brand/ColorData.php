<?php

namespace IVR\MultiTenancyUtils\Data\Brand;

use OzdemirBurak\Iris\BaseColor;
use OzdemirBurak\Iris\Color\Hex;
use OzdemirBurak\Iris\Exceptions\InvalidColorException;
use Spatie\LaravelData\Data;

class ColorData extends Data
{

    public function __construct(
        public string $primary,
        public string $secondary,
        public string $success,
        public string $info,
        public string $warning,
        public string $danger
    )
    {
    }

    /**
     * @throws InvalidColorException
     */
    public function primary(): BaseColor
    {
        return new Hex($this->primary);
    }

    /**
     * @throws InvalidColorException
     */
    public function secondary(): BaseColor
    {
        return new Hex($this->secondary);
    }

    /**
     * @throws InvalidColorException
     */
    public function success(): BaseColor
    {
        return new Hex($this->success);
    }

    /**
     * @throws InvalidColorException
     */
    public function info(): BaseColor
    {
        return new Hex($this->info);
    }

    /**
     * @throws InvalidColorException
     */
    public function warning(): BaseColor
    {
        return new Hex($this->warning);
    }

    /**
     * @throws InvalidColorException
     */
    public function danger(): BaseColor
    {
        return new Hex($this->danger);
    }
}
