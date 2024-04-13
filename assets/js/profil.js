

let links = document.querySelectorAll('.navprofil a')

let btnmodifs = document.querySelectorAll('td .modifier')
let btnsuprim = document.querySelectorAll('td .suprimer')

let confirm = document.querySelector('#confirmLink');
let paraph = document.querySelector('.modal-body p');
let titre = document.querySelector('#staticBackdropLabel');
links.forEach(link => {
    link.addEventListener('click', ()=>{
 document.querySelector('.navprofil a.active').classList.remove('active');
  link.classList.add('active')
        
  let attribut = link.getAttribute("href");

  document.querySelector('.main_profil .display').classList.remove('display')
  document.querySelector(attribut).classList.add('display');
    
 

 })

});

function replaceLink(link) {

    confirm.href = link;
}


btnmodifs.forEach(btn => {
    
    btn.addEventListener('click', function(){

        let link = this.getAttribute('href');
        let slug = this.getAttribute('data-slug');
        replaceLink(link);
        
        paraph.textContent ='Etes-vous sûr de vouloir modifier cet article';
        titre.textContent = 'Modification de  l\'article ' + slug;
    })
});

btnsuprim.forEach(btn => {
    
    btn.addEventListener('click', function(){

        let link = this.getAttribute('href');
        let slug = this.getAttribute('data-slug');
        replaceLink(link);
        
        paraph.textContent ='Etes-vous sûr de vouloir suprimer cet article';
        titre.textContent = 'Suppression de l\'article ' + slug;
    })
});