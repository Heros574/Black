<?php
    include 'funcoes.php';
    
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
            
        }

    
    function FazPost($conexao ,$Titulo ,$Conteudo,$email,$id){
        $Guarda = $conexao->prepare("INSERT INTO  POST (Titulo,conteudo,nomeU,id) VALUES(:T,:C,:E,:I)");
        $Guarda->bindParam(":T",$Titulo,PDO::PARAM_STR);
        $Guarda->bindParam(":C",$Conteudo,PDO::PARAM_STR);
        $Guarda->bindParam(":E",$email,PDO::PARAM_STR);
        $Guarda->bindParam(":I",$id,PDO::PARAM_STR);
        $Guarda->execute();
    }
    
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $Titulo = $_POST["Titulo"];
        $Conteudo = $_POST["Conteudo"];
        $email = $_POST["email"];
        $id = CriaID();
        try{    
                FazPost($conexao ,$Titulo ,$Conteudo,$email,$id);
                http_response_code(200);
                ob_clean();
                flush();
                echo json_encode(["Post efetuado"]); 
                exit();}
                catch(PDOException $e){
                        if($e->errorInfo[0] == 23000 or $e->errorInfo[0] == 1062  ){
                            if(strpos($e->errorInfo[2], 'Titulo') !== false){
                                http_response_code(401);
                                ob_clean();
                                flush();
                                echo json_encode(["T"]); 
                                exit();
                            }elseif(strpos($e->errorInfo[2], 'conteudo') !== false){
                                http_response_code(401);
                                ob_clean();
                                flush();
                                echo json_encode(["C"]); 
                                exit();
                            }elseif(strpos($e->errorInfo[2], 'id') !== false){
                              while(True){
                                $id = CriaID();
                                try{
                                    FazPost($conexao ,$Titulo ,$Conteudo,$email,$id);
                                    http_response_code(200);
                                    ob_clean();
                                    flush();
                                    echo json_encode(["Post efetuado"]); 
                                    exit();
                                    
                                }catch(PDOException $e){
                                    http_response_code(400);
                                    ob_clean();
                                    flush();
                                    echo ($e); 
                                    
                                }
                              }
                        }else{
                            http_response_code(400);
                            ob_clean();
                            flush();
                            echo ($e); 
                            exit();
                                    
                        }
                    }
                }
    }
?>