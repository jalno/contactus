<?php
use \packages\base;
use \packages\base\frontend\theme;
use \packages\base\translator;
use \packages\base\http;

use \packages\userpanel;
use \packages\userpanel\user;
use \packages\userpanel\date;
use \packages\base\views\FormError;

use \themes\clipone\utility;

use \packages\lettering\letter;

$letter = $this->getLetter();
$this->the_header();
?>
<div class="row">
	<div class="col-md-12">
		<!-- start: BASIC TABLE PANEL -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-comment-o"></i>
				<span><?php echo $letter->subject; ?></span>
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
				</div>
			</div>
			<div class="panel-body">
				<div class="space clearfix">
				    <div class="comment-photo">
						<img class="img-polaroid" src="<?php echo(theme::url('assets/images/user.png')) ?>" height="60" width="60" alt="client">
					</div>
				    <div class="comment-wraper text">
				        <h5 class="h-comment m-right-1"><?php echo $letter->name; ?></h5>
				        <div class="space black m-right-1">
				            <?php echo $letter->text; ?>
				        </div>
						<div class="panel-heading">
							<i class="fa fa-clock-o"></i>
							<a class="tooltips cursor-default" title="" data-original-title="<?php echo date::format('Y/m/d H:i', $letter->date); ?>"><span class="meta-tag"><?php echo $this->getDataTime($letter->date); ?></span></a>
							 | <span class="cursor-default"><?php echo $letter->email; ?></span>
				    	</div>
					</div>
				</div>
				<?php $reply = $letter->reply; if($reply){ ?>
				<div class="space clearfix reply">
					<div class="comment-photo">
						<img class="img-polaroid" src="<?php echo(theme::url('assets/images/user.png')) ?>" height="60" width="60" alt="client">
					</div>
					<div class="comment-wraper text">
						<h5 class="h-comment m-right-1"><?php echo $reply->sender->name." ".$reply->sender->lastname; ?></h5>
						<div class="space black m-right-1">
							<?php echo $reply->text; ?>
						</div>
						<div class="panel-heading">
							<i class="fa fa-clock-o"></i>
							<a class="tooltips cursor-default" title="" data-original-title="<?php echo date::format('Y/m/d H:i', $reply->date); ?>"><span class="meta-tag"><?php echo $this->getDataTime($reply->date); ?></span></a>
							 | <span class="cursor-default"><?php echo $reply->email->address; ?></span>
						</div>
					</div>
				</div>
				<?php }else{ ?>
				<div class="replaycontianer">
					<h3 style="font-family: b;"><?php echo translator::trans('send.reply'); ?></h3>
					<form action="<?php echo userpanel\url('contactus/view/'.$letter->id); ?>" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-6">
								<?php
								$this->createField(array(
									'name' => 'subject',
									'value' => translator::trans("letter.subject.reply", array('letter_subject' => $letter->subject))
								)); ?>
							</div>
							<div class="col-md-6">
								<?php $this->createField(array(
									'name' => 'email',
									'type' => 'select',
									'options' => $this->getEmailsValues()
								)); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php $this->createField(array(
									'name' => 'text',
									'type' => 'textarea',
									'rows' => 4,
									'class' => 'autosize form-control text-send'
								)); ?>
								<hr>
							</div>
						</div>
						<div class="row">
							<div class="col-md-8"></div>
							<div class="col-md-4">
								<div class="col-md-12" role="group">
									<button class="btn btn-teal btn-block" type="submit"><i class="fa fa-paper-plane"></i><?php echo translator::trans("send"); ?></button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<?php } ?>
			</div>
		</div>
		<!-- end: BASIC TABLE PANEL -->
	</div>
</div>
<?php
$this->the_footer();
