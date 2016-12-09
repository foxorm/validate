<?php
namespace FoxORM\Validate\Exceptions;
use Respect\Validation\Exceptions\ValidationException;
class StringAlphaException extends ValidationException{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must must be an alpha string',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not be an alpha string',
        ],
    ];
}