<div style="padding:10px">

<?php $step = 1; ?>

<b><?php echo lang('welcome to new account', logged_user()->getDisplayName()) ?></b><br/>
<?php echo lang('welcome to new account info', '<a target="_blank" href="'.ROOT_URL.'">'. ROOT_URL . '</a>') ?><br/><br/>

<?php if(logged_user()->isAccountOwner()){
		$step++;
		if(owner_company()->isInfoUpdated()) { ?>
  <p><b><?php echo '<a class="internalLink dashboard-link" href="'. get_url('company', 'edit_client', array('id' => owner_company()->getId())) .'">' . lang('new account step1 owner'). '</a>'?></b><img src="<?php echo image_url('16x16/complete.png');?>"/></p>
  <?php echo lang('new account step1 owner info')?><br/><br/>
<?php 	} else { ?>
  <p><b><?php echo '<a class="internalLink dashboard-link" href="'. get_url('company', 'edit_client', array('id' => owner_company()->getId())) .'">' . lang('new account step1 owner'). '</a>'?></b></p>
  <?php echo lang('new account step1 owner info')?><br/><br/>
<?php } // if 
}?>

<?php 
	if(logged_user()->isInfoUpdated()) { ?>
  <p><b><?php echo '<a class="internalLink dashboard-link" href="'.get_url('account','index').'">'. lang('new account step update account', $step).'</a>'?></b><img src="<?php echo image_url('16x16/complete.png');?>"/></p>
  <?php echo lang('new account step update account info') ?><br/><br/>
<?php } else { ?>
  <b><?php echo '<a class="internalLink dashboard-link" href="'.get_url('account','index').'">'. lang('new account step update account', $step).'</a>' ?></b><br/>
  <?php echo lang('new account step update account info') ?><br/><br/>
<?php } // if
	$step++;
?>

<?php if (count(Projects::count('`created_by_id` = ' . logged_user()->getId())) > 0) { ?>
  <p><b><?php echo '<a class="internalLin dashboard-link" href="' . get_url('project', 'add') . '">' . lang('new account step start workspace', $step) . '</a>' ?></b><img src="<?php echo image_url('16x16/complete.png');?>"/></p>
  <?php echo lang('new account step start workspace info', '<span class="ico-workspace-add" style="padding: 5px 16px 0 0">&nbsp;</span>', logged_user()->getPersonalProject()->getName()) ?><br/><br/>
<?php } else { ?>
  <b><?php echo '<a class="internalLink dashboard-link" href="' . get_url('project', 'add') . '">' . lang('new account step start workspace', $step) . '</a>' ?></b><br/>
  <?php echo lang('new account step start workspace info', '<span class="ico-workspace-add" style="padding: 5px 16px 0 0">&nbsp;</span>', logged_user()->getPersonalProject()->getName()) ?><br/><br/>
<?php } ?>
<?php $step++ ?>  

<b><?php echo lang('new account step actions',$step) ?></b>
	<?php 
	$task_count = ProjectTasks::count('`created_by_id` = ' . logged_user()->getId());
	$note_count = ProjectMessages::count('`created_by_id` = '.logged_user()->getId());
	//$contact = ProjectContacts::findOne(array('conditions'=>'created_by_id='.logged_user()->getId()));
	if ($task_count > 0 || $note_count > 0) {
		echo '<img src="'.image_url('16x16/complete.png').'" />';
	}?><br/>
<?php echo lang('new account step actions info') ?><br/>

<span class="ico-message" style="padding: 5px 16px 0 0">&nbsp;</span>
<a class='internalLink dashboard-link' href='<?php echo get_url('message', 'add')?> ' ><?php echo lang('message')?></a>&nbsp;|&nbsp;

<span class="ico-contact" style="padding: 5px 16px 0 0">&nbsp;</span>
<a class='internalLink dashboard-link' href='<?php echo get_url('contact', 'add')?> ' ><?php echo lang('contact')?></a>&nbsp;|&nbsp;

<span class="ico-company" style="padding: 5px 16px 0 0">&nbsp;</span>
<a class='internalLink dashboard-link' href='<?php echo get_url('company', 'add_client')?> ' ><?php echo lang('company')?></a>&nbsp;|&nbsp;

<span class="ico-event" style="padding: 5px 16px 0 0">&nbsp;</span>
<a class='internalLink dashboard-link' href='<?php echo get_url('event', 'add')?> ' ><?php echo lang('event')?></a>&nbsp;|&nbsp;

<span class="ico-upload" style="padding: 5px 16px 0 0">&nbsp;</span>
<a class='internalLink dashboard-link' href='<?php echo get_url('files', 'add_file')?> ' ><?php echo lang('upload a file')?></a>&nbsp;|&nbsp;

<span class="ico-documents" style="padding: 5px 16px 0 0">&nbsp;</span>
<a class='internalLink dashboard-link' href='<?php echo get_url('files', 'add_document')?> ' ><?php echo lang('document')?></a>&nbsp;|&nbsp;

<span class="ico-prsn" style="padding: 5px 16px 0 0">&nbsp;</span>
<a class='internalLink dashboard-link' href='<?php echo get_url('files', 'add_presentation')?> ' ><?php echo lang('presentation')?></a>&nbsp;|&nbsp;

<span class="ico-milestone" style="padding: 5px 16px 0 0">&nbsp;</span>
<a class='internalLink dashboard-link' href='<?php echo get_url('milestone', 'add')?> ' ><?php echo lang('milestone')?></a>&nbsp;|&nbsp;

<span class="ico-task" style="padding: 5px 16px 0 0">&nbsp;</span>
<a class='internalLink dashboard-link' href='<?php echo get_url('task', 'add_task')?> ' ><?php echo lang('task')?></a>&nbsp;|&nbsp;

<span class="ico-webpage" style="padding: 5px 16px 0 0">&nbsp;</span>
<a class='internalLink dashboard-link' href='<?php echo get_url('webpage', 'add')?> ' ><?php echo lang('weblink')?></a>

<?php /*&nbsp;|&nbsp;
<image src='<?php echo image_url('/16x16/time.png')?> ' />&nbsp;
<a class='internalLink dashboard-link' href='<?php echo get_url('time', 'index')?> ' ><?php echo lang('time')?></a>&nbsp;|&nbsp;

<image src='<?php echo image_url('/16x16/reporting.png')?> ' />&nbsp;
<a class='internalLink dashboard-link' href='<?php echo get_url('reporting', 'index')?> ' ><?php echo lang('reporting')?></a>&nbsp;&nbsp;
*/?>

<?php Hook::fire('render_getting_started', null, $ret)?>

<br/><br/><p><a class='internalLink' href='<?php echo get_url('config', 'remove_getting_started_widget')?> ' ><?php echo lang('remove this widget')?></a></p>

</div>
