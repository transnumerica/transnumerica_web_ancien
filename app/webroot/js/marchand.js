let resultatTabBody = document.querySelector('#resultat_tab_body');

function consommer(ticket_id){
    myFetch({url: ""+window.location.origin+"/m/consommer_billet_marchand", json: {ticket_id}, thenFct: (res)=>{
        console.log({res});
        let success = res?.success;
        let messages = res?.message;

        var msg = "";
        for(var str in messages){
            msg+= `${str} : ${messages[str]}; <br/>`;
        }
        //var aucunResultat = false;
        /*
        if(dataWeb == null) aucunResultat = true;
        else if(dataWeb.length==0) aucunResultat = true; 
        */

        //let changed = dataWeb?.changed;
        //alertK(`success : ${success} - ${typeof success}`);
        if(success) resultatTabBody.querySelector(`#T${ticket_id} .consomme`).innerHTML = "Consommé";
        else alertK(`${msg}<br/>Veuillez contacter le service client SVP`);
        
        //console.log({, dataWeb});
        
        
    }, 
        catchFct: (res)=>{},
        spinner: true,
    });
} 

function getTrByItem(item){
    return `<td><button class="btn btn-primary" onclick="myFetchTicketShow(${item.ticket_id});" >${item.command}</button></td>
    <td class="consomme">${item.consomme==1?"Consommé":`<button class="btn btn-secondary" onclick="consommer(${item.ticket_id});" >Réservé</button>`}  </td>
    <td>${item.firstname}</td>
    <td>${item.name}</td>
    <td>${item.total}</td>
    <td>${item.currency_iso}</td>
    <td>${item.mobile_money}</td>
    <td>${item.phone}</td>
    <td>${item.email}</td>
    <td>${item.departure_date}</td>
    <td>${item.created}</td>`;
}

function myFetchSalesMarchand({data}){
    myFetch({url: `${window.location.origin}/m/sales_marchand`, json: data, thenFct: (res)=>{
        console.log('après : ', res);
        let dataWeb = res?.data;
        var aucunResultat = false;
        if(dataWeb == null) aucunResultat = true;
        else if(dataWeb.length==0) aucunResultat = true; 
        
        if(aucunResultat) {
            resultatTabBody.innerHTML = "";
            alertK("Aucun Résultat Trouvé");
        }else{
            
            var strRes = "";
            for(let item of dataWeb){
                strRes+= `
<tr id="T${item.ticket_id}" >
    ${getTrByItem(item)}
    
</tr>
                `;
            }

            resultatTabBody.innerHTML = strRes;
            $('#reservationModal')?.modal('hide');

        }

        
        
    }, 
        catchFct: (res)=>{},
        spinner: true,
    });
} 