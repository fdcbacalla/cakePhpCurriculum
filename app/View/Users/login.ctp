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
		<h2>Login</h2>
		<?php echo $this->Flash->render(); ?>
		<?php
			echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'login')));
			echo $this->Form->input('email', array('type' => 'email'));
			echo $this->Form->input('password');
			echo $this->Form->end('Login');
		?>
	</div>
</div>
