var vieweroptions = { url: 'data-original' };
if(document.getElementById('describeWord'))
    var viewer = new Viewer(document.getElementById('describeWord'), vieweroptions);

$(document).ready(function() {

    // Home page snippets - UI related

    $('#openNavbarSearch').on('click', function(event){

        $('#navbarSearch').show('slide', {direction: 'right'}, 1);
    });

    // About page, nav tabs on show event
    $('.about-tabs a[data-toggle="tab"]').on('shown.bs.tab', function (event) {

        var jumpLoc = $('.about-tabs').offset().top - $('#mainNavBar').height() - 50;
        $("html, body").animate({scrollTop: jumpLoc}, 500);
    });

    $('.goTo').on('click', function (event) {

        var destination = $(this).attr('data-destination');

        var jumpLoc = $(destination).offset().top - $('#mainNavBar').height() - 50;
        console.log(jumpLoc);
        $("html, body").animate({scrollTop: jumpLoc}, 500);
    });

    $(function () {
        $('[data-toggle="popover"]').popover({

            trigger: 'focus',
            placement: 'bottom'            
        })
    });
});


function getUrlParameter(sParam) {

    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
}
