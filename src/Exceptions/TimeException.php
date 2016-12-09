<?php
namespace FoxORM\Validate\Exceptions;
use Respect\Validation\Exceptions\ValidationException;
class TimeException extends ValidationException{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must be valid time value',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not be valid time value',
        ],
    ];
}