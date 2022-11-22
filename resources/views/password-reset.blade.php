<head>
</head>
<body>
    <style>
        p{text-align: center;}
    </style>
    <form action="http://127.0.0.1:5000/api/new-password" method="post">
        <p>
            <input type="password" autocomplete="off" placeholder="your password" autofocus name="password">
        </p>
        <p>
            <input type="hidden" autocomplete="off" name="user_hash">
        </p>
        <p>
            <input type="hidden" autocomplete="off" name="code">
        </p>
        <p>
            <input type="submit" value="Отправить">
        </p>
    </form>
</body>