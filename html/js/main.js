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
function callByNumber(guichet,ticket)
{
    alert(guichet + " " + ticket);
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
                                                    "onclick": "callByNumber( document.getElementById(\"guichets\").value, document.getElementById(\"numberTicket\").value);"
                                                });
    buttonCallNumber.innerHTML="Appeler";                                           
    container.appendChild(buttonCallNumber);     
    set_guichets_list();
}