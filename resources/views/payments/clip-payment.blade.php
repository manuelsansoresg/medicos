<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="global.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz@6..12&display=swap"
      rel="stylesheet"
    />
    <title>Transparent Checkout SDK</title>
    
    <!-- Importa el SDK de Clip -->
    <script src="https://sdk.clip.mx/js/clip-sdk.js"></script>
  </head>
  <body>
    <h1>Transparent Checkout SDK</h1>
    
    
    <!-- Formulario para obtener el token de la tarjeta -->
    <form id="payment-form">
      <div id="checkout"></div>
      <button id="submit">Get Card Token ID</button>
      <p id="cardTokenId"></p>
    </form>
    <br><br>
    
    
    <!-- Autenticación -->
    <script>
      const API_KEY = "dGVzdF8wZWMxOTEyMS05ZmRjLTRjMDctOTA3Yi1hMWIyMzcwN2U3NDc6NmFkMjU0ODktYWYwYS00NGQ5LTk5ZTctMmVmYzAxZWRkZjU5"; //Aquí va tu API Key, no es necesario agregar nada más

      // Inicializa el SDK de Clip con la API Key proporcionada
      const clip = new ClipSDK(API_KEY);
      console.log(clip);
      // Verifica si la API Key ha sido ingresada correctamente
      if (API_KEY == "XXXXXXXXXX") {
        alert("Favor de ingresar tu API Key (https://dashboard.developer.clip.mx/applications)");
      }

      // Crea un elemento tarjeta con el SDK de Clip
      const card = clip.element.create("Card", {
        theme: "light",
        locale: "es",
      });
      card.mount("checkout");

      // Maneja el evento de envío del formulario
      document.querySelector("#payment-form").addEventListener("submit", async (event) => {
        event.preventDefault();       
        try {
          
          // Obtén el token de la tarjeta
          const cardToken = await card.cardToken();
          
          // Guarda el Card Token ID de la tarjeta en una constante
          const cardTokenID = cardToken.id;
          console.log("Card Token ID:", cardTokenID);
          
        } catch (error) {
          
          // Maneja errores durante la tokenización de la tarjeta
          switch (error.code) {
            case "CL2200":
            case "CL2290":
              alert("Error: " + error.message);
              throw error;
              break;
            case "AI1300":
              console.log("Error: ", error.message);
              break;
            default:
              break;
          }
        }        
      });
    </script>
  </body>
</html>