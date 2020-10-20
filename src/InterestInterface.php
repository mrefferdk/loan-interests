<?php


namespace effer\LoanInterests;


interface InterestInterface
{
    public function loadHtmlFromUrl();

    public function findInterestsInHtml($html): array;


}
