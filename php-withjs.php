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
            height: 310px;
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
    <script>
        var infos;

        function loadBook(){
            var length = localStorage.length;
            var paginationBtn = document.getElementById("pagination_btn");
            infos = [];

            if(length > 10){
                paginationBtn.style.display = "block";

                var idx = (length - 1) / 10;
                var mark = "";

                for(var i = 1; i <= idx + 1 ; i++){
                    mark += "<input type = 'button' id = '" + i + "' value = '" + i + "'" + 
                    "style = 'margin-right: 2px; background-color: darkslateblue; color: white' onclick = 'go_next_page(id)'/>";
                }

                paginationBtn.innerHTML = mark;
            }
            else{
                paginationBtn.style.display = "none";
            }

            if(length > 10) length = 10;
        }

        function eachBook(name){
            var detail = document.getElementById("each");
            var tag = JSON.parse(localStorage.getItem(name));

            if(tag[1] === "") tag[1] = "-";
            if(tag[2] === "") tag[2] = "-";

            each.style.border = "1px solid black";
            var markup = name + "<br>" + tag[0] + "<br>" + tag[1] + "<br>" + tag[2];
            detail.innerHTML = markup;
        }

        function go_next_page(page){
            var length = localStorage.length;
            var maxLength = page * 10;

            if(length < maxLength) maxLength = length;
            var markup = "";

            //for (var i = (page - 1) * 10; i < maxLength; i++) 
            //{
            //    markup += "<div class = 'show_box' id = '" + infos[i] + "' onclick = 'eachBook(id)'>"
            //        + infos[i] + "</div>";
            //}

            //document.getElementById("result").innerHTML = markup;
        }

        function saveBook(){
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
                        return;
                    }
                    if(!preg_match("/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/", $phone)){
                        echo "<script>alert('전화번호 형식이 옳지 않습니다!')</script>";
                        return;
                    }
        
                    $inQuery = "INSERT INTO info_table (name, phone, email, memo)"
                    . "VALUES ('$name', '$phone', '$email', '$memo')";

                    if(!($result = mysqli_query($database, $inQuery))){
                        print("<p>Could not execute query!!</p>");
                        die(mysqli_error($database));
                    }
                }

                mysqli_close($database);
            ?>

            loadBook();
        }

        function deleteBook(){
            <?php
            if(isset($_POST["uniDelete"])){
                $name = $_POST["name"];

                if(!($database = mysqli_connect("localhost", "root", "123456", "201724500", "3307")))
                    die("<p>Could not connect to Database!</p>");
                if(!(mysqli_select_db($database, "201724500")))
                    die("<p>Could not open 201724500 Database!");

                $delQuery = "DELETE FROM info_table WHERE name = '$name'";
                if(!($result = mysqli_query($database, $delQuery))){
                    print("<p>Could not execute query!!</p>");
                    die(mysqli_error($database));
                }

                mysqli_close($database);
            }
            ?>

            loadBook();
        }

        function clearBook(){
            <?php
            if(isset($_POST["allDelete"])){
                if(!($database = mysqli_connect("localhost", "root", "123456", "201724500", "3307")))
                    die("<p>Could not connect to Database!</p>");
                if(!(mysqli_select_db($database, "201724500")))
                    die("<p>Could not open 201724500 Database!");

                $allDelQuery = "DELETE FROM info_table";
                if(!($result = mysqli_query($database, $allDelQuery))){
                    print("<p>Could not execute query!!</p>");
                    die(mysqli_error($database));
                }

                mysqli_close($database);
            }
            ?>

            loadBook();
        }

        function start(){
            var saveButton = document.getElementById("save");
            saveButton.addEventListener("click", saveBook, false);
            var deleteButton = document.getElementById("uniDelete");
            deleteButton.addEventListener("click", deleteBook, false);
            var clearButton= document.getElementById("allDelete");
            clearButton.addEventListener("click", clearBook, false);
            loadBook();
        }

        window.addEventListener("load", start, false);
    </script>
</head>
<body>
    <form method = "post" action="test2.php">
        <p><label for = "name">이름 : </label>
            <input id = "name" name = "name" type="text"></p>
        <p><label for = "tel">전화번호 : </label>
            <input id = "tel" name = "tel" type="text"></p>
        <p><label for = "email">이메일 : </label>
            <input id = "email" name = "email" type="text"></p>
        <p><label for = "memo">메모 : </label>
            <input id = "memo" name = "memo" type="text"></p>
        <p><input id = "save" name = "save" type="submit" value="연락처 저장">
            <input id = "uniDelete" name = "uniDelete" type = "submit" value="연락처 삭제">
            <input id = "allDelete" name = "allDelete" type = "submit" value="모두 삭제"></p>
    </form>
    <?php
        if(!($database = mysqli_connect("localhost", "root", "123456", "201724500", "3307")))
            die("<p>Could not connect to Database!</p>");
        if(!(mysqli_select_db($database, "201724500")))
            die("<p>Could not open 201724500 Database!");

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

    <div id = "pagination_btn"></div>
    <div id = "each"></div>
</body>
</html>
