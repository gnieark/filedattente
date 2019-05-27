function createElem(type,attributes)
{
    var elem=document.createElement(type);
    for (var i in attributes)
    {elem.setAttribute(i,attributes[i]);}
    return elem;
}

function set_guichets_list()
{

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var myArr = JSON.parse(this.responseText);
            myArr.forEach(function(element){
                var option = createElem("option", {"value": element["id"]});
                option.innerHTML = element["text"];
                document.getElementById("guichets").appendChild(option);
            }
            );  
        }
    };
    xmlhttp.open("GET", "/api.php?entry=guichets", true);
    xmlhttp.send();
}
function callNext(guichet)
{




}
function callByNumber(guichet,ticket)
{
    document.getElementById("buttonCallNumber").disabled = true;
    document.getElementById("buttonCallNext").disabled = true;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/api.php?entry=call", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() { 
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            // Request finished. Do processing here.
            document.getElementById("numberTicket").value = "";
            document.getElementById("buttonCallNumber").disabled = false;
            document.getElementById("buttonCallNext").disabled = false;
        }
    }
    xhr.send("guichet=" + guichet + "&ticket=" + ticket);

}
function loadForm(guichet)
{
    //purge
    var container = document.getElementById("callsForm");
    container.innerHTML = "";

    var title = createElem("h2",{});
    title.innerHTML = "Appeler un ticket:";
    container.appendChild(title);
    var selectGuichets = createElem("select",{"id": "guichets"});
    container.appendChild(selectGuichets);

    var inputTicket = createElem("input",{  "type": "number",
                                            "id":   "numberTicket",
                                            "min": "1",
                                            "max": "9999"});

    container.appendChild(inputTicket);
    var buttonCallNumber = createElem( "button", {
            "id": "buttonCallNumber",
            "onclick": "callByNumber( document.getElementById(\"guichets\").value, document.getElementById(\"numberTicket\").value);"
    });
    buttonCallNumber.innerHTML="Appeler";                                           
    container.appendChild(buttonCallNumber);
    
    var buttonCallNext = createElem("button",{
        "id": "buttonCallNext",
        "onclick": "callNext(document.getElementById(\"guichets\").value);"
    });
    buttonCallNext.innerHTML="Appeler le prochain ticket";
    container.appendChild(buttonCallNext);

    set_guichets_list();
}