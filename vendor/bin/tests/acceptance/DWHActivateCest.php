<?php


class DWHActivateCest
{
    public function _before(AcceptanceTester $I)
    {
	    $I->loginAsAdmin();
	    $I->amOnPluginsPage();
	    $I->seePluginInstalled( 'daily-writing-habit' );
    }

    public function _after(AcceptanceTester $I)
    {

    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
	    $I->activatePlugin('daily-writing-habit');
	    $I->seePluginActivated('daily-writing-habit');
    }
}
