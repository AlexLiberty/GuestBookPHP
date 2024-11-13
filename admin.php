<?php
include 'db_connect.php';

if (isset($_POST['action']))
{
    $id = $_POST['id'];

    if ($_POST['action'] === 'delete')
    {
        $stmt = $pdo->prepare("UPDATE messages SET hide = 'hide' WHERE id_msg = ?");
        $stmt->execute([$id]);
    }
    elseif ($_POST['action'] === 'answer')
    {
        $answer = $_POST['answer'];
        $stmt = $pdo->prepare("UPDATE messages SET answer = ? WHERE id_msg = ?");
        $stmt->execute([$answer, $id]);
    }
    elseif ($_POST['action'] === 'approve')
    {
        $stmt = $pdo->prepare("UPDATE messages SET hide = 'show' WHERE id_msg = ?");
        $stmt->execute([$id]);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM messages ORDER BY puttime DESC");
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Адмін-панель</title>
</head>
<body>
<h2>Адміністрування повідомлень</h2>
<?php foreach ($messages as $message): ?>
    <div>
        <p><strong><?= htmlspecialchars($message['name']) ?></strong> (<?= htmlspecialchars($message['city']) ?>)</p>
        <p><?= htmlspecialchars($message['msg']) ?></p>
        <p>Дата: <?= $message['puttime'] ?></p>

        <?php if ($message['hide'] === 'hide'): ?>
            <p><strong>Статус:</strong> Сховано</p>
        <?php else: ?>
            <p><strong>Статус:</strong> Показано</p>
        <?php endif; ?>

        <?php if (!empty($message['answer'])): ?>
            <p><strong>Відповідь адміністратора:</strong> <?= htmlspecialchars($message['answer']) ?></p>
        <?php endif; ?>

        <form action="" method="POST" style="display: inline;">
            <input type="hidden" name="id" value="<?= $message['id_msg'] ?>">
            <button type="submit" name="action" value="delete">Сховати</button>
        </form>

        <form action="" method="POST" style="display: inline;">
            <input type="hidden" name="id" value="<?= $message['id_msg'] ?>">
            <button type="submit" name="action" value="approve">Схвалити</button>
        </form>

        <form action="" method="POST" style="display: inline;">
            <input type="hidden" name="id" value="<?= $message['id_msg'] ?>">
            <input type="text" name="answer" placeholder="Введіть відповідь">
            <button type="submit" name="action" value="answer">Відповісти</button>
        </form>
    </div>
    <hr>
<?php endforeach; ?>
</body>
</html>
