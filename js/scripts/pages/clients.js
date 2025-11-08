$(document).ready(function(){

  $(document).on('change','.check_committee_member',function(){
        var id_num = $(this).data('check');
        var value = $(this).val();
        if ($(this).val() == 'yes') {
          $('#div_position' + id_num).show();
        } else {
          $('#div_position' + id_num).hide();
          $('#position' + id_num).val('');
        }
    });

  if ($(".pickadate").length) {
      $(".pickadate").pickadate({
        format: "dd/mm/yyyy",
        onStart: function() { this.set({ select: new Date()}); }
      });
    }
 if ($(".pickadate1").length) {
      $(".pickadate1").pickadate({
        format: "dd/mm/yyyy",
       
      });
    }

// add class in row if checkbox checked

    $("#service").select2({
     
      dropdownAutoWidth: true,
      width: '100%',
      placeholder: "Select Services"
    });
    $("#company").select2({
     
      dropdownAutoWidth: true,
      width: '100%',
      placeholder: "Select company"
    });
  $(document).on('click','.add_row',function(){
      var i=$('.total').val();
      var j=parseInt(i)+1;
          $('.contact_div').append('<div class="row main_row"><input type=hidden value="'+(j)+'" class="form-control count cou'+j+'"><input type=hidden  class="form-control contact_id contact_id'+j+'" value=""><div class="col-md-4 col-12"> <div class="form-label-group"> <input type="text" class="form-control name name'+j+'" name="name[]" value="" placeholder="Name"> <label for="last-name-column">Name</label><span class="name_err'+j+' valid_err"></span> </div> </div> <div class="col-md-3 col-12"> <div class="form-label-group"> <input type="text" class="form-control email email'+j+'" name="email[]" value="" placeholder="Email Id"> <label for="last-name-column">Email Id</label><span class="email_err email_err'+j+' valid_err"></span> </div> </div> <div class="col-md-2 col-12"> <div class="form-label-group"> <input type="text" class="form-control contact contact'+j+'" name="contact[]" placeholder="Contact No" > <label for="city-column">Contact No</label><span class="contact_err contact_err'+j+' valid_err"></span> </div> </div> <div class="col-md-2 col-12"> <div class="form-label-group"> <input type="text" class="form-control whatsapp whatsapp'+j+'" name="whatsapp[]" placeholder="Whatsapp No" > <label for="city-column">Whatsapp No</label><span class="whatsapp_err whatsapp_err'+j+' valid_err"></span> </div> </div> <div class="col-md-10"> <div class="row"> <div class="col-md-3"> Are you committee member? </div> <div class="col-md-3"> <ul class="list-unstyled mb-0"> <li class="d-inline-block mr-2 mb-1"> <fieldset> <div class="radio"> <input type="radio" name="committee_member['+j+']" class="check_committee_member" value="yes" data-check="'+j+'" id="radio'+j+'"> <label for="radio'+j+'">Yes</label> </div> </fieldset> </li> <li class="d-inline-block mr-2 mb-1"> <fieldset> <div class="radio"> <input type="radio" name="committee_member['+j+']" class="check_committee_member" value="no" data-check="'+j+'" id="radio0'+j+1+'"> <label for="radio0'+j+1+'">No</label> </div> </fieldset> </li> </ul> </div> <div class="col-md-4" id="div_position'+j+'" style="display:none;"> <fieldset class="form-group"> <div class="input-group"> <select class="form-control position" id="position'+j+'" name="position['+j+']"> <option value="">Select Position</option> <option value="Chairman">Chairman</option> <option value="Secretary">Secretary</option> <option value="Treasurer">Treasurer</option> <option value="Committee Member">Committee Member</option> </select> </div> <span class="position_err'+j+' valid_err"></span> </fieldset> </div> </div> </div><div class="col-md-1 col-12 mb-1"> <button type="button" class="btn mr-2 btn-light-secondary delete_row"><i class="bx bx-trash-alt"></i></button> </div></div>');
        
          $('.total').val(j);
  });
  $(document).on('click', '.delete_row', function () {
          
         
      var i=$('.total').val();
      var j=parseInt(i)-1;
      // $('.total').val(j);
      $(this).closest('.main_row').remove();
      var count=$(this).closest('.main_row').find('.count').val();
      var count_arr = new Array();
      $('.count').each(function() {
          count_arr.push($(this).val());
          });
          console.log(count_arr);
 
    
    
});

$.ajax({
  type:'get',
  url:'autocomplete_client_name',
 

  success:function(data){
     
      $( ".client_name" ).autocomplete({
  
              source:data
          });
  }
});
});
$(document).on('blur','.email',function(){
  var mailformat = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
  var email=$(this).val();
  if(email!='')
  {
      if(mailformat.test(email)==false)
      {
          $(this).focus().css('border-color','red');
          $(this).closest('.form-label-group').find('.email_err').html('Invalid email id').css('color','red');
      }
      else
      {
          $(this).css('border-color','');
          $(this).closest('.form-label-group').find('.email_err').html('');
          return true;
      }
  }
  else
  {
      $(this).css('border-color','');
      $(this).closest('.form-label-group').find('.email_err').html('');
      return true;
  }
    
});


