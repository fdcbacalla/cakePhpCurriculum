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
        <div ng-repeat="value in message | orderObjectByIndex">
            <div ng-show="value.sender_id == chatter.id">
                <div class="chat-message sender-message">
                    <div class="message-content">{{ value.message }}</div>
                    <div class="message-time">{{ value.created_human }}</div>
                </div>
            </div>
            <div ng-show="value.sender_id != chatter.id">
                <div class="chat-message receiver-message">
                    <!-- Profile picture image goes here -->
                    <img src="/img/default_avatar.png" class="profile-picture" alt="Profile Picture">
                    <div class="message-content">{{ value.message }}</div>
                </div>
                <div class="message-time-receiver">{{ value.created_human }}</div>
            </div>
        </div>
        <div class="loading-here">
            <div class="loading-spinner"></div> <!-- Loading spinner -->
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
        var pages = 1
        var queryStart = true;

        app.controller('myCtrl', function($scope) {
            // Access the variable passed from CakePHP
            $scope.message = JSON.parse(`<?php echo json_encode($messageList); ?>`);
            $scope.chatter = JSON.parse('<?php echo json_encode($chatter); ?>');

            // Function to append new message data to the existing message variable
            $scope.appendNewMessages = function(newMessages) {
                // Merge newMessages into $scope.message
                angular.extend($scope.message, newMessages);
                // Apply changes to update the HTML display
                $scope.$apply(function() {
                    
                });
            };

            // Function to fetch messages from the server
            function fetchMessages($scope, page = 1) {
                var formData = {
                    pageNumber: page,
                    chatter: <?php echo $chatter['id']; ?>
                };

                $.ajax({
                    type: 'POST',
                    url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'ajaxMessages')); ?>',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        // Handle success response
                        console.log(response);
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
            
            // Function to check if the user has scrolled to the top of the chat container
            function isScrolledToTop(element) {
                // Calculate the distance between the scroll position and the bottom of the element
                var scrollTop = element.scrollTop;
                var scrollHeight = element.scrollHeight;
                var clientHeight = element.clientHeight;
                console.log(scrollTop, scrollHeight, clientHeight,scrollHeight + scrollTop);
                return scrollHeight + scrollTop == clientHeight + 1;
            }

            function scrollAfterLoading() {
                // Store the current scroll position
                var scrollHeight = $('.chat-container').scrollTop();
                $('.chat-container').scrollTop(scrollHeight + 0.5);
            }

            // Scroll event listener
            $('.chat-container').scroll(function() {
                // Check if the user has scrolled to the bottom
                if (isScrolledToTop(this) && queryStart) {
                    // Fetch more messages
                    queryStart = false;
                    $('.loading-here').show();
                    
                    setTimeout(() => {
                        fetchMessages($scope, ++pages);
                    }, 1500);
                }
            });
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
    </script>
<?php
$this->end();
