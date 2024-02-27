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

    // In your model (e.g., User model)
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
        'confirm_password' => array(
            'required' => array(
                'rule' => 'notBlank',
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
        return $data['confirm_password'] === $this->data[$this->alias]['password'];
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
}
