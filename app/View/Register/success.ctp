<?php
/**
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 */

if (!Configure::read('debug')):
	throw new NotFoundException();
endif;

$this->assign('css', $this->Html->css('card'));
?>

<!-- In thank_you.ctp -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Thank you for registering</h5>
        <p class="card-text">Welcome to our community!</p>
        <?php echo $this->Html->link('Back to homepage', array('controller' => 'home', 'action' => 'index'), array('class' => 'btn')); ?>
    </div>
</div>
