<?php 

class DWHSettingsCest
{
    public function _before(AcceptanceTester $I)
    {
	    $I->loginAsAdmin();
	    $I->amOnAdminPage('/');
    }

    // tests
    public function testingGoalConfigurationPage(AcceptanceTester $I)
    {
		$I->amOnAdminPage('admin.php?page=dwh-options');
		$I->see('Target number of words to write every day');
		$I->fillField('dwh_options[target_number_words]',200);
	    $I->fillField('dwh_options[number_days_show_habit]',5);
	    $I->click('Save Changes');
	    //Now I moved out of the page and come back again to see if the values were properly saved
	    $I->amOnAdminPage('/');
	    $I->amOnAdminPage('admin.php?page=dwh-options');
	    $I->seeInField('dwh_options[target_number_words]',200);
	    $I->seeInField('dwh_options[number_days_show_habit]',5);

    }
}
