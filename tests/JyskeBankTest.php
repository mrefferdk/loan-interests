<?php

use effer\LoanInterests\JyskeBank;
use PHPUnit\Framework\TestCase;

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
											<td>5</td>	
											<td>-0,32
											</td>
										</tr>
										<tr>
											<td>6</td>	
											<td>-0,26
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
            '-0,32',
            '-0,26',
        ];

        $this->assertEquals($expected, $jyskeBank->findInterestsInHtml());
    }

    public function testConvertCommaSeparator(): void
    {
        $jyskeBank = new JyskeBank();
        $this->assertEquals('2.5', $jyskeBank->convertCommaSeparator('2,5'));
    }
}
