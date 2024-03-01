<?php
/**
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 */

if (!Configure::read('debug')):
	throw new NotFoundException();
endif;

$this->assign('css', $this->Html->css('profile'));
?>

<div class="profile-container">
    <div class="profile-header">
        <a href="<?php echo $this->Html->url(array('action' => 'profile')); ?>" class="edit-button">Cancel</a>
    </div>
    <div class="profile-picture">
        <img src="<?php echo $user['avatar'] ?>" alt="Profile Picture">
        <div class="profile-name"><?php echo $user['name'] ?></div>
    </div>
    <div class="profile-details">
        <?php
        echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'update')));
        echo $this->Form->input('email', array('label' => 'New Email', 'value' => $user['email']));
        echo $this->Form->input('old_password', array('type' => 'password', 'label' => 'Old Password'));
        echo $this->Form->input('new_password', array('type' => 'password', 'label' => 'New Password', 'required' => false)); // Make new password not required
        echo $this->Form->input('confirm_password', array('type' => 'password', 'label' => 'Confirm Password', 'required' => false)); // Confirm password is required if new password is provided
        echo $this->Form->end('Update');
        ?>
    </div>
    <div class="profile-hobby">
        <!-- Add hobby details here if needed -->
    </div>
</div>


<?php
    echo $this->Form->end();
?>
