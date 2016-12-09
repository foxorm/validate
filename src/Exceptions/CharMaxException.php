<?php
namespace FoxORM\Validate\Exceptions;
use Respect\Validation\Exceptions\ValidationException;
class CharMaxException extends ValidationException{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} length must be less than or equal to "{{length}}"',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} length must be less than or equal to "{{length}}"',
        ],
    ];
}