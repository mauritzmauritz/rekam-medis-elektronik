<?php

namespace App\Http\Controllers;

use App\Fhir\Satusehat;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SatusehatController extends Controller
{
    public string $authUrl;
    public string $baseUrl;
    public string $consentUrl;
    public string $clientId;
    public string $clientSecret;
    public string $organizationId;

    public function __construct()
    {
        $this->authUrl = config('app.auth_url');
        $this->baseUrl = config('app.base_url');
        $this->consentUrl = config('app.consent_url');
        $this->clientId = config('app.client_id');
        $this->clientSecret = config('app.client_secret');
        $this->organizationId = config('app.organization_id');
    }

    public function getToken()
    {
        if (session()->has('token')) {
            if (session()->has('token_created_at')) {
                if (now()->diffInMinutes(session('token_created_at')) < 55) {
                    return session()->get('token');
                }
            }
        }
        session()->forget('token');
        session()->forget('token_created_at');

        $client = new Client();
        $url = $this->authUrl . '/accesstoken?grant_type=client_credentials';
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded',];
        $options = [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ],
        ];

        $request = new Request('POST', $url, $headers);

        $response = $client->sendAsync($request, $options)->wait();
        $contents = json_decode($response->getBody()->getContents());
        $token = $contents->access_token;

        session()->put('token', $token);
        session()->put('token_created_at', now());

        return $token;
    }

    public function get($resourceType, $satusehatId)
    {
        $validResourceTypes = array_keys(Satusehat::AVAILABLE_METHODS);

        if (!in_array($resourceType, $validResourceTypes)) {
            return response()->json([
                'error' => 'Invalid resource type. Keep in mind that resource type is case sensitive.',
                'validResourceTypes' => $validResourceTypes,
            ], 400);
        }

        $method = 'get';
        if (!in_array($method, Satusehat::AVAILABLE_METHODS[$resourceType])) {
            return response()->json([
                'error' => 'Method not allowed for this resource type.',
                'validMethods' => Satusehat::AVAILABLE_METHODS[$resourceType],
            ], 405);
        }

        $token = $this->getToken();

        $client = new Client();

        $url = $this->baseUrl . '/' . $resourceType . '/' . $satusehatId;
        $headers = ['Authorization' => 'Bearer ' . $token,];

        $request = new Request('GET', $url, $headers);

        try {
            $response = $client->sendAsync($request)->wait();
            $contents = json_decode($response->getBody()->getContents());
            return $contents;
        } catch (ClientException $e) {
            return response()->json(json_decode(
                $e->getResponse()->getBody()->getContents()
            ), $e->getCode());
        }
    }

    public function post(HttpRequest $fhirRequest, $res_type)
    {
        $validator = Validator::make($fhirRequest->all(), [
            'resourceType' => ['required', Rule::in(array_keys(Satusehat::AVAILABLE_METHODS))],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $method = 'post';
        if (!in_array($method, Satusehat::AVAILABLE_METHODS[$fhirRequest->input('resourceType')])) {
            return response()->json([
                'error' => 'Method not allowed for this resource type.',
                'validMethods' => Satusehat::AVAILABLE_METHODS[$fhirRequest->input('resourceType')],
            ], 405);
        }

        $token = $this->getToken();

        $client = new Client();

        $resourceType = $fhirRequest->input('resourceType');

        if ($resourceType != $res_type) {
            return response()->json([
                'error' => 'Resource type mismatch, check your request body.',
            ], 400);
        }

        $url = $this->baseUrl . '/' . $resourceType;
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ];

        $request = new Request('POST', $url, $headers, json_encode($fhirRequest->all()));

        try {
            $response = $client->sendAsync($request)->wait();
            $contents = json_decode($response->getBody()->getContents());
            return $contents;
        } catch (ClientException $e) {
            return response()->json(json_decode(
                $e->getResponse()->getBody()->getContents()
            ), $e->getCode());
        }
    }

    public function put(HttpRequest $fhirRequest, $res_type, $res_id)
    {
        $validator = Validator::make($fhirRequest->all(), [
            'resourceType' => ['required', Rule::in(array_keys(Satusehat::AVAILABLE_METHODS))],
            'id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $method = 'put';
        if (!in_array($method, Satusehat::AVAILABLE_METHODS[$fhirRequest->input('resourceType')])) {
            return response()->json([
                'error' => 'Method not allowed for this resource type.',
                'validMethods' => Satusehat::AVAILABLE_METHODS[$fhirRequest->input('resourceType')],
            ], 405);
        }

        $token = $this->getToken();

        $client = new Client();

        $resourceType = $fhirRequest->input('resourceType');
        $id = $fhirRequest->input('id');

        if (($resourceType != $res_type) || ($id != $res_id)) {
            return response()->json([
                'error' => 'Resource type or ID mismatch, check your request body.',
            ], 400);
        }

        $url = $this->baseUrl . '/' . $resourceType . '/' . $id;
        $headers = ['Authorization' => 'Bearer ' . $token,];

        $request = new Request('PUT', $url, $headers, json_encode($fhirRequest->all()));

        try {
            $response = $client->sendAsync($request)->wait();
            $contents = json_decode($response->getBody()->getContents());
            return $contents;
        } catch (ClientException $e) {
            return response()->json(json_decode(
                $e->getResponse()->getBody()->getContents()
            ), $e->getCode());
        }
    }
}
