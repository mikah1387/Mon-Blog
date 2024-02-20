BalloonEditor.create(document.querySelector('#editor')).
then(editor=>{

 document.querySelector('.addpost form').addEventListener('submit',function(e){
    e.preventDefault();
    
    this.querySelector('input[type="hidden"]').value =editor.getData();
    this.submit();
 })

});

