

import SecurityPassword from "./SecurityPassword"
// event validator formulaire de creation user ou annoncement
let form = document.querySelector('#create');
let password = form.querySelector('[name=password]')
let password1 = form.querySelector('[name=password1]')
let security = new SecurityPassword(form)
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

/* Add ajax form gené les errors */