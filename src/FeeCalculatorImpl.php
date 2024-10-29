<?php
namespace PragmaGoTech\Interview;

use PragmaGoTech\Interview\Model\LoanProposal;

class FeeCalculatorImpl implements FeeCalculator {
    use MathUtilities; // Use the MathUtilities trait

    public function calculate(LoanProposal $loanProposal): float {
        $term = $loanProposal->getTerm();
        $amount = $loanProposal->getAmount();

        // Get the fee structure from FeeStructure class
        $structure = FeeStructure::getStructure($term);
        if ($structure === null) {
            throw new \InvalidArgumentException("Invalid term.");
        }

        return $this->getFee($amount, $structure);
    }

    private function getFee(float $amount, array $structure): float {
        // Handle breakpoints
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

        if ($lowerBound === null) {
            return self::roundUp(0, $amount); // No fee for amounts < 1000
        } elseif ($upperBound === null) {
            return self::roundUp($lowerFee, $amount); // Maximum fee
        }

        // Interpolate linearly between the two breakpoints
        return self::linearInterpolation($amount, $lowerBound, $lowerFee, $upperBound, $upperFee);
    }
}
