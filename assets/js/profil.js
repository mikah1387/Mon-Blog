

let links = document.querySelectorAll('.navprofil a')


links.forEach(link => {
    link.addEventListener('click', ()=>{
 document.querySelector('.navprofil a.active').classList.remove('active');
  link.classList.add('active')
        
  let attribut = link.getAttribute("href");

  document.querySelector('.main_profil .display').classList.remove('display')
  document.querySelector(attribut).classList.add('display');
    
  console.log(document.querySelector(attribut));

 })

});
