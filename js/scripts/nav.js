$(document).ready(function(){
$('.company_btn').click(function(){
    var company=$(this).data('company');
   
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    
    });
    $.ajax({
                        type:'post',
                        url:'change_company',
                        data:{company:company},
                        success:function(data){
                          console.log(data);
                         
                          location.reload();
                        },
                        error:function(data)
                        {
                            console.log(data);
                        }
                    });
});
});