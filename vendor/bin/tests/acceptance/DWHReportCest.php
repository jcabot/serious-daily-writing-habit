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
	    $I->amOnAdminPage('admin.php?page=dwh');
    	$I->see("Daily Writing Habit reports"); //the HTML side loads
	    $I->seeInSource("# of Written Words"); // this text is inside a script tag
    	//$I->see("# of Written Words");                            // the JavaScript side loads
    }
}
