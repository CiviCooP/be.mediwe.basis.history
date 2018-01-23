<?php
use CRM_Basis_ExtensionUtil as E;

/**
 * Google.Afstand API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_google_Afstand_spec(&$spec) {
  $spec['adres']['api.required'] = 1;
  $spec['postcode']['api.required'] = 1;
  $spec['gemeente']['api.required'] = 1;
  $spec['adres_arts']['api.required'] = 1;
  $spec['postcode_arts']['api.required'] = 1;
  $spec['gemeente_arts']['api.required'] = 1;
}

/**
 * Google.Afstand API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_google_Afstand($params)
{
    try {
        $from = $params['adres'] . ',' . $params['postcode'] . ' ' . $params['gemeente'];
        $to = $params['adres_arts'] . ',' . $params['postcode_arts'] . ' ' . $params['gemeente_arts'];
        $from = urlencode($from);
        $to = urlencode($to);
        $data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
        $data = json_decode($data);
        $time = 0;
        $distance = 0;
        if (isset($data->rows[0])) {
            if ($data->rows[0]->elements[0]->status == 'NOT_FOUND') {
                return civicrm_api3_create_error("Google kent dit adres niet", $params);
            } else {
                foreach ($data->rows[0]->elements as $road) {
                    if (isset($road->duration)) {
                        $time += $road->duration->value;
                        $distance += $road->distance->value;
                    }
                }
            }
        }
        $returnValues[] = array(
            'km' => round($distance / 1000, 1),
            'reistijd' => round($time / 60, 1)
        );
        return civicrm_api3_create_success($returnValues, $params, 'Google', 'Afstand');
    } catch (Exception $ex) {
        throw new API_Exception("Exception $ex in Api call Google: Afstand");
    }
}