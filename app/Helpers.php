<?php

function getName($resource)
{
    if (isset($resource['name']) && !empty($resource['name'])) {
        return $resource['name'];
    }

    return null;
}

function parseName($nameData)
{
    if ($nameData === null) {
        return null;
    }

    foreach ($nameData as $name) {

        $displayName = '';

        if (isset($name['text']) && !empty($name['text'])) {
            $displayName = $name['text'];
        } else {
            $nameParts = [];

            if (isset($name['prefix']) && !empty($name['prefix'])) {
                $nameParts[] = implode(' ', $name['prefix']);
            }

            $givenName = isset($name['given']) ? implode(' ', $name['given']) : '';
            $familyName = isset($name['family']) ? $name['family'] : '';

            if (!empty($givenName)) {
                $nameParts[] = $givenName;
            }

            if (!empty($familyName)) {
                $nameParts[] = $familyName;
            }

            if (isset($name['suffix']) && !empty($name['suffix'])) {
                $nameParts[] = implode(' ', $name['suffix']);
            }

            $displayName = implode(' ', $nameParts);
        }

        // if (isset($name['period']) && isset($name['period']['end'])) {
        //     $endDate = new DateTime($name['period']['end']);
        //     $currentDate = new DateTime();
        //     if ($endDate > $currentDate) {
        //         $displayName .= ' (Active)';
        //     }
        // }

        return $displayName;
    }

    return null;
}

function getFullName($nameData)
{
    if ($nameData === null) {
        return null;
    }

    foreach ($nameData as $name) {

        $displayName = '';

        if (isset($name['text']) && !empty($name['text'])) {
            $displayName = $name['text'];
        } else {
            $nameParts = [];

            $givenName = isset($name['given']) ? implode(' ', $name['given']) : '';
            $familyName = isset($name['family']) ? $name['family'] : '';

            if (!empty($givenName)) {
                $nameParts[] = $givenName;
            }

            if (!empty($familyName)) {
                $nameParts[] = $familyName;
            }

            $displayName = implode(' ', $nameParts);
        }

        return $displayName;
    }

    return null;
}

function getPrefix($nameData)
{
    if ($nameData === null) {
        return null;
    }

    foreach ($nameData as $name) {

        $displayName = '';
        $nameParts = [];
        if (isset($name['prefix']) && !empty($name['prefix'])) {
            $nameParts[] = implode(' ', $name['prefix']);
        }
        $displayName = implode(' ', $nameParts);
    }
    return $displayName;
}

function getSuffix($nameData)
{
    if ($nameData === null) {
        return null;
    }

    foreach ($nameData as $name) {

        $displayName = '';
        $nameParts = [];
        if (isset($name['suffix']) && !empty($name['suffix'])) {
            $nameParts[] = implode(' ', $name['suffix']);
        }
        $displayName = implode(' ', $nameParts);
    }
    return $displayName;
}

function getIdentifier($resource)
{
    if (isset($resource['identifier']) && !empty($resource['identifier'])) {
        return $resource['identifier'];
    }

    return null;
}

function getMRN($identifier)
{
    if ($identifier === null) {
        return null;
    }

    foreach ($identifier as $id) {
        if (isset($id['type']['coding']) && is_array($id['type']['coding'])) {
            foreach ($id['type']['coding'] as $coding) {
                if (isset($coding['code']) && $coding['code'] === 'MR') {
                    $value = $id['value'];
                    return $value;
                }
            }
        }
    }

    return null;
}

function getNik($identifier)
{
    if ($identifier === null) {
        return null;
    }

    foreach ($identifier as $id) {
        if (isset($id['system']) && $id['system'] === 'https://fhir.kemkes.go.id/id/nik') {
            $nik = $id['value'];
            return $nik;
        }
    }

    return null;
}

function getIhs($identifier)
{
    if ($identifier === null) {
        return null;
    }

    foreach ($identifier as $id) {
        if (isset($id['system']) && $id['system'] === 'https://fhir.kemkes.go.id/id/nakes-his-number') {
            $nik = $id['value'];
            return $nik;
        }
    }

    return null;
}

function getGender($resource)
{
    if (isset($resource['gender']) && !empty($resource['gender'])) {
        return $resource['gender'];
    }

    return null;
}

function getBirthDate($resource)
{
    if (isset($resource['birthDate']) && !empty($resource['birthDate'])) {
        return $resource['birthDate'];
    }

    return null;
}

function getTelecom($resource)
{
    if (isset($resource['telecom']) && !empty($resource['telecom'])) {
        return $resource['telecom'];
    }

    return null;
}

