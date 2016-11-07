<?php
use \packages\base;
use \packages\base\frontend\theme;
use \packages\base\translator;
use \packages\base\http;

use \packages\userpanel;

use \themes\clipone\utility;

$this->the_header();
$letter = $this->getLetter();
?>
<div class="row">
	<div class="col-md-12">
		<!-- start: BASIC DELETE NEW -->
		<form action="<?php echo userpanel\url('contactus/delete/'.$letter->id); ?>" method="POST">
			<div class="alert alert-block alert-warning fade in">
				<h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> اخطار!</h4>
				<p>
					<?php echo translator::trans("letter.delete.warning", array("letter_id"=>$letter->id)); ?>
				</p>
				<p>
					<a href="<?php echo userpanel\url('contactus'); ?>" class="btn btn-light-grey"><i class="fa fa-chevron-circle-right"></i> <?php echo translator::trans('return'); ?></a>
					<button type="submit" class="btn btn-yellow"><i class="fa fa-trash-o tip"></i> <?php echo translator::trans("delete") ?></button>
				</p>
			</div>
		</form>
		<!-- end: BASIC DELETE NEW  -->
	</div>
</div>
<?php
$this->the_footer();
