export default class Validator{
    valide;
    check = function(form){
        for (var i =0 ; i < form.length-1;i++) {
            let element = form[i]
            //(dans notre cas c'est le label et on recupere la value pour affiche notre message d'error avec le label)
            element.label = form.children[i].firstElementChild.innerHTML


            if (element.value !== "") {
                switch (element.type) {
                    case 'email':
                        this.checkMail(element)
                        break;
                    case 'text':
                        this.checkText(element)
                        break;
                    case "password":
                        this.checkPassword(element)
                        break;
                    case 'select-one':
                        this.checkOption(element)
                        break;
                }
            } else {
                this.error(element.name, 'le champ ' + element.label + ' est require');
            }
        }
    }

    checkText = function(element){
        if(element.name === 'mail'){
            this.checkMail(element)
        }else if (element.name === 'password'){
            this.checkPassword(element)
        }else if (element.name === 'city'){
            this.checkCity(element)
        }else if (element.name === 'price'){
            this.checkPrice(element)
        }else if (element.name === 'surface'){
            this.checkPrice(element)
        }else if (element.name === 'room'){
            this.checkPrice(element)
        }else if(element.name === 'floor'){
            this.checkPrice(element)
        }else if (element.value.length < 4){
            this.error(element.name, 'le champ '+ element.label+ ' doit contenir plus 3 caractère !')
        }else{
            let regax = /^(?=.{4,20}$)(?![_.-])(?!.*[_.-]{2})[a-zA-Z_-]+([^._-])$/gm
            if (element.value.match(regax)){
                return true
            }else{
                this.error(element.name, 'le champ '+ element.label +' n\'accepte pas les chiffres!')
            }
        }
    }
    checkOption = function(element){
        if(element.value !== '0'){
            return true
        }else{
            this.error(element.name, 'le champ ' + element.label + ' est require!')
        }
    }
    checkZip = function(element){
        let regex = /[0-9]{5}/gm
        if (element.value.match(regex)){
            return true;
        }else{
            this.error(element.name, 'le champ '+ element.label + ' invalide!')
        }
    }
    checkCity = function(element){
        let regex = /[a-zA-Z-]/gm
        if (element.value.match(regex)){
            return true
        }else{
            this.error(element.name, 'le champ '+ element.label + ' invalide!')
        }
    }

    checkPrice = function(element){
        let regex = /[0-9]/gm
        if (element.value.match(regex)){
            return true
        }else{
            this.error(element.name, 'le champ ' + element.label + ' invalide!')
        }
    }

    checkMail = function(element){

        let regax = /^((\w[^\W]+)[\.\-]?){1,10}\@(([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/gm
        if (element.value.match(regax)){
            return true;
        }else{
            this.error('mail', 'le champ ' + element.label + ' n\'a pas valide! exemple@exemple.com')

        }
    }

    checkPassword = function(element){

        let regax = /^(?=(?:.*[A-Z]){2,})(?=(?:.*[a-z]){2,})(?=(?:.*\d){2,})(?=(?:.*[!@#$%^&*()\-_=+{};:,<.>]){2,})(?!.*(.)\1{2})([A-Za-z0-9!@#$%^&*()\-_=+{};:,<.>]{12,20})$/gm
        if (element.value.match(regax)){
            return true
        }else{

            this.error(element.name, 'le champ '+ element.label + ' n\'a pas valie :',
                [
                    'le mot de passe contient au moins 2 lettres majuscules !',
                    'le mot de passe comporte au moins 2 lettres minuscules',
                    'le mot de passe comporte au moins 2 chiffres (0-9)',
                    'le mot de passe contient au moins 2 caractères spéciaux, du groupe !@#$%^&*()-_=+{};:,<.>',
                    'le mot de passe ne comporte pas plus de 2 caractères consécutifs identiques',
                    'le mot de passe est composé de 12 à 20 caractères'
                ])
        }
    }
    //check form si il est valide
    checkform = function(form){
        let errorElement = form.querySelectorAll('.error')
        if(errorElement.length > 0){
            return false
        }
        return true
    }
    // create les errors
    error = function(name, message, menu){
        let input
        if(name === 'images[]'){
            input = document.querySelector('[type=file]')
        }else{
            input = document.querySelector('[name='+ name +']')
        }


        let div = document.createElement('div')
        let span = document.createElement('span')
        div.classList.add('error')
        span.className='error-message'
        div.innerHTML ='<i class="fa-solid fa-circle-exclamation"></i>'
        span.innerText = message
        div.appendChild(span)

        if (menu){
           let ul = document.createElement('ul')
            ul.className='error-message'
            for (let l = 0;l < menu.length; l++){
                let li = document.createElement('li')
                ul.appendChild(li)
                li.innerHTML = menu[l]
            }
            div.appendChild(ul)
        }
        input.parentNode.appendChild(div)
        return  false

    }
    //create message du seccuss
    seccuss = function (form, message){
        // create les elements
        let div = document.createElement('div')
        let span = document.createElement('span')
        // add les class 'Css'
        div.className='success'
        span.className ='success-message'
        div.innerHTML = '<i class="fa-solid fa-circle-check"></i>'
        span.innerHTML = message
        div.appendChild(span)
        form.parentNode.insertBefore(div, form)
    }
    // deleted seccuss
    deletedSeccuss = function (form){
        let seccussElement = form.querySelector('.seccuss')
        if(seccussElement){
            form.removeChild(seccussElement)
        }
    }
    // deleted errors
    deletedError = function () {
        let errorElements = document.querySelectorAll('.error')

        for (let i = 0; i < errorElements.length; i++){
            if(errorElements[i]){
                errorElements[i].parentNode.removeChild(errorElements[i])
            }
        }
    }
}