// 201724500 심진섭 Assignment
<?php
    ini_set('display_errors', 1);   // error message On

    // Initialize variables
    $mode = isset($_POST["mode"]) ? $_POST['mode'] : "select";
    $totalCount = 0;
    $err = "";
    $flag = true;

    // Check and assignment POST parameters
    $name = isset($_POST["txt_name"]) ? $_POST['txt_name'] : "";
    $phone = isset($_POST["txt_tel"]) ? $_POST['txt_tel'] : "";
    $email = isset($_POST["txt_email"]) ? $_POST['txt_email'] : "";
    $memo = isset($_POST["txt_memo"]) ? $_POST['txt_memo'] : "";

    // Connect to MySQL
    if(!($database = mysqli_connect("localhost", "root", "123456", "201724500", "3307")))
        die("<p>Could not connect to Database!</p>");
    if(!(mysqli_select_db($database, "201724500")))
        die("<p>Could not open 201724500 Database!");

    // Sql query processing according to mode
    if ($mode == "insert")
    {
        $uniFindQuery = "SELECT name FROM info_table WHERE name='$name'";
        if(!($result = mysqli_query($database, $uniFindQuery))){
            print("<p>Could not execute query!!</p>");
            die(mysqli_error($database));
        }

        if(mysqli_num_rows($result) !== 0){
            $err = "이미 있는 이름입니다!!";
            $flag = false;
        }

        if($flag){
            $inQuery = "INSERT INTO info_table (name, tel, email, memo)"
                    . "VALUES ('$name', '$phone', '$email', '$memo')";

            if(!($result = mysqli_query($database, $inQuery))){
                print("<p>Could not execute query!!</p>");
                die(mysqli_error($database));
            }
        }
    }
    else if ($mode == "delete")
    {
        $uniFindQuery = "SELECT name FROM info_table WHERE name='$name'";
        if(!($result = mysqli_query($database, $uniFindQuery))){
            print("<p>Could not execute query!!</p>");
            die(mysqli_error($database));
        }

        if(mysqli_num_rows($result) === 0){
            $err = "없는 이름 입니다!!";
            $flag = false;
        }

        $delQuery = "DELETE FROM info_table WHERE name = '$name'";
        if(!($result = mysqli_query($database, $delQuery))){
            print("<p>Could not execute query!!</p>");
            die(mysqli_error($database));
        }
    }
    else if ($mode == "delete_all")
    {
        $allDelQuery = "DELETE FROM info_table";
        if(!($result = mysqli_query($database, $allDelQuery))){
            print("<p>Could not execute query!!</p>");
            die(mysqli_error($database));
        }
    }
    
    // Execute select query
    $select_sql = "SELECT * FROM info_table";
    if(!($result = mysqli_query($database, $select_sql))){
        print("<p>Could not execute query!!</p>");
        die(mysqli_error($database));
    }
    $data_arr = array();
    while($row = mysqli_fetch_assoc($result)){
        $temp = array('name'=>$row['name'], 'tel'=>$row['tel'], 'email'=>$row['email'], 'memo'=>$row['memo']);
        array_push($data_arr, $temp);
    }
    
    mysqli_close($database);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Example</title>
    <style type="text/css">
        #maindiv {
            width: 300px;
            float: left;
        }
        label {
            display: inline-block;
            width: 85px;
        }
        #div_btn {
            margin: 5px 0px;
        }
        #contactContainer {
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            width: 200px;
            height: 250px;
        }    
        #contactContainer .contactBox {
            text-align: center;
            font-size: 5px;
            border: 1px solid;
            width: 80px;
            height: 30px;
            margin: 1px;
            padding: 5px;
            cursor: pointer;
        }
        #div_fullInfo {
            visibility: hidden;
            float: left;
            width: 200px;
            border: 2px solid black;
            border-radius: 10px;
            text-align: center;
        }
        #div_fullInfo span {
            display: block;
            font-size: 1.2em;
        }
        .pagination {
            visibility: hidden;
            display: inline-block;
        }
        .pagination div {
            float: left;
            padding: 8px 16px;
        }
        .pagination div.active {
            background-color: #4CAF50;
            color: white;
        }
        .pagination div:hover:not(.active) {background-color: #ddd;}
    </style>
    <script type="text/javascript">
        var contactArr = <?php echo json_encode($data_arr);?>;    // php 배열을 javascript 배열에 바로 대입하고 싶을 때는 json을 활용
        var curPage = 0;

        function makeAlert()    // php에서 생성한 에러 메시지를 경고창으로 띄워주는 함수
        {
            var err = "<?php echo $err;?>";
            if (err.length > 0)
            {
                window.alert(err);
                return;
            }
        }        
        function addContact()
        {
            var name = document.getElementById("txt_name").value;
            var tel = document.getElementById("txt_tel").value;
            var email = document.getElementById("txt_email").value;
            var memo = document.getElementById("txt_memo").value;

            if (name == "" || tel == "")
            {
                window.alert("이름과 연락처는 꼭 입력해야 합니다.");
                return;
            }

            // 이름 중복 체크는 php에서 수행
            //if (findContact(document.getElementById("txt_name").value) >= 0)
            //{
            //    window.alert("이미 등록된 연락처입니다.");
            //    return;
            //}

            //var contact = {name: name, tel: tel, email: email, memo: memo};
            //contactArr.push(contact);
            //contactArr.sort(compareName);

            //document.forms[0].reset();
            //makePages();
            //showContacts();
            //updateData();

            document.getElementById("mode").value = "insert";
            document.forms[0].submit();
        }
        function findContact(name)
        {
            for (var contactIdx in contactArr)
            {
                if (contactArr[contactIdx].name == name)
                    return contactIdx;
            }
            return -1;
        }
        /*function compareName(contect1, contect2)
        {
            if (contect1.name > contect2.name)
                return 1;
            else if (contect1.name < contect2.name)
                return -1;
            else
                return 0;
        }*/
        function delContact()
        {
            //var idx = findContact(document.getElementById("txt_name").value);
            // 이름 체크는 php에서 수행
            //if (idx < 0)
            //{
            //    window.alert("일치하는 이름을 가진 연락처가 없습니다.");
            //    return;
            //}
            //contactArr.splice(idx, 1);
            //makePages();
            //showContacts();
            //updateData();
            if (document.getElementById("txt_name").value == "")
            {
                window.alert("제거할 연락처의 이름을 입력해주세요.");
                return;
            }
            document.getElementById("mode").value = "delete";
            document.forms[0].submit();
        }
        function clearAll()
        {
            //contactArr = Array();
            //document.getElementById("contactContainer").innerHTML = "";
            //document.getElementById("div_fullInfo").style.visibility = "hidden";
            //makePages();
            //updateData();
            document.getElementById("mode").value = "delete_all";
            document.forms[0].submit();
        }
        function showContacts()
        {
            var contactContainer = document.getElementById("contactContainer");
            contactContainer.innerHTML = "";

            var startIdx = curPage * 10;
            var endIdx = startIdx + 9;
            for(var idx = startIdx; idx <= endIdx && idx < contactArr.length; idx++)
            {
                var contact = makeContact(contactArr[idx].name);
                contactContainer.innerHTML += contact;
            }
            document.getElementById("div_fullInfo").style.visibility = "hidden";
        }
        function showContactInfo(name)
        {
            var contact = contactArr[findContact(name)];
            document.getElementById("info_name").innerHTML = contact.name;
            document.getElementById("info_tel").innerHTML = contact.tel;
            if (contact.email.length > 0)
                document.getElementById("info_email").innerHTML = contact.email;
            else
                document.getElementById("info_email").innerHTML = "-";
            if (contact.memo.length > 0)
                document.getElementById("info_memo").innerHTML = contact.memo;
            else
                document.getElementById("info_memo").innerHTML = "-";
            document.getElementById("div_fullInfo").style.visibility = "visible";

            //document.getElementById("txt_name").value = contact.name;
        }
        function makeContact(name)
        {
            return "<div id='" + name + "' class='contactBox' onclick='showContactInfo(id)'>" + name + "</div>";
        }
        function makePages()
        {
            if (contactArr.length > 10)
            {
                var pageNav = document.getElementById("pageNav");
                pageNav.style.visibility = "visible";
                pageNav.innerHTML = "";
                var nPage = Math.ceil(contactArr.length / 10);
                for(var i =0; i < nPage; i++)
                {
                    if (i == curPage)
                        pageNav.innerHTML += "<div class=\"active\" onclick=\"changePage(" + i +")\">" + (i + 1) + "</div>";
                    else
                        pageNav.innerHTML += "<div onclick=\"changePage(" + i +")\">" + (i + 1) + "</div>";
                }
            }
            else
            {
                var pageNav = document.getElementById("pageNav");
                pageNav.style.visibility = "hidden";
                pageNav.innerHTML = "";
            }
        }
        function changePage(pageNum)
        {
            curPage = pageNum;
            makePages();
            showContacts();
        }
        //function updateData()
        //{
        //    localStorage.removeItem("contacts");
        //    localStorage.setItem("contacts", JSON.stringify(contactArr));
        //}
        function start()
        {
            makeAlert();
            //var contacts = localStorage.getItem("contacts");
            //if (contacts != null)
            //{
                //contactArr = JSON.parse(contacts);
                makePages();
                showContacts();
            //}
        }
    </script>
    </head>
<body onload="start()">
    <div id="maindiv">
        <form name="form1" method="POST" action="Q2.php">
            <label for="txt_name">이름:</label><input type="text" name="txt_name" id="txt_name"><br>
            <label for="txt_tel">전화번호:</label><input type="text" name="txt_tel" id="txt_tel"></label><br>
            <label for="txt_email">이메일:</label><input type="text" name="txt_email" id="txt_email"></label><br>
            <label for="txt_memo">메모:</label><input type="text" name="txt_memo" id="txt_memo"></label><br>
            <div id="div_btn">
                <input type="button" name="addBtn" id="addBtn" value="연락처 추가" onclick="addContact()">
                <input type="button" name="delBtn" id="delBtn" value="연락처 삭제" onclick="delContact()">
                <input type="reset" name="ClearBtn" id="clearBtn" value="모두 삭제" onclick="clearAll()">
            </div>
            <!--submit 구분을 위한 hidden input 추가-->
            <input type="hidden" name="mode" id="mode" value="insert"/>
        </form>
        <br>
        <div id="contactContainer">
        </div>
        <nav class="pagination" id="pageNav">
        </nav>
    </div>
    <div id="div_fullInfo">
        <span id="info_name"></span>
        <span id="info_tel"></span>
        <span id="info_email"></span>
        <span id="info_memo"></span>
    </div>
</body>
</html>
