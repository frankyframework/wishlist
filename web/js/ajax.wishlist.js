
function wishlist_addWishlist(id,seccion,status)
{

     var var_query = {
          "function": "wishlist_addWishlist",
          "vars_ajax":[id,seccion,status]
    };

    var var_function = [id,seccion,status];

    pasarelaAjax('GET',var_query,"wishlist_addWishlistHTML",var_function);
}

function wishlist_addWishlistHTML(response,id, seccion,status)
{

    var respuesta = null;
    if(response != "null" && response != null)
    {
        respuesta = JSON.parse(response);

        if(respuesta[0] && respuesta[0]["message"] == 'login')
        {
            window.location = respuesta[0]["path"];
        }
        else if(respuesta[0] && respuesta[0]["message"])
        {
            _alert(respuesta[0]["message"],"");
        }
    }
    else
    {
      if(status == 1)
      {
          $('[data-idlove='+id+']').addClass('active').children('span').text('Quitar de favorito');
      }
      else{
          $('[data-idlove='+id+']').removeClass('active').children('span').text('Guardar como favorito');
      }
    }
}


function wishlist_getWishlist(seccion)
{

     var var_query = {
          "function": "wishlist_getWishlist",
          "vars_ajax":[seccion]
    };

    var var_function = [seccion];

    pasarelaAjax('GET',var_query,"wishlist_getWishlistHTML",var_function,null);
}

function wishlist_getWishlistHTML(response,seccion)
{

    var respuesta = null;
    if(response != "null" && response != null)
    {
        respuesta = JSON.parse(response);

        for(var i=0;i<respuesta.length;i++)
        {
          $('[data-idlove='+respuesta[i]+']').addClass('active').children('span').text('Quitar de favorito');
        }
    }
}

$(document).ready(function(){
      $('[data-idlove]').click(function(){
         wishlist_addWishlist($(this).attr('data-idlove'),$(this).attr('data-seccion'),($(this).attr('class').search('active') > -1 ? 0 : 1));
      });
});