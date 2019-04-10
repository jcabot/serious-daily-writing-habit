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
	    //I prepare the target wordcount
	    $I->amOnAdminPage('admin.php?page=dwh-options');
	    $I->fillField('dwh_options[target_number_words]',8);
	    $I->fillField('dwh_options[number_days_show_habit]',2);
	    $I->click('Save Changes');
    }

    // tests
    public function reportPageLoads(AcceptanceTester $I)
    {
	    $I->amOnAdminPage('admin.php?page=dwh');
    	$I->see("Daily Writing Habit reports"); //the HTML side loads
	    $I->seeInSource("# of Written Words"); // // the JavaScript side loads-this text is inside a script tag
	   	//$I->see("# of Written Words");   //cannot be used since this is part of the javascript text
    }

	public function reportCalculation(AcceptanceTester $I)
	{

		$today = new DateTime('today');
		$today_parsed = $today->format('Y-m-d H:i:s');
		$I->factory()->post->create( array( 'post_title' => 'Test Post report', 'post_content' => 'a first 4words post ',
			'post_date'=>$today_parsed,'post_modified'=>$today_parsed, 'post_author'=>'1', 'post_status'=>'draft' ));


		$I->amOnAdminPage('admin.php?page=dwh');
		$I->see("Daily Writing Habit reports"); //the HTML side loads
		$I->seeInSource('[0, 4]');
		$I->seeInSource('[2, 2]'); //average straight line

		//$I->see("# of Written Words");                            // the JavaScript side loads
	}
    
}
