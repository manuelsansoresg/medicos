if($('#lead-curp').length > 0){
    $('#lead-curp').on('change', function(){
        console.log($(this).val());
        $.ajax({
            url: '/paciente/curp',
            type: 'POST',
            data: {curp: $(this).val()},
            success: function(response){
                let data = response.data;
                if(data.length > 0){
                    $('#lead-nombre').val(data[0].vnombre);
                    $('#lead-apellido').val(data[0].vapellido);
                    $('#lead-segundo-apellido').val(data[0].segundo_apellido);
                }
            }
        });
    });
}


