<?php
use \packages\userpanel;
use \packages\userpanel\Date;
use \packages\financial\Transaction;
use \packages\base\Translator;
use \themes\clipone\Utility;

use \packages\contactus\Letter;
$this->the_header();
?>
<div class="row">
	<div class="col-md-12">
	<?php if(!empty($this->getLetters())){ ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-external-link-square"></i> <?php echo Translator::trans("contactus"); ?>
				<div class="panel-tools">
					<a class="btn btn-xs btn-link tooltips" title="" href="#search" data-toggle="modal" data-original-title="<?php echo translator::trans("contactus.search"); ?>"><i class="fa fa-search"></i></a>
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<?php
						$hasButtons = $this->hasButtons();
						?>
						<thead>
							<tr>
								<th class="center">#</th>
								<th><?php echo Translator::trans("contactus.letter.subject"); ?></th>
								<th><?php echo Translator::trans("contactus.letter.name"); ?></th>
								<th><?php echo Translator::trans("contactus.letter.email"); ?></th>
								<th><?php echo Translator::trans("contactus.letter.send_at"); ?></th>
								<th><?php echo Translator::trans("contactus.letter.status"); ?></th>
								<?php if($hasButtons){ ?><th></th><?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach($this->getLetters() as $letter){
								$this->setButtonParam('view', 'link', userpanel\url("contactus/view/".$letter->id));
								$this->setButtonParam('delete', 'link', userpanel\url("contactus/delete/".$letter->id));
								$statusClass = Utility::switchcase($letter->status, array(
									'label label label-info' => Letter::read,
									'label label-default' => Letter::unread,
									'label label-success' => Letter::answered
								));
								$statusTxt = Utility::switchcase($letter->status, array(
									'contactus.letter.read' => Letter::read,
									'contactus.letter.unread' => Letter::unread,
									'contactus.letter.answered' => Letter::answered
								));
							?>
							<tr>
								<td class="center"><?php echo $letter->id; ?></td>
								<td><?php echo $letter->subject; ?></td>
								<td><?php echo $letter->name; ?></td>
								<td><?php echo $letter->email; ?></td>
								<td class="ltr"><?php echo Date::format('Y/m/d H:i', $letter->date); ?></td>
								<td class="hidden-xs"><span class="<?php echo $statusClass; ?>"><?php echo Translator::trans($statusTxt); ?></span></td>
								<?php
								if($hasButtons){
									echo("<td class=\"center\">".$this->genButtons()."</td>");
								}
								?>
								</tr>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<?php $this->paginator(); ?>
			</div>
		</div>
	<?php } ?>
	</div>
</div>
<!-- end: PAGE CONTENT-->
<div class="modal fade" id="search" tabindex="-1" data-show="true" role="dialog">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title"><?php echo Translator::trans('search'); ?></h4>
	</div>
	<div class="modal-body">
		<form id="letterSearch" class="form-horizontal" action="<?php echo userpanel\url("contactus"); ?>" method="GET">
			<?php
			$this->setHorizontalForm('sm-3','sm-9');
			$feilds = array(
				array(
					'name' => 'id',
					'type' => 'number',
					'label' => Translator::trans("contactus.letter.id"),
					'ltr' => true
				),
				array(
					'name' => 'email',
					'type' => 'email',
					'label' => Translator::trans("contactus.letter.email"),
					'ltr' => true
				),
				array(
					'name' => 'word',
					'label' => Translator::trans("contactus.letter.specific_word")
				),
				array(
					'type' => 'select',
					'label' => Translator::trans('contactus.letter.status'),
					'name' => 'status',
					'options' => $this->getStatusForSelect()
				),
				array(
					'type' => 'select',
					'label' => Translator::trans('search.comparison'),
					'name' => 'comparison',
					'options' => $this->getComparisonsForSelect()
				)
			);
			foreach($feilds as $input){
				echo $this->createField($input);
			}
			?>
		</form>
	</div>
	<div class="modal-footer">
		<button type="submit" form="letterSearch" class="btn btn-success"><?php echo Translator::trans("search"); ?></button>
		<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo Translator::trans('cancel'); ?></button>
	</div>
</div>
<?php
$this->the_footer();
