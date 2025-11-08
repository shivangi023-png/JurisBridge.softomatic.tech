 $('#submit').click(function(){
     console.log("inside save");
            $('.valid_err').html('');
            var arr=[];
                var name = new Array();
                $('.name').each(function() {
                    name.push($(this).val());
                    });
                    var email = new Array();
                $('.email').each(function() {
                    email.push($(this).val());
                    });
                    var contact = new Array();
                $('.contact').each(function() {
                    contact.push($(this).val());
                    });
                    var whatsapp = new Array();
                $('.whatsapp').each(function() {
                    whatsapp.push($(this).val());
                    });
                    var service = new Array();
                    $('.service :selected').each(function() {
                    service.push($(this).val());
                    }); 

                var client_name=$('.client_name').val();
                var name1=$('.name1').val();
                var email1=$('.email1').val();
                var contact1=$('.contact1').val();
                var whatsapp1=$('.whatsapp1').val();
                var case_no=$('.case_no').val();
                //var service=$('.service').val();
                var start_date=$('.start_date').val();
                var city=$('.city').val();
                var address=$('.address').val();
                var no_of_units=$('.no_of_units').val();
                var property_type=$('.property_type').val();
                var staff_comment=$('.staff_comment').val();
                var client_enquiry=$('.client_enquiry').val();
                var mailformat = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                var company=$('.company').val();
        if(client_name=='')   
        {
            arr.push('client_name_err');
            arr.push('Client name required');
        }  
        if(name=='')   
        {
            arr.push('name1_err');
            arr.push('Name required');
        }   
         if(email1!='' && mailformat.test(email1)==false)  
        {
            arr.push('email1_err');
            arr.push('Invalid client Email ID');
        }
        if(whatsapp1!='' && whatsapp1.length!=10)  
        {
            arr.push('whatsapp1_err');
            arr.push('Invalid client whatsapp no');
        } 
         if(contact1=='' || contact1.length!=10)  
        {
            arr.push('contact1_err');
            arr.push('Invalid client contact no');
        } 
        var total=$('.total').val();
        var count_arr = new Array();
                $('.count').each(function() {
                    count_arr.push($(this).val());
                    });
        for(var i=0;i<count_arr.length;i++)
        {
            
            
            var name1=$('.name'+count_arr[i]).val();
            var email1=$('.email'+count_arr[i]).val();
            var contact1=$('.contact'+count_arr[i]).val();
            var whatsapp1=$('.whatsapp'+count_arr[i]).val();
            if(name1=='')   
            {
                arr.push('name_err'+count_arr[i]);
                arr.push('Name required');
            }   
            if(email1!='' && mailformat.test(email1)==false)  
            {
                arr.push('email_err'+count_arr[i]);
                arr.push('Invalid  Email ID');
            }
            if(whatsapp1!='' && whatsapp1.length!=10)  
            {
                arr.push('whatsapp_err'+count_arr[i]);
                arr.push('Invalid  whatsapp no');
            } 
            if(contact1=='' || contact1.length!=10)  
            {
                arr.push('contact_err'+count_arr[i]);
                arr.push('Invalid client contact no');
            } 
        }
         if(address=='')   
        {
            arr.push('address_err');
            arr.push('Address required');
        }  
         if(city=='')   
        {
            arr.push('city_err');
            arr.push('City is required');
        }  
        if(service=='')   
        {
            arr.push('service_err');
            arr.push('Please select services');
        }
         if(start_date=='')   
        {
            arr.push('start_date_err');
            arr.push('Date is required');
        } 
         if(case_no=='')   
        {
            arr.push('case_no_err');
            arr.push('Case no is required');
        }    
        if(no_of_units=='')   
        {
            arr.push('no_of_units_err');
            arr.push('No of units required required');
        }  
        if(property_type=='')   
        {
            arr.push('property_type_err');
            arr.push('Property type required');
        }  
      

        if(arr!='')
        {
            for(var i=0;i<arr.length;i++)
            {
                var j=i+1;
                
                
                $('.'+arr[i]).html(arr[j]).css('color','red');
               
               
              
                i=j;
            }
        }
        else
        {
  
            $.ajax({
            type:'post',
            url:'client/add',
            data:{
            name:name,
            email:email,
            contact:contact,
            whatsapp:whatsapp,
            client_name:client_name,
            case_no:case_no,
            service:service,
            start_date:start_date,
            city:city,
            address:address,
            staff_comment:staff_comment,
            client_enquiry:client_enquiry,
            company:company,
            no_of_units:no_of_units,
            property_type:property_type
            },

            success:function(data){
              console.log(data);
              var res=JSON.parse(data);
              if(res.status=='success')
              {
                swal({
                    title: "Success!",
                    text: "Client detail inseted!",
                    icon: "success",
                  });
                $("#form").trigger('reset');
              }
               else
               {
                swal({
                    title: "error!",
                    text: res.msg,
                    icon: "error",
                  });
               
               } 
            },
           error:function(data){
              console.log(data);
          }
        });      
        }
       
        });
        $('.add_row').click(function(){
            var i=$('.total').val();
            var j=parseInt(i)+1;
                $('.contact_div').append('<div class="row main_row"><input type=hidden value="'+(j)+'" class="form-control count cou'+j+'"><div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"><input type="text" class="form-control name name'+j+'" name="name[]" autocomplete="off" placeholder="Name"><span class="name_err'+j+' valid_err"></span> </div> <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"><div class="form-group"><div class="form-line"><input type="text" class="form-control email email'+j+'" name="email[]"  placeholder="Email ID"> <span class="email_err email_err'+j+' valid_err"></span> </div> </div> </div> <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"><div class="form-group"><div class="form-line"><input type="text" class="form-control contact contact'+j+'" name="contact[]"  onkeyup="check();return false;" placeholder="Contact" autocomplete="off"><span class="contact_err contact_err'+j+' valid_err"></span> </div> </div> </div> <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"><div class="row"> <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10"><div class="form-group"><div class="form-line"><input type="text" class="form-control whatsapp whatsapp'+j+'" name="whatsapp[]" placeholder="Whatsapp No." autocomplete="off"><span class="whatsapp_err whatsapp_err'+j+' valid_err"></span> </div> </div> </div> <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2"> <button type="button" class="waves-effect btn bg-red delete_row"><i class="material-icons">delete</i></button> </div> </div> </div> ');
              
                $('.total').val(j);
        }); 

       