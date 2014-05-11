<?php echo Asset::js('jquery.fancybox.pack.js'); ?>
<?php echo Asset::js('jquery.fancybox.js'); ?>
<?php echo Asset::css('jquery.fancybox.css'); ?>
<?php echo Asset::js('jquery.expander.min.js'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-6">
          <h1>Ziņas un aktualitātes</h1> 
          <hr/>  
        </div>
    </div>
    <!-- ziņu bloks -->
    <?php if(!empty($news)) { ?>
    <?php foreach($news as $one) { ?>
    <div class="row">
        <div class="col-md-12">
            <h4><?php echo $one->title; ?></h4>
            <p><i>Publicēts <?php echo Date::forge($one->created_at)->format('%d.%m.%Y'); ?></i></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <a id="image_1" href="/assets/img/news/<?php echo $one->filename_sys; ?>"><img caption="<?php echo $one -> file_source; ?>" class="img img-responsive" src="/assets/img//news/<?php echo $one->filename_sys; ?>" alt=""/></a>
            <p class="text-center text-muted"><i><?php echo $one -> file_source; ?></i></p>
        </div>
        <div class="expandable col-md-8 text-justify">
            <p><?php echo $one -> body; ?></p>
        </div>
    </div>
    <hr/>
    <?php } ?>
    <?php } else { ?>
    <p>Atvaino! Pašlaik mums vēl nav nevienas labas ziņas, ko tev parādīt :)</p>
    <?php } ?>
    <!-- ziņu bloks beidzas -->
</div>

<script>
    $(document).ready(function() {
    $("#image_1").fancybox({
          helpers: {
              title : {
                  type : 'float'
              }
          }
      });
      
    $("#image_2").fancybox({
          helpers: {
              title : {
                  type : 'float'
              }
          }
      });

    // override default options (also overrides global overrides)
    $('div.expandable p').expander({
      slicePoint:       1300,  // default is 100
      expandText:       '<br/> <span class="btn btn-default btn-sm">Lasīt vairāk</span>', // default is 'read more'
      userCollapseText: '<br/> <span class="btn btn-default btn-sm">Rādīt mazāk</span>'  // default is 'read less'
    });
      
    });
</script>