<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppModel', 'Model');

class User extends AppModel {
    public $recursive = -1;

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->virtualFields['avatar'] = sprintf(
            "CASE 
                WHEN %s.picture IS NULL THEN '/img/default_avatar.png'
                ELSE CONCAT('/uploads/', %s.picture) 
            END",
            $this->alias,
            $this->alias
        );
    }  

    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Name is required'
            )
        ),
        'email' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Email is required'
            ),
            'validEmail' => array(
                'rule' => 'email',
                'message' => 'Please enter a valid email address'
            ),
            'uniqueEmail' => array(
                'rule' => 'isUnique',
                'message' => 'This email address has already been taken'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Password is required'
            )
        ),
        'new_password' => array(
            'allowEmpty' => array(
                'rule' => 'allowEmpty',
                'required' => false,
                'last' => true,
                'on' => 'update' // Apply this rule only during update operations
            ),
            // Add other validation rules as needed
        ),
        'confirm_password' => array(
            'required' => array(
                'rule' => array('confirmPasswordRequired'),
                'message' => 'Confirm Password is required'
            ),
            'match' => array(
                'rule' => array('comparePasswords'),
                'message' => 'Passwords do not match'
            )
        ),
        'gender' => array(
            'validGender' => array(
                'rule' => array('inList', array('male', 'female')),
                'message' => 'Please select a valid gender.'
            )
        ),
        'birthdate' => array(
            'validDate' => array(
                'rule' => 'date',
                'message' => 'Please enter a valid birthdate.'
            )
        ),
        'hobby' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Hobby is required'
            )
        ),
        'picture' => array(
            'validImage' => array(
                'rule' => array('extension', array('jpg', 'jpeg', 'png', 'gif')),
                'message' => 'Please upload a valid image (jpg, jpeg, png, gif).'
            )
        )
    );    

    public $hasMany = array(
        'SentMessage' => array(
            'className' => 'Message',
            'foreignKey' => 'sender_id'
        ),
        'ReceivedMessage' => array(
            'className' => 'Message',
            'foreignKey' => 'recipient_id'
        )
    );

    // Custom validation method to compare passwords
    public function comparePasswords($data) {
        return $data['confirm_password'] == $this->data[$this->alias]['new_password'] ?? $this->data[$this->alias]['password'];
    }

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = password_hash(
                $this->data[$this->alias]['password'],
                PASSWORD_DEFAULT
            );
        }
        return true;
    }

    // Define a method to calculate the avatar URL
    public function getAvatar($picture) {
        // Check if the picture is empty or null, and return the default avatar URL
        if (empty($picture)) {
            return 'default_picture.jpg';
        } else {
            // If picture is not empty, return the URL to the user's picture
            return $picture;
        }
    }

    /**
     * Custom validation method to compare two fields (e.g., new_password and confirm_password)
     */
    public function compareFields($field, $otherField) {
        // $field contains the value of the current field being validated
        // $otherField contains the value of the field to compare against

        return $field === $this->data[$this->alias][$otherField];
    }

    public function confirmPasswordRequired($check) {
        $data = $this->data['User'];
        if (!empty($data['new_password'])) {
            return !empty($check['confirm_password']);
        }
        return true; // Confirm password is not required if new password is not provided
    }

    // Custom validation rule to check uniqueness of email excluding the current user's email
    public function isUniqueEmail($email, $userId) {
        $conditions = array(
            'User.email' => $email,
            'NOT' => array('User.id' => $userId) // Exclude the current user's ID
        );
        return !$this->find('count', array('conditions' => $conditions));
    }

    // Function to calculate age
    public function getAge($birthdate) {
        // Convert birthdate string to DateTime object
        $birthdate = new DateTime($birthdate);
        
        // Get current date
        $currentDate = new DateTime();
        
        // Calculate difference between current date and birthdate
        $age = $birthdate->diff($currentDate)->y; // Extract years from the difference
        
        return $age;
    }
}
