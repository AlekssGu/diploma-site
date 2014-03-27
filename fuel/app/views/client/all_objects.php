
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Mani objekti</h1>
            <hr/>
            <a href="/klients" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>Nr.p.k</th>
                    <th>Nosaukums</th> 
                    <th>Adrese</th>
                    <th>Piezīmes</th>
                    <th>Skatīt</th>
                    <th>Labot</th>
                    <th>Dzēst</th>
                </tr>
                <?php if(isset($all_objects)) 
                    {   foreach ($all_objects as $nr => $key) 
                        { ?>
                <tr>
                    <td><?php echo $nr+1; ?></td>
                    <td><?php echo $key->name; ?></td>
                    <td><?php echo $key->address; ?></td>
                    <td><?php echo $key->notes; ?></td>
                    <td><a href="/klients/objekti/apskatit/<?php echo $key->object_id;?>"><span class="glyphicon glyphicon-eye-open"></span></a></td>
                    <td><a href="#"><span class="glyphicon glyphicon-pencil"></span></a></td>
                    <td><a href="/klients/objekti/dzest/<?php echo $key->object_id;?>"><span class="glyphicon glyphicon-remove"></span></a></td>
                    
                </tr>
                <?php }
                    }   ?>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
//
    });
</script>