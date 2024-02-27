<?php
/**
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 */

if (!Configure::read('debug')):
	throw new NotFoundException();
endif;

$this->assign('css', $this->Html->css('home'));
?>

<div class="home-container">
    <!-- Login/Logout buttons -->
    <!-- Welcome message -->
    <div class="centered">
        <h1>Message Board ni dodong</h1>
        <h3>Bin2</h3>
    </div>
</div>
