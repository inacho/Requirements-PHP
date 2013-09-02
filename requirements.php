<?php

/**
 * Configurable page to check the requirements of your PHP Application.
 * You can run this page in the browser or in the CLI.
 *
 * @author Ignacio de Tomás <nacho@inacho.es>
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

    output(PHP_EOL . 'You can also test this page in a browser' . PHP_EOL);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Requirements</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="robots" content="noindex, nofollow" />

    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet" />
    <style type="text/css">
    body {
        padding-top: 20px;
        padding-bottom: 40px;
    }
    .container-narrow {
        margin: 0 auto;
        max-width: 700px;
    }
    .container-narrow > hr {
        margin: 30px 0;
    }
    .jumbotron {
        margin: 60px 0;
        text-align: center;
    }
    .jumbotron h1 {
        font-size: 72px;
        line-height: 1;
    }
    .marketing {
        margin: 60px 0;
    }
    .marketing p + h4 {
        margin-top: 28px;
    }
    </style>

    <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    </head>
    <body>
        <div class="container-narrow">

            <div class="jumbotron">
                <h1>Requirements</h1>
            </div>

            <div class="row-fluid marketing">
                <div class="span12">
                    <table class="table">
                        <tbody>
                            <?php foreach ($values as $value): ?>
                                <tr>
                                    <td><?php echo $value['desc'] ?></td>
                                    <td>
                                        <?php if ($value['res']): ?>
                                            <img src="//p.yusukekamiyamane.com/icons/search/fugue/icons/tick.png" alt="OK" />
                                        <?php else: ?>
                                            <img src="//p.yusukekamiyamane.com/icons/search/fugue/icons/cross.png" alt="FAIL" />
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr>

            <div class="footer">
                <p>You can also test this page in the CLI</p>
            </div>

        </div>
    </body>
</html>
