<?php
declare(strict_types=1);
error_reporting(E_ALL);

require __DIR__ . '/../src/validate.php';


function e(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}


$values = ['name' => '', 'email' => '', 'message' => ''];
$errors = [];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    
    foreach ($values as $field => $_) {
        $raw = $_POST[$field] ?? '';
        $values[$field] = is_string($raw) ? trim($raw) : '';
    }

    
    if (!validate_required($values['name'])) {
        $errors['name'] = 'Name is required.';
    } elseif (!validate_length($values['name'], 2, 60)) {
        $errors['name'] = 'Name must be between 2 and 60 characters.';
    }

    
    if (!validate_required($values['email'])) {
        $errors['email'] = 'Email is required.';
    } elseif (!validate_email($values['email'])) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    
    if (!validate_required($values['message'])) {
        $errors['message'] = 'Message is required.';
    } elseif (!validate_length($values['message'], 10, 1000)) {
        $errors['message'] = 'Message must be between 10 and 1000 characters.';
    }

    
    if (count($errors) === 0) {
        header('Location: index.php?success=1', true, 303);
        exit;
    }
    
}

$success = ($_GET['success'] ?? '') === '1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 560px; margin: 40px auto; padding: 0 16px; }
        label { display: block; margin-top: 14px; font-weight: 600; }
        input, textarea { width: 100%; padding: 8px; box-sizing: border-box; margin-top: 4px; }
        .error { color: #b00020; font-size: 0.9em; margin: 4px 0 0; }
        .invalid { border: 2px solid #b00020; }
        .success { background: #e6f4ea; border: 1px solid #34a853; padding: 12px; border-radius: 6px; }
        button { margin-top: 18px; padding: 10px 20px; }
    </style>
</head>
<body>
    <h1>Contact Us</h1>

    <?php if ($success): ?>
        <p class="success">Thank you! Your message has been sent successfully.</p>
        <p><a href="index.php">Send another message</a></p>
    <?php else: ?>
        <form method="post" action="index.php" novalidate>
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?= e($values['name']) ?>"
                   class="<?= isset($errors['name']) ? 'invalid' : '' ?>">
            <?php if (isset($errors['name'])): ?>
                <p class="error"><?= e($errors['name']) ?></p>
            <?php endif; ?>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= e($values['email']) ?>"
                   class="<?= isset($errors['email']) ? 'invalid' : '' ?>">
            <?php if (isset($errors['email'])): ?>
                <p class="error"><?= e($errors['email']) ?></p>
            <?php endif; ?>

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="6"
                      class="<?= isset($errors['message']) ? 'invalid' : '' ?>"><?= e($values['message']) ?></textarea>
            <?php if (isset($errors['message'])): ?>
                <p class="error"><?= e($errors['message']) ?></p>
            <?php endif; ?>

            <button type="submit">Send</button>
        </form>
    <?php endif; ?>
</body>
</html>
