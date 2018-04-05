<?php


class DWHDeActivateCest
{
    public function _before(AcceptanceTester $I)
    {
	    $I->loginAsAdmin();
	    $I->amOnPluginsPage();
	    $I->seePluginInstalled( 'daily-writing-habit' );
	    $I->activatePlugin('daily-writing-habit');
	    $I->seePluginActivated('daily-writing-habit');
    }

    public function _after(AcceptanceTester $I)
    {

    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
	    $I->deactivatePlugin('daily-writing-habit');
	    $I->seePluginDeactivated('daily-writing-habit');
    }
}
