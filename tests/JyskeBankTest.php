<?php

use effer\LoanInterests\JyskeBank;
use PHPUnit\Framework\TestCase;

/**
 * @covers effer\LoanInterests\JyskeBank
 */
class JyskeBankTest extends TestCase {

    private $htmlOutput = "<table class=\"table no-trailer not-responsive\">
								<caption>Jyske Rentetilpasningslån - 30 år</caption>
								<thead>
									<tr>
										<th>Periode år</th>
										<th>Rente %</th>
									</tr>
								</thead>
								<tbody>
										<tr>
											<td>1</td>	
											<td>-0,48
											</td>
										</tr>
										<tr>
											<td>2</td>	
											<td>-0,20
											</td>
										</tr>
										<tr>
											<td>3</td>	
											<td>-0,42
											</td>
										</tr>
										<tr>
											<td>4</td>	
											<td>-0,34
											</td>
										</tr>
										
										<tr>
											<td>6</td>	
											<td>0,26
											</td>
										</tr>
								</tbody>
							</table>";

    private $badHtml = "<table class=\"table no-trailer not-responsive\">
								<caption>Jyske Rentetilpasningslån - 30 år</caption>
								<thead>
									<tr>
										<th>Periode år</th>
										<th>Rente %</th>
									</tr>
								</thead>
								<tbody>
										<tr>
											<td>1</td>	
											<td>xxx
											</td>
										</tr>
										
								</tbody>
							</table>";


    public function testFindInterestsInHtml(): void
    {

        $jyskeBank = new JyskeBank();
        $jyskeBank->setHtmlContent($this->htmlOutput);
        $expected = [
            '-0,48',
            '-0,20',
            '-0,42',
            '-0,34',
            '0,26',
        ];

        $this->assertEquals($expected, $jyskeBank->findInterestsInHtml($this->htmlOutput));

        $this->expectException(\Exception::class);

        $jyskeBank->findInterestsInHtml($this->badHtml);
    }


    public function testGetF1Interest(): void
    {
        $jyskeBank = new JyskeBank();
        $jyskeBank->setHtmlContent($this->htmlOutput);
        $expected = '-0.48';
        $this->assertEquals($expected, $jyskeBank->getF1Interest());

        $expected = '-0.20';
        $this->assertEquals($expected, $jyskeBank->getF2Interest());

        $expected = '-0.42';
        $this->assertEquals($expected, $jyskeBank->getF3Interest());

        $expected = '-0.34';
        $this->assertEquals($expected, $jyskeBank->getF4Interest());

        $expected = '0.26';
        $this->assertEquals($expected, $jyskeBank->getF5Interest());
    }

    public function testGetInterestByYear(): void
    {
        $jyskeBank = new JyskeBank();
        $jyskeBank->setHtmlContent($this->htmlOutput);

        $expected = '0.26';
        $this->assertEquals($expected, $jyskeBank->getInterestByYear(5));

        $expected = '-0.48';
        $this->assertEquals($expected, $jyskeBank->getInterestByYear(1));

        $this->expectException(\Exception::class);
        $this->assertEquals($expected, $jyskeBank->getInterestByYear(0));
    }

    public function testConvertCommaSeparator(): void
    {
        $jyskeBank = new JyskeBank();
        $this->assertEquals('2.5', $jyskeBank->convertCommaSeparator('2,5'));
    }
}
