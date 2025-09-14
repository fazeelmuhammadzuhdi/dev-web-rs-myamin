<?php

namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    /*
    |--------------------------------------------------------------------------
    | Base Site URL
    |--------------------------------------------------------------------------
    |
    | URL to your CodeIgniter root. Typically this will be your base URL,
    | WITH a trailing slash:
    |
    |	http://example.com/
    |
    | WARNING: You MUST set this value!
    |
    | If it is not set, then CodeIgniter will try to guess the protocol and path
    | your installation, but due to security concerns the hostname will be set
    | to $_SERVER['SERVER_ADDR'] if available, or localhost otherwise.
    | The auto-detection mechanism exists only for convenience during
    | development and MUST NOT be used in production!
    |
    | If you need to allow multiple domains, remember that this file is still
    | a PHP script and you can easily do that on your own.
    |
    */
    public $baseURL = 'http://localhost:8080/';

    /*
    |--------------------------------------------------------------------------
    | Allowed Hostnames in Production
    |--------------------------------------------------------------------------
    |
    | When running your application in production, you will need to restrict
    | the hostnames that are allowed to access your application. This helps
    | prevent DNS rebinding attacks.
    |
    | You may provide an array of hostnames, or set to an empty array to
    | allow all hostnames.
    |
    | Example:
    |     $allowedHostnames = [
    |         'example.com',
    |         'www.example.com',
    |     ];
    |
    */
    public $allowedHostnames = [];

    /*
    |--------------------------------------------------------------------------
    | Index File
    |--------------------------------------------------------------------------
    |
    | Typically this will be your index.php file, unless you've renamed it to
    | something else. If you are using mod_rewrite to remove the page set this
    | variable so that it is blank.
    |
    */
    public $indexPage = '';

    /*
    |--------------------------------------------------------------------------
    | URI PROTOCOL
    |--------------------------------------------------------------------------
    |
    | This item determines which getServer global should be used to retrieve the
    | URI string.  The default setting of 'REQUEST_URI' works for most servers.
    | If your links do not seem to work, try one of the other delicious flavors:
    |
    | 'REQUEST_URI'    Uses $_SERVER['REQUEST_URI']
    | 'QUERY_STRING'   Uses $_SERVER['QUERY_STRING']
    | 'PATH_INFO'      Uses $_SERVER['PATH_INFO']
    | 'PATH_TRANSLATED'    Uses $_SERVER['PATH_TRANSLATED']
    | 'PHP_SELF'       Uses $_SERVER['PHP_SELF']
    | 'ORIG_PATH_INFO' Uses $_SERVER['ORIG_PATH_INFO']
    |
    */
    public $uriProtocol = 'REQUEST_URI';

    /*
    |--------------------------------------------------------------------------
    | Default Locale
    |--------------------------------------------------------------------------
    |
    | The Locale roughly represents the language and location that your visitor
    | is viewing the site from. It affects the language strings and other
    | strings (like currency markers, numbers, etc), that your program
    | should run under for this request.
    |
    */
    public $defaultLocale = 'en';

    /*
    |--------------------------------------------------------------------------
    | Negotiate Locale
    |--------------------------------------------------------------------------
    |
    | If true, the current Request object will automatically determine the
    | language to use based on the value of the Accept-Language header.
    |
    | If false, no automatic detection will be performed.
    |
    */
    public $negotiateLocale = false;

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    |
    | If $negotiateLocale is true, this array lists the locales supported
    | by the application in descending order of priority. If no match is
    | found, the first locale will be used.
    |
    */
    public $supportedLocales = ['en'];

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | The default timezone that will be used in your application to display
    | dates with the date helper, and can be retrieved through app_timezone()
    |
    */
    public $appTimezone = 'UTC';

    /*
    |--------------------------------------------------------------------------
    | Default Character Set
    |--------------------------------------------------------------------------
    |
    | This determines which character set is used by default in various methods
    | that require a character set to be provided.
    |
    | See http://php.net/htmlspecialchars for a list of supported charsets.
    |
    */
    public $charset = 'UTF-8';

    /*
    |--------------------------------------------------------------------------
    | URI PROTOCOL
    |--------------------------------------------------------------------------
    |
    | If true, this will force every request made to this application to be
    | made via a secure connection (HTTPS). In development, you might want to
    | set this to false, especially if you are using a development environment
    | that doesn't support HTTPS.
    |
    */
    public $forceGlobalSecureRequests = false;

    /*
    |--------------------------------------------------------------------------
    | Session Variables
    |--------------------------------------------------------------------------
    |
    | 'sessionDriver'
    |
    |	The session storage driver to use:
    |		- CodeIgniter\Session\Handlers\FileHandler
    |		- CodeIgniter\Session\Handlers\DatabaseHandler
    |		- CodeIgniter\Session\Handlers\MemcachedHandler
    |		- CodeIgniter\Session\Handlers\RedisHandler
    |
    | 'sessionCookieName'
    |
    |	The session cookie name, must contain only [0-9a-z_-] characters
    |
    | 'sessionExpiration'
    |
    |	The number of SECONDS you want the session to last.
    |	Setting to 0 (zero) means expire when the browser is closed.
    |
    | 'sessionSavePath'
    |
    |	The location to save sessions to and is driver dependent.
    |
    |	For the 'files' driver, it's a path to a writable directory.
    |	WARNING: Only absolute paths are supported!
    |
    |	For the 'database' driver, it's a table name.
    |	Please read up the manual for the format with other session drivers.
    |
    |	IMPORTANT: You are REQUIRED to set a valid save path!
    |
    | 'sessionMatchIP'
    |
    |	Whether to match the user's IP address when reading the session data.
    |
    |	WARNING: If you're using the database driver, don't forget to update
    |	         your session table's PRIMARY KEY when changing this setting.
    |
    | 'sessionTimeToUpdate'
    |
    |	How many seconds between CI regenerating the session ID.
    |
    | 'sessionRegenerateDestroy'
    |
    |	Whether to destroy session data associated with the old session ID
    |	when auto-regenerating the session ID. When set to FALSE, the data
    |	will be later deleted by the garbage collector.
    |
    | Other session cookie settings are shared with the cookie settings.
    | The exception is 'cookiePrefix' and 'cookieHttpOnly' which are ignored here.
    |
    */
    public $sessionDriver            = 'CodeIgniter\Session\Handlers\FileHandler';
    public $sessionCookieName        = 'ci_session';
    public $sessionExpiration        = 7200;
    public $sessionSavePath          = WRITEPATH . 'session';
    public $sessionMatchIP           = false;
    public $sessionTimeToUpdate      = 300;
    public $sessionRegenerateDestroy = false;

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    |
    | 'csrfTokenName' = The token name
    | 'csrfHeaderName' = The header name
    | 'csrfCookieName' = The cookie name
    | 'csrfExpire' = The number in seconds the token should expire.
    | 'csrfRegenerate' = Regenerate token on every submission
    | 'csrfExcludeURIs' = Array of URIs which ignore CSRF checks
    | 'csrfRedirect' = Redirect to previous page with error on failure
    | 'csrfSameSite' = Setting for the SameSite cookie attribute
    |
    */
    public $csrfTokenName   = 'csrf_test_name';
    public $csrfHeaderName  = 'X-CSRF-TOKEN';
    public $csrfCookieName  = 'csrf_cookie_name';
    public $csrfExpire      = 7200;
    public $csrfRegenerate  = true;
    public $csrfExcludeURIs = [];
    public $csrfRedirect    = true;
    public $csrfSameSite    = 'Lax';

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy
    |--------------------------------------------------------------------------
    |
    | Enables the Response's Content Secure Policy to restrict the sources that
    | can be used for images, scripts, CSS files, audio, video, etc. If enabled,
    | the Response object will populate default values for the policy from the
    | ContentSecurityPolicy.php file. Controllers can always add to those
    | restrictions at run time.
    |
    | For a better understanding of CSP see https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP
    |
    */
    public $CSPEnabled = false;

    /*
    |--------------------------------------------------------------------------
    | Performance Optimizations
    |--------------------------------------------------------------------------
    |
    | Enable various performance optimizations
    |
    */
    public $enablePerformanceOptimizations = true;
    
    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Cache settings for better performance
    |
    */
    public $cacheEnabled = true;
    public $cacheTTL = 3600; // 1 hour
    public $cacheDriver = 'file'; // file, redis, memcached
    
    /*
    |--------------------------------------------------------------------------
    | Compression
    |--------------------------------------------------------------------------
    |
    | Enable gzip compression for better performance
    |
    */
    public $enableCompression = true;
    
    /*
    |--------------------------------------------------------------------------
    | Minification
    |--------------------------------------------------------------------------
    |
    | Enable CSS/JS minification
    |
    */
    public $enableMinification = true;
}