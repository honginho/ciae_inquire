/*!
 * Start Bootstrap - SB Admin 2 v3.3.7+1 (http://startbootstrap.com/template-overviews/sb-admin-2)
 * Copyright 2013-2016 Start Bootstrap
 * Licensed under MIT (https://github.com/BlackrockDigital/startbootstrap/blob/gh-pages/LICENSE)
 */
$(function() {
    $('#side-menu').metisMenu();
});

function clickDel(event){
    if(confirm("確定刪除此筆資料?")){

    }else{
        event.preventDefault();
    }
}
function sort(id){
    var query = {}
    if(location.search != "")
        query = explodeQuery(location.search);
    console.log(query);
    if(query.sortBy == id && query.orderBy == "desc")
        query.orderBy = "asc";
    else{
        query.orderBy = "desc";
        query.sortBy = id;
    }
    
    var url = "?";
    for(var key in query){
        // url += `${key}=${query[key]}&`;
        url += key+"="+query[key]+"&";
    }
    url = url.slice(0,-1);
    location.assign(url);
}
function explodeQuery(query){
    // console.log(query);
    query = query.slice(1);
    var result = {};
    var array = query.split("&");
    array.forEach(function(element){
        var temp = element.split("=");
        result[temp[0]] = temp[1];
    });
    return result;
}
(function changeIcon(){
    // console.log("a");
    if(location.search != ""){
        var query = explodeQuery(location.search);
        var icon = document.getElementById(query.sortBy).children[0];
        icon.classList.remove("fa-sort");
        if(query.orderBy == 'desc')
            icon.classList.add("fa-sort-desc");
        else if(query.orderBy == 'asc')
            icon.classList.add("fa-sort-asc");
    }
    
})()
function checkFile(event){
    var fileName = document.getElementById('file').value.split(/(\\|\/)/g).pop();
    // console.log(fileName);
    var test = /(.xls$|.xlsx$)/;
    if(!test.test(fileName)){
        event.preventDefault();
        document.getElementById('file_error').innerHTML = 
            '<div class="alert alert-danger alert-dismissible" role="alert">'+
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                    '<span aria-hidden="true">&times;</span>'+
                '</button>'+
                '<strong>檔案格式錯誤</strong>'+
            '</div>'
        
    }
}
//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        var topOffset = 50;
        var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        var height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    // var element = $('ul.nav a').filter(function() {
    //     return this.href == url;
    // }).addClass('active').parent().parent().addClass('in').parent();
    var element = $('ul.nav a').filter(function() {
        return this.href == url;
    }).addClass('active').parent();

    while (true) {
        if (element.is('li')) {
            element = element.parent().addClass('in').parent();
        } else {
            break;
        }
    }
});
