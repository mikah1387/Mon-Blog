let burg = document.querySelector('.burg')
let navbar = document.querySelector('.navbar')
let body = document.querySelector('body')
burg.addEventListener('click', function(){

    this.classList.toggle("open");
    navbar.classList.toggle("open");
    body.classList.toggle("overhidden");
    

})