<?php
   include 'funcoes.php';

   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
   header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
   header("Access-Control-Allow-Headers: Content-Type, Authorization");
   header("Access-Control-Allow-Credentials: true"); 

    try{
        $conexao = new PDO("mysql:host=localhost;port=3307;dbname=Black","root","");
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        
    }catch(PDOException $e){
    
        }

    if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
        http_response_code(200);
        exit();
    }

    function FazC($conexao,$id,$IDp,$Nom,$Texto,$nomeR){
            $Fc = $conexao->prepare("INSERT INTO PostCom (id,idPM,nomeU,nomeUR,conteudo) values(:id,:IDP,:N,:NR,:T)");
            $Fc->bindParam(":id",$id,PDO::PARAM_STR);
            $Fc->bindParam(":IDP",$IDp,PDO::PARAM_STR);
            $Fc->bindParam(":N",$Nom,PDO::PARAM_STR);
            $Fc->bindParam(":T",$Texto,PDO::PARAM_STR);
            $Fc->bindParam(":NR",$nomeR,PDO::PARAM_STR);
            $Fc->execute();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $IDp = $_POST["IDP"];
        $Texto = $_POST["texto"];
        $Nom = $_POST["NomeU"];
        $NomR = $_POST["NomeR"];
        $id = CriaID();
            
        try{
            FazC($conexao,$id,$IDp,$Nom,$Texto,$NomR);
            http_response_code(200);
            ob_clean();
            flush();
            echo json_encode(["Comentario feito com sucesso!"]);
            exit();
        }catch(PDOException $e){
          if($e->errorInfo[0] == 23000 or $e->errorInfo[0] == 1062){
            if(strpos($e->errorInfo[2], 'Conteudo') !== false){
                http_response_code(401);
                ob_clean();
                flush();
                echo json_encode(["C"]); 
                exit();
          }
          if(strpos($e->errorInfo[2], 'id') !== false){
            while(True){
                try{
                    $id = CriaID();
                    FazC($conexao,$id,$IDp,$Nom,$Texto,$NomR);
                    http_response_code(200);
                    ob_clean();
                    flush();
                    echo json_encode(["Comentario feito com sucesso!"]);
                    exit();
                }catch(PDOException $e){
                    $id = CriaID();
                    FazC($conexao,$id,$IDp,$Nom,$Texto,$NomR);
                    echo($e);
                }
            }
           
      }
        }else{
            echo($e);
        }
        

        
    }}

    
    if($_SERVER["REQUEST_METHOD"] === "GET"){
        $id = $_GET["IDP"];
        $n = $_GET["nomeU"];
        try{ 
            $buscar = $conexao->prepare("SELECT * FROM PostCom  Where idPM = :id " );
            $buscar->bindParam(":id",$id,PDO::PARAM_STR);
            $buscar->execute();
            $PostsC = $buscar->fetchAll(PDO::FETCH_NUM) ;
            foreach($PostsC as $key => $linha){
                $id = $linha[3];
                
                
                $ad = $conexao->prepare("SELECT COUNT(*) from LikesPC WHERE id = :id and NomeU = :nome");
                $ad->bindParam(":id",$id,PDO::PARAM_STR);
                $ad->bindParam(":nome",$n,PDO::PARAM_STR);
                $ad->execute();
                $resultado = $ad->fetchColumn();
                
                if($resultado == 0){
                    $PostsC[$key][] = "0";
                    
                }else{
                    
                    $PostsC[$key][] = "1";
                }
            }

            echo json_encode($PostsC);
        }catch(PDOException $e){
            http_response_code(400);
                echo $e;
            }
    }

   
?>

