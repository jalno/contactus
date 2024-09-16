<?php
use packages\base\Translator;
use packages\userpanel;

$this->the_header();
?>
<div class="row">
	<div class="col-md-12">
		<!-- start: BASIC DELETE NEW -->
		<form action="<?php echo userpanel\url('contactus/delete/'.$this->letter->id); ?>" method="POST">
			<div class="alert alert-block alert-warning fade in">
				<h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> <?php echo t('attention'); ?>!</h4>
				<p>
					<?php echo t('contactus.letter.delete.warning', ['letter_id' => $this->letter->id]); ?>
				</p>
				<p>
					<a href="<?php echo userpanel\url('contactus'); ?>" class="btn btn-light-grey"><i class="fa fa-chevron-circle-right"></i> <?php echo t('return'); ?></a>
					<button type="submit" class="btn btn-teal"><i class="fa fa-trash-o tip"></i> <?php echo t('delete'); ?></button>
				</p>
			</div>
		</form>
		<!-- end: BASIC DELETE NEW  -->
	</div>
</div>
<?php
$this->the_footer();
