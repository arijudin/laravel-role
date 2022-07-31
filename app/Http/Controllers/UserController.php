<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class UserController extends Controller
{
    public function index()
    {
      $data = User::query()
      ->orderBy('name', 'ASC')
      ->when(request('find'), function ($data) {
        $data->where('name', 'ilike', '%' . request('find') . '%')
          ->where('email', 'ilike', '%' . request('find') . '%');
      })
      ->when(request('role'), function ($data) {
        $data->whereIn('role', request('role'));
      })
      ->paginate(request('per_page', 50));

      return $this->successNotArray($data, "Successfully retrieve data.");
    }

    public function update($id, Request $request)
    {

        Validator::make($request->all(), [
          'name' => ['required', 'string', 'max:255'],
          'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($id)],
          'role' => ['required'],
          'biodata' => ['nullable'],
          'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
      ])->validateWithBag('updateProfileInformation');

      $user = User::where('id', $id)->first();

      $user->forceFill([
          'name' => $request['name'],
          'role' => $request['role'],
          'email' => $request['email'],
          'biodata' => $request['biodata'],
      ])->save();

      return $this->success([], "Successfully update data.");
    }

    public function destroy($id)
    {
      User::where('id', $id)->delete();

      return $this->success([], "Successfully delete data.");
    }

    public function exportExcl()
    {
      return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function exportPdf()
    {
      $users = User::orderBy('id', 'ASC')->get();
 
    	$pdf = PDF::loadview('users_pdf',['users' => $users]);
    	return $pdf->download('users.pdf');
    }
}
