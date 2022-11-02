import { Article } from './moduls/article.mjs';
window.onload = function () {
    numFactura();
    llistaArticles();
    document.getElementsByTagName("button")[0].onclick = agregarFila;
}

function numFactura() {
    if(localStorage.getItem("article") != 0) {
        document.getElementsByName("numFactura")[0].textContent = parseInt(document.getElementsByName("numFactura")[0].textContent)+1;
    }
}

function llistaArticles() {
    var tipusArticle = ["Llibre", "Taula", "Cadira"];
    var select = document.getElementsByName("articles")[0];
    for (let value in tipusArticle) {
        var option = document.createElement("option");
        option.text = tipusArticle[value];
        select.appendChild(option);
    }
}
function agregarFila() {
    if(document.getElementsByName("articles")[0].value == "Llibre"){
        if(document.getElementsByName("Llibre")[0]){
            window.alert("Ja existeix");
        }else{
            var article = new Article('0001', 'Llibre', '1', '10€', '10€');
            escriureFila(article);
            localStorage.setItem("article") = article;
            document.getElementsByName("Llibre")[0].addEventListener("change", () => {
                canviarQuantitat(article);
                calcularResultats(article);
            });
            calcularResultats(article);
        }
    }else if(document.getElementsByName("articles")[0].value == "Taula"){
        if(document.getElementsByName("Taula")[0]){
            window.alert("Ja existeix");
        }else{
        var article = new Article('0002', 'Taula', '1', '30€', '30€');
        escriureFila(article);
        localStorage.setItem("article") = article;
        document.getElementsByName("Taula")[0].addEventListener("change", () => {
            canviarQuantitat(article);
            calcularResultats(article);
        });
        calcularResultats(article);
        }
    }else if(document.getElementsByName("articles")[0].value == "Cadira"){
        if(document.getElementsByName("Cadira")[0]){
            window.alert("Ja existeix");
        }else{
        var article = new Article('0003', 'Cadira', '1', '20€', '20€');
        escriureFila(article);
        localStorage.setItem("article") = article;
        document.getElementsByName("Cadira")[0].addEventListener("change", () => {
            canviarQuantitat(article);
            calcularResultats(article);
        });
        calcularResultats(article);
        }
    }
}
function escriureFila(article){
    document.getElementById('taula').insertRow(-1).innerHTML =
    '<td class="fila'+article.nom+'">'+article.codi+'</td><td>'+article.nom+'</td><td><input type="number" name="'+article.nom+'" value="1"></td><td>'+article.preu+'</td><td class="total" name="total'+article.nom+'">'+article.total+'</td>';
}

function canviarQuantitat(article){
    //setquantitat i setpreu
    if(document.getElementsByName(article.nom)[0].value <= 0){
            document.querySelector("tr:has(.fila"+article.nom).remove();
        }else{
        //setQuantitat(document.getElementsByName(article.nom)[0].value);
        var total = parseInt(article.preu) * document.getElementsByName(article.nom)[0].value+"€";
        document.getElementsByName("total"+article.nom)[0].textContent = total;
        //setTotal(total);
        calcularResultats(article)
    }   
}

function calcularResultats(article){
    let taula = document.querySelectorAll(".total");
    let suma = 0;
    taula.forEach(td => {
        suma = suma + parseInt(td.textContent);
    });
    document.getElementsByName("baseImposable")[0].textContent = suma+"€";
    document.getElementsByName("iva")[0].textContent = (21*suma/100)+"€";
    document.getElementsByName("import")[0].textContent = (suma + 21*suma/100)+"€";
}