console.log("Ce programme JS vient d'être chargé");
$(document).ready(function()
{   
    $('#inputRecherche input').keyup(function (e) { 
        var value_lenght = $(this).val().length;
        if(value_lenght >= 3){
            
            $.get('marvel.php',{sug:$(this).val()},
            function (reponse){
                console.log(reponse);
                $('.suggestion').remove();
                for(val in reponse){
                    console.log(reponse[val].page_id);
                    $("#suggestion").append($("<p class=\"suggestion\" page_id=\""+reponse[val].page_id+"\" id=\"1\">"+reponse[val].name+"</p>"));
                }    
            });

        }else{
            $('.suggestion').remove();
        }
    });

    $("#suggestion").on('click','.suggestion',(e)=>{
        $.get('marvel.php',{page_id:$(e.target).attr('page_id')},
        (reponse)=>{
            console.log(reponse);
            var strsplit = reponse.name.split(' (');

            var url = reponse.url.split("/revi")[0];

            var newCard = $("<h1>"+strsplit[0]+"</h1><ul><li>Real Name: "+strsplit[1].substring(0, strsplit[1].length - 1)+"</li><li>eyes: "+reponse.EYE+"</li><li>Hair: "+reponse.HAIR+"</li><li>Sex: "+reponse.SEX+"</li><li>Years: "+reponse.Year+"</li></ul>")
            var newImg = $("<img src=\""+url+"\">");
            $("#backface").children().remove();
            $("#backface").append(newCard);
            $('.suggestion').remove();

            $("#frontface").children().remove();
            $("#frontface").append(newImg);
        });
    });

});