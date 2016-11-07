<?php
use \packages\userpanel;
use \packages\userpanel\date;
use \packages\financial\transaction;
use \packages\base\translator;
use \themes\clipone\utility;

use \packages\contactus\contact_letter;
$this->the_header();
?>
<!-- start: PAGE CONTENT -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-external-link-square"></i>لیست حساب ها
				<div class="panel-tools">
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
								<th><?php echo translator::trans("letter.createdate"); ?></th>
								<th><?php echo translator::trans("letter.name"); ?></th>
								<th><?php echo translator::trans("letter.email"); ?></th>
								<th><?php echo translator::trans("letter.subject"); ?></th>
								<th><?php echo translator::trans("letter.status"); ?></th>
								<?php if($hasButtons){ ?><th></th><?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach($this->getLetters() as $letter){
								$this->setButtonParam('view', 'link', userpanel\url("contactus/view/".$letter->id));
								$this->setButtonParam('delete', 'link', userpanel\url("contactus/delete/".$letter->id));
								$statusClass = utility::switchcase($letter->status, array(
									'label label label-info' => contact_letter::read,
									'label label-default' => contact_letter::unread,
									'label label-success' => contact_letter::answered
								));
								$statusTxt = utility::switchcase($letter->status, array(
									'contact_letter.read' => contact_letter::read,
									'contact_letter.unread' => contact_letter::unread,
									'contact_letter.answered' => contact_letter::answered
								));
							?>
							<tr>
								<td class="center"><?php echo $letter->id; ?></td>
								<td><?php echo date::format('Y/m/d H:i', $letter->date); ?></td>
								<td><?php echo $letter->name; ?></td>
								<td><?php echo $letter->email; ?></td>
								<td><?php echo $letter->subject; ?></td>
								<td class="hidden-xs"><span class="<?php echo $statusClass; ?>"><?php echo translator::trans($statusTxt); ?></span></td>
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
			</div>
		</div>
		<!-- end: BASIC TABLE PANEL -->
	</div>
</div>
<!-- end: PAGE CONTENT-->
<?php
$this->the_footer();
