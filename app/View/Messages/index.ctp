<?php
/**
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 */

if (!Configure::read('debug')):
	throw new NotFoundException();
endif;

$this->assign('css', $this->Html->css('message'));
?>

<div class="main-container">
    <div class="message-container">
		<div class="new-message-button">
			<?php echo $this->Html->link('New Message', array('controller' => 'Messages', 'action' => 'message')); ?>
		</div>
		<?php foreach ($messageList as $message): ?>
			<a href="<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'chatbox', $currentUserId != $message['Message']['sender_id'] ? $message['Message']['sender_id'] : $message['Message']['recipient_id'])); ?>" class="message-item">
				<div class="profile-picture">
					<!-- Profile picture of the second party -->
					<img src="<?php echo $currentUserId != $message['Message']['sender_id'] ? $message['Sender']['avatar'] : $message['Recipient']['avatar'] ?>" alt="Profile Picture">
				</div>
				<div class="message-content">
					<!-- Name/Username of the second party (optional) -->
					<div class="name"><?php echo $currentUserId != $message['Message']['sender_id'] ? $message['Sender']['name'] : $message['Recipient']['name'] ?></div>
					<!-- Latest chat message -->
					<div class="latest-message"><?php echo $message['Message']['message'] ?></div>
					<!-- Date/Time of the latest message -->
					<div class="date">Feb 17, 2024</div>
				</div>
			</a>
		<?php endforeach; ?>
        <div class="message-item">
			<div class="profile-picture">
				<!-- Profile picture of the second party -->
				<img src="/img/default_avatar.png" alt="Profile Picture">
			</div>
			<div class="message-content">
				<!-- Name/Username of the second party (optional) -->
				<div class="name">Jane Smith</div>
				<!-- Latest chat message -->
				<div class="latest-message">Hi there! How's it going?</div>
				<!-- Date/Time of the latest message -->
				<div class="date">Feb 17, 2024</div>
			</div>
		</div>

		<div class="message-item">
			<div class="profile-picture">
				<!-- Profile picture of the second party -->
				<img src="/img/default_avatar.png" alt="Profile Picture">
			</div>
			<div class="message-content">
				<!-- Name/Username of the second party (optional) -->
				<div class="name">John Doe</div>
				<!-- Latest chat message -->
				<div class="latest-message">Hey, how are you?</div>
				<!-- Date/Time of the latest message -->
				<div class="date">Feb 18, 2024</div>
			</div>
			<!-- Unread indicator -->
			<div class="unread-indicator"></div>
			 
		</div>
    </div>
</div>
