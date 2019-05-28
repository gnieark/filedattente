
function createElem(type,attributes)
{
    var elem=document.createElement(type);
    for (var i in attributes)
    {elem.setAttribute(i,attributes[i]);}
    return elem;
}

function callByNumber(guichet,ticket)
{
    document.getElementById("buttonCallNumber").disabled = true;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/api.php?entry=call", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() { 
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            // Request finished. Do processing here.
            document.getElementById("numberTicket").value = "";
            document.getElementById("buttonCallNumber").disabled = false;
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
            "onclick": "callByNumber( document.getElementById(\"guichets\").value, document.getElementById(\"numberTicket\").value);",
            "placeHolder" : "Numéro du ticket à appeler"
    });
    buttonCallNumber.innerHTML="Appeler";                                           
    container.appendChild(buttonCallNumber);
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
function getGuichetById(id)
{
    for(var i=0; i < guichets.length; i++)
    {
        if(guichets[i]["id"]  == id){
            return guichets[i];
        }

    }
    return 0;
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
function hideViewOne()
{
    document.getElementById("viewOne").style.display = "none";
}

function clean_olds_calls()
{
    console.log("clean");

    var xmlhttp = new XMLHttpRequest();
   
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var calls = JSON.parse(this.responseText);
 
            //create list of guichets used
            var guichetsList = [];
            for(var i=0; i < calls.length; i++ )
            {
                guichetsList.push(calls[i]["guichet"]);
            }
            var regex = new RegExp(/call([0-9]+)/);
 
            for (var i = 0; i < guichetsGroupes.length; i++) {
                var container = document.getElementById("group"+ guichetsGroupes[i]["id"]);
                var chields = container.childNodes;
                for (var j = 0; j < chields.length; j++) {
        
                    var match = regex.exec(chields[j].id)
                    if(match){
                        if (guichetsList.indexOf(match[1]) < 0) {
                            chields[j].parentElement.removeChild(chields[j]);
                        }
                    }
                }
            }
            window.setTimeout(clean_olds_calls, 60000);
        }
    };
    xmlhttp.open("GET", "/api.php?entry=call&from_time=0", true);
    xmlhttp.send();

}

function add_a_call(callDef)
{
    if(document.getElementById("call" + callDef["guichet"])){
        //destroy It
        document.getElementById("call" + callDef["guichet"]).parentElement.removeChild(document.getElementById("call" + callDef["guichet"]));
    }

    var guichet = getGuichetById(callDef["guichet"]);
   
    var container = document.getElementById("group" + guichet["group"] );
    var newCall = createElem("article",{"id": "call" + callDef["guichet"], "class":"call" });

    var emNumeroAppele = createElem("em",{"class": "numeroappele"});
    emNumeroAppele.innerHTML = "numéro appelé";
    newCall.appendChild(emNumeroAppele);

    var emGuichet = createElem("em",{"class":"GuichetNumber"});
    emGuichet.innerHTML = guichet["text"];
    newCall.appendChild(emGuichet);

    var emguichettitre = createElem("em",{"class": "guichetTitre"});
    emguichettitre.innerHTML = "Guichet";
    newCall.appendChild(emguichettitre);

    var emTicket = createElem("em",{"class":"ticketnumber"});
    emTicket.innerHTML =  callDef["ticket"];
    newCall.appendChild(emTicket);


    //container.appendChild(newCall);
    container.insertBefore(newCall, container.children[1]);
    
    document.getElementById("artViewOne").innerHTML = newCall.innerHTML;
    document.getElementById("viewOne").style.display = "block";
    sonnerie.play();
    window.setTimeout(hideViewOne, 4000);
}    
function refershCallsView()
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
            for(var i = 0; i < calls.length; i ++)
            {
                add_a_call(calls[i]);
                if(lastTime < calls[i]["call_time"]){
                    lastTime = calls[i]["call_time"];
                }
            }
            window.setTimeout(refershCallsView, 2000);
        }
    };
    xmlhttp.open("GET", "/api.php?entry=call&from_time=" + lastTime, true);
    xmlhttp.send();
}