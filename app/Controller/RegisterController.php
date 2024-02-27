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
 App::uses('User', 'Model');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link https://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class RegisterController extends AppController {

/**
 *
 * @var array
 */
public $uses = array('User'); // Load the User model

/**
 * Displays a view
 *
 * @return CakeResponse|null
 * @throws ForbiddenException When a directory traversal attempt.
 * @throws NotFoundException When the view file could not be found
 *   or MissingViewException in debug mode.
 */
	public function index() {
        // Check if the user is already logged in
        if ($this->Auth->user()) {
            // Redirect logged-in users to another page, such as the home page
            return $this->redirect(['controller' => 'home', 'action' => 'index']);
        }

        if ($this->request->is('post')) {
            $this->User->create();
    
            if ($this->User->save($this->request->data)) {
                // Retrieve the user data after saving
                $userData = $this->User->findByEmail($this->request->data['User']['email']);
    
                // Log in the user
                $this->Auth->login($userData['User']);
    
                // Update last login datetime
                $this->User->id = $userData['User']['id'];
                $this->User->saveField('last_login', date('Y-m-d H:i:s'));
    
                // Redirect the user to a success page or render success view
                $this->render('success');
            } else {
                // Handle validation errors
                $validationErrors = $this->User->validationErrors;
                $errorMessage = $this->_collectValidationErrors($validationErrors);
                $this->Session->setFlash($errorMessage);
                $this->set('userData', $this->request->data);
            }
        }
    }

    // Helper function to collect validation errors into a single message
    protected function _collectValidationErrors($validationErrors) {
        $errorMessage = '';
        foreach ($validationErrors as $field => $errors) {
            foreach ($errors as $error) {
                $errorMessage .= $error . '<br>';
            }
        }
        return $errorMessage;
    }
}
