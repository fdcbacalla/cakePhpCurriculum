<?php
/**
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 */

if (!Configure::read('debug')):
	throw new NotFoundException();
endif;

$this->assign('css', $this->Html->css('registration'));
?>
<div class="main-container">
	<div class="registration-container">
		<h2>Register</h2>
		<?php echo $this->Flash->render(); ?>
		<?php
			echo $this->Form->create('User', array('url' => array('controller' => 'register', 'action' => 'index')));
			echo $this->Form->input('name', array('minlength' => '5', 'maxlength' => '20'));
			echo $this->Form->input('email', array('type' => 'email'));
			echo $this->Form->input('password', array('type' => 'password', 'required'));
			echo $this->Form->input('confirm_password', array('type' => 'password', 'label' => 'Confirm Password'));
			echo $this->Form->end('Register');
		?>
	</div>
</div>
