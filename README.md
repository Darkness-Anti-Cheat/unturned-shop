
<div align="center">
    <h1>Darkness Website</h1>
    <img src="/assets/plugins/images/banner.png"></img>
</div>

<h1>README EN ESPAÑOL </h1>

[README EN ESPAÑOL PROYECTO](README_PROJECT.md)

<h1>Libraries PHP using</h1>

- Stripe <b>composer</b> [Github link](https://github.com/stripe/stripe-php)
- Parsedown <b>composer</b> [Github link](http://parsedown.org)
- AntiXSS <b>composer</b> [Github link](https://github.com/voku/anti-xss)
- Restcord <b>composer</b> [Github link](https://github.com/restcord/restcord)
- SteamAuthAPI <b>PHP</b>
- DiscordAPI <b>PHP</b>
- Websocket <b>composer</b>

<h1>Libraries JS using</h1>

- Sweetalert2 <b>usage of modals alert</b> [Link](https://sweetalert2.github.io/recipe-gallery/)
- Marked <b>needed for anti xss</b> [Link](https://github.com/markedjs/marked)
- EasyMD <b>usage of markdown</b> [Link](https://github.com/Ionaru/easy-markdown-editor)
- Lightweight <b>usage of charts</b> [Link](https://github.com/tradingview/lightweight-charts)
- Sortablejs <b>usage of movable cards</b> [Link](https://sortablejs.github.io/Sortable/)
- Recaptcha google <b>for future usages</b>

<h1>Admin pages</h1>

- Manager users - <b>Edit, update, transfer registered users on the web</b> <b>|</b> [section link](/admin/manage_users.php)
- Manage payments - <b>View payments or purchases for products</b> <b>|</b> [section link](/admin/manage_payments.php)
- Manage products - <b>Add,remove, logic remove, update products to the store with prices, descriptions, etc.</b> <b>|</b> [section link](/admin/manage_products.php)
- Manage servers - <b>Being able to add, remove, update servers to the list</b> <b>|</b> [section link](/admin/manage_servers.php)
- Manage settings - <b>Change settings or disable</b> <b>|</b> [section link](/admin/manage_settings.php)

<h1>User pages</h1>

- Dashboard <b>|</b> [section link](index.php)
- Shop <b>|</b> [section link](shop.php)
- Servers <b>|</b> [section link](servers.php)
- Terms and conditions <b>|</b> [section link](terms.php)
- Account <b>|</b> [section link](account.php)

<h1>Features</h1>

Have your own store without depending on other companies such as <b>tebex</b>, using <b>stripe</b> as a payment gateway, in addition to being able to manage several servers

- Server <b>websocket</b> configuration, in the manage servers section there may be an error when using the check websocket due to <b>HTTPS</b> security, you will need to put your <b>websocket</b> server in <b>WSS</b>

- You can put <b>Google Adsense</b> as Monization

- <b>Stripe</b> it's the main paymentwall

<h1>Installation and requirements</h1>

- mysql
- php-fm 7.4^
- composer
- apache/nginx
- make

<h3>Important things</h3>

First configure all credentials and other stuff in file called <b>.env.example</b>, also after you put your configuration, rename the file <b>.env.example</b> to <b>.env</b>. [env](.env) file and sql, also if you want to build, type <b>"make build"</b>, you need <b>"make installed"</b>

Dockerfile yml configuration [section link](docker/docker-compose.yml)

Also configure your <b>NGINX</b> to http or https in [section link](docker/nginx/nginx.conf), as default we already removed .php extension on url's but is forcing to HTTP instead of HTTPS

## License

Open Source Non-Commercial License (Version 1.0)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

This license is not intended for commercial use. Any commercial use, including but not limited to selling or distributing the Software for commercial purposes, is prohibited. Users of the Software are required to attribute the author(s) of the original Software.



