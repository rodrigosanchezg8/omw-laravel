<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\CompanyStore;
use App\Http\Requests\CompanyUpdate;
use App\Services\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(CompanyService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {

            $list = $this->service->list();

            return response()->json($list);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function store(CompanyStore $request)
    {
        try {

            $company = $this->service->store($request->all());

            return response()->json([
                'header' => 'Éxito',
                'message' => 'Compañia creada.',
                'company' => $company,
                'status' => 200
            ]);

        } catch (Exception $e) {

            return response()->json([
                'status' => 'failed',
                'list' => $e->getMessage(),
            ]);

        }
    }

    public function show($company_id)
    {
        try {

            $company = $this->service->getDetailedCompany($company_id);

            return response()->json($company);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function update(Company $company, CompanyUpdate $request)
    {
        try {

            $this->service->update($company, $request->all());

            return response()->json([
                'header' => 'Éxito',
                'message' => 'Compañia actualizada.',
                'status' => 200
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function delete(Company $company)
    {
        try {

            $this->service->delete($company);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }
}
