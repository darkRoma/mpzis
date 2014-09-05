function setCookieRu()
{
    jQuery.cookie('language', 'ru', { expires: 3650, path: '/' });
    location.reload(true);
}
function setCookieUa()
{
    jQuery.cookie('language', 'ua', { expires: 3650, path: '/' });
    location.reload(true);
}