<?php
namespace FoxORM\Validate\Exceptions;
use Respect\Validation\Exceptions\ValidationException;
class StringAlphaNumericException extends ValidationException{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must be an alpha-numeric string',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not be an alpha-numeric string',
        ],
    ];
}