<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Jaunumu pārvaldība</h1>
            <hr/>
            <a href="/" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    <div class='row main-block'>
        <?php if(Session::get_flash('error')) { ?>
            <div class="alert alert-danger">
                <p>Kļūda!<?php echo Session::get_flash('error'); ?></p>
            </div>
        <?php } ?>
        <?php if(Session::get_flash('success')) { ?>
            <div class="alert alert-success">
                <p><?php echo Session::get_flash('success'); ?></p>
            </div>
        <?php } ?>
        
        <?php if ($news): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nosaukums</th>
                        <th>Kopsavilkums</th>
                        <th>Statuss</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($news as $item): ?>		
                        <tr>
                            <td><?php echo $item->title; ?></td>
                            <td><?php echo $item->excerpt; ?></td>
                            <td><?php echo $item->status; ?></td>
                            <td>
                                <div class="btn-toolbar">
                                    <div class="btn-group">
                                        <?php if($item->status != 'Publisks') { ?>
                                        <a href='/darbinieks/jaunumi/publicet/<?php echo $item->id; ?>' class='btn btn-sm btn-primary'>Publicēt</a>
                                        <?php } ?>
                                        <a href='/darbinieks/jaunumi/skatit/<?php echo $item->id; ?>' class='btn btn-sm btn-default'>Apskatīt</a>
                                        <a href='/darbinieks/jaunumi/labot/<?php echo $item->id; ?>' class='btn btn-sm btn-default'>Labot</a>
                                        <a href='/darbinieks/jaunumi/dzest/<?php echo $item->id; ?>' class='btn btn-sm btn-danger' onclick='return confirm("Vai tiešām vēlaties dzēst?")'>Dzēst</a>
                                    </div>
                                </div>
                            </td>
                            </tr>
                    <?php endforeach; ?>	
                </tbody>
            </table>

        <?php else: ?>
        <p>Pašlaik nav izveidota neviena ziņa</p>
        <?php endif; ?>
        
        <p>
            <a href='/darbinieks/jaunumi/izveidot' class='btn btn-success'>Pievienot jaunu ziņu</a>
        </p>
    </div>
