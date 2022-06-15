export default class SecurityPassword {
     option = [
        {
            name :'chiffre',
            regex:/(.*[0-9])/g,
            message : "le mot de passe doit contenir 1 chiffre (0-9)",
        },
        {
            name :'majuscules',
            regex:/(.*[A-Z])/g,
            message : "le mot de passe doit contenir 1 lettres majuscules",
        },
        {
            name : 'minuscules',
            regex:/(.*[a-z])/g,
            message : "le mot de passe doit contenir 1 lettres minuscules",
        },
        {
            name : 'alphanumérique',
            regex:/(.*[!@#$%^&*()_+<>?])/g,
            message : "le mot de passe doit contenir (!@#$%^&*()_+<>?)",
        },
        {
            name : 'caractères',
            regex:/(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,16}/g,
            message : "le mot de passe est composé de 8 à 16 caractères sans espace",
        },
        {
            name : 'confirme',
            message : "le mot de passe doit être confirmer",
        },
    ]
    valide = false;
    icon = "fa-solid fa-check"
    constructor(form) {
        this.form = form
    }
    AddIcon = function(selector){
        this.removeIcon(selector)
        this.li.innerHTML = '<i class="'+this.icon +'"></i>' + this.li.innerHTML
        this.valide = true
    }
    removeIcon = function (selector) {
        this.li = this.form.querySelector('.'+selector)
        let IconElement = this.li.firstElementChild
        console.log(IconElement)
        if(IconElement){
            this.li.removeChild(IconElement)
            this.valide = false
        }

    }
    createElement = function(input){
        let div = document.createElement('div')
        div.className = "password"
        let ul = document.createElement('ul')
        let li = document.createElement('li')

        this.option.forEach(function (item,index,array) {
            let li = document.createElement('li')
            li.className = item.name
            li.innerText = item.message;
            ul.appendChild(li)
        })

        div.appendChild(ul)
        input.parentNode.appendChild(div)
    }

    deledElement = function(element){
        let parent = element.parentElement
        let child = parent.querySelector('.password')
        if(child){
            parent.removeChild(child)
        }
    }
    getValide = function(){
        return this.valide
    }


    ScurePassword = function(password){

        this.option.forEach(function(item, key, array){

            if (item.regex){
                if(password.match(item.regex)){
                    this.AddIcon(item.name)
                }else{
                    this.removeIcon(item.name)
                }
            }
        }, this)
    }
    checkPassword = function (password, password1){
        if (password !== ''){
            if(password === password1){
                this.AddIcon('confirme')
            }else{
                this.removeIcon('confirme')
            }
        }
    }


}