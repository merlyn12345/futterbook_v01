$(document).ready(function(){
    $('#kategorie option').bind('click', function(){
        $.get('/secure/selectdata/'+$(this).val(), function (response){
            if(response){
               if($('#item').length > 0){
                    $('#item').remove();
               }
               $(response).insertAfter('#kategorie');
            }
        });
    });
    $("#submitbutton").bind("click", function (event) {

        event.preventDefault();

        const form = $('#submitform')[0];

        // Create an FormData object
        const data = new FormData(form);

        // disabled the submit button
        $("#submitbutton").prop("disabled", true);

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "/secure/submit",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            success: function (data) {

                $("#output").html(data);
                console.log("SUCCESS : ", data);
                $("#submitbutton").prop("disabled", false);

            },
            error: function (e) {

                $("#output").text(e.responseText);
                console.log("ERROR : ", e);
                $("#submitbutton").prop("disabled", false);

            }
        });

    });
   // "https://www.polynomic.net/secure/ext_shop/{{userId}}"
    $('#external').bind('click', function(e){
        e.preventDefault();
        const userId = $(this).prop('href').split('/')[$(this).prop('href').split('/').length-1];
        $.ajax({
            type: "GET",
            url: "https://www.polynomic.net/ext_shop/"+userId,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            success: function (data) {
                let htmlstring = '';
                for (let eintrag of data) {

                        htmlstring += '<div><span>Zutat</span><h2><a href="details/' + eintrag.nahrungID + '">' + eintrag.nahrungID + ' - '+eintrag.Name+'</a></h2><p>Menge: ' + eintrag.amount + '<br>Datum: ' + eintrag.stime + '<br>Energie (cal): ' + eintrag["Energie (cal)"] + '</p></div>';

                }



                $("#output").html(htmlstring);
                console.log("SUCCESS : ", htmlstring);
                $("#submitbutton").prop("disabled", false);

            },
            error: function (e) {

                $("#output").text(e.responseText);
                console.log("ERROR : ", e);
                $("#submitbutton").prop("disabled", false);

            }
        });

    });
});