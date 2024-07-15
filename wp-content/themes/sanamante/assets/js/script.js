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

let menuMovil = jQuery("#menu-movil");
let menuSmall = jQuery("#mf-menu-small");
menuSmall.click((e) => {
    e.preventDefault();
    if (!menuMovil.hasClass('menu-on')) {
        menuMovil.addClass('menu-on');
        menuSmall.find('i.fas').removeClass('fa-bars');
        menuSmall.find('i.fas').addClass('fa-times text-light');
        return;
    }
    menuMovil.removeClass('menu-on');
    menuSmall.find('i.fas').removeClass('fa-times text-light');
    menuSmall.find('i.fas').addClass('fa-bars');
});