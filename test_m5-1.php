<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_1-21</title>
    <style>
        h1{
            text-align:center;
        }
        dt{
            float:left;
        }
        form{
            display:flex;
            justify-content:center;
            border:3px solid black;
            background-color:#f5f5f5;
        }
        .form{
            width:300px;
            text-align:right;
            margin:30px;
        }
        
        .contents{
            margin:30px;
        }
    </style>
</head>
<body>

     <?php
     
        //データベースに接続
        $dsn='データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
        //データベース内にテーブルを作成
        $sql = "CREATE TABLE IF NOT EXISTS test1"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "password char(32),"
        . "updated DATETIME"
        .");";
        $stmt = $pdo->query($sql);
        
        
     
        //ファイル名
        $filename="misson_5-1.txt";
        
        
        //編集機能
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) &&!empty($_POST["editNum"]) && !empty($_POST["password"])){
         
        //レコードを編集
        $id = $_POST["editNum"]; 
        $name = $_POST["name"];
        $comment = $_POST["comment"]; 
        $password= $_POST["password"];
        $date = new DateTime();
        $updated= $date->format('Y-m-d H:i:s');
        $sql = 'UPDATE test1 SET name=:name,comment=:comment,password=:password,updated=:updated WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':updated', $updated, PDO::PARAM_STR);
        $stmt->execute();
        
        echo "投稿を変更しました<br>";
        
        }
        //新規投稿   
        elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])){
            
            //レコードを作成   
            $sql = $pdo -> prepare("INSERT INTO test1 (name,comment,password,updated) VALUES (:name, :comment, :password, :updated)");
            $sql->bindParam(':name', $name, PDO::PARAM_STR);
            $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql->bindParam(':password', $password, PDO::PARAM_STR);
            $sql->bindParam(':updated', $updated, PDO::PARAM_STR);
            $name = $_POST["name"];
            $comment =$_POST["comment"];
            $password =$_POST["password"];
            $date = new DateTime();
            $updated= $date->format('Y-m-d H:i:s');
            $sql->execute();
       
            echo "投稿を受け付けました<br>";
        }
        
        
        //削除機能
        //削除番号が入力されていたとき
        if(!empty($_POST["delete"]) && !empty($_POST["delPass"])){
            
            //入力された削除番号とパスワードを取得
            $delPass=$_POST["delPass"];
            $delete=$_POST["delete"];
            
            //レコードの抽出
            $sql = 'SELECT * FROM test1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
            
                //レコードのid&パスが、入力した削除番号&パスと 一致したら
                if($row["id"] == $delete && $row["password"] == $delPass){
                
                //対象のデータレコードを削除
                $id = $_POST["delete"];
                $sql = 'delete from test1 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute(); 
            
            echo "投稿を削除しました<br>";
    
                }
            
            }
            
        }
        
        
        //編集する投稿をフォームに表示
        if(!empty($_POST["edit"]) && !empty($_POST["editPass"])){
            
            //編集番号とパスワードを取得
            $edit = $_POST["edit"];
            $editPass = $_POST["editPass"];
            
            //レコードの抽出
            $sql = 'SELECT * FROM test1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
            
                //レコードのidと編集番号、パスワードが一致したら番号、名前、コメント、パスワードを取得
                if($row["id"] == $edit && $row["password"] == $editPass){
                
                    $editNum = $row['id'];
                    $editName = $row['name'];
                    $editComment = $row['comment'];
                    $pass = $row["password"];
                
                }
                
            }
            
        }
           
                
        
    ?>
    
    <!--フォーム-->
    <h1>[簡単な掲示板]</h1>
    <form action="" method="post">
        <div class="form">
            <dl><!--新規投稿フォーム-->
                <dt>名前</dt>
                <dd><input type="text" name="name" placeholder="名前" value=
                "<?php //編集中の名前を表示
                    if(!empty($editName)){echo $editName;}?>"></dd><br>
            
                <dt>コメント</dt>
                <dd><input type="text" name="comment" placeholder="コメント" value=
                "<?php //編集中のコメントを表示
                     if(!empty($editComment)){echo $editComment;}?>"></dd><br>
        
                <dt>パスワード</dt>
                <dd><input type="password" name="password" placeholder="パスワード" value=
                    "<?php //編集中のコメントを表示
                        if(!empty($pass)){echo $pass;}?>"></dd><br>
            
                <!--編集中の番号-->
                <input type="hidden" name="editNum" placeholder="編集中" value=
                "<?php //編集中の番号を表示
                    if(!empty($editNum)){echo $editNum;}?>">
        
                 <input type="submit" name="submit"><br><br>
            </dl>
        </div>
        
        <div class="form">
            <dl><!--編集番号フォーム-->
                <dt>編集</dt>
                <dd><input type="number" name="edit" placeholder="編集したい番号"></dd><br>
                <dt>パスワード</dt>
                <dd><input type="password" name="editPass" placeholder="パスワード"></dd>
                <br><input type="submit" name="submit" value="編集"><br><br>
            </dl>
        </div>
        
        <div class="form">
            <dl><!--削除フォーム-->
                <dt>削除番号</dt>
                <dd><input type="number" name="delete" placeholder="削除番号"></dd><br>
                <dt>パスワード</dt>
                <dd><input type="password" name="delPass" placeholder="パスワード"></dd>
                <br><input type="submit" name="submit" value="削除">
            </dl>
        </div>
    </form>

    
    
    <div class="contents">
    <h2>[投稿内容]</h2><?php
        
    
        //入力したレコードを表示
        $sql = 'SELECT * FROM test1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['updated'].'<br>';
        echo "<hr>";
        }
        
    ?>
    </div>
</body>
</html>
