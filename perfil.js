function CriaPost(t,c,e,id,qt,lug){
    $(lug).append(`
        <div id="${id}" class="postBod w-75  text-center PosPerf" >
            <p class="id">${id}</p>
            <h1 class="text-white">${t}</h1>
            <p class="imail">${e}</p>
            <hr>
            <div class="texto">
                ${c}
            </div>
            <hr>
            
            
                
                    <button  class="like but" data-like="false">
                        <span class="material-symbols-outlined i ">thumb_up</span>
                    </button>
                    <p class="quantiL">${qt}</p>
          

                <button class="comenta but">
                    <span class="material-symbols-outlined i">speaker_notes</span>
                </button>
            
        </div>
        `);


}

$(document).on("click",".comenta",function(){
    $("#comentar,#tampa").css({
        "display" : "block",
        "opacity" : "1"

    })
    $("#comentar").find("#buga").text($(this).parent().find(".id").text()+","+$(this).parent().find(".imail").text())
    var l = $("#comentar").find("#buga").text().split(",")
    fazC(l[0])


})


$("#Fechar").click(function(event){
    
   
    $("#comentar,#tampa").css({
        "opacity" : "0",
        
    })
   setTimeout(function(){
        $("#comentar,#tampa").css({"display" : "none"})
   },400)
})

function CriaComentario(c,nr,e,id,qt){
    if(e == nr){
        let coment = $(`
           
                <div class="comentario w-75  bor card mb-5 text-white">
                <p class="id">${id}</p>
                        <div class="card-body">
                            <p class="imail text-center">${e} Respondeu a si mesmo</p>
                            <hr>
                            <p>${c}</p>
                        </div>
                        <DIV class="card-footer">
                            <button  class="likeC but " data-like="false">
                                <span class="material-symbols-outlined ic">thumb_up</span>
                            </button>
                            <p class="quantiLC">${qt}</p>
     
                        </div>
                        
                </div>    
                
        
            `);
            $("#ContemComentarios").append(coment)
            return coment
    }
    else{ let coment = $(`
        <div class="comentario w-75  bor card mb-5 text-white">
         <p class="id">${id}</p>
                <div class="card-body">
                    <p class="imail">${e} em resposta a ${nr}</p>
                    <hr>
                    <p>${c}</p>
                </div>
                <DIV class="card-footer">
                    <button  class="likeC but" data-like="false">
                        <span class="material-symbols-outlined ic">thumb_up</span>
                    </button>
                    <p class="quantiLC">${qt}</p>
          
    
                
                </DIV>
                

            </div>
        `)
        $("#ContemComentarios").append(coment)
        return coment
    }}
   
function fazC(x){
        $.ajax({
            url : "http://127.0.0.1/Comentarios.php",
            type :"get",
            dataType: "JSON", 
            data : {"IDP" :x, "nomeU" : sessionStorage.getItem("nome")},
            
            success: function(xhr){
                $("#ContemComentarios").html(" ")
               
                xhr.forEach(post => {
                
                    let  comentario = CriaComentario(post[0],post[1],post[2],post[3],post[5])
                    if(post[5] == 0){
    
                        $(comentario).find(".likeC").find(".material-symbols-outlined").removeClass("branco").addClass("cinza")
                       
                    }else{
                        if(post[6] === "1"){
                        
                        $(comentario).find(".likeC").find(".material-symbols-outlined").addClass("branco").removeClass("cinza")
                       
                        
                    
                    }}
                       
                          
        
                });
                
            },
            error:  function(xhr){
                alert(xhr.responseText)
            }
        })
    
    }
 

