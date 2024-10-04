<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>
    <link rel="stylesheet" href="/jobdb/style.css">
</head>

<body>
    <div class="container">
        <div class="block_A">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQitgJ_QOFgbuQ_oNenxF1LbEshrMwfWh_HOg&usqp=CAU" alt="Botoko Cafes Logo" class="logo">
            <h1>Botoko Cafe'</h1>
            <p>"Experience the perfect blend of comfort and flavor at our cozy little coffee shop. Where every cup is crafted with love!"</p>
        </div>
        <div class="block_B">
            <h2>Welcome New User!!</h2>
            <form id="login-form" action="check_login_staff.php" method="post">
                <label for="tel">Tel:</label>
                <input type="tel" id="tel" name="tel" placeholder="Your Tel" required>
                
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
