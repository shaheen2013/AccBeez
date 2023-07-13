<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $companies = Company::latest()->withCount('bills', 'boms', 'sales', 'bomSales', 'companyUsers')->with('companyUsers.user')->get();
        return response()->successResponse('Company list', $companies);
    }

    public function getAll()
    {
        // $user = Auth::user();  
        // $role = User::find($user->id)->getRoleNames()[0];     
        // $companies = Company::when(in_array("User",$role),function($q){
        //     $q->whereIn('id',CompanyUser::where('user_id',Auth::id())->pluck('company_id'));
        // })->latest()->withCount('bills', 'boms', 'sales', 'bomSales', 'companyUsers')->with('companyUsers.user')->get();

        $companies = Company::all();
        $deletedCompanies = Company::onlyTrashed()->latest()->get();


        return response()->json([
            'companies'=>$companies,
            'deletedCompanies'=>$deletedCompanies

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyRequest $request)
    {
        $requestData = $request->validated();
        $requestData['slug'] = Str::slug($request->name);
        $company = Company::create($requestData);

        $data = new CompanyResource($company);

        return response()->successResponse('New company info', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            
            $company = Company::find($id);
            $company->delete();  
    
            return response()->json(['status'=>true,'data'=>$company],200);   
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('companycontroller destroy method : ',$th->getTrace());
            return response()->json(['status'=>false,'message'=>'Something wrong! Please try again. '],400);   
        }
    }

    public function restore($id)
    {
        try {
            
            DB::table('companies')->where('id',$id)->update(['deleted_at'=>null]);
            $company = Company::find($id);  
    
            return response()->json(['status'=>true,'data'=>$company],200);   
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('companycontroller restore method : ',$th->getTrace());
            return response()->json(['status'=>false,'message'=>'Something wrong! Please try again. '],400);   
        }
    }

    public function companyOverview(Request $request)
    {
        $company = Company::where('slug', $request->slug)
                        ->withCount('bills', 'boms', 'sales', 'bomSales')
                        ->first();
        return response()->json([
            'company' => $company
        ]);
    }
}
