<?php
session_start();
include './service/login.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="block_A">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQitgJ_QOFgbuQ_oNenxF1LbEshrMwfWh_HOg&usqp=CAU" alt="Botoko Cafes Logo" class="logo">
            <h1>Botoko Cafe'</h1>
            <p>"Experience the perfect blend of comfort and flavor at our cozy little coffee shop. Where every cup is crafted with love!"</p>
        </div>
        <div class="block_B">
            <h2>Welcome back</h2>
            <form id="login-form" action="" method="post">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Your Email" require>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Your Password" require>

                <button type="submit" class="btn">Submit</button>

                <?php if (isset($message_error)) : ?>
                    <p style="color: red;"><?php echo $message_error; ?></p>
                <?php endif; ?>
                
            </form>
        </div>
    </div>
<script>

</script>

</body>

</html>