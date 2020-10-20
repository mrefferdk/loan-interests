<?php

namespace effer\LoanInterests;

use Exception;

class JyskeBank implements InterestInterface
{
    protected $url = "https://www.jyskebank.dk/bolig"; // More interests: https://www.jyskebank.dk/bolig/boliglaan/kurser

    protected $htmlContent;

    /**
     * @return string
     */
    public function loadHtmlFromUrl()
    {
        if (empty($this->htmlContent)) {
            $this->htmlContent = file_get_contents($this->url); // @codeCoverageIgnore
        }

        return $this->htmlContent;
    }


    public function findInterestsInHtml($html): array
    {
        //$html = $this->loadHtmlFromUrl();
        $tableHtml = $this->findTableHtml($html);
        $pattern = "@<td>(-?[0-9]+,[0-9]+)@s";
        preg_match_all($pattern, $tableHtml, $matches);

        if (!isset($matches[1]) || count($matches[1]) < 5) {
            throw new Exception('Invalid preg_match. Html might have changed');
        }
        return $matches[1];
    }

    /**
     * Replace Danish comma separated values with PHP comma separator
     *
     * @param $number
     * @return mixed
     */
    public function convertCommaSeparator($number)
    {
        return str_replace(',', '.', $number);
    }

    /**
     * @param $html
     * @return mixed
     */
    public function findTableHtml($html)
    {
        $pattern = "@<caption>Jyske Rentetilpasning(.*)" . "/tbody>@s";
        preg_match($pattern, $html, $matches);
        return $matches[0];
    }

    /**
     * @param $year - use values from 1-6 . JyskeBank has F1-F6 loans
     * @return mixed
     * @throws Exception
     */
    public function getInterestByYear($year)
    {
        if ($year < 1 || $year > 6) {
            throw new Exception('Provide a number from 1 to 6');
        }

        $html = $this->loadHtmlFromUrl();

        $interests = $this->findInterestsInHtml($html);
        $interest = $this->convertCommaSeparator($interests[$year - 1]); // starts with index 0 as F1

        return $interest;
    }

    public function getF1Interest()
    {
        return $this->getInterestByYear(1);
    }

    public function getF2Interest()
    {
        return $this->getInterestByYear(2);
    }

    public function getF3Interest()
    {
        return $this->getInterestByYear(3);
    }

    public function getF4Interest()
    {
        return $this->getInterestByYear(4);
    }

    public function getF5Interest()
    {
        return $this->getInterestByYear(5);
    }

    /**
     * @param mixed $htmlContent
     */
    public function setHtmlContent($htmlContent): void
    {
        $this->htmlContent = $htmlContent;
    }


}
