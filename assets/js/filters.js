window.onload = ()=>{
     let formfilter = document.querySelector('.div_select');
    let selectElement = document.querySelector('#categorie');
  
    selectElement.addEventListener('change',(e)=> {
   
        //  selectedOptionValue = e.target.value;
        //  console.log(selectedOptionValue);
        const form = new FormData(formfilter);
        const params = new URLSearchParams();
        console.log(form)
        form.forEach((val,key) => {
            

     
        //    params.append(key,val);
        console.log(params.append(key,val))   ;
       
        });
      const url = new URL(window.location.href)
    //   let u = "?categorie=" + selectedOptionValue;
    //   console.log(u);
      console.log(params.toString());
      fetch(url.pathname + "?" + params.toString(), {
         
          headers: {
             "X-Requested-with": "XMLHttpRequest"
          }

      }).then(response =>
        response.json()
    ).then(data =>{
       const content = document.querySelector('#content');
       content.innerHTML = data.content;
       history.pushState({},null,url.pathname  + "?" +params.toString() )
    //    history.pushState({},null,url.pathname + "?" + params.toString())
    }).catch(e=>alert(e));
     
    });
}