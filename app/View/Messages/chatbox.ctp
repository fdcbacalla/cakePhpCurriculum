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

<div class="main-container" ng-app="myApp" ng-controller="myCtrl">
    <div class="chat-box-container" style="margin-left: auto">
        <div class="chat-label">
            {{ chatter.name }}
            <button class="settings-button toggle-settings">&#9881;</button>
        </div>
        <div class="chat-container">
            <div ng-repeat="value in message | orderObjectByIndex">
                <div ng-show="value.sender_id != chatter.id">
                    <div class="chat-message sender-message" ng-mouseenter="showDeleteButton = true" ng-mouseleave="showDeleteButton = false">
                    <div class="message-content" ng-class="{ 'expand': isHovered }" ng-mouseenter="isHovered = true" ng-mouseleave="isHovered = false">{{ value.message }}</div>
                        <div class="delete-button" ng-show="showDeleteButton">
                            <button ng-click="deleteMessage(value.id)">Delete</button>
                        </div>
                    </div>
                    <div class="message-time-sender">
                        <div>{{ value.created_human }}</div>
                    </div>
                </div>
                <div ng-show="value.sender_id == chatter.id">
                    <div class="chat-message receiver-message">
                        <img src="{{ chatter.avatar }}" class="profile-picture" alt="Profile Picture">
                        <div class="message-content" ng-class="{ 'expand': isHovered }" ng-mouseenter="isHovered = true" ng-mouseleave="isHovered = false">{{ value.message }}</div>
                    </div>
                    <div class="message-time-receiver">
                        <div>{{ value.created_human }}</div>
                    </div>
                </div>
            </div>
            <div class="loading-here">
                <div class="loading-spinner"></div> <!-- Loading spinner -->
            </div>
        </div>
        <form ng-submit="sendMessage()">
            <div class="message-input">
                <button class="floating-button" ng-click="handleButtonClick()">
                    <span class="button-icon">&#9993;</span> <!-- Icon representation using HTML entity -->
                    New Message
                </button>
                <input type="text" placeholder="Type your message..." ng-model="messageText">
                <button type="submit" class="submit-button">Send</button>
            </div>
        </form>
    </div>
    <div class="settings-container" style="margin-right: auto; display: none;">
        <form ng-submit="searchMessage()">
            <div class="message-input">
                <input type="text" placeholder="Type your message..." ng-model="searchMessageText">
                <button type="submit" class="submit-button">Search</button>
            </div>
        </form>
        <div class="chat-container">
            <div ng-repeat="value in searchList">
                <div ng-show="value.sender_id != chatter.id">
                    <div class="chat-message sender-message">
                        <!-- Profile picture image goes here -->
                        <img src="{{ user.avatar }}" class="profile-picture" alt="Profile Picture">
                        <div class="message-content">{{ value.message }}</div>
                    </div>
                    <div class="message-time-receiver">{{ value.created_human }}</div>
                </div>
                <div ng-show="value.sender_id == chatter.id">
                    <div class="chat-message receiver-message">
                        <!-- Profile picture image goes here -->
                        <img src="{{ chatter.avatar }}" class="profile-picture" alt="Profile Picture">
                        <div class="message-content">{{ value.message }}</div>
                    </div>
                    <div class="message-time-receiver">{{ value.created_human }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->start('script');
?>
    <script>
        // AngularJS code
        var app = angular.module('myApp', []);
        var pages = 1
        var queryStart = true;
        var latest = 0;

        app.controller('myCtrl', function($scope) {
            // Access the variable passed from CakePHP
            $scope.message = JSON.parse(`<?php echo json_encode($messageList); ?>`);
            $scope.chatter = JSON.parse('<?php echo json_encode($chatter); ?>');
            $scope.user = JSON.parse('<?php echo json_encode($user); ?>');
            $scope.searchList = {};
            // Initialize input placeholder
            $scope.inputPlaceholder = 'Type your message...';
            $scope.messageText = null;
            $scope.searchMessageText = null;
            $scope.showSettingsPanel = false;
            $scope.showDeleteButton = false; // Initialize to false
            latest = getHighestIndex($scope.message);
            console.log(getHighestIndex($scope.message));

            // Function to append new message data to the existing message variable
            $scope.appendNewMessages = function(newMessages) {
                angular.extend($scope.message, newMessages);
                $scope.$apply(function() {
                    
                });
            };
            
            function getHighestIndex($object) {
                // Get array of keys
                var keys = Object.keys($object);

                // Find the maximum value in the array of keys
                var maxIndex = Math.max.apply(null, keys.map(function(key) {
                    return parseInt(key);
                }));

                return maxIndex;
            }

            // Function to fetch messages from the server
            function fetchMessages($scope, page = 1) {
                var formData = {
                    pageNumber: page,
                    chatter: $scope.chatter.id
                };

                $.ajax({
                    type: 'POST',
                    url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'ajaxFetchMessages')); ?>',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        queryStart = true;
                        $scope.appendNewMessages(response.messageList);
                        
                        scrollAfterLoading();
                        $('.loading-here').hide();
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                        queryStart = true;
                        scrollAfterLoading();
                        $('.loading-here').hide();
                    }
                });
            }

            function createNewMessage($newMessage) {
                var formData = {
                    message: $newMessage,
                    chatter: $scope.chatter.id
                };

                $.ajax({
                    type: 'POST',
                    url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'ajaxNewMessage')); ?>',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        // Handle success response
                        console.log(response);
                        $scope.appendNewMessages(response.messageList);
                        latest = getHighestIndex(response.messageList);
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }

            // Function to fetch new messages
            function fetchSearchMessage($message) {
                var formData = {
                    pageNumber: 1,
                    chatter: $scope.chatter.id,
                    message: $message
                };

                $.ajax({
                    type: 'POST',
                    url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'ajaxFetchMessages')); ?>',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response.messageList);
                        $scope.searchList = response.messageList;
                        $scope.$apply();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            // Function to fetch new messages
            function fetchNewMessages() {
                var formData = {
                    pageNumber: 1,
                    chatter: $scope.chatter.id
                };

                $.ajax({
                    type: 'POST',
                    url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'ajaxFetchMessages')); ?>',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if(latest != getHighestIndex(response.messageList)) {
                            latest = getHighestIndex(response.messageList);
                            $('.floating-button').css('display', 'flex');
                            $scope.appendNewMessages(response.messageList);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }

            // Set interval to fetch new messages every 5 seconds
            setInterval(fetchNewMessages, 5000);

            // Function to check if the user has scrolled to the top of the chat container
            function isScrolledToTop(element) {
                var scrollTop = element.scrollTop;
                var scrollHeight = element.scrollHeight;
                var clientHeight = element.clientHeight;
                console.log(scrollTop, scrollHeight, clientHeight,scrollHeight + scrollTop);
                return scrollHeight + scrollTop == clientHeight + 1;
            }

            //Rescroll to avoid reloading/refetching old data
            function scrollAfterLoading() {
                var scrollHeight = $('.chat-container').scrollTop();
                $('.chat-container').scrollTop(scrollHeight + 0.5);
            }

            // Scroll event listener
            $('.chat-container').scroll(function() {
                if (isScrolledToTop(this) && queryStart) { // Fetch more messages
                    queryStart = false;
                    $('.loading-here').show();
                    
                    setTimeout(() => {
                        fetchMessages($scope, ++pages);
                    }, 1500);
                }
            });

            // Function to send a message
            $scope.sendMessage = function() {
                if($scope.messageText !== null && $scope.messageText.trim() !== '') {
                    console.log('Sending message:', $scope.messageText);
                    createNewMessage($scope.messageText);
                }
                $scope.messageText = null;
            };

            // Function to search a message
            $scope.searchMessage = function() {
                if($scope.searchMessageText !== null && $scope.searchMessageText.trim() !== '') {
                    console.log('Sending message:', $scope.searchMessageText);
                    fetchSearchMessage($scope.searchMessageText);
                }
            };

            // Scroll to latest
            $scope.handleButtonClick = function() {
                $('.chat-container').animate({
                    scrollTop: $('.chat-container div:first').offset().top
                }, 1000);

                $('.floating-button').hide();
            };

            $scope.toggleSettingsPanel = function() {
                $scope.showSettingsPanel = !$scope.showSettingsPanel;
            };

            $scope.deleteMessage = function(messageId) {
                console.log(messageId);

                $.ajax({
                    type: 'POST',
                    url: '/messages/delete', // Endpoint URL for deleting messages
                    data: { messageId: messageId }, // Data to send with the request
                    success: function(response) {
                        delete $scope.message[messageId];
                        console.log('Message deleted successfully');
                        // You can also remove the message from the UI here if needed
                    },
                    error: function(xhr, status, error) {
                        // Error callback
                        console.error('Error deleting message:', error);
                    }
                });
            };
        });

        app.filter('orderObjectByIndex', function() {
            return function(items) {
                // Convert keys to integers before sorting
                var keys = Object.keys(items).map(function(key) {
                    return parseInt(key);
                });
                // Sort keys in descending order
                keys.sort(function(a, b) {
                    return b - a;
                });
                // Create a new array with sorted items
                var result = [];
                angular.forEach(keys, function(key) {
                    result.push(items[key]);
                });
                return result;
            };
        });

        /* JavaScript to toggle visibility of settings container */
        $(document).ready(function() {
            $('.toggle-settings').click(function() {
                $('.settings-container').toggle();
                $('.chat-box-container').toggleClass('centered');
            });
        });
    </script>
<?php
$this->end();
