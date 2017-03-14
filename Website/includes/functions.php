<?php
/**
 * Created by PhpStorm.
 * User: mohamedelzarei
 * Date: 11/30/16
 * Time: 10:31 PM
 */


define("DIE_MSG", "Oops! An Error Occured With the database.");

function formattedMessage($msg, $type = 0)
{
    if ($type == 1) {
        return "<div class=\"container\">
            <div class=\"alert alert-danger\">" .
            $msg .
            "</div>
            </div>";
    } else if ($type == 2) {
        return "<div class=\"container\">
            <div class=\"alert alert-success\">" .
            $msg .
            "</div>
            </div>";
    } else {
        return "<div class=\"container\">
            <div class=\"alert alert-info\">" .
            $msg .
            "</div>
            </div>";
    }

}

function callProcedure($name, $parmaters)
{
    $q = "CALL " . $name . "(";
    foreach ($parmaters as $par)
        $q = $q . "'" . $par . "',";
    $q[strlen($q) - 1] = ')';
    return $q;
}

function redirect_to($url,$dur = 3000)
{
    $string = '<script type="text/javascript">';
    $string .= 'setTimeout(function () {';
    $string .= 'window.location = "' . $url . '"';
    $string .= '},'.$dur. ');';
    $string .= '</script>';

    echo $string;
}