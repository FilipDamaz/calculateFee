<?php
use PragmaGoTech\Interview\MathUtilities;

class MathUtilitiesTest extends \PHPUnit\Framework\TestCase {
    public function testLinearInterpolation() {
        $result = MathUtilities::linearInterpolation(2500, 2000, 90, 3000, 90);
        $this->assertEquals(90, $result); // 2500 is still within the lower bound

        $result = MathUtilities::linearInterpolation(2500, 2000, 90, 4000, 115);
        $this->assertEquals(96.25, $result); // Interpolated value
    }

    public function testRoundUp() {
        $result = MathUtilities::roundUp(115, 2750);
        $this->assertEquals(115, $result); // Total will be 2865, already multiple of 5

        $result = MathUtilities::roundUp(114, 2749);
        $this->assertEquals(116, $result); // Total will be 2864, round up to next multiple of 5
    }
}
