function formhash(form,password){
    //crea elemento di input che verrà usato come campo di output per la password criptata
    /*var p1 = document.createElement("input");
    var p2 = document.createElement("input");

    //aggiungi elemento al form
    form.appendChild(p1);
    form.appendChild(p2);

    p1.name = "password";
    p1.type = "hidden";
    p1.value = hex_sha512(password1.value);

    p2.name = "password2";
    p2.type = "hidden";
    p2.value = hex_sha512(password2.value);

    //non inviare la password in chiaro
    password1.value = "";
    password2.value = "";

    //esegui il submit
    form.submit();*/
    password.value = hex_sha512(password.value);
    //password2.value = hex_sha512(password2.value);

    form.submit();
}

