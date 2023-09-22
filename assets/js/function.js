"use strict";
const d = document;
d.addEventListener("DOMContentLoaded", function(event) {

    // options
    const breakpoints = {
        sm: 540,
        md: 720,
        lg: 960,
        xl: 1140
    };

    var sidebar = document.getElementById('sidebarMenu');
    if(sidebar && d.body.clientWidth < breakpoints.lg) {
        sidebar.addEventListener('shown.bs.collapse', function () {
            document.querySelector('body').style.position = 'fixed';
        });
        sidebar.addEventListener('hidden.bs.collapse', function () {
            document.querySelector('body').style.position = 'relative';
        });
    }

    var iconNotifications = d.querySelector('.notification-bell');
    if (iconNotifications) {
        iconNotifications.addEventListener('shown.bs.dropdown', function () {
            iconNotifications.classList.remove('unread');
        });
    }

    [].slice.call(d.querySelectorAll('[data-background]')).map(function(el) {
        el.style.background = 'url(' + el.getAttribute('data-background') + ')';
    });

    //Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
     return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
      return new bootstrap.Popover(popoverTriggerEl)
    });

    var scroll = new SmoothScroll('a[href*="#"]', {
        speed: 500,
        speedAsDuration: true
    });


    // if (menu_active) {
    //     let _menu = $('#sidebarMenu').find('#_menu-'+menu_active);
    //     if (_menu.length) {
    //         _menu.addClass('active');
    //         let parent = _menu.parents('.nav-item');
    //         if (parent.length) {
    //             parent
    //                 .find('.multi-level').addClass('show').end()
    //                 .find('.collapsed').removeClass('collapsed').attr('aria-expanded', 'true');
    //         }
    //     }

    // }

})

function disabledPageForm() {
    $('#main-body').find('input, select, textarea, button').prop('disabled', true);
}



function addComma(input, decision = 0, defaultText = '0') {
    if (input || input === 0) {
        var parts = input.toString().replace(/[^-0-9\.]+/g, '');
        parts = parts.split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        parts[0] = (parts[0]) ? parts[0] : '0';
        if (parts.length >= 2) {
            if (parts[1].length >= decision) {
                parts[1] = parts[1].substring(0, decision)
            } else {
                parts[1] = parts[1].padEnd(decision, '0');
            }
        } else if (decision) {
            parts.push('0');
            parts[1] = parts[1].padEnd(decision, '0');
        }

        parts = parts.slice(0, 2);
        return parts.join(".");
    }
    return defaultText;
}


function dateToShortThaiDateText(dateText) {
    const months = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    const date = new Date(dateText);
    const month = months[date.getMonth()];
    const year = (date.getFullYear()).toString().substr(-2);
    return String(date.getDate()).padStart(2, '0') + ' ' + month + ' ' + year
}