<?php
include 'config.php';

if ($_POST) {
    if (isset($_POST['create'])) {
        $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)")->execute([$_POST['name'], $_POST['email']]);
    }
    if (isset($_POST['update'])) {
        $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?")->execute([$_POST['name'], $_POST['email'], $_POST['id']]);
    }
    if (isset($_POST['delete'])) {
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$_POST['id']]);
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Users</h1>
        
        <form method="POST">
            <?php if ($edit): ?>
                <input type="hidden" name="id" value="<?= $edit['id'] ?>">
            <?php endif; ?>
            
            <input type="text" name="name" placeholder="Name" value="<?= $edit['name'] ?? '' ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?= $edit['email'] ?? '' ?>" required>
            
            <button name="<?= $edit ? 'update' : 'create' ?>"><?= $edit ? 'Update' : 'Add' ?></button>
            <?php if ($edit): ?><a href="index.php"><button type="button">Cancel</button></a><?php endif; ?>
        </form>

        <table>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Actions</th></tr>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td class="actions">
                    <a href="?edit=<?= $u['id'] ?>"><button>Edit</button></a>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="id" value="<?= $u['id'] ?>">
                        <button name="delete" class="btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>