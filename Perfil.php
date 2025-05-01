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
        $ema = $_POST["email"];
        $resultado = array();
        $buscar = $conexao->prepare("SELECT * FROM usuario WHERE email = :e");
        $buscar->bindParam(":e",$ema,PDO::PARAM_STR);
        $buscar->execute();
        $resultado["nome"] = $buscar->fetchColumn();
        $buscar = $conexao->prepare("SELECT COUNT(*) FROM Post WHERE nomeU = :e");
        $buscar->bindParam(":e",$resultado["nome"],PDO::PARAM_STR);
        $buscar->execute();
        $resultado["Posts"] = $buscar->fetchColumn();

        $buscar = $conexao->prepare("SELECT SUM(P.quanti_Likes) FROM Post P WHERE nomeU = :e ");
        $buscar->bindParam(":e",$resultado["nome"],PDO::PARAM_STR);
        $buscar->execute();
        $resultado["Likes"] = $buscar->fetchColumn();
    if($_POST["Tipo"] == "Feitos"){
      try{
        $buscar = $conexao->prepare("SELECT * FROM Post  WHERE nomeU = :e ");
        $buscar->bindParam(":e",$resultado["nome"],PDO::PARAM_STR);
        $buscar->execute();
        $resultado["PostesF"] = $buscar->fetchAll(PDO::FETCH_NUM);
        foreach( $resultado["PostesF"] as $key => $linha){
            $id = $linha[3];
            $ad = $conexao->prepare("SELECT COUNT(*) from LikesP WHERE id = :id and NomeU = :nome");
            $ad->bindParam(":id",$id,PDO::PARAM_STR);
            $ad->bindParam(":nome",$resultado["nome"],PDO::PARAM_STR);
            $ad->execute();
            $r = $ad->fetchColumn();
            
            if($r == 0){
                $resultado["PostesF"][$key][] = "0";
                
            }else{
                
                $resultado["PostesF"][$key][] = "1";
            }
        }


        echo json_encode($resultado);
      } catch(PDOException $e){

      }}else if ($_POST["Tipo"] == "Curtidos"){
        $buscar = $conexao->prepare("SELECT PL.id  FROM likesp Pl  WHERE nomeU = :e ");
        $buscar->bindParam(":e",$resultado["nome"],PDO::PARAM_STR);
        $buscar->execute();
        $ids = $buscar->fetchAll(PDO::FETCH_NUM);
        foreach( $ids as $id){
            $id1 = $id[0];
            $ad = $conexao->prepare("SELECT * from Post WHERE id = :id ");
          
            $ad->bindParam(":id",$id1,PDO::PARAM_STR);
            $ad->execute();
          
            $resultado["PostesF"][] = $ad->fetchAll(PDO::FETCH_NUM);
            
        }

        echo json_encode($resultado);
      }else{
        $buscar = $conexao->prepare("SELECT PLC.idPM  FROM postcom PLC WHERE nomeUR = :e ");
        $buscar->bindParam(":e",$resultado["nome"],PDO::PARAM_STR);
        $buscar->execute();
        $ids = $buscar->fetchAll(PDO::FETCH_NUM);
        foreach( $ids as $id){
            $id1 = $id[0];
            $ad = $conexao->prepare("SELECT * from Post WHERE id = :id ");
            $ad->bindParam(":id",$id1,PDO::PARAM_STR);
            $ad->execute();
            $resultado["PostesF"][] = $ad->fetchAll(PDO::FETCH_NUM);

            foreach( $resultado["PostesF"] as $key => $linha){
                $id = $linha[0][3];
                $ad1 = $conexao->prepare("SELECT COUNT(*) from LikesP WHERE id = :id and NomeU = :nome");
                $ad1->bindParam(":id",$id,PDO::PARAM_STR);
                $ad1->bindParam(":nome",$resultado["nome"],PDO::PARAM_STR);
                $ad1->execute();
                $r = $ad1->fetchColumn();
                
                if($r == 0){
                    $resultado["PostesF"][$key][] = "0";
                    
                }else{
                    
                    $resultado["PostesF"][$key][] = "1";
                }
            }
            
        }

        echo json_encode($resultado);
      }
    }


    
    
?>