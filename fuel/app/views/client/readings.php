<?php echo Asset::js('bootstrap-datepicker.js'); ?>
<?php echo Asset::css('datepicker3.css'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Ievadīt skaitītāja mērījumus</h1>
            <hr/>
            <a href="/" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    

    <div class="row">
        <br/>
            <div class="col-md-12" id="skaititaji">
                <?php if(!empty($meters)) { ?>
                
                        <div class='entered-reading hidden'>
                            <hr/>
                            <h3 class='text-center'></h3>
                            <hr/>
                        </div>
                
                            <table class="table text-center">
                                <thead>
                                    <th>Nr.p.k</th>
                                    <th>Ūdens tips</th>
                                    <th>Skaitītāja numurs</th>
                                    <th>Pēdējās iesniegšanas datums</th>
                                    <th>Iepriekšējais rādījums</th>
                                    <th>Nākošais rādījums</th>
                                    <th></th>
                                    <th></th>
                                    <th>Rādījumu vēsture</th>
                                </thead>
                                <?php foreach ($meters as $nr => $meter) { ?>
                                <tr>
                                    <td><?php echo $nr + 1; ?></td>
                                    <td><?php if($meter->water_type == 'A') echo 'Aukstais ūdens'; else echo 'Karstais ūdens'; ?></td>
                                    <td><?php echo $meter->meter_number;?></td>
                                    <td><?php echo date_format(date_create($meter->date_taken), 'd.m.Y') ;?></td>
                                    <td class='previous-reading'><?php echo $meter->lead; ?></td>
                                    <td>
                                        <form method="POST" action="/klients/iesniegt-merijumu">
                                            <input type="hidden" name="meter_id" value="<?php echo $meter->id;?>"/>
                                            <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
                                            <input autofocus name="reading" class="reading-input form-control" type="text"/>
                                            <input type='hidden' class='input-action' name="action" />
                                            
                                    </td>
                                    <td><button onclick='return confirm("Iesniegt bez iespējas vēlāk labot?")' type="submit" class="submit-rdn btn btn-default">Iesniegt</button></td>
                                    <td><button type="submit" class="save-rdn btn btn-primary">Saglabāt</button></td>
                                        </form>
                                    <td><a href="/klients/objekti/radijumi/<?php echo $meter->id; ?>" class="btn btn-link">Apskatīt</a></td>
                                </tr>
                                <?php } ?>
                            </table>
                <?php } else echo '<p>Jums pašlaik nav neviens skaitītājs</p>'; ?>
            </div>            
        </div>

<script>
    $(document).ready(function() {
    
    $('#mytab a:first').tab('show');
    
    $('.dismiss_service').click(function(){
        $('#service').attr('value',$(this).attr('data-pk'));
    });
    
        //Ja ievadīts rādījums, tad parādām lietotājam, cik daudz ir ievadīts kopš pagājušā mērījuma
    $('.reading-input').keyup(function() {
        //Ja nav skaitliska vērtība, tad paziņo
        if(!parseInt($(this).val()))
        {
            $('.entered-reading h3').text('Ievadītajam rādījumam jābūt skaitliskai vērtībai!');
            $('.entered-reading').removeClass('hidden');
        }
        else if ( (parseInt($(this).val()) - parseInt($(this).parent().parent().parent().find('.previous-reading').text())) < 0 )
        {
            $('.entered-reading h3').text('Ievadītajam rādījumam jābūt lielākam par iepriekšējo rādījumu!');
            $('.entered-reading').removeClass('hidden');
        }
        else
        {
            //Ja ir skaitliska vērtība, tad parāda, cik ir ievadīts
            $('.entered-reading h3').text('Jūsu ievadītais patēriņš: ' + (parseInt($(this).val()) - parseInt($(this).parent().parent().parent().find('.previous-reading').text()) ) + ' kubikmetri');
            $('.entered-reading').removeClass('hidden');
        }   
    });
    
    $('.date-pick .input-group.date').datepicker({
        weekStart: 1,
        format: "dd.mm.yyyy",
        autoclose: true,
        todayBtn: "linked",
        language: "lv",
        orientation: "top auto",
        todayHighlight: true
    });
    
    //Darbības {saglabāt;iesniegt}
    $('.submit-rdn').click(function(){
        $('.input-action').attr('value','1');
    })    
    $('.save-rdn').click(function(){
        $('.input-action').attr('value','2');
    })    

    });
</script>