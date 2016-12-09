<?php
namespace FoxORM\Validate\Exceptions;
use Respect\Validation\Exceptions\ValidationException;
class UrlExistsException extends ValidationException{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must be a existing and reachable url',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not be a existing and reachable url',
        ],
    ];
}