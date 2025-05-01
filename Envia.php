<?php
    
    header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");
    






    if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
            http_response_code(200);
            
        }
    
    

    if($_SERVER["REQUEST_METHOD"] === "POST"){
        
        $nome = $_POST['nome'] ;
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT) ;
        $email = $_POST['email'] ;
        
        try{
            $conexao = new PDO("mysql:host=localhost;port=3307;dbname=Black","root","");
            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            
        }catch(PDOException $e){
            echo "Erro na conexão: " . $e->getMessage();
            exit();
            }

        
        
        $checartE = $conexao->prepare("SELECT COUNT(*) FROM Usuario WHERE email = :email");
        $checartE->bindParam(":email",$email, PDO::PARAM_STR);
        $checartE->execute();
        if(filter_var($email,FILTER_VALIDATE_EMAIL)){
            if($checartE->fetchColumn() == 0){
                try{
                    $sql = $conexao->prepare("INSERT INTO Usuario (nome,email,senha) VALUES (:nome,:email,:senha)");
                    $sql->bindParam(":nome",$nome, PDO::PARAM_STR);
                    $sql->bindParam(":senha",$senha, PDO::PARAM_STR);
                    $sql->bindParam(":email",$email, PDO::PARAM_STR);
                    $sql->execute();

                    http_response_code(200);
                    ob_clean();
                    flush();
                    exit();}catch(PDOException $e){
                        $erro = $e->getMessage();
                        if(strpos($erro,'nome')){
                            http_response_code(401);
                            ob_clean();
                            flush();
                            echo json_encode("2"); 
                            exit();
                        }

                    }

            
            } else {
                $checarS = $conexao->prepare("SELECT senha from Usuario Where email = :email");
                $checarS->bindParam(":email",$email);
                $checarS->execute();
                if(password_verify($_POST['senha'],$checarS->fetchColumn()) ){    
                    $checarN = $conexao->prepare("SELECT nome from Usuario Where email = :email");
                    $checarN->bindParam(":email",$email);
                    $checarN->execute();
                    if($nome == $checarN->fetchColumn() ){
                            ob_clean();
                            flush();
                            echo json_encode(["Login efetuado com sucesso!"]);
                        } else {
                            http_response_code(401);
                            ob_clean();
                            flush();
                            echo json_encode(["2"]); 
                            exit(); 
                        }
                } else {
                    http_response_code(401);
                    ob_clean();
                    flush();
                    echo json_encode(["1"]); 
                    exit();}}
        
        } else {
            http_response_code(401);
            ob_clean();
            flush();
            echo json_encode(["3"]); }
    }


    
    
?>