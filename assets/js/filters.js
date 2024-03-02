window.onload = ()=>{
    //  let formfilter = document.querySelector('.div_select');
    //  let selectElement = document.querySelector('#categorie');

    let selectCaty = document.querySelector('.selectcaty');
    
        
            selectCaty .addEventListener('change',(e)=> {
                let prantEl = selectCaty .parentNode;
                const form = new FormData(prantEl);
                const params = new URLSearchParams();  
                form.forEach((val,key) => {         
            
                params.append(key,val)  ;
                            
                });

               console.log( params.toString());
             const url = new URL(window.location.href) 
             
     fetch(url.pathname + "?" + params.toString() + "&ajax=1",{
         
          headers: {
             "X-Requested-with": "XMLHttpRequest"
           }

         }).then(response =>
          response.json()
         ).then(data =>{
       
        const content = document.querySelector('#content');
        content.innerHTML = data.content;
        history.pushState({},null,url.pathname + "?" +params.toString() )

        }).catch(e=>alert(e));
               })
    
        let selectElement = document.querySelector('.selecttrie');
    
        
               selectElement.addEventListener('change',(e)=> {
                   let prantEl = selectElement.parentNode;
                   const form = new FormData(prantEl);
                   const params = new URLSearchParams();  
                   form.forEach((val,key) => {         
               
                   params.append(key,val)  ;
                               
                   });
   
                  console.log( params.toString());
                const url = new URL(window.location.href) 
                
        fetch(url.pathname + "?" + params.toString() + "&ajax=1",{
            
             headers: {
                "X-Requested-with": "XMLHttpRequest"
              }
   
            }).then(response =>
             response.json()
            ).then(data =>{
          
           const content = document.querySelector('#content');
           content.innerHTML = data.content;
           history.pushState({},null,url.pathname + "?" +params.toString() )
   
           }).catch(e=>alert(e));
                  })
        
    // selectElement.addEventListener('change',(e)=> {
   
    //     //  selectedOptionValue = e.target.value;
    //     //  console.log(selectedOptionValue);
    //     const form = new FormData(formfilter);
    //     const params = new URLSearchParams();
       
    //     form.forEach((val,key) => {         
    //  //    params.append(key,val);
    //     params.append(key,val)  ;
       
    //     });
    //     console.log( params.append(key,val))
    //   const url = new URL(window.location.href)     
    //   fetch(url.pathname + "?" + params.toString() + "&ajax=1", {
         
    //       headers: {
    //          "X-Requested-with": "XMLHttpRequest"
    //       }

    //   }).then(response =>
    //     response.json()
    // ).then(data =>{
       
    //    const content = document.querySelector('#content');
    //    content.innerHTML = data.content;
    //    history.pushState({},null,url.pathname + "?" +params.toString() )

    // }).catch(e=>alert(e));
     
    // });

}

