// prepare the form when the DOM is ready 
$(document).ready(function() { 
  
   $('form').submit(function(e){
        
      

        $.ajax({
              method: "POST",
              url: $(this).attr('action'),
              data : $(this).serialize(),
              // headers: {
              //     "Content-Type":"application/json",
              //     "Accept":"application/json",
              //     "Authorization":"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMjYyM2E2NGFhYjI5NDhmNmNkMWI5NWUwM2I2ODBhMTViNzUzMWM0MmQ1ZDVkYTZjODhiYTM4ZWNlNzIxNTAwYTU0MTEzYTE4YTczMWE3MmIiLCJpYXQiOjE1ODU2MjI0NDMsIm5iZiI6MTU4NTYyMjQ0MywiZXhwIjoxNjE3MTU4NDQzLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.B6edmkEnAs5-aZw9z6w7TEGg3BNeC-JH5ZUyvAaWlAvMiXxmETRI3ZpB5VhIbD7Wj-ydx1GFDONYJgOI6whj-szurCOn5vuvqNhPRsbIiY61_643xPBEg2i1432RMgVeVLDHKrbFJN-61ON4AqWA7-e19d0hKBCeODsP2L0C8Gt8A_WOXH-9Mzh_454A-wVKQKGrNoiJO14K1_v9JbxWdTG8idlIaYR4ejd2N4_bUeOTj5f_krxcRv_wD2ayn-W688kb0pS9-fSPee0qPfsPUyYIfBsclCid3hsqp8ZvFfs16lMTfWY37GV14QgcXRjLuCQNCyZPkTwxbB4WJ2qZZezJeyYJRseKjQo0Ajt1n59lsyZQo2IUKg5uRrg-ZoiDY7fGAXsDkXsZvUcgIPdsntvSSjlf4xRBmgggb45iYH4TzBE5Mo7ozhxOHcnpm0dvQtGfFQ-OG9GBEnYOypv4b-NySpzP2VAl12BWXPIlKxHOZ4dO92iy4jkTK2IwdtZdMbTJO5fPjJWxA1Z-7WiwqrEb0DjnE_BJ2AxJ0T6u9ZY5jyfFeT6NYNlbH2mIx5XJ4BhTcKbah69yX8PYwUiHEtWNeFpCSO86E-OFW55eE8Yaer1Y8PQL37C_qD_1yYUosX1i_2JiKjsnuDMwCVM9vCy5SqsW-vm30Vd2x4mp23o"
              // },
              beforeSend: function(request) {

                  // request.setRequestHeader("Content-Type","application/json");
                  // request.setRequestHeader("Authorization","application/json");

                  $('.appriseInner button').click();
                  apprise("<i><b><font color='grey'><center> Processing information please wait ...</center> </font></b></i><center><i class='fa fa-fw fa-save'></i> </center>");
                  $('.aButtons').hide();
                  setTimeout(function() {
                      $('.appriseInner button').click();
                  }, 3000);
            },
          }).done(function(data, status, jqxhr) {

             
                  $('.appriseOverlay').remove();
                  $('.appriseOuter').remove();

                  apprise("<i><b><font color='grey'><center>"+data.message+"</center></font></b></i>", null, function(){
                       
                       window.open(data.intended,data.page);

                  });


          }).fail(function(error, status, jqxhr){   
              
                  $('.appriseOverlay').remove();
                  $('.appriseOuter').remove();

                  apprise("<i><b><font color='grey'><center>"+error.responseJSON.message+"</center></font></b></i>", null, function(){

                      $.each(error.responseJSON.errors, function( key, value ) {
                          $('#'+key+'-error').show();
                          $('#'+key+'-error').text(value[0]);
                          $('#'+key+'-error').css({"color":"red","font-size":"13px"});
                          $('#'+key).addClass('is-invalid');
                          $('#'+key+'').parent().removeClass('has-success');
                          $('#'+key+'').parent().addClass('has-error');
                      });

                   });
          });

          e.preventDefault();
     });


   $('.confirmation').on('click', function () {
        return confirm('Are you sure?');
    });

  function previewImage(input, previewDom) {

    if (input.files && input.files[0]) {

      $(previewDom).show();

      var reader = new FileReader();

      reader.onload = function(e) {
        $(previewDom).find('img').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }else{
      $(previewDom).hide();
    }

  }

  function createUsername(name) {
      return name.toLowerCase()
        .replace(/ /g,'_')
        .replace(/[^\w-]+/g,'')
        ;;
  }

  $('input,textarea').on('keyup',function(){
    if($(this).val()){
      $(this).parent().removeClass('has-error');
      $(this).parent().addClass('has-success'); 
      $(this).parent().find('em').text('');
      $(this).removeClass('is-invalid');
    }
  });

   $('select').on('change',function(){
    if($(this).val()){
      $(this).parent().removeClass('has-error');
      $(this).parent().addClass('has-success'); 
      $(this).parent().find('em').text('');
      $(this).removeClass('is-invalid');
    }
  });

  $(document).on('click','.edit',function(){
     alert(1);
  });

});