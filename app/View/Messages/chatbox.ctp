<?php
/**
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 */

if (!Configure::read('debug')):
	throw new NotFoundException();
endif;

$this->assign('css', $this->Html->css('chat-box'));
?>

<div class="main-container">
    <div class="chat-label">Chat Messages</div>
    <div class="chat-container" ng-app="myApp" ng-controller="myCtrl">
        <!-- Reverse the order of messages using orderBy -->
        <div ng-repeat="value in message | orderBy: 'Message.created': true">
            <div class="chat-message sender-message" ng-show="value.Message.sender_id == chatter.id">
                <div class="message-content">{{ value.Message.message }}</div>
                <div class="message-time">{{ value.Message.created_human }}</div>
            </div>
            <div class="chat-message receiver-message" ng-show="value.Message.sender_id != chatter.id">
                <!-- Profile picture image goes here -->
                <img src="/img/default_avatar.png" class="profile-picture" alt="Profile Picture">
                <div class="message-content">{{ value.Message.message }}</div>
                <div class="message-time">{{ value.Message.created_human }}</div>
            </div>
        </div>
    </div>
    <div class="message-input">
        <input type="text" placeholder="Type your message...">
        <button>Send</button>
    </div>
</div>

<?php
pr($messageList);
pr($chatter);
$this->start('script');
?>
    <script>
        // AngularJS code
        var app = angular.module('myApp', []);

        app.controller('myCtrl', function($scope) {
            // Access the variable passed from CakePHP
            $scope.message = JSON.parse(`<?php echo json_encode($messageList); ?>`);
            $scope.chatter = JSON.parse('<?php echo json_encode($chatter); ?>');

            console.log($scope.chatter);
        });

        // Assuming chatContainer is the DOM element representing the chat container
        var chatContainer = document.querySelector('.chat-container');
        console.log(chatContainer);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    </script>
<?php
$this->end();
