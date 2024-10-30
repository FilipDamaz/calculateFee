<?php

namespace PragmaGoTech\Interview;

use PragmaGoTech\Interview\Model\LoanProposal;

class FeeCalculatorImpl implements FeeCalculator
{
    use MathUtilities;

    public function calculate(LoanProposal $loanProposal): float
    {
        $term = $loanProposal->getTerm();
        $amount = $loanProposal->getAmount();

        $structure = FeeStructure::getStructure($term);
        if ($structure === null) {
            throw new \InvalidArgumentException("Invalid term.");
        }

        return $this->getFee($amount, $structure);
    }

    /**
     * Calculate fee based on amount and fee structure.
     *
     * @param float $amount
     * @param array<float|int, float> $structure
     * @return float
     */
    private function getFee(float $amount, array $structure): float
    {
        $lowerBound = null;
        $upperBound = null;
        $lowerFee = null;
        $upperFee = null;

        foreach ($structure as $key => $fee) {
            if ($amount < $key) {
                $upperBound = $key;
                $upperFee = $fee;
                break;
            }
            $lowerBound = $key;
            $lowerFee = $fee;
        }

        // Default to 0 if no lower fee is available to avoid null values
        $lowerFee = $lowerFee ?? 0;
        $upperFee = $upperFee ?? 0;

        if ($lowerBound === null) {
            return self::roundUp(0, $amount); // No fee for amounts < 1000
        } elseif ($upperBound === null) {
            return self::roundUp($lowerFee, $amount); // Maximum fee
        }

        // Interpolate linearly between the two breakpoints
        return self::linearInterpolation(
            $amount,
            (float) $lowerBound,
            (float) $lowerFee,
            (float) $upperBound,
            (float) $upperFee
        );
    }
}
