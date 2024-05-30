<?php
use packages\base\Frontend\Theme;
use packages\base\Translator;
use packages\contactus\Letter;
use packages\userpanel;
use packages\userpanel\Date;

$this->the_header();
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-comment-o"></i>
				<span><?php echo $this->letter->subject; ?></span>
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
				</div>
			</div>
			<div class="panel-body">
				<div class="row letter">
					<div class="col-sm-2 profile">
						<img class="img-polaroid" src="<?php echo Theme::url('assets/images/user.png'); ?>" height="60" width="60" alt="client">
					</div>
					<div class="col-sm-10 content">
						<h5><?php echo $this->letter->name; ?></h5>
						<div class="text">
							<?php echo nl2br($this->letter->text); ?>
						</div>
						<div class="panel-footer">
							<i class="fa fa-clock-o"></i>
							<a class="tooltips cursor-default" title="" data-original-title="<?php echo Date::format('Y/m/d H:i', $this->letter->date); ?>"><span class="meta-tag"><?php echo $this->getDataTime($this->letter->date); ?></span></a>
								| <span class="cursor-default"><?php echo $this->letter->email; ?></span>
						</div>
					</div>
				</div>
				<?php if (Letter::answered == $this->letter->status and $reply = $this->letter->getReply()) { ?>
				<div class="row letter">
					<div class="col-sm-2 profile">
						<img class="img-polaroid" src="<?php echo Theme::url('assets/images/user.png'); ?>" height="60" width="60" alt="client">
					</div>
					<div class="col-sm-10 content">
						<h5><?php echo $reply->name; ?></h5>
						<div class="text">
							<?php echo $reply->text; ?>
						</div>
						<div class="panel-footer">
							<i class="fa fa-clock-o"></i>
							<a class="tooltips cursor-default" title="" data-original-title="<?php echo Date::format('Y/m/d H:i', $reply->date); ?>"><span class="meta-tag"><?php echo $this->getDataTime($reply->date); ?></span></a>
								| <span class="cursor-default"><?php echo $reply->email->address; ?></span>
						</div>
					</div>
				</div>
				<?php } else { ?>
				<div class="replaycontianer">
					<h3 style="font-family: b;"><?php echo Translator::trans('send.reply'); ?></h3>
					<form action="<?php echo userpanel\url('contactus/view/'.$this->letter->id); ?>" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-6">
								<?php
                                $this->createField([
                                    'name' => 'subject',
                                ]); ?>
							</div>
							<div class="col-md-6">
								<?php $this->createField([
								    'name' => 'email',
								    'type' => 'select',
								    'ltr' => true,
								    'options' => $this->getAddressesForSelect(),
								]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php $this->createField([
								    'type' => 'textarea',
								    'name' => 'html',
								    'class' => 'form-control ckeditor',
								    'label' => Translator::trans('email.text'),
								]); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 col-md-offset-4">
								<div class="btn-group col-xs-12">
									<button type="submit" class="btn btn-success col-xs-10"><i class="fa fa-paper-plane"></i> <?php echo Translator::trans('send'); ?></button>
									<span class="btn btn-file  btn-success  col-xs-2">
										<i class="fa fa-paperclip"></i>
										<input type="file" name="attachments[]" multiple="mutliple">
									</span>
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
