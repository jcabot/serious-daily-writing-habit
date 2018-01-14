<?php


class WPFirstCest
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
	    $I->amOnPage('/');
	    $I->see('DEV_PLUGINS');
	    $I->loginAsAdmin();

	    $I->amOnPluginsPage();
       //$I->canSee('Hello Dolly');
	    $I->see('Daily');


	    //$I->loginAs('admin', 'admin');


	 //   $I->see('Hello Dolly');
	  //  $I->activatePlugin('daily-writing-habit');
	  //$I->seePluginInstalled( 'daily-writing-habit' );
    }
}