//like dos posts
$(document).on("click",".like",function(){
    
if($(this).attr("data-like") === "true"){
    $(this).find(".material-symbols-outlined").addClass("cinza")
    $(this).find(".material-symbols-outlined").removeClass("branco")
    $(this).attr("data-like","false")
    var b = $(this)
    $.ajax({
        url: "http://127.0.0.1/likes.php",
        type: "POST",
        data: {"id" : $(this).parent().find(".id").text(), "id2" : "True" , "Nome" : sessionStorage.getItem("nome")},
        dataType: "json",
        success: function(xhr){
        b.parent().find(".quantiL").text(xhr)
        
        },
        error: function(){}
    })
}else{
    $(this).find(".material-symbols-outlined").addClass("branco")
    $(this).find(".material-symbols-outlined").removeClass("cinza")
    $(this).attr("data-like","true")
    var b = $(this)
    $.ajax({
        url: "http://127.0.0.1/likes.php",
        type: "POST",
        data: {"id" : $(this).parent().find(".id").text() , "Nome" : sessionStorage.getItem("nome")},
        dataType: "json",
        success: function(xhr){
        b.parent().find(".quantiL").text(xhr)
        
        },
        error: function(){}
    })
}
})



$("#options").click(function(){
    if($(this).attr("contador") == "true"){
        
        $(".opcoes").css({
            "border-bottom-left-radius" : "0px ",
            "border-bottom-right-radius" : "0px "
        })

    $("#op1").css({
        "transform": "scaleY(1)",
        "height": "100%"
    })
  
    $("#flechaP").css({
        "transform": "rotate(180deg)",
    })

    $(this).attr("contador", "false");
    }else{
       
        $("#op1").css({  
            "height": "0",
            "transform": "scaleY(0)"
            
       })
       $(".opcoes").css({
        "border-bottom-left-radius" : "10px ",
        "border-bottom-right-radius" : "10px "
    })
       $(this).attr("contador", "true");
       $("#flechaP").css({
        "transform": "rotate(0deg)",
    })

    }

})



//Like dos comentarios
$(document).on("click",".likeC",function(){
            if($(this).attr("data-like") === "true"){
                $(this).find(".material-symbols-outlined").addClass("cinza")
                $(this).find(".material-symbols-outlined").removeClass("branco")
                $(this).attr("data-like","false")
                var pai = $(this).parent()
             
               
                $.ajax({
                    url: "http://127.0.0.1/likes.php",
                    type: "POST",
                    data: {"id" : pai.parent().find(".id").text(), "id2" : "True" ,"tipo": "C", "Nome" : sessionStorage.getItem("nome")},
                    dataType: "json",
                    success: function(xhr){
                    pai.find(".quantiLC").html(xhr)
                    
                    },
                    error: function(){}
                })
            }else{
                $(this).find(".material-symbols-outlined").addClass("branco")
                $(this).find(".material-symbols-outlined").removeClass("cinza")
                $(this).attr("data-like","true")
                var pai = $(this).parent()
               
               
                $.ajax({
                    url: "http://127.0.0.1/likes.php",
                    type: "POST",
                    data: {"id" : pai.parent().find(".id").text(),"tipo": "C", "Nome" : sessionStorage.getItem("nome")},
                    dataType: "json",
                    success: function(xhr){
                        pai.find(".quantiLC").html(xhr)
                    
                    },
                    error: function(){}
                })
            }
        })

$("#Enviar").click(function(event){ 
            event.preventDefault()
        
            var pai = $(this).parent()
            var vo = pai.parent().find("#buga").text()
            var lo = vo.split(",")
           
            $.ajax({
                url : "http://127.0.0.1/Comentarios.php",
                type :"POST",
                dataType: "JSON",
                data: {"texto": $("#inputCome").val(), "IDP":lo[0],"NomeR" : lo[1], "NomeU":sessionStorage.getItem("nome")},
                success : function(xhr){
                  
                    alert(xhr)
        
                   fazC(lo[0])
                    $("#Escreve")[0].reset()
                },
                error : function(){},
        
            })
        })



