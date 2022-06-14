/* declaration des variable */
/*
let forms = document.querySelectorAll('form') // récupére tous les formulaires return array
let i
*/
//test
import validator from "./Validator";


// event validator formulaire de creation user ou annoncement
let form = document.querySelector('#create');
form.addEventListener('submit', function(e){
    e.preventDefault()
    let button = form.querySelector('button');
    let buttonText = button.textContent

    let validation = new validator();
    validation.deletedSeccuss(this)
    validation.deletedError()
    validation.check(this)
    if (validation.checkform(this)){
        button.disabled = true
        button.textContent = 'chargement ...'
        let data = new FormData(this)
        let xhr = new XMLHttpRequest()
        xhr.onreadystatechange = function (){
            if(xhr.readyState === 4){

                if(xhr.status != 200){
                    let datas = JSON.parse(xhr.responseText)
                    validation.error(datas.name,datas.message)
                }else{
                    console.log(xhr.responseText)
                    let datas = JSON.parse(xhr.responseText)
                    validation.seccuss(form, datas.success)
                }
            }

        }
        button.disabled = false
        button.textContent = buttonText
        xhr.open('POST',this.getAttribute('action'), true)
        xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest')
        xhr.send(data)

    }
})
