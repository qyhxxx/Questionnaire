<html>
<head>
    <meta charset="utf8">
</head>
<body>
<form action="" method="POST">
    {{ csrf_field() }}
    name：
    <input type="text" name="questionnaire[name]"><br>
    remark:
    <input type="text" name="questionnaire[remark]"><br>
    <button type="submit">submit</button>
</form>
</body>
</html>