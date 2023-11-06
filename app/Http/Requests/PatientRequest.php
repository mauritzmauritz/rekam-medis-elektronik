<?php

namespace App\Http\Requests;

use App\Constants;
use App\Models\Patient;
use App\Models\PatientContact;
use Illuminate\Validation\Rule;

class PatientRequest extends FhirRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge(
            $this->baseAttributeRules(),
            $this->baseDataRules(),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->getTelecomDataRules('telecom.*.'),
            $this->getAddressDataRules('address.*.'),
            $this->contactDataRules(),
            $this->getReferenceDataRules('general_practitioner.*.')
        );
    }

    public function baseAttributeRules(): array
    {
        return [
            'patient' => 'required|array',
            'identifier' => 'required|array',
            'telecom' => 'required|array',
            'address' => 'required|array',
            'contact' => 'required|array',
            'general_practitioner' => 'required|array',
        ];
    }

    public function baseDataRules(): array
    {
        return [
            'patient.active' => 'required|boolean',
            'patient.name' => 'required|string|max:255',
            'patient.prefix' => 'nullable|string|max:255',
            'patient.suffix' => 'nullable|string|max:255',
            'patient.gender' => ['required', 'string', Rule::in(Constants::GENDER)],
            'patient.birth_date' => 'nullable|date',
            'patient.birth_place' => 'nullable|string|max:255',
            'patient.deceased' => 'nullable|array',
            'patient.marital_status' => ['nullable', 'string', Rule::in(Patient::MARITAL_STATUS_CODE)],
            'patient.multiple_birth' => 'nullable|array',
            'patient.language' => 'nullable|string|max:255',
        ];
    }

    public function contactDataRules(): array
    {
        $address = $this->getAddressDataRules('contact.*.contact_data.');
        $address['contact.*.contact_data.address_use'] = $address['contact.*.contact_data.use'];
        $address['contact.*.contact_data.address_line'] = $address['contact.*.contact_data.line'];
        unset($address['contact.*.contact_data.use']);
        unset($address['contact.*.contact_data.line']);
        return array_merge(
            [
                'contact.*.contact_data.relationship' => ['required', 'string', Rule::in(PatientContact::RELATIONSHIP_CODE)],
                'contact.*.contact_data.name' => 'required|string|max:255',
                'contact.*.contact_data.prefix' => 'nullable|string|max:255',
                'contact.*.contact_data.suffix' => 'nullable|string|max:255',
                'contact.*.contact_data.gender' => ['required', 'string', Rule::in(Constants::GENDER)],
            ],
            $address,
            $this->getTelecomDataRules('contact.*.telecom.*.')
        );
    }

    public function messages(): array
    {
        // create the corresponding validation error message according to the rules above
        return [
            //Untuk required
            'patient.active.required' => 'Harus dipilih.',
            'patient.name.required' => 'Nama harus diisi.',
            'patient.gender.required' => 'Jenis kelamin harus dipilih',

            'identifier.*.system.required' => 'Identifier system harus diisi',
            'identifier.*.use.required' => 'Identifier use harus diisi',
            'identifier.*.value.required' => 'Identifier value harus diisi',

            'telecom.*.system.required' => 'Sistem telekomunikasi pasien harus diisi',
            'telecom.*.use.required' => 'Kegnuaan telekomunikasi pasien harus diisi',
            'telecom.*.value.required' => 'Keterangan nilai telekomunikasi pasien harus diisi',

            'address.*.use.required' => 'Kegunaan alamat pasien harus diisi',
            'address.*.line.required' => 'Alamat pasien harus diisi',
            'address.*.country.required' => 'Asal negara pasien harus diisi',
            'address.*.postal_code.required' => 'Kode pos pasien harus diisi',
            'address.*.province.required' => 'Asal provinsi pasien harus diisi',
            'address.*.city.required' => 'Asal kota pasien harus diisi',
            'address.*.district.required' => 'Asal kecamatan pasien harus diisi',
            'address.*.village.required' => 'Asal kelurahan/desa pasien harus diisi',
            'address.*.rt.required' => 'Asal RT pasien harus diisi',
            'address.*.rw.required' => 'Asal RW pasien harus diisi',

            'contact.*.contact_data.relationship.required' => 'Jenis kontak darurat pasien harus diisi',
            'contact.*.contact_data.name.required' => 'Nama kontak darurat pasien harus diisi',
            'contact.*.contact_data.gender.required' => 'Jenis kelamin kontak darurat pasien harus diisi',
            'contact.*.contact_data.address_use.required' => 'Kegunaan alamat kontak darurat pasien harus diisi',
            'contact.*.contact_data.address_line.required' => 'Alamat kontak darurat pasien harus diisi',
            'contact.*.contact_data.country.required' => 'Asal negara kontak darurat pasien harus diisi',
            'contact.*.contact_data.postal_code.required' => 'Kode pos kontak darurat pasien harus diisi',
            'contact.*.contact_data.province.required' => 'Asal provinsi kontak darurat pasien harus diisi',
            'contact.*.contact_data.city.required' => 'Asal kota kontak darurat pasien harus diisi',
            'contact.*.contact_data.district.required' => 'Asal kecamatan kontak darurat pasien harus diisi',
            'contact.*.contact_data.village.required' => 'Asal kelurahan/desa kontak darurat pasien harus diisi',
            'contact.*.contact_data.rt.required' => 'Asal RT kontak darurat pasien harus diisi',
            'contact.*.contact_data.rw.required' => 'Asal RW kontak darurat pasien harus diisi',

            'contact.*.telecom.*.system.required' => 'Sistem telekomunikasi kontak darurat pasien harus diisi',
            'contact.*.telecom.*.use.required' => 'Kegunaan telekomunikasi kontak darurat pasien harus diisi',
            'contact.*.telecom.*.value.required' => 'Keterangan nilai telekomunikasi kontak darurat pasien harus diisi',

            'general_practitioner.*.reference.required' => 'Referensi general practitioner harus diisi',

            // Untuk Rule::in
            'patient.gender.in' => 'Harus termasuk "male", "female", "other", atau "unknown"',

            'identifier.*.use.in' => 'Harus termasuk "usual", "official", "temp", "secondary", atau "old"',

            'telecom.*.system.in' => 'Harus termasuk "phone", "fax", "email", "pager", "url", "sms", atau "other"',
            'telecom.*.use.in' => 'Harus termasuk "home", "work", "temp", "old", atau "mobile"',

            'address.*.use.in' => 'Harus termasuk "home", "work", "temp", "old", atau "billing"',

            'contact.*.contact_data.relationship.in' => 'Harus termasuk "BP", "CP", "EP", "PR", "E", "C", "F", "I", "N", "S", atau "U"',
            'contact.*.contact_data.gender.in' => 'Harus termasuk "male", "female", "other", atau "unknown"',
            'contact.*.contact_data.address_use.in' => 'Harus termasuk "home", "work", "temp", "old", atau "billing"',

            'contact.*.telecom.*.system.in' => 'Harus termasuk "phone", "fax", "email", "pager", "url", "sms", atau "other"',
            'contact.*.telecom.*.use.in' => 'Harus termasuk "home", "work", "temp", "old", atau "mobile"',

            //Untuk gte
            'address.*.province.gte' => 'Nilai provinsi asal pasien tidak boleh negatif',
            'address.*.city.gte' => 'Nilai kota asal pasien tidak boleh negatif',
            'address.*.district.gte' => 'Nilai kecamatan asal pasien tidak boleh negatif',
            'address.*.village.gte' => 'Nilai kelurahan/desa asal pasien tidak boleh negatif',
            'address.*.rt.gte' => 'Nilai RT asal pasien tidak boleh negatif',
            'address.*.rw.gte' => 'Nilai RW asal pasien tidak boleh negatif',

            'contact.*.contact_data.province.gte' => 'Nilai provinsi asal kontak darurat pasien tidak boleh negatif',
            'contact.*.contact_data.city.gte' => 'Nilai kota asal kontak darurat pasien tidak boleh negatif',
            'contact.*.contact_data.district.gte' => 'Nilai kecamatan asal kontak darurat pasien tidak boleh negatif',
            'contact.*.contact_data.village.gte' => 'Nilai kelurahan/desa asal kontak darurat pasien tidak boleh negatif',
            'contact.*.contact_data.rt.gte' => 'Nilai RT asal kontak darurat pasien tidak boleh negatif',
            'contact.*.contact_data.rw.gte' => 'Nilai RW asal kontak darurat pasien tidak boleh negatif',

            //Untuk digits
            'address.*.province.digits' => 'Nilai provnsi asal pasien harus terdiri dari 2 digit angka',
            'address.*.city.digits' => 'Nilai kota asal pasien harus terdiri dari 4 digit angka',
            'address.*.district.digits' => 'Nilai kecamatan asal pasien harus terdiri dari 6 digit angka',
            'address.*.village.digits' => 'Nilai kelurahan/desa asal pasien harus terdiri dari 10 digit angka',

            'contact.*.contact_data.province.digits' => 'Nilai provinsi asal kontak darurat pasien harus terdiri dari 2 digit angka',
            'contact.*.contact_data.city.digits' => 'Nilai kota asal kontak darurat pasien harus terdiri dari 4 digit angka',
            'contact.*.contact_data.district.digits' => 'Nilai kecamatan asal kontak darurat pasien harus terdiri dari 6 digit angka',
            'contact.*.contact_data.village.digits' => 'Nilai kelurahan/desa asal kontak darurat pasien harus terdiri dari 10 digit angka',

            //Untuk max_digits
            'address.*.rt.max_digits' => 'Nilai RT asal pasien harus terdiri dari maksimal 2 digit angka',
            'address.*.rw.max_digits' => 'Nilai RW asal pasien harus terdiri dari 2 digit angka',

            'contact.*.contact_data.rt.max_digits' => 'Nilai RT asal kontak darurat pasien harus terdiri dari 2 digit angka',
            'contact.*.contact_data.rw.max_digits' => 'Nilai RW asal kontak darurat pasien harus terdiri dari 2 digit angka',

            //Untuk integer
            'address.*.province.integer' => 'Nilai provinsi asal pasien harus berbentuk angka',
            'address.*.city.integer' => 'Nilai kota asal pasien harus berbentuk angka',
            'address.*.district.integer' => 'Nilai asal kecamatan pasien harus berbentuk angka',
            'address.*.village.integer' => 'Nilai asal kelurahan/desa pasien harus berbentuk angka',
            'address.*.rt.integer' => 'Nilai asal RT pasien harus berbentuk angka',
            'address.*.rw.integer' => 'Nilai asal RW pasien harus berbentuk angka',

            'contact.*.contact_data.province.integer' => 'Nilai provinsi asal kontak darurat pasien harus berbentuk angka',
            'contact.*.contact_data.city.integer' => 'Nilai kota asal kontak darurat pasien harus berbentuk angka',
            'contact.*.contact_data.district.integer' => 'Nilai asal kecamatan kontak darurat pasien harus berbentuk angka',
            'contact.*.contact_data.village.integer' => 'Nilai asal kelurahan/desa kontak darurat pasien harus berbentuk angka',
            'contact.*.contact_data.rt.integer' => 'Nilai asal RT kontak darurat pasien harus berbentuk angka',
            'contact.*.contact_data.rw.integer' => 'Nilai asal RW kontak darurat pasien harus berbentuk angka',
        ];
    }
}