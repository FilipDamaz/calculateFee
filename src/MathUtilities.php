<?php
namespace PragmaGoTech\Interview;

trait MathUtilities {
    public static function linearInterpolation(float $x, float $x0, float $y0, float $x1, float $y1): float {
        // Calculate the interpolated value
        $slope = ($y1 - $y0) / ($x1 - $x0);
        return $y0 + ($slope * ($x - $x0));
    }

    public static function roundUp(float $numberToRound, float $secondNumber, int $roundPrecision = 5): float {
        $total = $numberToRound + $secondNumber;
        return ceil($total / $roundPrecision) * $roundPrecision - $secondNumber;
    }
}
