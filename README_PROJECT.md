
<div align="center">
    <h1>Darkness Website</h1>
    <img src="/assets/plugins/images/banner.png"></img>
</div>

<h3>Librerias usadas en PHP</h3>

- Stripe <b>composer</b> [Github link](https://github.com/stripe/stripe-php)
- Parsedown <b>composer</b> [Github link](http://parsedown.org)
- AntiXSS <b>composer</b> [Github link](https://github.com/voku/anti-xss)
- Restcord <b>composer</b> [Github link](https://github.com/restcord/restcord)
- SteamAuthAPI <b>PHP</b>
- DiscordAPI <b>PHP</b>
- Websocket <b>composer</b>

<h3>Librerias usadas para JS</h3>

- Sweetalert2 <b>Para el uso de alertas</b> [Link](https://sweetalert2.github.io/recipe-gallery/)
- Marked <b>Para el uso de Anti XSS</b> [Link](https://github.com/markedjs/marked)
- EasyMD <b>Para el uso de un textarea para el Markdown</b> [Link](https://github.com/Ionaru/easy-markdown-editor)
- Lightweight <b>Para el uso de las gráficas</b> [Link](https://github.com/tradingview/lightweight-charts)
- Sortablejs <b>Para el uso de mover cajas</b> [Link](https://sortablejs.github.io/Sortable/)
- Recaptcha google <b>Para el uso de captchas, aunque no se este usando</b>

<h3>Páginas de administrador</h3>

- Manager users - <b>Aquí podrás editar,actualizar los usuarios</b> <b>|</b> [section link](/admin/manage_users.php)
- Manage payments - <b>Aquí podrás ver todos los pagos, volver a enviar pagos, ver el usuario que compro el producto etc...</b> <b>|</b> [section link](/admin/manage_payments.php)
- Manage products - <b>Aquí puedes añadir, borrar, actualizar productos y asociarlos con los servidores</b> <b>|</b> [section link](/admin/manage_products.php)
- Manage servers - <b>Esta es la lista de los servidores, donde configuramos los websocket para el funcionamiento de que se conecten con el juego y los usuarios que compren reciban los pagos</b> <b>|</b> [section link](/admin/manage_servers.php)
- Manage settings - <b>Actualizar, Editar la configuración de la página web, esto actualiza el archivo .env</b> <b>|</b> [section link](/admin/manage_settings.php)

<h3>Páginas de usuario</h3>

- Dashboard - <b>Aquí podrás editar,actualizar los usuarios</b> <b>|</b> [section link](index.php)
- Shop - <b>Aquí puedes ver todos los productos para comprar</b> <b>|</b> [section link](shop.php)
- Servers <b>Aquí puedes ver todos los servidores disponibles</b> <b>|</b> [section link](servers.php)
- Terms and conditions <b>Aquí puedes leer los términos y condiciones</b> <b>|</b> [section link](terms.php)
- Account <b>Aquí puedes ver los productos que has comprado</b> <b>|</b> [section link](account.php)

<h3>¿Qué tiene de utilidad?</h3>

Muchas veces dependemos de sitios externos como <b>TEBEX</b> o <b>ENJIN</b> donde se llevan un gran porcentaje de los pagos que realizan los usuarios para comprar cosas virtuales del juego "Unturned", gracias a esta aplicación, podremos desplegar nuestro sitio web propio sin depender de este tipo de empresas, donde el usuario inicia sesión con la plataforma <b>STEAM</b> y ya puede comprar un producto, cada producto tiene asociado un servidor o muchos servidores, los pagos son unicamente con tarjeta de crédito, mediante <b>STRIPE</b>, es una pasarela de pago bastante sencilla de configurar, solo poniendo dos KEYS en el <b>.env</b> ya tendriamos la pasarela de pago configurada.

<h3>Como funcionan los pagos</h3>

Cuando el <b>usuario</b> compra un producto, la web realiza los siguientes pasos, verifica si se ha realizado la compra <b>STRIPE</b> maneja el paso de si se ha realizado correctamente, si en este caso se ha realizado no saltaria ningún error, te llevaria a la sección <b>checkout</b> donde realiza los siguientes pasos, verifica si el producto recibido mediante el <b>POST</b> es correcto, también si el <b>TOKEN</b> del pago de <b>STRIPE</b> es correcto, si es este caso no saltara ningún mensaje de fraude, y ahora obtenemos el producto, hacemos un bucle de los servidores asociados, y obtenemos los datos de su configuración <b>WEBSOCKET</b>, si el servidor esta <b>ONLINE</b> entonces el jugador/usuario recibe el producto, ya que la aplicación web, envia una petición con el comando y el permiso para que reciba el producto el usuario/jugador.

<b>Y te preguntaras?, como se que he conectado por ejemplo mi servidor correctamente y esta todo configurado o como puedo configurarlo</b>

Pues muy sencillo, necesitas un plugin que he creado yo llamado <b>SHOP</b> creado en <b>C#</b>, ya que esto va dedicado solo al juego <b>Unturned</b>, aunque tu mismo podrias adaptar la web para otro juego, pero necesitas un minimo de conocimientos para poder hacerlo, este <b>plugin</b> hace como servidor <b>websocket</b> y la aplicación web es el <b>cliente</b> que envia las peticiones, para poder verificar de que nuestro <b>servidor</b> se ha <b>conectado</b> en la sección de [servers](/admin/manage_servers.php) podremos encontrar un botón llamado <b>"Test websocket"</b>, cuando le presionamos este hara una petición de testeo, si recibimos respuesta, entonces nos saldra una alerta y el botón cambiara de color verde, y si no, de color rojo

Además de que puedes implementar los anuncios de <b>Google Adsense</b> para obtener más ingresos, la página web esta diseñada para que <b>Google Adsense</b> acepte nuestra web como adecuada y apta para tener anuncios.

Ahora, para hacer una prueba y ver su funcionamiento dejo un [link](http://test.darknesscommunity.club/) de la página web desplegada, con datos insertados etc.., también dejo una cuenta ya creada de <b>STEAM</b> y así no debes de crearte una:

``
Usuario: afuniba317
``
``
Contraseña: http://test.darknesscommunity.club/
``

Debido a que el <b>STRIPE</b> esta en modo de pruebas, también dejare asociada una tarjeta de test que proporciona <b>STRIPE</b>, también dejo de donde lo he sacado [Tarjetas test](https://docs.stripe.com/testing?testing-method=card-numbers), así puedes realizar tu mismo una simulación de un pago.

``
NUM TARJETA: 4242424242424242
``
``
FECHA: 06/29 (Te la puedes inventar)
``
``
CCV: 141 (Te la puedes inventar)
``
``
COD POSTAL: 22222 (Te la puedes inventar)
``

<h3>¿Que estamos utilizando en los DOCKERS?</h3>

- mysql
- php-fm 7.4^
- composer
- apache/nginx
- make

<h3>COSAS IMPORTANTES</h3>

Antes de desplegar el docker, debes de configurar la base de datos y todo en el archivo [env](.env), todas las variables de entorno tienen su nombre correspondiente, todo esta en ingles, pero las <b>opcionales</b> y las <b>importantes estan marcadas</b>, para poder desplegar la aplicación, necesitaremos tener el <b>"Make"</b>, en <b>linux</b> viene por defecto.

``make build`` para construir el proyecto
``make start`` para iniciar el proyecto una vez construido

Dockerfile yml configuraciones [section link](docker/docker-compose.yml)

Si quieres que tu <b>NGINX</b> este en HTTP o HTTPS [section link](docker/nginx/), podras configurarlo en esta sección, donde tienes el <b>nginx.conf</b> e otros
