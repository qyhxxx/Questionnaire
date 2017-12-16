<html>
<head>
    <meta charset="utf8">
</head>
<body>
<form action="" method="POST">
    {{ csrf_field() }}
    name：
    <input type="text" name="questionnaire[name]" value="{{ $questionnaire->name }}"><br>
    remark:
    <input type="text" name="questionnaire[remark]" value="{{ $questionnaire->remark }}"><br>
    topic:
    <input type="text" name="question[topic]"><br>
    remark:
    <input type="text" name="question[remark]"><br>
    <input type="checkbox" name="question[isrequired]" value="1">必选<br>
    option1:
    <input type="text" name="option[0]"><br>
    option2:
    <input type="text" name="option[1]"><br>
    option3:
    <input type="text" name="option[2]"><br>
    problem1:
    <input type="text" name="problem[0]"><br>
    problem2:
    <input type="text" name="problem[1]"><br>
    problem3:
    <input type="text" name="problem[2]"><br>
    类型
    <select name="question[qtype]">
        @foreach($question->qtype() as $qtype => $value)
            <option value="{{ $qtype }}">{{ $value }}</option>
        @endforeach
    </select><br>
    最多可选<input type="text" name="question[max]">项<br>
    最少可选<input type="text" name="question[min]">项<br>
    <button type="submit">submit</button>
</form>
</body>
</html>