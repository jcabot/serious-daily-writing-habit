<?php

/**
 * Results display view for the plugin
 *
 * @since      1.0.0
 * @package    Daily_Writing_Admin\admin
 * @subpackage Daily_Writing_Admin\admin\partials
 */



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function results_page_layout() {
	echo '<div class="wrap">' . "\n";
	echo '	<h1>' . get_admin_page_title() . '</h1>' . "\n";
	echo 'test page';
}
    /*
    $target=get_option('target_number_words');
    $ndays=get_option('number_days_show_habit');
	$target_datapoints=[];
	$days_datapoints=[];
	for ($i = 1; $i <= $ndays; $i++) {
		$days_datapoints[]=$i;  // X-axis for the chart
		$target_datapoints[]=$target; // fixed horizontal line to help visualizing the days we reached the target
	}
	$counts_datapoints=get_latests_increments($ndays);


    // Admin Page Layout
    echo '<div class="wrap">' . "\n";
    echo '	<h1>' . get_admin_page_title() . '</h1>' . "\n";
    echo 'test page';
    ?>

        <canvas id="myChart" width="400" height="400"></canvas>
        <script>
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?php echo implode( ', ', $days_datapoints ); ?>], //Example - labels:   ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                datasets: [{
                    label: '# of Words',
                    data: [<?php echo implode( ', ', $counts_datapoints ); ?>], // Exaple - data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                },{
                    label: 'Writing Target',
                    data: [<?php echo implode( ', ', $target_datapoints ); ?>], // Exaple - data: [12, 19, 3, 5, 2, 3],
                    type: 'line'
                }]
            ],

        }});
        </script>

  <?php
    echo '</div>' . "\n";

    }

?>
    */
