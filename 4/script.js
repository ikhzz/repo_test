const detailButton = document.querySelectorAll('.showDetails'),
  detailNode = document.querySelectorAll('.detail'),
  closeDetailNode = document.querySelectorAll('.detail .close')

detailButton.forEach((e, i)=> {
  e.addEventListener('click', ()=> {
    console.log('a')
    detailNode[i].classList.toggle('showDetail')
  })
})

closeDetailNode.forEach((e, i)=> {
  e.addEventListener('click', ()=> {
    detailNode[i].classList.toggle('showDetail')
  })
})

document.querySelector('.addRole').addEventListener('click', ()=> {
  document.querySelector('.option').style.display = 'grid';
  document.querySelector('.roleOption').style.display = 'grid'
})

document.querySelector('.addHero').addEventListener('click', ()=> {
  document.querySelector('.option').style.display = 'grid';
  document.querySelector('.heroOption').style.display = 'grid'
})

