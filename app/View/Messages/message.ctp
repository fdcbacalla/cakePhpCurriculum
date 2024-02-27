<?php
/**
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 */

if (!Configure::read('debug')):
	throw new NotFoundException();
endif;

$this->assign('css', $this->Html->css('new-message'));
$this->assign('script', $this->Html->script('new-message'));
?>

<div class="main-container">
    <div class="new-message-container">
        <h2>New Message</h2>
        <a href="/messages" class="go-back-button">Go Back</a>
        <?php echo $this->Form->create('Message', ['url' => ['controller' => 'messages', 'action' => 'message']]); ?>
            <div class="form-group">
				<?php echo $this->Form->input('recipient_id', ['label' => 'Recipient', 'options' => $recipients, 'id' => 'recipient']); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('message', ['type' => 'textarea', 'rows' => '4', 'cols' => '50']); ?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->button('Send'); ?>
            </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
