<?php
session_start();
include 'functions.php';
$pdo = pdo_connect_mysql();
// Output message variable
$msg = '';
// Check if POST data exists (user submitted the form)
if (isset($_POST['title'], $_POST['username'], $_POST['email'], $_POST['priority'], $_POST['msg'])) {
    // Validation checks... make sure the POST data is not empty
    if (empty($_POST['title']) || empty($_POST['username']) || empty($_POST['email']) ||empty($_POST['priority']) || empty($_POST['msg'])) {
        $msg = 'Please complete the form!';
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $msg = 'Please provide a valid email address!';
    } else {
        // Insert new record into the tickets table
        $stmt = $pdo->prepare('INSERT INTO tickets (title, username, email, priority, msg) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([ $_POST['title'], $_POST['username'], $_POST['email'], $_POST['priority'],  $_POST['msg'] ]);
        // Redirect to the view ticket page, the user will see their created ticket on this page
        header('Location: view.php?id=' . $pdo->lastInsertId());
       
    }
}
?>
<?=template_header('Create Ticket')?>
 


<div class="content create">
	<h2>Create Ticket</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="title">Title</label>
        <input type="text" name="title" placeholder="Title" id="title" required>
        <label for="username">Username</label>
        <input type="text" name="username" placeholder="username" id="username" value="<?php echo $_SESSION['name'];?>" required>
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="johndoe@example.com" id="email" required>
        <label for="priority">Priority</label>
        <input type="text" placeholder="Enter prioirty - Low, Medium or High" id="priority" name="priority" required>
        <label for="msg">Message</label>
        <textarea name="msg" placeholder="Enter your message here..." id="msg" name="msg" required></textarea>
        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
