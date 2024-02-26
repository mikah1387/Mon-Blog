ClassicEditor.create(document.querySelector('#editor'),{
    toolbar: {
        items: [
            'heading',
            '|',
            'bold',
            'italic',
            'underline',
            
            '|',
            'link',
            'bulletedList',
            'numberedList',
           
            '|',
            'alignment',
            'indent',
            'outdent',
            '|',
            'fontFamily',
            'fontSize',
            'fontColor',
            'fontBackgroundColor',
            '|',
            'blockQuote',
            'codeBlock',
            'insertTable',
            'mediaEmbed',
            'imageUpload',
            
            
            '|',
            'undo',
            'redo'
        ]
    },
    language: 'fr',
    codeBlock: {
        languages: [
            { language: 'plaintext', label: 'Plain text' }, // The default language.
            { language: 'c', label: 'C' },
            { language: 'cs', label: 'C#' },
            { language: 'cpp', label: 'C++' },
            { language: 'css', label: 'CSS' },
            { language: 'diff', label: 'Diff' },
            { language: 'html', label: 'HTML' },
            { language: 'java', label: 'Java' },
            { language: 'javascript', label: 'JavaScript' },
            { language: 'php', label: 'PHP' },
            { language: 'python', label: 'Python' },
            { language: 'ruby', label: 'Ruby' },
            { language: 'typescript', label: 'TypeScript' },
            { language: 'xml', label: 'XML' }
        ],
       
    }

}).
then(
  
    editor=>{

 document.querySelector('.addpost form').addEventListener('submit',function(e){
    e.preventDefault();
    
    this.querySelector('.input_hidden').value =editor.getData();
    this.submit();
 })

}
);

