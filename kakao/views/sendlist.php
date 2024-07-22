<!-- views/home.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
<h1>Users List</h1>

<ul>
    <?php foreach ($data['sendlist'] as $user): ?>
        <li><?php echo $user->fmessage; ?></li>
    <?php endforeach; ?>
</ul>
<!---->
<!--<h2>Create User</h2>-->
<!--<form action="--><?php //echo BASE_URL; ?><!--/index.php?action=createUser" method="post">-->
<!--    <input type="text" name="name" placeholder="Name">-->
<!--    <input type="email" name="email" placeholder="Email">-->
<!--    <button type="submit">Create</button>-->
<!--</form>-->
<!---->
<!--<h2>Update User</h2>-->
<!--<form action="--><?php //echo BASE_URL; ?><!--/index.php?action=updateUser&id=1" method="post">-->
<!--    <input type="text" name="name" placeholder="Name">-->
<!--    <button type="submit">Update</button>-->
<!--</form>-->
<!---->
<!--<h2>Delete User</h2>-->
<!--<form action="--><?php //echo BASE_URL; ?><!--/index.php?action=deleteUser&id=1" method="post">-->
<!--    <button type="submit">Delete</button>-->
<!--</form>-->
</body>
</html>