//posts de inicio feitos
function VErPF(){
    var email = sessionStorage.getItem("email");
    
    $.ajax({
        url: "http://localhost/Perfil.php",
        type: "POST",
        data: {"email" : email,"Tipo":"Feitos"},
        dataType: "json",
        success: function(xhr){
            $(".NomeUsuario").text(xhr.nome)
            $(".qP").text("Posts:  "+xhr.Posts)
            $(".qL").text("Likes:  "+xhr.Likes)
           
            for(let i =0 ; i <= xhr.PostesF.length; i++ ){
                
                CriaPost(xhr.PostesF[i][0],xhr.PostesF[i][1],xhr.PostesF[i][2],xhr.PostesF[i][3],xhr.PostesF[i][4],".contem_TUDO")
                if( xhr.PostesF[i][6] == 1){

                    setTimeout(function(){
                        $("#"+xhr.PostesF[i][3]+"").find(".like").find(".material-symbols-outlined").css({"color":"white"})
                       },10)
                  }
            
            
            }
           

        },
        error: function(xhr) {

        }



    })
}

$(document).ready(function(){
    VErPF()
})

function VerPC(){
    var email = sessionStorage.getItem("email");
    
    $.ajax({
        url: "http://localhost/Perfil.php",
        type: "POST",
        data: {"email" : email,"Tipo":"Curtidos" },
        dataType: "json",
        success: function(xhr){
            $(".NomeUsuario").text(xhr.nome)
            $(".qP").text("Posts:  "+xhr.Posts)
            $(".qL").text("Likes:  "+xhr.Likes)
            if(!xhr.PostesF){
                $(".contem_TUDO").append("<h1 class='text-center text-white'> Nenhum Post Curtido </h1>")
            }else{
            for(let i =0 ; i <= xhr.PostesF.length; i++ ){
                
                CriaPost(xhr.PostesF[i][0][0],xhr.PostesF[i][0][1],xhr.PostesF[i][0][2],xhr.PostesF[i][0][3],xhr.PostesF[i][0][4],".contem_TUDO")
                setTimeout(function(){
                        $("#"+xhr.PostesF[i][0][3]+"").find(".like").find(".material-symbols-outlined").css({"color":"white"})
                       },10)
                  
            
            
            }
           ;

        }},
        error: function(xhr) {
         
        }



    })
}

function VerPCom(){
    var email = sessionStorage.getItem("email");
    
    $.ajax({
        url: "http://localhost/Perfil.php",
        type: "POST",
        data: {"email" : email,"Tipo":"Comentado" },
        dataType: "json",
        success: function(xhr){
            $(".NomeUsuario").text(xhr.nome)
            $(".qP").text("Posts:  "+xhr.Posts)
            $(".qL").text("Likes:  "+xhr.Likes)
            if(!xhr.PostesF){
                $(".contem_TUDO").append("<h1 class='text-center text-white'> Nenhum Post Comentado </h1>")
            }else{
            for(let i =0 ; i <= xhr.PostesF.length; i++ ){
                
                CriaPost(xhr.PostesF[i][0][0],xhr.PostesF[i][0][1],xhr.PostesF[i][0][2],xhr.PostesF[i][0][3],xhr.PostesF[i][0][4],".contem_TUDO")
                if( xhr.PostesF[i][1] == "1"){
                    setTimeout(function(){
                      
                        $("#"+xhr.PostesF[i][0][3]+"").find(".like").find(".material-symbols-outlined").css({"color":"white"})
                       },10)
                  }else {
               
                  }
            
            
            }
           ;

        }},
        error: function(xhr) {
         
        }



    })
}

function TButoes(x){
    if($("#op2").find(x).length > 0){

    }else{
        $(".contem_TUDO").empty()
        var b1 =  $("#op2").find("button").first().detach();
        var b2 = $("#op1").find(x).detach();
   
        $("#op1").find("hr").remove()
    
        $("#op2").prepend(b2)
    
        $("#op1").append("<hr>")
        $("#op1").append(b1)}
   
}





$("#b2").click(function(){
    TButoes("#b2")
    VerPC()
})

$("#b3").click(function(){
    VerPCom()
    TButoes("#b3")
    
})
$("#b1").click(function(){
    VErPF()
    TButoes("#b1")
})