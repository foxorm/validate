<?php
namespace FoxORM\Validate\Exceptions;
use Respect\Validation\Exceptions\ValidationException;
class CharEqualException extends ValidationException{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} length must be equal to "{{length}}"',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} length must not be equal to "{{length}}"',
        ],
    ];
}