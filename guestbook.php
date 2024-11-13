<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $name = $_POST['name'];
    $city = $_POST['city'] ?? null;
    $email = $_POST['email'] ?? null;
    $url = $_POST['url'] ?? null;
    $msg = $_POST['msg'];

    if (!empty($name) && !empty($msg))
    {
        $stmt = $pdo->prepare("INSERT INTO messages (name, city, email, url, msg, hide) VALUES (?, ?, ?, ?, ?, 'hide')");
        $stmt->execute([$name, $city, $email, $url, $msg]);
        echo "Повідомлення додано і очікує на схвалення адміністратора.";
    }
    else
    {
        echo "Ім'я та повідомлення є обов'язковими для заповнення.";
    }
}

$stmt = $pdo->prepare("SELECT * FROM messages WHERE hide = 'show' ORDER BY puttime DESC");
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Гостьова книга</title>
</head>
<body>
<h2>Додати повідомлення</h2>
<form action="" method="POST">
    <label>Ім'я: <input type="text" name="name" required></label><br>
    <label>Місто: <input type="text" name="city"></label><br>
    <label>Email: <input type="email" name="email"></label><br>
    <label>URL: <input type="url" name="url"></label><br>
    <label>Повідомлення: <textarea name="msg" required></textarea></label><br>
    <button type="submit">Відправити</button>
</form>

<h2>Повідомлення</h2>
<?php foreach ($messages as $message): ?>
    <div>
        <p><strong><?= htmlspecialchars($message['name']) ?></strong> (<?= htmlspecialchars($message['city']) ?>)</p>
        <p><?= htmlspecialchars($message['msg']) ?></p>
        <?php if (!empty($message['answer'])): ?>
            <p><strong>Відповідь адміністратора:</strong> <?= htmlspecialchars($message['answer']) ?></p>
        <?php endif; ?>
        <p>Дата: <?= $message['puttime'] ?></p>
    </div>
    <hr>
<?php endforeach; ?>
</body>
</html>
