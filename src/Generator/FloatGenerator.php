<?php
namespace Eris\Generator;

use Eris\Generator;
use DomainException;

function float()
{
    return new FloatGenerator();
}

class FloatGenerator implements Generator
{
    public function __construct()
    {
    }

    public function __invoke($size, $rand)
    {
        $value = (float) $rand(0, $size) / (float) $rand(1, $size);

        $signedValue = $rand(0, 1) === 0
            ? $value
            : $value * (-1);
        return GeneratedValueSingle::fromJustValue($signedValue, 'float');
    }

    public function shrink(GeneratedValueSingle $element)
    {
        $value = $element->unbox();
        if (!$this->contains($element)) {
            throw new DomainException(
                'Cannot shrink ' . $value . ' because it does not belong ' .
                'to the domain of Floats'
            );
        }

        if ($value < 0.0) {
            return GeneratedValueSingle::fromJustValue(min($value + 1.0, 0.0), 'float');
        }
        if ($value > 0.0) {
            return GeneratedValueSingle::fromJustValue(max($value - 1.0, 0.0), 'float');
        }
        return GeneratedValueSingle::fromJustValue(0.0, 'float');
    }

    public function contains(GeneratedValueSingle $element)
    {
        return is_float($element->unbox());
    }
}
