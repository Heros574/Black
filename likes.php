<?php
    header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");
    


    try{
        $conexao = new PDO("mysql:host=localhost;port=3307;dbname=Black","root","");
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        
    }catch(PDOException $e){
        echo $e;
        }



    if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
            http_response_code(200);
            
        }
    
    

    if($_SERVER["REQUEST_METHOD"] === "POST"){
        try{
           if(array_key_exists("tipo",$_POST)){
            //Comemntario
                if(array_key_exists("id2",$_POST)){
                    $id  = $_POST["id"];
                    $nome = $_POST["Nome"];
                    try{
                        $adl = $conexao->prepare("DELETE from LikesPC where id =  :id and NomeU = :Nome");
                        $adl->bindParam(":id",$id,PDO::PARAM_STR);
                        $adl->bindParam(":Nome",$nome,PDO::PARAM_STR);
                        $adl->execute();
                    }catch(PDOException $e){
                            http_response_code(400);
                            ob_clean();
                            flush();
                            echo json_encode([$e]); 
                            exit();
                    }

                    $ad = $conexao->prepare("SELECT COUNT(*) from LikesPC WHERE id = :id");
                    $ad->bindParam(":id",$id,PDO::PARAM_STR);
                    $ad->execute();
                    $resultado = $ad->fetchColumn();
                    http_response_code(200);
                    ob_clean();
                    flush();
                    echo json_encode([$resultado]); 
                    exit();
                }else{
                $id  = $_POST["id"];
                $nome = $_POST["Nome"];

                try{
                    $adl = $conexao->prepare("INSERT INTO LikesPC (id,NomeU) values (:id , :Nome)");
                    $adl->bindParam(":id",$id,PDO::PARAM_STR);
                    $adl->bindParam(":Nome",$nome,PDO::PARAM_STR);
                    $adl->execute();
                }catch(PDOException $e){
                    if($e->errorInfo[0] == 23000 or $e->errorInfo[0] == 1062){
                        http_response_code(200);
                        ob_clean();
                        flush();
               
                        exit();
                    }else{
                        http_response_code(400);
                        ob_clean();
                        flush();
                        echo json_encode([$e]); 
                        exit();
                    }
                }
                $ad = $conexao->prepare("SELECT COUNT(*) from LikesPC WHERE id = :id");
                $ad->bindParam(":id",$id,PDO::PARAM_STR);
                $ad->execute();
                $resultado = $ad->fetchColumn();
                http_response_code(200);
                ob_clean();
                flush();
                echo json_encode([$resultado]); 
            }

           }else{


            //Post
            if(array_key_exists("id2",$_POST)){
                $id  = $_POST["id"];
                $nome = $_POST["Nome"];
                try{
                    $adl = $conexao->prepare("DELETE from LikesP where id =  :id and NomeU = :Nome");
                    $adl->bindParam(":id",$id,PDO::PARAM_STR);
                    $adl->bindParam(":Nome",$nome,PDO::PARAM_STR);
                    $adl->execute();
                }catch(PDOException $e){
                        http_response_code(400);
                        ob_clean();
                        flush();
                        echo json_encode([$e]); 
                        exit();
                }
                
                $ad = $conexao->prepare("SELECT COUNT(*) from LikesP WHERE id = :id");
                $ad->bindParam(":id",$id,PDO::PARAM_STR);
                $ad->execute();
                $resultado = $ad->fetchColumn();
                http_response_code(200);
                ob_clean();
                flush();
                echo json_encode([$resultado]); 
                exit();

            }else{
                $id  = $_POST["id"];
                $nome = $_POST["Nome"];
                try{
                    $adl = $conexao->prepare("INSERT INTO LikesP (id,NomeU) values (:id , :Nome)");
                    $adl->bindParam(":id",$id,PDO::PARAM_STR);
                    $adl->bindParam(":Nome",$nome,PDO::PARAM_STR);
                    $adl->execute();
                }catch(PDOException $e){
                    if($e->errorInfo[0] == 23000 or $e->errorInfo[0] == 1062){
                        http_response_code(200);
                        ob_clean();
                        flush();
                      
                        exit();
                    }else{
                        http_response_code(400);
                        ob_clean();
                        flush();
                        echo json_encode([$e]);
                        exit(); 
                    }
                }
                $ad = $conexao->prepare("SELECT COUNT(*) from LikesP WHERE id = :id");
                $ad->bindParam(":id",$id,PDO::PARAM_STR);
                $ad->execute();
                $resultado = $ad->fetchColumn();
                http_response_code(200);
                ob_clean();
                flush();
                echo json_encode([$resultado]);
                exit(); 
        }}
    }catch(PDOException $e){
                echo($e);
            }

    }


    
    
?>