





if(sessionStorage.getItem("Log") != "True"){
    $("#tampa").css({"display":"block"})
    $("#tampa").css({"opacity":"1"})
    $("#form").submit(function(event){
    event.preventDefault()
    

    var dados = {
        nome: $("#Nome").val(),
        senha: $("#Senha").val(),
        email: $("#email").val()
    }


    $.ajax({
        url: "http://localhost/Envia.php",
        type: "POST",
        data: dados,
        dataType: "json",
        success: function(xhr){
            sessionStorage.setItem("Log", "True");
            sessionStorage.setItem("email",  $("#email").val()  );
            
            sessionStorage.removeItem("nome");
            sessionStorage.setItem("nome",  $("#Nome").val()  );
            alert(xhr[0])
            $("#Nome").val(null)
            $("#Senha").val(null)
            $("#email").val(null)
            $("#form").css({"opacity":"0"})
            setTimeout(function() {
                $("#form").remove(); 
            }, 500)
            $("#tampa").css({"opacity":"0"})
            setTimeout(function() {
                $("#tampa").css({"display":"none"}); 
            }, 1000)
        },
            
        error: function(xhr) {
           
                if(JSON.parse(xhr.responseText) == "1" ){
                    alert("Senha errada")
                }
                if(JSON.parse(xhr.responseText) == "2" ){
                    alert("Nome inválido")
                  
                }
                if(JSON.parse(xhr.responseText) == "3" ){
                    alert("Email invalido")
                  
                }
                
                
           
        }

       
    })

})}else{
    $("#form").remove(); 

}


$("#menu").click(function(){
    $("#tampa").css({"display":"block"})
    $("#opition").css({"display":"block"})

    $("#tampa").animate({
        opacity : "1" ,
    },500)

    $("#opition").animate({
        width : "25%"
    },100);

    setTimeout(function() {
        $("#fp").css({"display":"block"}) 
    }, 300)

    
    $("#fp").animate({
        left : "-10%" ,
        
        },500)
    
})

$("#fp").click(function(){

    
    $("#tampa").animate({
        opacity : "0" ,
    },250).fadeOut()

    setTimeout(function(){
        $("#opition").animate({
            width : "0%"
        },100).fadeOut(500)
    })

    $("#fp").animate({
        left : "0%" ,
        }).fadeOut(0)


   
    
    
    
})




$("#Postar").click(function(){
    window.location.href = "postar.html"
})

$("#Perfil").click(function(){
    window.location.href = "perfil.html"
})



$("#Inicio").click(function(){
    window.location.href = "index.html"
})

$("#Postador").submit(function(event){
    event.preventDefault()
    var dado = {Titulo: $("#Titu").val(), Conteudo : $("#Conteudo").val(), email : sessionStorage.getItem("nome")}
    $.ajax({
        url: "http://127.0.0.1/post.php",
        type: "POST",
        data: dado,
        dataType: "json",
        xhrFields: {
            withCredentials: true  
        },
        success: function(xhr){
            alert(xhr[0])
            $("#Titu").val(null)
            $("#Conteudo").val(null)
        },
        error: function(xhr){
          
            $("#Titu").css({
                "animation" : "" 
            })
            $("#Conteudo").css({
                "animation" : "" 
            })
            if(xhr == "T"){
                alert("Já existe um post com este título")

                $("#Titu").css({
                    "animation" : "erro 1s ease-in-out" 
                })
            }
            if(xhrw == "C"){
                alert("Já existe um post com este conteúdo")
                $("#Conteudo").css({
                    "animation" : "erro 1s ease-in-out" 
                })
            }
        }
    })
  

    
})