function getTelecomDetails($telecom)
{
    $telecomDetails = [];
    if (isset($telecom['system']) && !empty($telecom['system'])) {
        $telecomDetails['system'] = $telecom['system'];
    } else {
        $telecomDetails['system'] = '';
    }
    if (isset($telecom['use']) && !empty($telecom['use'])) {
        $telecomDetails['use'] = $telecom['use'];
    } else {
        $telecomDetails['use'] = '';
    }
    if (isset($telecom['value']) && !empty($telecom['value'])) {
        $telecomDetails['value'] = $telecom['value'];
    } else {
        $telecomDetails['value'] = '';
    }
    return $telecomDetails;
}

function getAddress($resource)
{
    if (isset($resource['address']) && !empty($resource['address'])) {
        return $resource['address'];
    }

    return null;
}

function getAddressDetails($address)
{
    $addressDetails = [];

    if (isset($address['use']) && !empty($address['use'])) {
        $addressDetails['use'] = $address['use'];
    } else {
        $addressDetails['use'] = '';
    }

    if (isset($address['line']) && !empty($address['line'])) {
        $addressDetails['line'] = $address['line'][0];
    } elseif (isset($address['text']) && !empty($address['text'])) {
        $addressDetails['line'] = $address['text'];
    } else {
        $addressDetails['line'] = '';
    }

    if (isset($address['postalCode']) && !empty($address['postalCode'])) {
        $addressDetails['postalCode'] = $address['postalCode'];
    } else {
        $addressDetails['postalCode'] = '';
    }

    if (isset($address['country']) && !empty($address['country'])) {
        $addressDetails['country'] = $address['country'];
    } else {
        $addressDetails['country'] = '';
    }

    if (isset($address['extension']) && !empty($address['extension'])) {
        $extensionData = $address['extension'][0]['extension'];

        foreach ($extensionData as $extension) {
            $url = $extension['url'];
            $value = $extension['valueCode'];
            $addressDetails[$url] = $value;
        }
    } else {
        $addressDetails['rt'] = -1;
        $addressDetails['rw'] = -1;
        $addressDetails['village'] = -1;
        $addressDetails['district'] = -1;
        $addressDetails['city'] = -1;
        $addressDetails['province'] = -1;
    }

    return $addressDetails;
}

function getQualifications($resource)
{
    if (isset($resource['qualification']) && !empty($resource['qualification'])) {
        return $resource['qualification'];
    }

    return null;
}

function getQualificationDetails($qualification)
{
    $qualificationDetails = [];

    if (isset($qualification['code']['coding'][0]['code']) && !empty($qualification['code']['coding'][0]['code'])) {
        $qualificationDetails['code'] = $qualification['code']['coding'][0]['code'];
    } else {
        $qualificationDetails['code'] = '';
    }

    if (isset($qualification['code']['coding']) && !empty($qualification['code']['coding'])) {
        if (isset($qualification['code']['coding'][0]['code']) && !empty($qualification['code']['coding'][0]['code'])) {
            $qualificationDetails['code'] = $qualification['code']['coding'][0]['code'];
        } else {
            $qualificationDetails['code'] = '';
        }

        if (isset($qualification['code']['coding'][0]['system']) && !empty($qualification['code']['coding'][0]['system'])) {
            $qualificationDetails['system'] = $qualification['code']['coding'][0]['system'];
        } else {
            $qualificationDetails['system'] = '';
        }

        if (isset($qualification['code']['coding'][0]['display']) && !empty($qualification['code']['coding'][0]['display'])) {
            $qualificationDetails['display'] = $qualification['code']['coding'][0]['display'];
        } else {
            $qualificationDetails['display'] = '';
        }
    } else {
        $qualificationDetails['code'] = '';
        $qualificationDetails['system'] = '';
        $qualificationDetails['display'] = '';
    }

    if (isset($qualification['identifier'][0]['value']) && !empty($qualification['identifier'][0]['value'])) {
        $qualificationDetails['identifier'] = $qualification['identifier'][0]['value'];
    } else {
        $qualificationDetails['identifier'] = '';
    }

    if (isset($qualification['issuer']['reference']) && !empty($qualification['issuer']['reference'])) {
        $qualificationDetails['issuer'] = $qualification['issuer']['reference'];
    } else {
        $qualificationDetails['issuer'] = '';
    }

    if (isset($qualification['period']) && !empty($qualification['period'])) {
        if (isset($qualification['period']['start']) && !empty($qualification['period']['start'])) {
            $qualificationDetails['periodStart'] = date('Y-m-d', strtotime($qualification['period']['start']));
        } else {
            $qualificationDetails['periodStart'] = '1900-01-01';
        }
        if (isset($qualification['period']['end']) && !empty($qualification['period']['end'])) {
            $qualificationDetails['periodEnd'] = date('Y-m-d', strtotime($qualification['period']['end']));
        } else {
            $qualificationDetails['periodEnd'] = null;
        }
    } else {
        $qualificationDetails['periodStart'] = '1900-01-01';
        $qualificationDetails['periodEnd'] = null;
    }

    return $qualificationDetails;
}
