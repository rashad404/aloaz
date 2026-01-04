<?php
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?><script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Kisi',     <?php echo $manCount?>],
            ['Qadin',      <?php echo $womanCount?>],
        ]);

        var options = {
            title: 'Gender',
            is3D: true
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
    }




    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart1);
    function drawChart1() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['18-25',     <?php echo $age_18_25?>],
            ['25-30',     <?php echo $age_25_30?>],
            ['30-40',     <?php echo $age_30_40?>],
            ['40 +',     <?php echo $age_40?>],
            ['Not set',     <?php echo $age_not_set;?>],

        ]);

        var options = {
            title: 'Age',
            is3D: true
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d_1'));
        chart.draw(data, options);
    }

    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart2);
    function drawChart2() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Aktiv söhbətlər',     <?php echo $active_messages_count;?>],
            ['Deaktiv söhbətlər',      <?php echo $deactive_messages_count?>],
        ]);

        var options = {
            title: 'Conversations Status',
            is3D: true
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d_2'));
        chart.draw(data, options);
    }

    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart4);
    function drawChart4() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['profil şəkli olanlar',     <?php echo $isset_profile_photo_count;?>],
            ['Profil şəkli olmayanlar',      <?php echo $empty_profile_photo_count?>],
        ]);

        var options = {
            title: 'Profile Photos',
            is3D: true
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d_4'));
        chart.draw(data, options);
    }



    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart3);
    function drawChart3() {
        var data = google.visualization.arrayToDataTable([
            ['Heftenin gunleri', 'Users', 'Messages'],
            ['1-ci gun',  100,      10000],
            ['2-ci gun',  200,      2000],
            ['3-cu gun',  600,       4000],
            ['4-cu gun',  350,      2500],
            ['5-cu gun',  350,      2500],
            ['6-cu gun',  350,      2500],
            ['7-cu gun',  350,      2500]
        ]);

        var options = {
            title: 'Alochat Performance',
            hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
            vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }


</script>

<div class="site-index">

         <h2>Statistika!</h2>

    <div class="body-content">


        <div class="row">
            <div class="col-md-6">
                <div id="piechart_3d" style="width: 500px; height: 300px;"></div>
            </div>
            <div class="col-md-6">
                <div id="piechart_3d_1" style="width: 500px; height: 300px;"></div>
            </div>
            <div class="col-md-6">
                <div id="piechart_3d_2" style="width: 500px; height: 300px;"></div>
            </div>
            <div class="col-md-6">
                <div id="piechart_3d_4" style="width: 500px; height: 300px;"></div>
            </div>

        </div>

    </div>
</div>
acilmasi : <?= $vaxt; ?>