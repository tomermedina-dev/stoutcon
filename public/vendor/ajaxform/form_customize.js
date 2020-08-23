// prepare the form when the DOM is ready 

  $(document).ready(function() { 


   $('form').on('submit',(function(e) {
        var form = $(this);
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
             beforeSend: function(request) {
       
                 swal({
                    title: 'Processing information',
                    text: 'Please wait...',
                    icon: 'info',
                    timer: 1000,
                    showConfirmButton:false,
                })


            },
          }).done(function(data, status, jqxhr) {

            setTimeout(function () { 

            swal({
              title: "Success!",
              text: ""+data.message+"",
              type: "success",
              confirmButtonText: "OK"
            },
            function(isConfirm){
              if (isConfirm) {

                if(data.intended){
                  window.open(data.intended,data.page);
                }
                
                form[0].reset();

                if($('.table').length){
                   
                  $('table').DataTable().ajax.reload();

                }

                swal.close();
                form.closest(".modal").modal('toggle');

              }
            }); }, 2000);
 


          }).fail(function(error, status, jqxhr){   
          
          
           

                $.each(error.responseJSON.errors, function( key, value ) {
                    $('#'+key+'-error').show();
                    $('#'+key+'-error').text(value[0]);
                    $('#'+key+'-error').css({"color":"red","font-size":"13px"});
                    $('#'+key).addClass('is-invalid');
                    $('#'+key+'').parent().removeClass('has-success');
                    $('#'+key+'').parent().addClass('has-error');
                });

                  swal({
                    title: "Error!",
                    text: ""+error.responseJSON.message+"",
                    type: "error",
                    confirmButtonText: "OK",
                    closeOnClickOutside: false,
                    allowOutsideClick: false,
                    backdrop:true,

                  });


                
          });

    }));




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

   $('input[type="file"]').on('change',function(){
      $(this).parent().parent().find('em').text('');
      $(this).removeClass('is-invalid');

   });



  $(document).on('click','#add',function(){
      $('form').trigger("reset");
      $('.modal-title').text('Add new!');
      $('#modal-xl').modal({backdrop: 'static', keyboard: false}).show();
      $('form').attr('action',$(this).data('action'));
      $('form').removeClass('has-error');
      $('form').find('.form-control').removeClass('is-invalid'); 
      $('form').addClass('has-success'); 
      $('form').find('em').text('');
   });



 $(document).on('click','.edit',function(){
    action = $(this).data('action');
    $('form').attr('action',$(this).data('action'));
    $('form').removeClass('has-error');
    $('form').addClass('has-success');
    $('form').find('.form-control').removeClass('is-invalid'); 
    $('form').find('em').text('');

    $.ajax({
    method: "GET",
    url: action,
    beforeSend: function(request) {
      //console.log($(this).data('action'));
    },
    }).done(function(data, status, jqxhr) {

       $.each(data.data, function( key, value ) {
            $('#'+key+'').val(value);
            $('.modal-title').text('Update account!');
            $('#modal-xl').modal({backdrop: 'static', keyboard: false}).show();
        });

         if(data.intended){
            $('form').attr('action',data.intended);

           // $('form').attr('method','PUT');
         }

    }).fail(function(error, status, jqxhr){   

    });
  });


 $(document).on('click','.delete',function(){
  action = $(this).data('action');

  swal({
      title: "Are you sure?",
      text: "The data will be temporarily removed from database!",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      confirmButtonText: "Yes, delete it!",
      cancelButtonText: "No, cancel plx!",
      closeOnConfirm: false,
      closeOnCancel: false
    },
    function(isConfirm) {
      if (isConfirm) {

           $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method:"POST",
                url: action,
                beforeSend: function(request) {
                  //console.log($(this).data('action'));
                },
                }).done(function(data, status, jqxhr) {
                   
                   swal({
                        title: 'Processing information',
                        text: data.message,
                        icon: 'info',
                        timer: 1000,
                        buttons: false,
                    })

                   $('table').DataTable().ajax.reload();

                   if(data.intended){
                      $('form').attr('action',data.intended);
                   }

                }).fail(function(error, status, jqxhr){   

             });

        swal("Deleted!", "Your data has been deleted.", "success");
      } else {
        swal("Cancelled", "Your data file is safe :)", "error");
      }
    });


   });

 $(document).on('click','.view',function(){
    
   $('#modal-l').modal({backdrop: 'static', keyboard: false}).modal();
   $('.modal-title').text('View Profile!');

    action = $(this).data('action');

   $.ajax({
    method: "GET",
    url: action,
    beforeSend: function(request) {
      //console.log($(this).data('action'));
    },
    }).done(function(data, status, jqxhr) {

      
      $('.modal-title').text(''+data.data.name+ '');

       $('#_information').html('');

       $.each(data.data, function( key, value ) {
            html = '<dt class="col-sm-4">'+ toTitleCase(key.split('_').join(' ')) +'</dt>';
            html += '<dd class="col-sm-8">'+value+'</dd>';

            $('#_information').append(html);
          
            
        });

    }).fail(function(error, status, jqxhr){   

    });
  });


 function toTitleCase(str) {
    return str.replace(/(?:^|\s)\w/g, function(match) {
        return match.toUpperCase();
    });
}



});