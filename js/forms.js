function formhash(form,password,password2){
    //crea elemento di input che verrà usato come campo di output per la password criptata
    var p1 = document.createElement("input");
    var p2 = document.createElement("input");


    //aggiungi elemento al form
    form.appendChild(p1);
    form.appendChild(p2);


    //alert("PASS1 _"+password.value+"_");
    p1.name = "password";
    p1.type = "hidden";
    if(password.value === "")
        p1.value = hex_sha512(password.value);
    else
        p1.value = null;


    p2.name = "password2";
    p2.type = "hidden";
    if(password2.value === "")
        p2.value = hex_sha512(password2.value);
    else
        p2.value = null;



    //non inviare la password in chiaro
    password.value = "";
    password2.value = "";


    //esegui il submit
    form.submit();
}