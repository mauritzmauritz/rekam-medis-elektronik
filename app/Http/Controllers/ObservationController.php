<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ObservationRequest;
use App\Http\Resources\ObservationResource;
use App\Models\Observation;
use App\Models\ObservationBasedOn;
use App\Models\ObservationCategory;
use App\Models\ObservationComponent;
use App\Models\ObservationComponentInterpretation;
use App\Models\ObservationComponentReferenceRange;
use App\Models\ObservationDerivedFrom;
use App\Models\ObservationFocus;
use App\Models\ObservationIdentifier;
use App\Models\ObservationInterpretation;
use App\Models\ObservationMember;
use App\Models\ObservationNote;
use App\Models\ObservationPartOf;
use App\Models\ObservationPerformer;
use App\Models\ObservationReferenceRange;
use App\Models\Resource;
use App\Models\ResourceContent;
use App\Services\FhirService;

class ObservationController extends Controller
{
    public function postObservation(ObservationRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        return $fhirService->insertData(function () use ($body) {
            $resource = Resource::create([
                'res_type' => 'Observation',
                'res_ver' => 1,
            ]);

            $resourceKey = ['resource_id' => $resource->id];

            $observation = Observation::create(array_merge($resourceKey, $body['observation']));

            $observationKey = ['observation_id' => $observation->id];

            $this->createInstances(ObservationIdentifier::class, $observationKey, $body, 'identifier');
            $this->createInstances(ObservationBasedOn::class, $observationKey, $body, 'based_on');
            $this->createInstances(ObservationPartOf::class, $observationKey, $body, 'part_of');
            $this->createInstances(ObservationCategory::class, $observationKey, $body, 'category');
            $this->createInstances(ObservationFocus::class, $observationKey, $body, 'focus');
            $this->createInstances(ObservationPerformer::class, $observationKey, $body, 'performer');
            $this->createInstances(ObservationInterpretation::class, $observationKey, $body, 'interpretation');
            $this->createInstances(ObservationNote::class, $observationKey, $body, 'note');
            $this->createInstances(ObservationReferenceRange::class, $observationKey, $body, 'reference_range');
            $this->createInstances(ObservationMember::class, $observationKey, $body, 'has_member');
            $this->createInstances(ObservationDerivedFrom::class, $observationKey, $body, 'derived_from');

            if (isset($body['component']) && !empty($body['component'])) {
                $this->createInstances(ObservationComponent::class, $observationKey, $body['component'], 'component_data', [
                    [
                        'model' => ObservationComponentInterpretation::class,
                        'key' => 'obs_comp_id',
                        'bodyKey' => 'interpretation'
                    ],
                    [
                        'model' => ObservationComponentReferenceRange::class,
                        'key' => 'obs_comp_id',
                        'bodyKey' => 'reference_range'
                    ],
                ]);
            }

            $resourceData = new ObservationResource($resource);
            $resourceText = json_encode($resourceData);

            ResourceContent::create([
                'resource_id' => $resource->id,
                'res_ver' => 1,
                'res_text' => $resourceText,
            ]);

            return response()->json($resource->observation->first(), 201);
        });
    }
}
