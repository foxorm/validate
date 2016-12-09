<?php
namespace FoxORM\Validate\Exceptions;
use Respect\Validation\Exceptions\ValidationException;
class StringAlphaDashException extends ValidationException{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must be an alpha-dash string',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not be an alpha-dash string',
        ],
    ];
}