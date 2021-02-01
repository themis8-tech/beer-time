const button = document.getElementById('event_join');

button.addEventListener('click', () => {
   //initi de la requete
   let xhr = new XMLHttpRequest();
   //recup de l'attribut
   let url = button.getAttribute('data-url');
   xhr.open('POST', url);
   //depart de la requete
   xhr.send();

   xhr.onload = () => {

      let data = JSON.parse(xhr.response);

      if (data.status === true) {
         button.setAttribute('disabled', 'disabled');
         button.innerText = 'Inscription confirmée';
      }

   };

});