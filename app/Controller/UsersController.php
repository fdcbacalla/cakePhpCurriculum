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

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link https://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class UsersController extends AppController {

/**
 * Load the User model
 *
 * @var array
 */
public $uses = array('User');

	public function index() {
        return $this->redirect(array('controller' => 'users', 'action' => 'profile'));
	}

	public function login() {
		if ($this->request->is('post')) {
			$email = $this->request->data['User']['email'];
			$password = $this->request->data['User']['password'];
	   
			$user = $this->User->findByEmail($email);
	   
			if ($user && password_verify($password, $user['User']['password'])) {
				// Password is correct, authenticate the user
				$this->Auth->login($user['User']);
				
				// Update last login datetime
				$this->User->id = $user['User']['id'];
				$this->User->saveField('last_login', date('Y-m-d H:i:s'));
				
				return $this->redirect($this->Auth->redirectUrl());
			} else {
				// Invalid username or password
				$this->Flash->error(__('Invalid username or password, try again'));
			}
		}
	}

    public function logout() {
        // Clear user session data and log the user out
        $this->Auth->logout();

        // Redirect the user to a different page (e.g., the homepage)
        return $this->redirect(array('controller' => 'home', 'action' => 'landing'));
    }

	public function profile() {
		App::uses('CakeTime', 'Utility');
		App::uses('TimeHelper', 'View/Helper');

		$timeHelper = new TimeHelper(new View());
		$user = $this->User->findById($this->Auth->user()['id'])['User'];
		$profilePicture = empty($user['picture']) ? '/img/default_avatar.png' : '/uploads/' . $user['picture'];
		$this->set(compact('user', 'profilePicture', 'timeHelper'));
	}

	public function edit() {
		$user = $this->User->findById($this->Auth->user()['id'])['User'];
		$profilePicture = empty($user['picture']) ? '/img/default_avatar.png' : '/uploads/' . $user['picture'];
		$this->set(compact('user', 'profilePicture'));
	
		if ($this->request->is('post')) {
			$uploadsDir = WWW_ROOT . 'uploads' . DS;
			// Retrieve the submitted data from the form
			$postData = $this->request->data;
	
			// Remove picture from request data if it's empty
			if (empty($postData['User']['picture']['name'])) {
				unset($postData['User']['picture']);
			}
	
			// Assuming the user ID is stored in the session or passed along with the form
			$userId = $this->Auth->user('id');
	
			// Set the ID of the user to update
			$postData['User']['id'] = $userId;
	
			// Validate the form data including the uploaded picture
			$this->User->set($postData);
			if ($this->User->validates()) {
				// Verify if picture is uploaded
				if (isset($postData['User']['picture']['error']) && $postData['User']['picture']['error'] === UPLOAD_ERR_OK) {
					// Generate a new filename based on the user's ID and the original file extension
					$picture = $postData['User']['picture'];
					$fileName = $picture['name'];
					$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
					$newFileName = $userId . '-profilePicture.' . $fileExtension;
	
					// Move the uploaded picture to the uploads directory with the new filename
					$tmpName = $picture['tmp_name'];
					move_uploaded_file($tmpName, $uploadsDir . $newFileName);
	
					// Resize the uploaded picture
					$this->resizePicture($uploadsDir . $newFileName, $newFileName, $uploadsDir, 200, 200);
	
					// Update the picture field in the database
					$postData['User']['picture'] = $newFileName;
				}
	
				// Attempt to save the updated user data
				if ($this->User->save($postData)) {
					// User data updated successfully
					$this->Flash->success('Profile updated successfully.');
					return $this->redirect(array('action' => 'profile'));
				} else {
					// Failed to update user data
					$this->Flash->error('Failed to update profile. Please try again.');
				}
			} else {
				// Validation failed
				$this->Flash->error('Validation failed. Please check your input.');
			}
		}
	}		
	
	private function resizePicture($fullPath, $fileName, $destination, $maxWidth, $maxHeight) {
		list($width, $height) = getimagesize($fullPath);
		$ratio = min($maxWidth / $width, $maxHeight / $height);
		$newWidth = $width * $ratio;
		$newHeight = $height * $ratio;
	
		// Resample
		$imageResized = imagecreatetruecolor($newWidth, $newHeight);
		$image = imagecreatefromjpeg($fullPath);
		imagecopyresampled($imageResized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
	
		// Save image to file
		imagejpeg($imageResized, $destination . $fileName);
	
		// Free memory
		imagedestroy($image);
		imagedestroy($imageResized);
	}

	private function uploadFile($data) {
		// Check if the file is actually uploaded
		if (!empty($data['picture']['tmp_name'])) {
			return is_uploaded_file($data['picture']['tmp_name']);
		}
		return false;
	}
}
