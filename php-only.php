<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2번 문제</title>
    <style>
        p{
            margin: 0px;
        }
        label{
            display: inline-block;
            width: 80px;
        }
        .flex_container{
            width: 300px;
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
        }
        .show_box{
            margin: 5px;
            width: 125px;
            height: 50px;
            border: 1px solid black;
            text-align: center;
            font-size: 10px;
            line-height: 50px;
        }
        #each{
            border-radius: 10px;
            position: absolute;
            top: 5px; right: 5px;
            width: 20%;
            height: auto;
            text-align: center;
        }
        #pagination_btn{
            margin-left: 50px;
        }
    </style>
</head>
<body>
    <?php
        $name = isset($_POST["name"]) ? $_POST['name'] : "";
        $phone = isset($_POST["tel"]) ? $_POST['tel'] : "";
        $email = isset($_POST["email"]) ? $_POST['email'] : "";
        $memo = isset($_POST["memo"]) ? $_POST['memo'] : "";

        if(!($database = mysqli_connect("localhost", "root", "123456", "201724500", "3307")))
                die("<p>Could not connect to Database!</p>");
        if(!(mysqli_select_db($database, "201724500")))
                die("<p>Could not open 201724500 Database!");

        if(isset($_POST["save"])){
            if($name === ""){
                echo "<script>alert('이름은 공백일 수 없습니다.')</script>";
                die();
            }
            if(!preg_match("/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/", $phone)){
                echo "<script>alert('전화번호 형식이 옳지 않습니다!')</script>";
                die();
            }
        
            $inQuery = "INSERT INTO info_table (name, phone, email, memo)"
            . "VALUES ('$name', '$phone', '$email', '$memo')";

            if(!($result = mysqli_query($database, $inQuery))){
                print("<p>Could not execute query!!</p>");
                die(mysqli_error($database));
            }
        }

        if(isset($_POST['uniDelete'])){

        }

        if(isset($_POST['allDelete'])){
            
        }

        print("<form method = 'post' action='test2.php'>"
            . "<p><label for = 'name'>이름 : </label>"
            .     "<input id = 'name' name = 'name' type='text'></p>"
            . "<p><label for = 'tel'>전화번호 : </label>"
            .     "<input id = 'tel' name = 'tel' type='text'></p>"
            . "<p><label for = 'email'>이메일 : </label>"
            .     "<input id = 'email' name = 'email' type='text'></p>"
            . "<p><label for = 'memo'>메모 : </label>"
            .     "<input id = 'memo' name = 'memo' type='text'></p>"
            . "<p><input id = 'save' name = 'save' type='submit' value='연락처 저장'>"
            .     "<input id = 'uniDelete' name = 'uniDelete' type ='submit' value='연락처 삭제'>"
            .     "<input id = 'allDelete' name = 'allDelete' type='submit' value='모두 삭제'></p>"
            . "</form>"
            . "<div id = 'result' class = 'flex_container'></div>"
            . "<div id = 'pagination_btn'></div>"
            . "<div id = 'each'></div>");
        
        $findQuery = "SELECT name FROM info_table";
        if(!($result = mysqli_query($database, $findQuery))){
            print("<p>Could not execute query!!</p>");
            die(mysqli_error($database));
        }

        while($row = mysqli_fetch_row($result)){
            foreach($row as $name => $value){
                print("<div class = 'show_box' id = '" . $value . "' onclick = 'eachBook(id)'>"
                    . $value . "</div>");
            }
        }

        mysqli_close($database);
    ?>
</body>
</html>
