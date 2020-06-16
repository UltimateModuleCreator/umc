<?php

/**
 * UMC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @copyright Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru <ultimate.module.creator@gmail.com>
 *
 */

declare(strict_types=1);

namespace App\Umc\CoreBundle\Service\Validator;

class RestrictedWords implements ValidatorInterface
{
    /**
     * @var string[]
     */
    private $reserved = [
        '__halt_compiler', 'abstract', 'and', 'array',
        'as', 'break', 'callable', 'case',
        'catch', 'class', 'clone', 'const',
        'continue', 'declare', 'default', 'die',
        'do', 'echo', 'else', 'elseif',
        'empty', 'enddeclare', 'endfor', 'endforeach',
        'endif', 'endswitch', 'endwhile', 'eval',
        'exit', 'extends', 'final', 'for',
        'foreach', 'function', 'global', 'goto',
        'if', 'implements', 'include', 'include_once',
        'instanceof', 'insteadof', 'interface', 'isset',
        'list', 'namespace', 'new', 'or',
        'print', 'private', 'protected', 'public',
        'require', 'require_once', 'return', 'static',
        'switch', 'throw', 'trait', 'try',
        'unset', 'use', 'var', 'while', 'xor', 'void'
    ];
    /**
     * @var string
     */
    private $getter;
    /**
     * @var array
     */
    private $restricted;
    /**
     * @var string
     */
    private $errorMessage;

    /**
     * RestrictedWords constructor.
     * @param string $getter
     * @param array $restricted
     * @param string $errorMessage
     */
    public function __construct(string $getter, array $restricted, string $errorMessage)
    {
        $this->getter = $getter;
        $this->restricted = $restricted;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @param object $object
     * @return string[]
     */
    public function validate($object): array
    {
        $errors = [];
        $getter = $this->getter;
        $value = $object->$getter();
        if (in_array($value, $this->restricted)) {
            $errors[] = sprintf($this->errorMessage, $value);
        }
        if (in_array($value, $this->reserved)) {
            $errors[] = "{$value} is a reserved PHP word and cannot be used";
        }
        return $errors;
    }
}
