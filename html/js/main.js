
function createElem(type,attributes)
{
    var elem=document.createElement(type);
    for (var i in attributes)
    {elem.setAttribute(i,attributes[i]);}
    return elem;
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
    for (var i = 0; i < guichets.length; i++) {
        var optionG = createElem("option",{"value" : guichets[i]["id"],});
        optionG.innerHTML = guichets[i]["text"];
        selectGuichets.appendChild(optionG);
    }
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

    
}


function refershCallsView(lastTime)
{


}