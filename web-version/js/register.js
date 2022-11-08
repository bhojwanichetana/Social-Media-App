const form = document.querySelector('.form form')
submitbtn = form.querySelector('.submit input'),
errortxt = form.querySelector('.error-txt'),

form.onsubmit = (e) => {
    e.preventDefault();
}

submitbtn.onclick = () =>{
    //ajax
    let xhr = new XMLHttpRequest();
    xhr.open("POST","./php/signup.php", true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status == 200){
                let data = xhr.response;
                if(data == "Success"){
                    location.href = "./verify.php"
                }
                else {
                    errortxt.innerText = data;
                    errortxt.style.display = "block";
                }
            }
        }
    }
    //sending data from ajax to php
    let formData = new FormData(form);//creating new object
    xhr.send(FormData);//sending data to php
}