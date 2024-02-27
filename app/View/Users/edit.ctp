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
$this->assign('script', $this->Html->script('profile_edit'));
?>

<?php
    echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'edit'), 'type' => 'file'));
?>
<div class="main-container">
    <div class="profile-container" style="display: flex; width: 800px">
        <div class="profile-header">
            <a href="<?php echo $this->Html->url(array('action' => 'profile')); ?>" class="edit-button">Cancel</a>
        </div>
        <div class="profile-picture">
            <img id="preview-image" src="<?php echo $profilePicture; ?>" alt="Profile Picture">
            <?php
                echo $this->Form->input('picture', array('type' => 'file', 'id' => 'image-input', 'required' => 'disabled'));
            ?>
        </div>
        <div class="profile-details">
            <h2>Edit Profile</h2>
            <?php
                echo $this->Form->input('name', array('value' => $user['name']));
                echo $this->Form->input('gender', array('options' => array('male' => 'Male', 'female' => 'Female'), 'empty' => 'Select Gender', 'default' => $user['gender']));
                echo $this->Form->input('birthdate', array('type' => 'text', 'class' => 'datepicker', 'value' => $user['birthdate']));
                echo $this->Form->input('hobby', array('label' => 'Hobby', 'value' => $user['hobby']));
                echo $this->Form->button('Save');
            ?>
        </div>
    </div>
</div>

<?php
    echo $this->Form->end();
?>
