
// function pour un smooth scroll

// $(function() {
//     $(".navbar a, footer a").on("click", function (event) {
//         event.preventDefault();
//         var hash = this.hash;
//
//         $('body,html').animate({scrollTop: $(hash).offset().top}, 900, function(){window.location.hash = hash;})
//     });
// })



$(function() {


// cacher la navbar après click sur mobile

    $('.navbar-nav>li>a').on('click', function(){
        $('.navbar-collapse').collapse('hide');
    });

// ajax pour formulaire de contact

    $('#contact-form').submit(function(e){

        // ne pas traiter le form avec le champ action du form, empêche les actions par default.
        e.preventDefault();

        $('.comments').empty();
        var postdata = $('#contact-form').serialize();


        $.ajax({
            type: 'POST',
            url: 'php/contact.php',
            data: postdata,
            dataType: 'json',
            success: function(result) {

                if(result.isSuccess){
                    $("#contact-form").append("<p class='thank-you'>Votre message a bien été envoyé. Merci</p>");
                    $("#contact-form")[0].reset();
                } else {
                    $("#firstname + .comments").html(result.firstnameError);
                    $("#name + .comments").html(result.nameError);
                    $("#email + .comments").html(result.emailError);
                    $("#phone + .comments").html(result.phoneError);
                    $("#message + .comments").html(result.messageError);

                }

            }


        });

    });


});
