<?php
/**
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Elements
 * @since         CakePHP(tm) v 0.10.0.1076
 */

if (!Configure::read('debug')):
	throw new NotFoundException();
endif;

$loggedIn = $this->Session->check('Auth.User');
?>

<?php if ($loggedIn): ?>
    <ul>
        <li><?php echo $this->Html->link('Messages', array('controller' => 'messages', 'action' => 'index')); ?></li>
        <li><?php echo $this->Html->link('Profile', array('controller' => 'users', 'action' => 'profile')); ?></li>
        <!-- Add more navigation links as needed -->
    </ul>
<?php endif; ?>
