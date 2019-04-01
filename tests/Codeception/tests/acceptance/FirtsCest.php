<?php

class FirtsCest
{
    public function tryEnteringTheDemoSite(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Search');
    }

    public function trySearchingForAFile(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->fillField('contains', 'abc');
        $I->click('Search');
        $I->see('/var/www/html/public/demo_files/file1.txt');
    }
}
