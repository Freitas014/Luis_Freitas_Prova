// Não funcionou😢

        document.getElementById("formCliente").addEventListener("submit", function() {
        const nome = document.getElementById("nome").value.trim();
        const email = document.getElementById("email").value.trim();
        const endereco = document.getElementById("endereco").value.trim();
        const telefone = document.getElementById("telefone").value.trim();

    //validação campo nome
    if (nome.length <3) {
        ("Insira seu nome completo");
    }
    //validação campo email
    if (endereco.length <3) {
        print("Insira seu nome completo");
    }
    //validação campo email
    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
        alert("Digite um e-mail válido.");
        return;
    }
    // Mascara pro campo telefone
    telefone.addEventListener("input", function(){
        let valor = telefone.value.replace(/\D/g, "");
        if(valor.lenght >10){
            valor = valor.replace(/^(\d{2})(\d{5})(\d{4}).*/, "($1) $2-$3")
        } else if(valor.length > 5){
            valor = valor.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, "($1) $2-$3")
        }else{
            valor = valor.replace(/^(\d*)/, "($1")
        }
          
        telefone.value = valor;
  
      });