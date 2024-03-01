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
App::uses('SoftDeleteBehavior', 'Model/Behavior');

class Message extends AppModel {
    // Soft delete behavior
    public $actsAs = array('SoftDelete');
    
    // Define soft delete field
    public $softDeleteField = 'deleted_at';

    public $validate = array(
        'message' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Message is required'
            )
        ),
        'recipient' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Recipient is required'
            )
        )
    );

    public $belongsTo = array(
        'Sender' => array(
            'className' => 'User',
            'foreignKey' => 'sender_id',
            'fields' => array('name', 'avatar')
        ),
        'Recipient' => array(
            'className' => 'User',
            'foreignKey' => 'recipient_id',
            'fields' => array('name', 'avatar')
        )
    );

    public $virtualFields = array(
        'created_human' => 'DATE_FORMAT(Message.created, "%b %d, %Y %h:%i %p")'
    );
}
