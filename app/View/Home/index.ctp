<?php
/**
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 */

if (!Configure::read('debug')):
	throw new NotFoundException();
endif;

App::uses('Debugger', 'Utility');

$this->assign('script', $this->Html->script('home'));
?>

<div class="main-container">
	Home Page.
	<input class="datepicker" type="date" value="<?php echo date('Y-m-d') ?>">
</div>
