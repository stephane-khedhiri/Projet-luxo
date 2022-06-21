

import SecurityPassword from "./SecurityPassword"
// event validator formulaire de creation user ou annoncement

let form = document.querySelector('#create');
let password;
let password1;
if (form){
 password = form.querySelector('[name=password]')
 password1 = form.querySelector('[name=password1]')

let security = new SecurityPassword(form)
function createError(name , message){
    let div = document.createElement('div')
    let span = document.createElement('span')
    let label = form.querySelector('[for='+name+']')
    div.classList = 'error'
    span.classList = 'error-message';
    span.innerHTML = message.replace(name, label.innerHTML)
    div.appendChild(span)
    let element = form.querySelector('[name='+ name +']')
    element.parentNode.appendChild(div)
}
function deletedError(){
    let divs = form.querySelectorAll('.error')
    divs.forEach(function (item, key, array) {
        if(item){
            item.parentNode.removeChild(item)
        }
    })
}
if(password && password1){
password.addEventListener('click', function (e){
    if(!password.value){
        security.deledElement(password1)
        security.createElement(password1)
    }
})

    password.addEventListener('input', function(event){
        security.ScurePassword(password.value)
    })
    password1.addEventListener('input',function(event){
        security.checkPassword(password.value,password1.value)
    })
}

form.addEventListener('submit',function(e){
    e.preventDefault()
    deletedError()
    let button = form.querySelector('button')
    let buttonText = button.textContent
    let data = new FormData(this)
    let request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if(this.readyState == 4){
            if (this.status !== 200 || security.valide == false){

                let datas = JSON.parse(request.responseText);
                if(security.valide == false){
                    datas.password = {'key': 'password', 'message':'le champ password n\'a pas valide !'}
                }
                for (const[key,value] of Object.entries(datas)){
                    createError(key, value.message)
                }

            }

            }

        }
    request.open('POST', this.getAttribute('action'), true);
    request.setRequestHeader('X-Requested-With', 'xmlhttprequest');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send(data)
})
}

