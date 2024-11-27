
function btn_mode() 
{
    const btn_mode = document.getElementById('btn-mode');
    const overlay = document.createElement('div'); 
    overlay.style.position = 'fixed';
    overlay.style.top = '0';
    overlay.style.left = '0';
    overlay.style.width = '100%'; 
    overlay.style.height = '100%'; 
    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)'; 
    overlay.style.zIndex = '9999';
    overlay.style.pointerEvents = 'none';
    overlay.id = "overlay";

    document.body.appendChild(overlay); 

    const get_overlay = document.getElementById('overlay'); 

    btn_mode.addEventListener('click', () => {
        if (window.localStorage.getItem('btn-mode') !== 'true') {
            document.getElementById("btn-mode").innerHTML = "Dark";
            get_overlay.hidden = true;
            window.localStorage.setItem('btn-mode', 'true');
        } 
        else 
        {
            document.getElementById("btn-mode").innerHTML = "Light";
            get_overlay.hidden = false;
            window.localStorage.setItem('btn-mode', 'false');
        }
    });

    if (window.localStorage.getItem('btn-mode') === 'false') 
    {
        document.getElementById("btn-mode").innerHTML = "Light";
        get_overlay.hidden = false;
    } 
    else 
    {
        document.getElementById("btn-mode").innerHTML = "Dark";
        get_overlay.hidden = true;
    }
}

window.onload = function() 
{
    btn_mode();
}
