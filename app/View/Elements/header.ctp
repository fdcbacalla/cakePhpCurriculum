<?php
/**
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Elements
 * @since         CakePHP(tm) v 0.10.0.1076
 */

if (!Configure::read('debug')):
	throw new NotFoundException();
endif;

$loggedIn = $this->Session->check('Auth.User');
?>

<header>
<h1><a href="/">Message Board</a></h1>
    <!-- Include navigation links or other header content here -->

    <!-- Login/Register or Logout buttons -->
    <div class="header-buttons">
        <?php if ($loggedIn): ?>
            <a href="/users/logout" class="button">Logout</a>
        <?php else: ?>
            <a href="/users/login" class="button">Login</a>
            <a href="/register" class="button">Register</a>
        <?php endif; ?>
    </div>
</header>
