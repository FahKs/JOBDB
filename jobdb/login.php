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
    <style>
        .btn {
            background-color: #28a745; /* สีเขียว */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px; /* เพิ่มระยะห่างขวา */
        }

        .phone-login {
            background-color: #007bff; /* สีน้ำเงิน */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .phone-login:hover {
            background-color: #0056b3; /* เปลี่ยนสีเมื่อเอาเมาส์ชี้ */
        }
    </style>
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
                <input type="email" id="email" name="email" placeholder="Your Email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Your Password" required>

                <button type="submit" class="btn">Submit</button>
                <button type="button" class="phone-login" onclick="location.href='login_with_phone.php'">Login with your phone</button>

                <?php if (isset($message_error)) : ?>
                    <p style="color: red;"><?php echo $message_error; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>

</html>




