<?php
namespace FoxORM\Validate\Exceptions;
use Respect\Validation\Exceptions\ValidationException;
class NameException extends ValidationException{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must be a valid name',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not be a valid name',
        ],
    ];
}