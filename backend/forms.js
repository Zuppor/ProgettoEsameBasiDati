function formhash(form,password){
    //crea elemento di input che verrà usato come campo di output per la password criptata
    var p = document.createElement("input");

    //aggiungi elemento al form
    form.appendChild(p);

    p.name = "password";
    p.type = "hidden";
    p.value = hex_sha512(password.value);

    //non inviare la password in chiaro
    password.value = "";
    //esegui il submit
    form.submit();
}