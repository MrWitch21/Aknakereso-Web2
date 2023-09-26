const validation = new JustValidate("#signup");

validation.addField("#username", [
    {
        rule: "required"
    }
    
]).addField("#email",[
    {
        rule:"required"
    },
    {
        rule:"email"
    },
    {
        validator : (value) => () => {
            fetch("validate-email.php?email="+encodeURIComponent(value)).then(function(response){
                return response.json();
            }).then(function(json){
                return json.available
            });
        },
        errorMessage: "Ilyen email már létezik"
    }
]).addField("#password",[
    {
        rule:"required"
    },
    {
        rule:"password"
    }
]).addField("#password-confirm",[
    {
        validator: (value, fields) =>{
            return value === fields["#password"].elem.value;
        },
        errorMessage: "Nem egyezzik a két jelszó"
    }
]).onSuccess((event)=>{
    document.getElementById("submit").submit();
});