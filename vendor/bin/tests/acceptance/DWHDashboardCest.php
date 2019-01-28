<?php


class DWHDashboardCest
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
    public function dashboardWidgetExists(AcceptanceTester $I)
    {
	    $I->amOnAdminPage('/');
    	$I->see("Writing Daily Habit: How are you doing today");

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
		$I->see("0 / 8");
		$I->see("Time is running");

	    //I enter the first post
		// I can't use the havePostInDatabase because this won't trigger the post_update action
		//$I->havePostInDatabase(['post_type' => 'post', 'post_title' => 'A post', 'post_status' => 'publish', 'post_content' => 'three word post']);

		// Gather post data.
		/*$my_post = array(
			'post_title'    => 'My post',
			'post_content'  => 'Three words post',
			'post_status'   => 'publish',
			'post_author'   => 1,
		);
		wp_insert_post( $my_post ); */

        $I->factory()->post->create( array( 'post_title' => 'Test Post tbree', 'post_content' => 'a four words post', 'post_author'=>'1', 'post_status'=>'draft' ));
	//		$p = factory->post->create( array( 'post_title' => 'Test Post tbree' ) );
		// 3 < 20 - I should see on the dashboard the message saying to keep going
		$I->amOnAdminPage('/');
		$I->see("4 / 8");
		$I->see("Keep pushing your writing");

	//I add a second post

		$I->factory()->post->create( array( 'post_title' => 'Test Post four', 'post_content' => 'another four words post', 'post_author'=>'1','post_status'=>'draft' ));

		//3+6>8 - I should see a good to go message
		$I->amOnAdminPage('/');
		$I->see("8 / 8");
		$I->see("good to go");
		//check vendor/lucatume/wp-browser  .... WPDb.php class
	}
}
