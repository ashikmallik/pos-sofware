

function usershow_photo(input) 
{
    
    if (input.files && input.files[0]) 
    {
        var reader = new FileReader();
        reader.onload = function (e) 
        {
            $('#pre_photo')
            .attr('src', e.target.result)
            .width(140)
            .height(140);
        };
        reader.readAsDataURL(input.files[0]);
    }
    
  
}

function usershow_left_photo(input) 
{
    
    if (input.files && input.files[0]) 
    {
        var reader = new FileReader();
        reader.onload = function (e) 
        {
            $('#pre_photoleft')
            .attr('src', e.target.result)
            .width(140)
            .height(140);
        };
        reader.readAsDataURL(input.files[0]);
    }
    
  
}



function usershow_photodown(input) 
{
    
    if (input.files && input.files[0]) 
    {
        var reader = new FileReader();
        reader.onload = function (e) 
        {
            $('#pre_photodown')
            .attr('src', e.target.result)
            .width(140)
            .height(140);
        };
        reader.readAsDataURL(input.files[0]);
    }
    
  
}
function usershow_photodown_stand(input) 
{
    
    if (input.files && input.files[0]) 
    {
        var reader = new FileReader();
        reader.onload = function (e) 
        {
            $('#pre_photodown_stand')
            .attr('src', e.target.result)
            .width(140)
            .height(140);
        };
        reader.readAsDataURL(input.files[0]);
    }
    
  
}
function usershow_downphoto_stand(input) 
{
    
    if (input.files && input.files[0]) 
    {
        var reader = new FileReader();
        reader.onload = function (e) 
        {
            $('#pre_downphoto_stand')
            .attr('src', e.target.result)
            .width(140)
            .height(140);
        };
        reader.readAsDataURL(input.files[0]);
    } 
}


function usershow_downphoto_ac(input) 
{
    
    if (input.files && input.files[0]) 
    {
        var reader = new FileReader();
        reader.onload = function (e) 
        {
            $('#pre_photo_ac')
            .attr('src', e.target.result)
            .width(140)
            .height(140);
        };
        reader.readAsDataURL(input.files[0]);
    } 
}

function test()
{ 
    
    var permited =[];

    $('input[name="MenuPermission[]"]').each(function () {

       if($(this).is(':checked')==true)
        {
          permited.push(this.value);   
        }
    });
                
              
     var s= $('#menuaccess').val(permited);
    // alert(s);
   
}
function usershow_photo_wechat(input) 
{
    
    if (input.files && input.files[0]) 
    {
        var reader = new FileReader();
        reader.onload = function (e) 
        {
            $('#wechat_photo')
            .attr('src', e.target.result)
            .width(140)
            .height(140);
        };
        reader.readAsDataURL(input.files[0]);
    }
    
  
}
function usershow_photo_viber(input) 
{
    
    if (input.files && input.files[0]) 
    {
        var reader = new FileReader();
        reader.onload = function (e) 
        {
            $('#viber_photo')
            .attr('src', e.target.result)
            .width(140)
            .height(140);
        };
        reader.readAsDataURL(input.files[0]);
    }
    
  
}
function usershow_photo_whatsapp(input) 
{
    
    if (input.files && input.files[0]) 
    {
        var reader = new FileReader();
        reader.onload = function (e) 
        {
            $('#whatsapp_photo')
            .attr('src', e.target.result)
            .width(140)
            .height(140);
        };
        reader.readAsDataURL(input.files[0]);
    }
    
  
}
function usershow_photo_line(input) 
{
    
    if (input.files && input.files[0]) 
    {
        var reader = new FileReader();
        reader.onload = function (e) 
        {
            $('#line_photo')
            .attr('src', e.target.result)
            .width(140)
            .height(140);
        };
        reader.readAsDataURL(input.files[0]);
    }
    
  
}
function usershow_photo_qq(input) 
{
    
    if (input.files && input.files[0]) 
    {
        var reader = new FileReader();
        reader.onload = function (e) 
        {
            $('#qq_photo')
            .attr('src', e.target.result)
            .width(140)
            .height(140);
        };
        reader.readAsDataURL(input.files[0]);
    }
    
  
}





function work()
{ 
    
    var permited =[];

    $('input[name="WorkPermission[]"]').each(function () {

       if($(this).is(':checked')==true)
        {
          permited.push(this.value);
        }
    });
                
              
     var s= $('#workaccess').val(permited);
    // alert(s);
   
}



