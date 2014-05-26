
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Mani objekti</h1>
            <hr/>
            <a href="/klients" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    <div class="row main-block">
        <div class="col-md-12">
            <?php if(!empty($all_objects)) { ?>
                <table class="table table-striped">
                    <tr>
                        <th>Nr.p.k</th>
                        <th>Nosaukums</th> 
                        <th>Adrese</th>
                        <th>Piezīmes</th>
                        <th>Skatīt</th>
                    </tr>
                    <?php if(isset($all_objects)) { ?>
                         <?php foreach ($all_objects as $nr => $key) { ?>
                            <tr>
                                <td><?php echo $nr+1; ?></td>
                                <td><?php echo $key->name; ?></td>
                                <td><?php echo $key->address; ?></td>
                                <td><?php echo $key->notes; ?></td>
                                <td><a href="/klients/objekti/apskatit/<?php echo $key->object_id;?>">Apskatīt</a></td>

                            </tr>
                          <?php } ?>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>Pašlaik nav neviena objekta</p>
            <?php } ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
//
    });
</script>