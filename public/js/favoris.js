

  function addFavoris(id) {
    httpRequest = new XMLHttpRequest();

    if (!httpRequest) {
      alert('Abandon :( Impossible de créer une instance de XMLHTTP');
      return false;
    }
    httpRequest.onreadystatechange = function(){alertContentsAddFavoris(id);};
    httpRequest.open('GET', 'https://myapp.localhost/addfavoris/'+id);
    httpRequest.send();
  }

  function alertContentsAddFavoris(id) {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
      if (httpRequest.status === 200) {
          var response = JSON.parse(httpRequest.responseText);
          var wrapper = document.createElement("div");
          wrapper.innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">'+response.message
          +'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
          document.getElementById("alert").appendChild(wrapper);
          var changeButtonwrapper = document.getElementById("favoris"+id);
          console.log(id);
            changeButtonwrapper.innerHTML = '<button onclick="removeFavoris('+id+')" class="btn btn-danger">Retirer aux favoris</button>';
       
      } else {
        alert('Il y a eu un problème avec la requête.');
      }
    }
  }

  function removeFavoris(id) {
    httpRequest = new XMLHttpRequest();

    if (!httpRequest) {
        alert('Abandon :( Impossible de créer une instance de XMLHTTP');
        return false;
    }
    httpRequest.onreadystatechange = function(){alertContentsRemoveFavoris(id);};
    httpRequest.open('GET', 'https://myapp.localhost/removefavoris/' + id);
    httpRequest.send();
}

function alertContentsRemoveFavoris(id) {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
      if (httpRequest.status === 200) {
        var response = JSON.parse(httpRequest.responseText);
            var wrapper = document.createElement("div");
            wrapper.innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">' + response.message
                + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            document.getElementById("alert").appendChild(wrapper);
            
            var changeButtonwrapper = document.getElementById("favoris"+id);
            changeButtonwrapper.innerHTML = '<button onclick="addFavoris('+id+')" class="btn btn-warning">Ajouter aux favoris</button>';
      } else {
        alert('Il y a eu un problème avec la requête.');
      }
    }                
}
  