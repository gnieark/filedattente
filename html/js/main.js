
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

function initializeViewsTable()
{
    for (var i = 0; i < guichetsGroupes.length; i++) {
        var articleGroup = createElem("article",{"id": "group" + guichetsGroupes[i]["id"]});

        var articleGroupTitle = createElem("h2",{});
        articleGroupTitle.innerHTML = guichetsGroupes[i]["text"];
        articleGroup.appendChild(articleGroupTitle);

        document.getElementById("view").appendChild(articleGroup);
    }

}
function getGroupeId(guichetId)
{
    for(var i = 0; i < guichets.length; i++){
        if(guichets[i]["id"] == guichetId)
        {
            return guichets[i]["group"];
        }
    }
    return 0;
}
function add_a_call(callDef)
{
    if(document.getElementById("call" + callDef["guichet"])){
        //destroy It
        document.getElementById("call" + callDef["guichet"]).parentElement.removeChild(document.getElementById("call" + callDef["guichet"]));
    }

   
    var container = document.getElementById("group" + getGroupeId( callDef["guichet"]) );
    var newCall = createElem("article",{"id": "call" + callDef["guichet"] });
    var emGuichet = createElem("em",{"class":"GuichetNumber"});
    //******************************* */


}    
function refershCallsView(lastTime)
{
    if (document.getElementById("view").innerHTML == "")
    {
        initializeViewsTable();
    }
    //PutInfos

    var xmlhttp = new XMLHttpRequest();
   
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var calls = JSON.parse(this.responseText);
            calls.array.forEach(function(element) {
                add_a_call(element);
            });
        }
    };
    xmlhttp.open("GET", "/api.php?entry=call&from_time=" + lastTime, true);
    xmlhttp.send();

}