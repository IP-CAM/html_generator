//Init global vars

$(document).ready(function() {
   arrList = [];
});

//Selecting products and add them in to array
function onAddProduct(){
    var e = document.getElementById("labelExport").innerHTML="";
    var name = window.arrList;
    name.push($('#product_item').find(':selected').text());
    showListproducts(name);
}

//Pass theme and period to php for making header.html
function passThemeToPhp(){
//    var arrTxt = [];
//    arrTxt.push(document.getElementById("editTextTheme").value);
//    arrTxt.push(document.getElementById("editTextPeriod").value);
//    var data = arrTxt.join(',');
    
    var data = document.getElementById("editTextTheme").value + '|' + document.getElementById("editTextPeriod").value;
    console.log(data);
    
    $.ajax({
    type: 'GET',
    cache: false,
    data: {theme:data},
    dataType: 'json',
    url: 'php/set_theme.php',
    });
}

//Removing selected products from array
function onRemoveProduct(numberElement){
    var name = window.arrList;
    console.log("onRemoveProduct: name.lengh = "+name.length);
    name.splice(numberElement,1);
    showListproducts(name);
}

//Showing list of products
function showListproducts(name){
    var e = document.getElementById("list");
        console.log("showListproducts name.lengh = "+name.length);
        var str = "";
        for(var i=name.length; i>=0; i--){
            if(typeof name[i] == 'undefined'){
            }else{
                var number=(i+1);
                str = str +'<p>'+number+') '+name[i]+' <a class="btn btn-link btn-sm"  id="'+i+'" onClick="onRemoveProduct('+i+'); return false;" >удалить</a>' + '</p>';
            }
        }
        e.innerHTML= str;
}

//Pass array to php
function passArrayToPhp(){
    passThemeToPhp();
    var name = window.arrList;
//    var data = JSON.stringify(name);
    
    var data = name.join(',');
    
    $.ajax({
    type: 'GET',
    cache: false,
    data: {v:name},
    dataType: 'json',
    url: 'php/export_to_html.php',
    });
    document.getElementById("label_success").innerHTML="Экспорт успешен - нажмите Готово";
}