<?php 

class DWHDashboardCest
{
    public function _before(AcceptanceTester $I)
    {
	    $I->loginAsAdmin();
	    $I->amOnAdminPage('/');
    }

    // tests
    public function dashboardWidgetExists(AcceptanceTester $I)
    {
		$I->see("Writing Daily Habit: How are you doing today");
	    //$I->havePostInDatabase(['post_type' => 'post', 'post_title' => 'A post', 'post_status' => 'publish']);
	    //check vendor/lucatume/wp-browser  .... WPDb.php class
    }

	// tests
	public function dashboardWidgetMessages(AcceptanceTester $I)
	{
		//I prepare the target wordcount
		$I->amOnAdminPage('admin.php?page=dwh-options');
		$I->fillField('dwh_options[target_number_words]',8);
		$I->fillField('dwh_options[number_days_show_habit]',5);
		$I->click('Save Changes');

		//Initially I have zero words written today so I should see the alert message
		$I->amOnAdminPage('/');
		$I->see("Time is running");

		//I enter the first post
		$I->havePostInDatabase(['post_type' => 'post', 'post_title' => 'A post', 'post_status' => 'publish', 'post_content' => 'three word post']);

		// 3 < 20 - I should see on the dashboard the message saying to keep going
		$I->amOnAdminPage('/');
		$I->see("Keep pushing your writing");

		//I add a second post

		$I->havePostInDatabase(['post_type' => 'post', 'post_title' => 'A post', 'post_status' => 'publish', 'post_content' => 'this is a six word post']);

		//3+6>8 - I should see a good to go message
		$I->amOnAdminPage('/');
		$I->see("good to go");

		//check vendor/lucatume/wp-browser  .... WPDb.php class
	}
}
