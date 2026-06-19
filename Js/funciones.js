$(document).ready(function(){

jQuery.validator.addMethod("mayus", function(value, element) {
    return this.optional(element) || /^[A-Z].*/.test(value);
}, 'La primera letra debe ser Mayúscula');
    $("#Regis_for").validate({
        rules:{
            nombre:{
                required: true,
                minlength: 4,
                mayus:true
            },
            contra: {
                required:true,
                minlength: 6,
                mayus:true
            },
            correo: {
                required:true,
                email: true
            },
            telefono: {
                required:true,
                digits: true,
                minlength: 9,
                maxlength: 11
            }
        },
        messages:{
            nombre: {
                required: "El nombre de usuario es requerido",
                minlength: "Mínimo 4 caracteres"
            },
            contra: {
                required: "La contraseña es requerida",
                minlength: "Mínimo 6 caracteres"
            },
            correo: {
                required: "El correo es requerido",
                email: "El correo no es válido"
            },
            telefono: {
                required: "El teléfono es requerido",
                digits: "Solo números",
                minlength: "Mínimo 9 dígitos"
            }
        }
    });
});