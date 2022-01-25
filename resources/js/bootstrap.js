// window._ = require('lodash');

window.Popper = require('popper.js').default;
window.$ = window.jQuery = require('jquery');
require('bootstrap');

window.Swal = require('sweetalert2');

window.swalSuccess = function (message) {
    $(document.activeElement).blur();
    return Swal.fire({
        title: 'عملیات موفق آمیز بود!',
        html: message,
        type: 'success',
        customClass: 'rtl-t',
        confirmButtonText: 'تایید',
        confirmButtonClass: 'text-shadow bg-primary',
    });
};

window.swalInfo = function (message) {
    $(document.activeElement).blur();
    return Swal.fire({
        html: '<h4 style="font-size:20px;">' + message + "</h4>",
        type: 'question',
        customClass: 'rtl-t',
        confirmButtonText: 'تایید',
        confirmButtonClass: 'text-shadow bg-primary',
    });
};

window.swalWarning = function (message,withTitle=true) {
    $(document.activeElement).blur();
    if (withTitle) {
        return Swal.fire({
            title: 'یک مشکل وجود دارد!',
            html: message,
            type: 'warning',
            customClass: 'rtl-t',
            confirmButtonText: 'تایید',
            confirmButtonClass: 'text-shadow bg-primary',
        });
    }
    else {
        return Swal.fire({
            html: '<h4 style="font-size:21px;">' + message + "</h4>",
            type: 'warning',
            customClass: 'rtl-t',
            confirmButtonText: 'تایید',
            confirmButtonClass: 'text-shadow bg-primary',
        });
    }
};

window.swalError = function (message,withTitle=true) {
    $(document.activeElement).blur();
    if (withTitle) {
        return Swal.fire({
            title: 'یک مشکل وجود دارد!',
            html: message,
            type: 'error',
            customClass: 'rtl-t',
            confirmButtonText: 'تایید',
            confirmButtonClass: 'text-shadow bg-primary',
        });
    }
    else {
        return Swal.fire({
            html: '<h4 style="font-size:21px;">' + message + "</h4>",
            type: 'error',
            customClass: 'rtl-t',
            confirmButtonText: 'تایید',
            confirmButtonClass: 'text-shadow bg-primary',
        });
    }
};

window.swalConnectionError = function () {
    return swalError("مشکلی در هنگام ارتباط با سرور پیش آمده! لطفا دوباره تلاش کنید. (در صورت تداوم این مشکل یکبار صفحه را بارگزاری مجدد کنید.)",false);
};

window.trim = function (val) { return val ? val.toString().trim() : ''; };

window.csrfToken = function () {
    return trim($('meta[name="csrf-token"]').attr('content'));
};

window.inArray = function (target,arr) {
    var res = false;
    for (var i=0;i<arr.length;i++) {
        if (target == arr[i]) {
            res = true;
            break;
        }
    }
    return res;
};

window.fixNumbers = function (str) {
    var newStr = trim(str);
    if (newStr=="") { return newStr; }
    var faNums = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    var enNums = ['0','1','2','3','4','5','6','7','8','9'];
    var reg;
    for (var i=0;i<faNums.length;i++) {
        reg = new RegExp(faNums[i],'g');
        newStr = newStr.replace(reg,enNums[i]);
    }
    return newStr;
};

window.fixUrl = function (url) {
    url = trim(url);
    if (url.slice(-1) == '/')
        url = url.slice(0,-1);
    return trim(url);
};

window.fixRoute = function (route) {
    route = trim(route);
    if (route == '')
        return route;
    if (route.charAt(0) != '/')
        route = '/' + route;
    return route;
};

window.appRoute = function (route) {
    return fixRoute(route);
};

window.ajaxPost = function (url,data=false,csrf=true) {
    if (csrf) { $.ajaxSetup({headers: {'X-CSRF-Token': csrfToken()}}); }
    return (data ? $.post(appRoute(url),data) : $.post(appRoute(url)));
};

window.ajaxGet = function (url) {
    return $.get(appRoute(url));
};

window.enInput = function () {
    $('.en-input').each(function () {
        var val = $(this).val().toString().trim();
        if (val != '') {
            $(this).css('direction','ltr');
        }
        else {
            $(this).css('direction','rtl');
        }
    });

    $('.en-input').on('input',function (e) {
        var targetElm = e.target;
        var currentVal = targetElm.value.toString().trim();
        if (currentVal != '') {
            targetElm.style.direction = "ltr";
        }
        else {
            targetElm.style.direction = "rtl";
        }
    });
};

// window.axios = require('axios');

// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
