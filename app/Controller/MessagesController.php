<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

 App::uses('AppController', 'Controller');
 App::uses('Message', 'Model');
 App::uses('User', 'Model');
 App::uses('PaginatorComponent', 'Controller/Component');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link https://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class MessagesController extends AppController {

/**
 *
 * @var array
 */
public $uses = array('Message', 'User', 'Paginator'); // Load the Message model

/**
 * Displays a view
 *
 * @return CakeResponse|null
 * @throws ForbiddenException When a directory traversal attempt.
 * @throws NotFoundException When the view file could not be found
 *   or MissingViewException in debug mode.
 */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny(); // Deny access to all actions by default
    }

	public function index() {
        $currentUserId = $this->Auth->user('id');

        $this->Message->Behaviors->load('Containable');

        $messageList = $this->Message->find('all', array(
            'conditions' => array(
                'OR' => array(
                    array('Message.sender_id' => $currentUserId),
                    array('Message.recipient_id' => $currentUserId)
                ),
                'Message.id IN (SELECT MAX(id) FROM messages AS LatestMessage 
                                WHERE 
                                    (LatestMessage.sender_id = Message.sender_id AND LatestMessage.recipient_id = Message.recipient_id) 
                                OR 
                                    (LatestMessage.sender_id = Message.recipient_id AND LatestMessage.recipient_id = Message.sender_id) 
                                GROUP BY 
                                    IF(LatestMessage.sender_id = '.$currentUserId.', LatestMessage.recipient_id, LatestMessage.sender_id)
                                )'
            ),
            'contain' => array(
                'Sender' => array(
                    'fields' => array('name', 'avatar')
                ),
                'Recipient' => array(
                    'fields' => array('name', 'avatar')
                )
            ),
            'order' => array('Message.created DESC')
        ));

        $this->set(compact('messageList', 'currentUserId'));
    }

    public function message() {
        $currentUserId = $this->Auth->user('id');
        $recipients = $this->User->find('list', [
            'conditions' => ['User.id !=' => $currentUserId],
            'fields' => ['User.id', 'User.name']
        ]);
        $this->set(compact('recipients'));

        if ($this->request->is('post')) {
            // Create a new, empty entity
            $newMessage = $this->Message;
            $newMessage->create();

            // Populate the entity with form data
            $newMessage->set($this->request->data);

            // Set the value of the column
            $newMessage->set('sender_id', $this->Auth->user()['id']);
            
            // Save the form data
            if ($this->Message->save()) {
                // Data saved successfully
                $this->Flash->success('Message sent successfully.');
                return $this->redirect(['action' => 'index']);
            } else {
                // Data failed to save
                $this->Flash->error('Error sending message. Please try again.');
            }
        }
    }

    public function chatbox($chatterId = null) {
        if (!$chatterId) {
            return $this->redirect(array('controller' => 'messages', 'action' => 'index'));
        }

        $currentUserId = $this->Auth->user('id');
        $messageList = $this->messages($currentUserId, $chatterId);

        if (!$messageList) {
            return $this->redirect(array('controller' => 'messages', 'action' => 'index'));
        }

        $this->User->recursive = -1;
        $chatter = $this->User->find('all', array(
            'fields' => array(
                "id",
                "name",
                "avatar"
            ),
            'conditions' => array(
                'User.id' => $chatterId
            )
        ));
        $user = $this->User->find('all', array(
            'fields' => array(
                "id",
                "name",
                "avatar"
            ),
            'conditions' => array(
                'User.id' => $currentUserId
            )
        ));

        $user = reset($user)['User'];
        $chatter = reset($chatter)['User'];

        $this->set(compact('messageList', 'chatter', 'user'));
    }

    public function ajaxFetchMessages() {
        $this->autoRender = false; // Disable view rendering

        // Check if it's an Ajax request
        if ($this->request->is('ajax')) {
            $page = $this->request->data['pageNumber'];
            $chatterId = $this->request->data['chatter'];
            $message = $this->request->data['message'] ?? null;
            $limit = 10;
            $currentUserId = $this->Auth->user('id');

            $messageList = $this->messages($currentUserId, $chatterId, $page, $limit, $message);

            // Process data and prepare response
            $response = array('status' => 'success', 'message' => 'Data received successfully.', 'messageList' => $messageList);

            // Convert response to JSON and output
            echo json_encode($response);
        }
    }

    public function ajaxNewMessage() {
        $this->autoRender = false; // Disable view rendering

        // Check if it's an Ajax request
        if ($this->request->is('ajax')) {
            $message = $this->request->data['message'];
            $chatterId = $this->request->data['chatter'];
            $currentUserId = $this->Auth->user('id');
    
            // Save the new message to the database
            $newMessage = array(
                'Message' => array(
                    'sender_id' => $currentUserId,
                    'recipient_id' => $chatterId,
                    'message' => $message
                )
            );
            $this->Message->create();
            $this->Message->save($newMessage);
    
            // Fetch updated message list
            $messageList = $this->messages($currentUserId, $chatterId);
    
            // Process data and prepare response
            $response = array('status' => 'success', 'message' => 'Data received successfully.', 'messageList' => $messageList);
    
            // Convert response to JSON and output
            echo json_encode($response);
        }
    }

    public function delete() {
        $this->autoRender = false; // Disable rendering of view
    
        // Check if it's a POST request and if message ID is provided
        if ($this->request->is('ajax') && isset($this->request->data['messageId'])) {
            $messageId = $this->request->data['messageId'];
    
            // Assuming you have a Message model with a soft delete behavior
            $message = $this->Message->id = $messageId;
            // If message exists, update deleted_at timestamp
            if ($message) {
                $this->Message->id = $messageId;
                if ($this->Message->saveField('deleted_at', date('Y-m-d H:i:s'))) {
                    // Message soft-deleted successfully
                    $this->response->statusCode(200); // Set HTTP status code
                    $this->response->body(json_encode(['message' => 'Message soft-deleted successfully']));
                } else {
                    // Error soft-deleting message
                    $this->response->statusCode(500); // Set HTTP status code
                    $this->response->body(json_encode(['error' => 'Error soft-deleting message']));
                }
            } else {
                // Message not found
                $this->response->statusCode(404); // Set HTTP status code
                $this->response->body(json_encode(['error' => 'Message not found']));
            }
        } else {
            // Invalid request
            $this->response->statusCode(400); // Set HTTP status code
            $this->response->body(json_encode(['error' => 'Invalid request']));
        }
    
        // Send response
        $this->response->type('json'); // Set response content type
        return $this->response;
    }

    private function messages($userId, $chatterId, $page = 1, $limit = 10, $searchTerm = null) {
        // Calculate the offset based on the page number and limit
        $offset = ($page - 1) * $limit;
    
        // Define conditions for the search query
        $conditions = array(
            'AND' => array(
                'OR' => array(
                    array('Message.sender_id' => $userId, 'Message.recipient_id' => $chatterId),
                    array('Message.recipient_id' => $userId, 'Message.sender_id' => $chatterId)
                ),
                'Message.deleted_at IS NULL' // Condition to check if deleted_at is null
            )
        );
    
        // Add search condition if search term is provided
        if (!empty($searchTerm)) {
            $conditions[] = array('Message.message LIKE' => "%$searchTerm%");
        }
    
        // Find messages with offset, limit, and search condition
        $messageList = $this->Message->find('all', array(
            'fields' => array(
                'id',
                'sender_id',
                'recipient_id',
                'message',
                'created',
                'created_human'
            ),
            'conditions' => $conditions,
            'order' => array('Message.id DESC'),
            'limit' => $limit,
            'offset' => $offset,
            'recursive' => -1
        ));
    
        // Restructure the array with message_id as the key
        $formattedMessageList = array();
        foreach ($messageList as $message) {
            $formattedMessageList[$message['Message']['id']] = $message['Message'];
        }
    
        return $formattedMessageList;
    }
}
