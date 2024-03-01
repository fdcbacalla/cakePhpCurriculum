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

<div class="main-container">
	<div class="profile-container">
		<div class="profile-header">
			<?php echo $this->Html->link('Edit', array('controller' => 'Users', 'action' => 'edit'), array('class' => 'edit-button')); ?>
			<?php echo $this->Html->link('Update', array('controller' => 'Users', 'action' => 'update'), array('class' => 'edit-button')); ?>
		</div>
		<div class="profile-picture">
			<img src="<?php echo $profilePicture; ?>" alt="Profile Picture">
		</div>
		<div class="profile-details">
			<h2><?php echo $user['name'] . ', ' . $user['age']; ?> </h2>
			<p>Gender: <?php echo ucfirst($user['gender']); ?></p>
			<p>Birthdate: <?php echo CakeTime::format($user['birthdate'], '%B %d, %Y'); ?></p>
			<p>Date Joined: <?php echo CakeTime::format($user['created'], '%B %d, %Y, %I:%M %p'); ?></p>
			<p>Last Login: <?php echo CakeTime::format($user['last_login'], '%B %d, %Y, %I:%M %p'); ?> <br> <?php echo $timeHelper->timeAgoInWords($user['last_login'])?></p>
		</div>
	</div>
</div>
