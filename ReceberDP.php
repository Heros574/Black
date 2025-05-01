w<?php
    header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
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
    
    

    if($_SERVER["REQUEST_METHOD"] === "GET"){
        try{
            $buscar = $conexao->prepare("SELECT * FROM Post ORDER BY data_criacao DESC LIMIT 10");
            $buscar->execute();
            $Posts = $buscar->fetchAll(PDO::FETCH_NUM);
            foreach($Posts as $key => $linha){
                $id = $linha[3];
                $n = $_GET["nomeU"];
                
                $ad = $conexao->prepare("SELECT COUNT(*) from LikesP WHERE id = :id and NomeU = :nome");
                $ad->bindParam(":id",$id,PDO::PARAM_STR);
                $ad->bindParam(":nome",$n,PDO::PARAM_STR);
                $ad->execute();
                $resultado = $ad->fetchColumn();
                
                if($resultado == 0){
                    $Posts[$key][] = "0";
                    
                }else{
                    
                    $Posts[$key][] = "1";
                }
            }


            http_response_code(200);
            ob_clean();
            flush();
            echo json_encode($Posts);
            
          
            exit();

        }catch(PDOException $e){
            http_response_code(400);
            echo $e;
          
            exit();
            }
    }


    
    
?>