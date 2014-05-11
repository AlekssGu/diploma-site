<div class='container'>
    <?php echo Asset::js('bootstrap.file-input.js'); ?>
    <?php echo Form::open(array("class"=>"form-horizontal", 'enctype' => 'multipart/form-data')); ?>
        <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
	<fieldset>
		<div class="form-group">
			<?php echo Form::label('Virsraksts', 'title', array('class'=>'control-label')); ?>

				<?php echo Form::input('title', Input::post('title', isset($news) ? $news->title : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Ziņas virsraksts')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Kopsavilkums', 'excerpt', array('class'=>'control-label')); ?>

				<?php echo Form::textarea('excerpt', Input::post('excerpt', isset($news) ? $news->excerpt : ''), array('class' => 'col-md-8 form-control', 'rows' => 8, 'placeholder'=>'Kopsavilkums')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Ziņas saturs', 'body', array('class'=>'control-label')); ?>

				<?php echo Form::textarea('body', Input::post('body', isset($news) ? $news->body : ''), array('class' => 'col-md-8 form-control', 'rows' => 8, 'placeholder'=>'Pilnā ziņa')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Atsauce uz attēla vietni', 'source', array('class'=>'control-label')); ?>

				<?php echo Form::input('source', Input::post('source', isset($news) ? $news->file_source : ''), array('class' => 'col-md-8 form-control', 'placeholder'=>'Piemēram, attēls no vietnes google.com')); ?>

		</div>
		<div class="form-group">
			<?php if (isset($news)) echo Form::label('Attēls: ' . $news->filename, 'image', array('class'=>'control-label')); ?>
		</div>
                <div class='col-md-4 col-sm-4 col-xs-12'>
                    <input type='file' name='image' class='btn-success form-control' data-filename-placement="inside" />
                </div> 
		<div class="col-xs-12">
                    <br/>
			<?php echo Form::submit('submit', 'Saglabāt un publicēt', array('class' => 'visible-xs btn btn-primary btn-block')); ?>		
                        <?php echo Form::submit('submit', 'Saglabāt un publicēt', array('class' => 'hidden-xs btn btn-primary')); ?>		
                </div>
	</fieldset>
<?php echo Form::close(); ?>
    
<script>
$('input[type=file]').bootstrapFileInput();
</script>
</div>