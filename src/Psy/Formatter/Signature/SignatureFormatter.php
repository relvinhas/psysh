<?php

namespace Psy\Formatter\Signature;

use Psy\Formatter\Formatter;
use Psy\Formatter\Signature\ClassSignatureFormatter;
use Psy\Formatter\Signature\ConstantSignatureFormatter;
use Psy\Formatter\Signature\FunctionSignatureFormatter;
use Psy\Formatter\Signature\MethodSignatureFormatter;
use Psy\Formatter\Signature\PropertySignatureFormatter;
use Psy\Reflection\ReflectionConstant;

/**
 * An abstract representation of a function, class or property signature.
 */
class SignatureFormatter implements Formatter
{
    /**
     * Format a signature for the given reflector.
     *
     * Defers to subclasses to do the actual formatting.
     *
     * @param \Reflector $reflector
     *
     * @return string Formatted signature.
     */
    public static function format(\Reflector $reflector)
    {
        switch (true) {
            case $reflector instanceof \ReflectionFunction:
                return FunctionSignatureFormatter::format($reflector);

            case $reflector instanceof \ReflectionClass:
                return ClassSignatureFormatter::format($reflector);

            case $reflector instanceof ReflectionConstant:
                return ConstantSignatureFormatter::format($reflector);

            case $reflector instanceof \ReflectionMethod:
                return MethodSignatureFormatter::format($reflector);

            case $reflector instanceof \ReflectionProperty:
                return PropertySignatureFormatter::format($reflector);

            default:
                throw new \InvalidArgumentException('Unexpected Reflector class: '.get_class($reflector));
        }
    }

    /**
     * Print the signature name.
     *
     * @return string
     */
    public static function formatName(\Reflector $reflector)
    {
        return $reflector->getName();
    }

    /**
     * Print the method, property or class modifiers.
     *
     * Techinically this should be a trait. Can't wait for 5.4 :)
     */
    protected static function formatModifiers(\Reflector $reflector)
    {
        return implode(' ', array_map(function($modifier) {
            return sprintf('<comment>%s</comment>', $modifier);
        }, \Reflection::getModifierNames($reflector->getModifiers())));
    }
}