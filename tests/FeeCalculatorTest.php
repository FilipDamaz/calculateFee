<?php
use PragmaGoTech\Interview\FeeCalculatorImpl;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\FeeStructure;

class FeeCalculatorTest extends \PHPUnit\Framework\TestCase {
    private $calculator;

    protected function setUp(): void {
        $this->calculator = new FeeCalculatorImpl();
    }

    public function testCalculateFeeFor12Months() {
        $loanProposal = new LoanProposal(12, 11500);
        $this->assertEquals(230, $this->calculator->calculate($loanProposal));

        $loanProposal = new LoanProposal(12, 19250);
        $this->assertEquals(385, $this->calculator->calculate($loanProposal));
    }

    public function testCalculateFeeFor24Months() {
        $loanProposal = new LoanProposal(24, 2750);
        $this->assertEquals(115, $this->calculator->calculate($loanProposal));

        $loanProposal = new LoanProposal(24, 13500);
        $this->assertEquals(540, $this->calculator->calculate($loanProposal));
    }

    public function testEdgeCases() {
        $loanProposal = new LoanProposal(12, 1000);
        $this->assertEquals(50, $this->calculator->calculate($loanProposal));

        $loanProposal = new LoanProposal(12, 20000);
        $this->assertEquals(400, $this->calculator->calculate($loanProposal));
    }

    public function testInvalidTerm() {
        $this->expectException(\InvalidArgumentException::class);
        $loanProposal = new LoanProposal(36, 10000);
        $this->calculator->calculate($loanProposal);
    }

    public function testFeeStructure() {
        $structure = FeeStructure::getStructure(12);
        $this->assertNotNull($structure);
        $this->assertArrayHasKey(1000, $structure);
        $this->assertEquals(50, $structure[1000]);

        $structure = FeeStructure::getStructure(24);
        $this->assertNotNull($structure);
        $this->assertArrayHasKey(2000, $structure);
        $this->assertEquals(100, $structure[2000]);
    }
}
