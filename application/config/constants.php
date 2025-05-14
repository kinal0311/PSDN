<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

defined('SMTP_PORT')      OR define('SMTP_PORT', 465);
defined('SMTP_HOST')      OR define('SMTP_HOST', 'universalteleservices.in.cp-in-1.hostgatorwebservers.com');
defined('SMTP_USERNAME')      OR define('SMTP_USERNAME', 'noreply@universalteleservices.in');
defined('SMTP_PASSWORD')      OR define('SMTP_PASSWORD', 'syR,me.h@BaF');
defined('SMTP_MAIL_FROM')      OR define('SMTP_MAIL_FROM', 'noreply@universalteleservices.in');
defined('SMTP_MAIL_FROM_NAME')      OR define('SMTP_MAIL_FROM_NAME', 'noreply');
defined('THEME_BODY_HEADER_COLOR')      OR define('THEME_BODY_HEADER_COLOR', 'theme-blue-grey');
defined('LIST_PAGE_LIMIT')      OR define('LIST_PAGE_LIMIT', 25);
//defined('USERS_TYPE_LIST')      OR define('USERS_TYPE_LIST', serialize (array (4=>'SUBADMIN',2=>'DISTRIBUTOR',1=>'DEALER')));
defined('USERS_TYPE_LIST')      OR define('USERS_TYPE_LIST', serialize (array (/*4=>'SUBADMIN',*/6=>'TECHNICIAN',2=>'DISTRIBUTOR',1=>'DEALER')));
defined('VEHICLE_SPEED')      OR define('VEHICLE_SPEED', serialize (array(40,50,55,60,65,80)));
defined('EXPIRE_DATE_VALUE')      OR define('EXPIRE_DATE_VALUE', 365);
defined('RTO_PASSWORD')      OR define('RTO_PASSWORD', '12345');

defined('GST_APPLICABLE') OR define('GST_APPLICABLE', 1);
defined('CGST_PERCENT') OR define('CGST_PERCENT', 9);
defined('SGST_PERCENT') OR define('SGST_PERCENT', 9);
defined('INVOICE_DUE_PERIOD') OR define('INVOICE_DUE_PERIOD', 10);
defined('AWS_S3_BUCKET_URL') OR define('AWS_S3_BUCKET_URL', 'https://techpsdn.s3.ap-south-1.amazonaws.com/');