<?php

/**
 * Configurable page to check the requirements of your PHP Application.
 * You can run this page in the browser or in the CLI.
 *
 * @author    Ignacio de Tomás <nacho@inacho.es>
 * @copyright 2013 Ignacio de Tomás (http://inacho.es)
 */


error_reporting(E_ALL);
ini_set('display_errors', '1');


// ================
// = START CHECKS =
// ================

$values[] = array('desc' => 'PHP version >= 5.3.0', 'res' => (version_compare(PHP_VERSION, '5.3.0') >= 0));
$values[] = array('desc' => 'APC module installed', 'res' => function_exists('apc_store'));
$values[] = array('desc' => 'SOAP module installed', 'res' => extension_loaded('soap'));
$values[] = array('desc' => 'gettext installed', 'res' => function_exists('gettext'));

$values[] = array('desc' => 'shell_exec function enabled', 'res' => isAvailable('shell_exec'));
$values[] = array('desc' => 'curl function enabled', 'res' => function_exists('curl_version'));
$values[] = array('desc' => 'Magic Quotes disabled', 'res' => (! get_magic_quotes_gpc()));

$values[] = array('desc' => 'Zend framework installed', 'res' => (@include 'Zend/Application.php'));

if (PHP_OS == 'Darwin') { // Mac specific checks
    $values[] = array('desc' => 'wkhtmltopdf installed (MacOSX)', 'res' => shellCommandExists('/Applications/wkhtmltopdf.app/Contents/MacOS/wkhtmltopdf'));

} else { // Linux specific checks
    $values[] = array('desc' => 'xvfb-run installed (Linux)', 'res' => shellCommandExists('xvfb-run'));
    $values[] = array('desc' => 'wkhtmltopdf installed (Linux)', 'res' => shellCommandExists('/usr/bin/wkhtmltopdf'));
}


// =============
// = FUNCTIONS =
// =============

function output($str)
{
    fwrite(STDOUT, $str);
}

function green($str)
{
    return chr(27) . '[32m' . $str . chr(27) . '[0m';
}

function red($str)
{
    return chr(27) . '[31m' . $str . chr(27) . '[0m';
}

function check($value)
{
    return $value ? green('OK') : red('FAIL');
}

function isAvailable($func)
{
    $disabled = ini_get('disable_functions');

    if ($disabled) {
        $disabled = explode(',', $disabled);
        $disabled = array_map('trim', $disabled);
        return ! in_array($func, $disabled);
    }

    return true;
}

function shellCommandExists($cmd)
{
    $returnVal = shell_exec("which $cmd");
    return (empty($returnVal) ? false : true);
}


// ==================
// = OUTPUT RESULTS =
// ==================

