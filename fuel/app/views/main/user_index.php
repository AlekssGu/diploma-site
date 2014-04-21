<?php echo Asset::js('highcharts.js'); ?>
<?php echo Asset::js('exporting.js'); ?>
<script>
$(function () {
        $('#container').highcharts({
            title: {
                text: 'Ūdens patēriņš',
                x: -20 //center
            },
            subtitle: {
                text: 'Klienta numurs: 00900299',
                x: -20
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun',
                    'Jūl', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: 'Patēriņš (m3)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            credits: {
                enabled: false
            },
            tooltip: {
                
                valueSuffix: 'm3'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'Ūdensapgāde',
                data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
            }, {
                name: 'Notekūdeņi',
                data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
            }]
        });
    });
    
</script>
            <div class="container">
                <div class="main-block row">
                    <div class="col-md-3">
                        <a href="/klients" class="btn btn-block btn-primary" title="Apskatīt klienta datus"><span class="glyphicon glyphicon-user"></span> Klienta informācija</a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-block btn-primary" title="Dati par patērētajiem resursiem"><span class="glyphicon glyphicon-tint"></span> Patēriņa dati</a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-block btn-primary" title="Saņemtie pakalpojumi"><span class="glyphicon glyphicon-leaf"></span> Pakalpojumi</a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-block btn-primary" title="Uzdot jautājumu"><span class="glyphicon glyphicon-question-sign"></span> Uzdot jautājumu</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <h3 class="text-center">Mana pēdējā aktivitāte</h3>
                                <hr/>
                                <h4>Iesniegtais mērījums:</h4>
                                <p>Pēdējais mērījums: 00041345 (15m<span style="vertical-align:super; font-size:0.7em;">3</span>)</p>
                                <h4>Veiktie maksājumi:</h4>
                                <p>Pēdējais maksājums: 13.03.2014</p>
                                <h4>Saņemtie pakalpojumi:</h4>
                                <p>Pēdējais pakalpojums: Nosēdbedres likvidācija</p>
                            </div>
                        </div>
                        <div class='text-center'>
                        <a href="/klients/iesniegt-merijumu" class="btn btn-info">Ievadīt mērījumu</a>
                        <a href="#" class="btn btn-info">Paziņot par bojājumu</a>
                        <a href="#" class="btn btn-info">Pasūtīt pakalpojumus</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>