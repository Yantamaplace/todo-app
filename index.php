<?php
    $conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_NAME'));
    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
    $conn->query("CREATE TABLE IF NOT EXISTS todos (id INT AUTO_INCREMENT PRIMARY KEY, task TEXT)");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['add'])) {
            $stmt = $conn->prepare("INSERT INTO todos (task) VALUES (?)");
            $stmt->bind_param("s", $_POST['task']);
            $stmt->execute();
        } elseif (isset($_POST['delete'])) {
            $stmt = $conn->prepare("DELETE FROM todos WHERE id = ?");
            $stmt->bind_param("i", $_POST['id']);
            $stmt->execute();
        } elseif (isset($_POST['edit'])) {
            $stmt = $conn->prepare("UPDATE todos SET task = ? WHERE id = ?");
            $stmt->bind_param("si", $_POST['task'], $_POST['id']);
            $stmt->execute();
        }
    }

    $result = $conn->query("SELECT * FROM todos");
    ?>
    <form method="post">
        <input name="task" placeholder="やること">
        <button type="submit" name="add">ついか</button>
    </form>
    <ul>
        <?php while($row = $result->fetch_assoc()): ?>
        <li>
            <?= htmlspecialchars($row['task']) ?>
            <form style="display:inline" method="post">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <input name="task" value="<?= htmlspecialchars($row['task']) ?>">
                <button name="edit">へんしゅう</button>
                <button name="delete">さくじょ</button>
            </form>
        </li>
        <?php endwhile; ?>
    </ul>
    <?php $conn->close(); ?>