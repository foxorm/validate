<?php
namespace FoxORM\Validate\Exceptions;
use Respect\Validation\Exceptions\ValidationException;
class LuhnException extends ValidationException{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must be a valid SIREN or SIRET number',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not be a valid SIREN or SIRET number',
        ],
    ];
}