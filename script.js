const editButton = document.querySelectorAll('.edit'),
    editNode = document.querySelectorAll('.edits')

editButton.forEach((e,i) => {
    e.addEventListener('click',()=> {
        console.log('a')
        editNode[i].classList.toggle('showDetail')
    })
});
if(document.querySelector('.login')){
    document.querySelector('.login').addEventListener('click', ()=> {
        console.log('tes')
          document.querySelector('.option').style.display = 'grid';
      document.querySelector('.loginOption').style.display = 'grid'
    })
}

if(document.querySelector('.addContent')){
    document.querySelector('.addContent').addEventListener('click', ()=> {
        console.log('tes')
          document.querySelector('.option').style.display = 'grid';
      document.querySelector('.contentOption').style.display = 'grid'
    })
}
