<?php
/**
 * Created by PhpStorm.
 * User: jcabo
 * Date: 11/28/2018
 * Time: 7:32 PM
 */

class DWHActivatePluginCest {

	public function _before(AcceptanceTester $I)
	{
	}

	public function _after(AcceptanceTester $I)
	{

	}


	public function environmentTest(AcceptanceTester $I)
	{
		$I->loginAsAdmin();
		$I->amOnAdminPage('/');
		$I->see('Dashboard');
	}

	public function activateTest(AcceptanceTester $I)
	{

		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		$I->seePluginInstalled('serious-daily-writing-habit');
	    $I->activatePlugin('serious-daily-writing-habit');
	}

	public function deactivateTest(AcceptanceTester $I)
	{
		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		$I->seePluginInstalled('serious-daily-writing-habit');
		$I->activatePlugin('serious-daily-writing-habit');
		$I->deactivatePlugin('serious-daily-writing-habit');
	}
}