$(document).on('blur','.contact',function(){
  var phoneno = /^\d{10}$/;
  var mob_no=$(this).val();
 
  if(/^\d{10}$/.test(mob_no))
  {
      
     
        
              $(this).css('border-color','');
              $(this).closest('.form-label-group').find('.contact_err').html('');
              return true;
      
  }
      else
      {
          $(this).focus().css('border-color','red');
          $(this).closest('.form-label-group').find('.contact_err').html('Invalid number; must be ten digits').css('color','red');
      }
  
    
});
 

$(document).on('blur','.whatsapp',function(){
  
  var phoneno = /^\d{10}$/;
  var what_no=$(this).val();
 if(what_no.length!=0)
 {
  if(/^\d{10}$/.test(what_no))
  {
      
     
        
              $(this).css('border-color','');
              $(this).closest('.form-label-group').find('.whatsapp_err').html('');
              return true;
      
  }
      else
      {
          $(this).focus().css('border-color','red');
          $(this).closest('.form-label-group').find('.whatsapp_err').html('Invalid whatsapp number; must be ten digits').css('color','red');
      }
  
 }
 else
 {
  $(this).css('border-color','');
              $(this).closest('.form-label-group').find('.whatsapp_err').html('');
              return true;
 }
});

$( ".client_name" ).autocomplete({
  
  select: function( event, ui ) {
      $('.contact_div').empty();
      $('.valid_err').html('');
     //console.log(ui.item.value);
     var client_name=ui.item.value;
     $.ajax({
      type:'get',
      url:'get_exist_client',
     
      data:{
          client_name:client_name
      },
      success:function(data){
          console.log(data);
          
           $('#update').css('display','block');
            $('#submit').css('display','none');
          jQuery.each(data, function(i, val) {
              console.log(val.company_id[0]);
              $('#property_type').val(val.property_type);
              $('#company').val(val.company_id).prop('disabled', true);;
              $('#company').trigger('change');
              $('.client_id').val(val.id);
              $('.no_of_units').val(val.no_of_units);
              $('.start_date').val(val.date_format);
              $('.address').val(val.address);
              $('.longitude').val(val.longitude);
              $('.latitude').val(val.latitude);
              $('.source').val(val.source);
             
              $('.city').val(val.city).prop('disabled', true);;
              $('.case_no_span').text(val.case_no);
              $('.client_enquiry').val(val.remarks);
              $('#service').val(val.services_id);
              $('#service').trigger('change');

              var client_contacts=val.client_contacts;
              var j=1;
              if(client_contacts!='')
              {
                  for(k=0; k<client_contacts.length; k++){
                  $('.contact_div_up').empty();
                  if(client_contacts[k].name==null)
                      {
                          client_contacts[k].name='';
                      }
                      if(client_contacts[k].email==null)
                      {
                          client_contacts[k].email='';
                      }
                      if(client_contacts[k].contact==null)
                      {
                          client_contacts[k].contact='';
                      }
                      if(client_contacts[k].whatsapp==null)
                      {
                          client_contacts[k].whatsapp='';
                      }
                      if(client_contacts[k].position==null)
                      {
                          client_contacts[k].position='';
                      }
                      if(client_contacts[k].committee_member==null)
                      {
                          client_contacts[k].committee_member='';
                      }

                      var committee_member = '<div class="col-md-10"> <div class="row"> <div class="col-md-3"> Are you committee member? </div> <div class="col-md-3"> <ul class="list-unstyled mb-0"> <li class="d-inline-block mr-2 mb-1"> <fieldset> <div class="radio">';
                      if(client_contacts[k].committee_member == 'yes'){
                        console.log('Yes'+j);
                         committee_member +='<input type="radio" name="committee_member['+k+']" class="check_committee_member" value="yes" data-check="'+k+'" id="radio'+k+'" checked="checked"> <label for="radio'+k+'">Yes</label> </div> </fieldset> </li> <li class="d-inline-block mr-2 mb-1"> <fieldset> <div class="radio"> <input type="radio" name="committee_member['+k+']" class="check_committee_member" value="no" data-check="'+k+'" id="radio0'+k+1+'"> <label for="radio0'+k+1+'">No</label> </div> </fieldset> </li> </ul> </div><div class="col-md-4" id="div_position'+k+'">';
                      }else if(client_contacts[k].committee_member == 'no'){
                        console.log('NO'+j);
                         committee_member +='<input type="radio" name="committee_member['+k+']" class="check_committee_member" value="yes" data-check="'+k+'" id="radio'+k+'"> <label for="radio'+k+'">Yes</label> </div> </fieldset> </li> <li class="d-inline-block mr-2 mb-1"> <fieldset> <div class="radio"> <input type="radio" name="committee_member['+k+']" class="check_committee_member" value="no" data-check="'+k+'" id="radio0'+k+1+'" checked="checked"> <label for="radio0'+k+1+'">No</label> </div> </fieldset> </li> </ul> </div><div class="col-md-4" id="div_position'+k+'" style="display:none;">';
                      }
                      
                      committee_member += '<fieldset class="form-group"> <div class="input-group"><select class="form-control position" id="position'+k+'" name="position['+k+']">';

                       if(client_contacts[k].position == ''){
                        committee_member +='<option value="">Select Position</option> <option value="Chairman">Chairman</option> <option value="Secretary">Secretary</option> <option value="Treasurer">Treasurer</option> <option value="Committee Member">Committee Member</option> </select>';
                       }else if(client_contacts[k].position == 'Chairman'){
                        committee_member +='<option value="">Select Position</option> <option value="Chairman" selected>Chairman</option> <option value="Secretary">Secretary</option> <option value="Treasurer">Treasurer</option> <option value="Committee Member">Committee Member</option> </select>';
                       }else if(client_contacts[k].position == 'Secretary'){
                        committee_member +='<option value="">Select Position</option> <option value="Chairman">Chairman</option> <option value="Secretary" selected>Secretary</option> <option value="Treasurer">Treasurer</option> <option value="Committee Member">Committee Member</option> </select>';
                       }else if(client_contacts[k].position == 'Treasurer'){
                        committee_member +='<option value="">Select Position</option> <option value="Chairman">Chairman</option> <option value="Secretary">Secretary</option> <option value="Treasurer" selected>Treasurer</option> <option value="Committee Member">Committee Member</option> </select>';
                      }else if(client_contacts[k].position == 'Committee Member'){
                        committee_member +='<option value="">Select Position</option> <option value="Chairman">Chairman</option> <option value="Secretary">Secretary</option> <option value="Treasurer">Treasurer</option> <option value="Committee Member" selected>Committee Member</option> </select>';
                      }
                      committee_member +='</div> <span class="position_err'+k+' valid_err"></span> </fieldset> </div> </div> </div>';
                      

                  if(k==0)
                  {
                      
                      $('.contact_div').append('<div class="row main_row"><input type=hidden value="'+(j)+'" class="form-control count cou'+j+'"><input type=hidden  class="form-control contact_id contact_id'+j+'" value="'+client_contacts[k].id+'"><div class="col-md-4 col-12"> <div class="form-label-group"> <input type="text" class="form-control name name'+j+'" name="name[]" value="'+client_contacts[k].name+'" placeholder="Name"> <label for="last-name-column">Name</label><span class="name_err'+j+' valid_err"></span> </div> </div> <div class="col-md-3 col-12"> <div class="form-label-group"> <input type="text" class="form-control email email'+j+'" name="email[]" value="'+client_contacts[k].email+'" placeholder="Email Id"> <label for="last-name-column">Email Id</label><span class="email_err email_err'+j+' valid_err"></span> </div> </div> <div class="col-md-2 col-12"> <div class="form-label-group"> <input type="text" class="form-control contact contact'+j+'" name="contact[]" value="'+client_contacts[k].contact+'" placeholder="Contact No" > <label for="city-column">Contact No</label><span class="contact_err contact_err'+j+' valid_err"></span> </div> </div> <div class="col-md-2 col-12"> <div class="form-label-group"> <input type="text" class="form-control whatsapp whatsapp'+j+'" name="whatsapp[]" value="'+client_contacts[k].whatsapp+'" placeholder="Whatsapp No" > <label for="city-column">Whatsapp No</label><span class="whatsapp_err whatsapp_err'+j+' valid_err"></span> </div> </div> </div>'+committee_member+'');

                  }
                  else
                  {
                      $('.contact_div').append('<div class="row main_row"><input type=hidden value="'+(j)+'" class="form-control count cou'+j+'"><input type=hidden  class="form-control contact_id contact_id'+j+'" value="'+client_contacts[k].id+'"><div class="col-md-4 col-12"> <div class="form-label-group"> <input type="text" class="form-control name name'+j+'" name="name[]" value="'+client_contacts[k].name+'" placeholder="Name"> <label for="last-name-column">Name</label><span class="name_err'+j+' valid_err"></span> </div> </div> <div class="col-md-3 col-12"> <div class="form-label-group"> <input type="text" class="form-control email email'+j+'" name="email[]" value="'+client_contacts[k].email+'" placeholder="Email Id"> <label for="last-name-column">Email Id</label><span class="email_err email_err'+j+' valid_err"></span> </div> </div> <div class="col-md-2 col-12"> <div class="form-label-group"> <input type="text" class="form-control contact contact'+j+'" name="contact[]" value="'+client_contacts[k].contact+'" placeholder="Contact No" > <label for="city-column">Contact No</label><span class="contact_err contact_err'+j+' valid_err"></span> </div> </div> <div class="col-md-2 col-12"> <div class="form-label-group"> <input type="text" class="form-control whatsapp whatsapp'+j+'" name="whatsapp[]" value="'+client_contacts[k].whatsapp+'" placeholder="Whatsapp No" > <label for="city-column">Whatsapp No</label><span class="whatsapp_err whatsapp_err'+j+' valid_err"></span> </div> </div> '+committee_member+'<div class="col-md-1 col-12 "> <button type="button" class="btn mr-2 btn-light-secondary delete_row"><i class="bx bx-trash-alt"></i></button> </div></div>');
                    
                  }
                  
                  $('.total').val(j);
              }
              $('.add_row_up').css('display','block')
              }
              

            });
           
      }
  });
  }
});