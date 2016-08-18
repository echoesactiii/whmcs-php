<?php
use Illuminate\Database\Capsule\Manager as Capsule;

function get_env($vars)
{
    $array = array('action' => array(), 'params' => array());
    if (isset($vars['cmd'])) {
        //Local API mode
        $array['action'] = $vars['cmd'];
        $array['params'] = (object)$vars['apivalues1'];
        $array['adminuser'] = $vars['adminuser'];

    } else {
        //Post CURL mode
        $array['action'] = $vars['_POST']['action'];
        unset($vars['_POST']['username']);
        unset($vars['_POST']['password']);
        unset($vars['_POST']['action']);
        $array['params'] = (object)$vars['_POST'];
    }
    return (object)$array;
}

try {
    $vars = get_defined_vars();
    //Get the parameters
    $request = get_env($vars);

    $gid = (int)$request->params->gid;

    if ($gid)
        $productGroups = Capsule::table('tblproductgroups')->where('id', $gid)->get();
    else
        $productGroups = Capsule::table('tblproductgroups')->get();

    if (count($productGroups))
        $apiresults = array(
            "result" => "success",
            "productGroups" => $productGroups
        );
    else
        $apiresults = array(
            "result" => "error",
            "message" => 'There are no product groups'
        );
} catch (Exception $e) {
    $apiresults = array("result" => "error", "message" => $e->getMessage());
}
?>