if (PHP_SAPI == 'cli') {
    foreach ($values as $value) {
        output($value['desc'] . ': ' . check($value['res']) . PHP_EOL);
    }

    output(PHP_EOL . 'You can also view this file in a browser' . PHP_EOL);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Requirements</title>

    <link rel="icon" type="image/png" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAqJJREFUeNp0U0tPE1EU/ubV6QBtKQaQQnBhgiYmElKJJgaVECIxMZroxoULN7B05V6XygqjRl2ZqH9AXSigsjCRBMrCjZZECKUWqFpKmZl23p47nQ5q9Ey+ObnnnHse372Xw99ysQm40ry35jBC/+uEFKFAmIKHt3vucKMCXCBwZBIIUuh6fW346lnLsiCKIp7MPXtDtrGGU8TdBBAT/uzC9QCLdGD+9qOgNglKi+7oqu9j4rCfBx7/ExZoExxvcnppJitJEqaXZrNsHdh9v/iPrY8ocRr1Ar50JjtimqYhGW2NbWvl20FchjBRT1B1AbnRjJe+fPpSularwfM8H6ZpQlVVDB481qcoClzXxcv5VwEH/jxUqkZJxDpxu7u7qFQqYUs8z8OoauWFD7M5tj7SP9jrjxEmCGeunwtjnKEh0WgUnz9l8lVdu89aX/w4N46IlEFE9BOM0Kw3gk4mfeU4MC0Tuq5Dr+qQIzI0tWKQ63GQc4ICwCBgVJ4aHRo7l2hp3b+5WRhIxttilmvvK24XoZs6dWrDEzwYqlpybdsJEtyEIKYgSRkOd2KLQyeH06waI0g3dKyWVsFzPERBDA7Dg61WyzvrGz4HLd0dvWpJyzmW0y9SiQLHcWnTMNTMwnxWao7EYn3JPhSscml+PW+VDUNMROTm4+09rQMHjvonw77vao4RKeCMVFxbWeksbmysk/2WW3NOJQ63pUov1pbtinmPzesajmNtVQ/Jg/FORMhCMPL6lme6D0W8s9jDqD+OHroL3RxshebmvMZlqWt2TX57Y/6xZ20yvzejhDihHU9rXdix4UQdNJ1v76Ww8SB83F/TWwuhEp/PjRRjSWa8EOK+66uzvPMgL/nbFD7sQJ/5eQIzyDUa8HLuF1JdvwQYAL+KPe88BpnxAAAAAElFTkSuQmCC" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" />
    <style>

        /* Sticky footer styles
        -------------------------------------------------- */

        html,
        body {
          height: 100%;
          /* The html and body elements cannot have any padding or margin. */
        }

        /* Wrapper for page content to push down footer */
        #wrap {
          min-height: 100%;
          height: auto !important;
          height: 100%;
          /* Negative indent footer by its height */
          margin: 0 auto -60px;
          /* Pad bottom by footer height */
          padding: 0 0 60px;
        }

        /* Set the fixed height of the footer here */
        #footer {
          height: 60px;
          background-color: #f5f5f5;
        }

        /* Custom page CSS
        -------------------------------------------------- */

        #header {
            text-align: center;
            margin-bottom: 40px;
        }

        #header h1 {
            font-size: 56px;
        }

        #footer p {
            margin: 20px 0;
        }

    </style>
    <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    </head>
    <body>

        <div id="wrap">
            <div class="container">
                <div id="header">
                    <h1>Requirements</h1>
                </div>

                <table class="table">
                    <tbody>
                        <?php foreach ($values as $value): ?>
                            <tr>
                                <td><?php echo $value['desc'] ?></td>
                                <td>
                                    <?php if ($value['res']): ?>
                                        <img alt="OK" width="16" height="16" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAhxJREFUeNpi/P//PwMlgImBQsACIhhnCAFZjEAGkANy0B8gwQ00m5kRogpEMYHl5YF4LZBXw/D3/47/Ea/xuOD7PwaGX1AMMvDvfwGGf/8nprkkGQPZLUAVHqR4gY3hH0Ofoby+6ZcvXxjinWL0Gf4wtMC9QAAwAjXXK0jIO8gLyUl9/Pzx98Fjh24AXdOA7gIPoP/PwJwGdvoPoNP/MGQIcwkGq4mrKHz5+uXf0UtH7wBdMRGodgvCgP9ATX/+tyT5JxqCaDAfIu7DwcSWJckjqfzgwQOGU5dP3f3w4cNSoJq5DL//I0Xjn38tMX4xes+ePWOK8IowAPGBmnNZ/jPVS4vLqH7985Xl5YcXDz99+rAJGIDtQAxxIcKA/zVLViy8xM7J9uvU7VPMDnaOOkAb4sVkxTV+sPxgf/fhzdOP797vZ/gLjD4GBojObT8gAQRKiYx9/AxADaAwaDF2NtN6+vMZpwCnAMP7b+8Zfrz49vrj3fdHGJgZkhhYmT4w7P4J1wzWCyY8OBgY5JiBmBFsiLSdvMYP3l/cv1/+/PD57Psz/5kYEhgO/H4K1owEEAYwQlOcAtAQc2YPBmnGFi4TfpnvFz7d+f/wXyrD8T/XGR79w4hfZANAYcELxAJAzMdgzGTJoMOQxnDmfyfD1f9XgGIgq39AaRD+xQDyNBDADAA5gR2IOaA0MzRX/IPiP1D8F4n+BzeAEgAQYAC7HATaTnWSLQAAAABJRU5ErkJggg==" />
                                    <?php else: ?>
                                        <img alt="FAIL" width="16" height="16" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAcJJREFUeNqkUz1PAkEQfStggjESejU0GozlGqn8SGywkYIYY0IsaLCwIBTQUN5fMLGm8S8QSWwslVAYjAlUBEJDhCgWwp3nzN6eHqIVl8zN7rx5b+dm9oRt25jlmcOMj59f10JAkPcBcXIGWdECyqYn6TfGdZ9S9d4K4gQYx4WCtJzE+G/sKJudwpQABUGnGSf5vKzX60jmctL8SYzz+iCdls1mEzuplMIsLSC4iSUh1ClUlpHIZGStVkM0GsVNqVRlIJZIyG63i1AohMdKpUrZRQqXz4j7LWA7VSiR/WRSNhsNRRgOh+i02wgGg3hrtRSZelLmI6cExs7nKJGVtTX50uupMn0+H157PUWmZpYDXLoWUFPo6MC87jivx4MBFtxOWZYS11VipNdT98DWDVsPh2XQNLFIMdc4xpg9OZ3JMdIpRowSXVKt36+yuXvGxn+N0XS+3zj0kG+JSPEi261H5FCLmN9lUyNWyZ+Qag54eA6Hbfa8j1A88g+2qrlqCkKIZdovbAG7m8D5E3B5D9xR7IPsk/u7DextABd14OrBwd6J23YFligQ0IPwXE7lbedXUAPya5yHMiLuq5j1d/4SYAAj3NATBGE4PgAAAABJRU5ErkJggg==" />
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="footer">
            <div class="container">
                <div class="row">
                    <div class="col-xs-8">
                        <p>You can view this page in the CLI.</p>
                    </div>
                    <div class="col-xs-4">
                        <p class="text-right"><a target="_blank" href="https://github.com/inacho/Requirements-PHP">View in GitHub</a></p>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>