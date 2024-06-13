let overlayImages = jQuery('.image-text-hover');

jQuery.each(overlayImages, function (i, element) {
    let text = jQuery(element).find('img').attr('alt');
    jQuery(element).append("<div class='overlay'><div class='text'>"+text+"</div></div>");
});

let imgAgendaItem = jQuery('.img-agenda-item');

jQuery.each(imgAgendaItem, function (i, element) {
    let text = jQuery(element).find('img').attr('alt');
    jQuery(element).append("<a href='"+text+"' target='_blank' class='text'>Agendar</a>");
});


window.onscroll = function() {myFunction()};

var header = document.getElementById("mf-header");
var main = document.getElementById("home-view");
var sticky = header.offsetTop;

function myFunction() {
    if (window.pageYOffset > sticky) {
        header.classList.add("sticky");
    } else {
        header.classList.remove("sticky");
    }
}