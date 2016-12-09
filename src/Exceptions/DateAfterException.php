<?php
namespace FoxORM\Validate\Exceptions;
use Respect\Validation\Exceptions\ValidationException;
class DateAfterException extends ValidationException{
    const INCLUSIVE = 1;

    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} date must be greater than {{after}}',
            self::INCLUSIVE => '{{name}} date must be greater than or equal to {{after}}',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} date must not be greater than {{after}}',
            self::INCLUSIVE => '{{name}} date must not be greater than or equal to {{after}}',
        ],
    ];

    public function chooseTemplate()
    {
        return $this->getParam('inclusive') ? static::INCLUSIVE : static::STANDARD;
    }
}