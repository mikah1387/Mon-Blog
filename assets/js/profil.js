

let links = document.querySelectorAll('.barnav a')

let btnmodifs = document.querySelectorAll('td .modifier')
let btnsuprim = document.querySelectorAll('td .suprimer')

let confirm = document.querySelector('#confirmLink');
let paraph = document.querySelector('.modal-body p');
let titre = document.querySelector('#staticBackdropLabel');

links.forEach(link => {
    link.addEventListener('click', ()=>{
 document.querySelector('.barnav a.active').classList.remove('active');
  link.classList.add('active')
        
  let attribut = link.getAttribute("href");

  document.querySelector('.main .display').classList.remove('display')
  document.querySelector(attribut).classList.add('display');
    
 

 })

});

function replaceLink(link) {

    confirm.href = link;
}

function modifContext(mot, slug =''){

    paraph.textContent ='Etes-vous sûr de vouloir le modifier ';
    titre.textContent = 'Modification du '+ mot + slug ;
}
function suprimContext(mot, slug =''){

    paraph.textContent ='Etes-vous sûr de vouloir le suprimer ';
    titre.textContent = 'Suppression du '+ mot + slug ;
}
btnmodifs.forEach(btn => {
    
    btn.addEventListener('click', function(){

        let link = this.getAttribute('href');
        let slug = this.getAttribute('data-slug');
    
        if (link.includes('article')) {
           
            let mot = 'l\'article ';
            modifContext(mot, slug);   
        }else if (link.includes('comments')) {
            let mot = 'commentaire';
            modifContext(mot );
            
        }    
        replaceLink(link);
        

    })
});

btnsuprim.forEach(btn => {
    
    btn.addEventListener('click', function(){

        let link = this.getAttribute('href');
        let slug = this.getAttribute('data-slug');
        if (link.includes('article')) {
           
            let mot = 'l\'article ';
            suprimContext(mot, slug);   
        }else if (link.includes('comments')) {
            let mot = 'commentaire';
            suprimContext(mot);   

            
        }    
        replaceLink(link);
        
      
    })
});