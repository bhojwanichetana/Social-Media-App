// like button

let likeBtn= documen.querySelector('.hearticon');
let likeImg=document.querySelector('like-icon');

likeBtn.addEventListener('click', ()=>{
    likeImg.classList.toggle('show');
})