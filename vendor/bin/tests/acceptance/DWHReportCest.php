<?php 

class DWHReportCest
{
    public function _before(AcceptanceTester $I)
    {
	    $I->haveHttpHeader('X-Requested-With', 'Codeception');
	    $I->loginAsAdmin();
	    $I->amOnPluginsPage();
	    $I->seePluginInstalled('serious-daily-writing-habit');
	    $I->activatePlugin('serious-daily-writing-habit');
    }

    // tests
    public function reportPageLoads(AcceptanceTester $I)
    {
    }
}
