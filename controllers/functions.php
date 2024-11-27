<?php

function change_env($key, $value)
{
    $env = __DIR__ . '/../.env';

    $content = file_get_contents($env);

    $pattern = '/^' . preg_quote($key, '/') . '=.*/m';

    if (preg_match($pattern, $content)) 
    {
        $content = preg_replace($pattern, $key . '="' . $value . '"', $content);
    } 
    else 
    {
        $content .= PHP_EOL . $key . '="' . $value . '"';
    }

    file_put_contents($env, $content);
}

function upload_file($dest, $post_name) {
    $temp_name = $_FILES[$post_name]['tmp_name'];
    $original_name = $_FILES[$post_name]['name'];
    
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];

    $url = $protocol . "://" . $host;

    $upload_dest = $dest . $original_name;

    $upload_url = $url . "/storage/" . $original_name;

    move_uploaded_file($temp_name, $upload_dest);

    return $upload_url;
}

function is_secure() {
    return
      (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
      || $_SERVER['SERVER_PORT'] == 443;
}

function is_phone() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $mobile_agents = array(
        'iPhone',            // Apple iPhone
        'iPod',              // Apple iPod touch
        'Android',           // Android
        'webOS',             // Palm Pre/Pixi
        'Windows Phone',     // Windows Phone
        'BlackBerry',        // BlackBerry
        'Symbian',           // Symbian
        'Windows Mobile',    // Windows Mobile
        'Opera Mini',        // Opera Mini
        'Opera Mobi'         // Opera Mobile
    );

    foreach ($mobile_agents as $agent) {
        if (strpos($user_agent, $agent) !== false) {
            return true;
        }
    }
    return false;
}


function webhook($color, $description, $title, $username = "Admin", $image_user = "", $url_webhook) 
{
        $json_data = json_encode([

                    "content" => "**$title** @everyone",

                    "tts" => false,
                    "embeds" => [
											[
		            // Embed Title
                    "title" => $title,

		            // Embed Type
                    "type" => "rich",

		            // Embed Description
                    "description" => $description,

		            // Timestamp of embed must be formatted as ISO8601
                    "timestamp" => date("c", strtotime("now")),

		            // Embed left border color in HEX
                    "color" => hexdec( $color ),

		            // Footer
                    "footer" => [
                        "text" => $username,
                        "icon_url" => $image_user
                    ]
                ]
            ]

        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );


        $ch = curl_init( $url_webhook );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec( $ch );
		// If you need to debug, or find out why you can't send message uncomment line below, and execute script.
		// echo $response;
        curl_close( $ch );
}

function redirect($url)
{
    if (!headers_sent())
    {    
        header('Location: '.$url);
        exit;
        }
    else
        {  
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>';
        exit;
    }
}

function is_admin($name) {
	return $name == "admin" ? true : false; 
}

# A function which returns users IP
function client_ip()
{
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
		return $_SERVER['REMOTE_ADDR'];
	}
}

# Check user's avatar type
function is_animated($avatar)
{
	$ext = substr($avatar, 0, 2);
	if ($ext == "a_")
	{
		return ".gif";
	}
	else
	{
		return ".png";
	}
}

function get_client_ip() {
    if (getenv('HTTP_CLIENT_IP'))
        return getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        return getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        return getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        return getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        return getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        return getenv('REMOTE_ADDR');
    else
        return 'UNKNOWN';
}
?>
