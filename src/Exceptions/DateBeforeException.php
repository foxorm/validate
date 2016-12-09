<?php
namespace FoxORM\Validate\Exceptions;
use Respect\Validation\Exceptions\ValidationException;
class DateBeforeException extends ValidationException{
    const INCLUSIVE = 1;

    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} date must be less than {{before}}',
            self::INCLUSIVE => '{{name}} date must be less than or equal to {{before}}',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} date must not be less than {{before}}',
            self::INCLUSIVE => '{{name}} date must not be less than or equal to {{before}}',
        ],
    ];

    public function chooseTemplate()
    {
        return $this->getParam('inclusive') ? static::INCLUSIVE : static::STANDARD;
    }
}