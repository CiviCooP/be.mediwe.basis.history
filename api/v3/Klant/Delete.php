<?php
/**
 * Created by PhpStorm.
 * User: CDeman
 * Date: 20/07/2017
 * Time: 23:43
*/


function _civicrm_api3_klant_Delete_spec(&$spec) {
    $spec['id']['api.required'] = 1;
}

/**
 * Klant.Create API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */

function civicrm_api3_klant_Delete($params) {
    $klant = new CRM_Basis_Klant();
    $returnValues = $klant->deleteWithId($params['id']);
    return civicrm_api3_delete_success($returnValues, $params, 'Klant', 'Delete');
}
