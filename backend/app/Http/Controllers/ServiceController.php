<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    private function redirectObsoleteModule()
    {
        return redirect()
            ->route('admin.subscriptions.index')
            ->with('warning', 'The legacy Services module is no longer used. Manage service availability from Subscriptions.');
    }

    public function index()
    {
        return $this->redirectObsoleteModule();
    }

    public function create()
    {
        return $this->redirectObsoleteModule();
    }

    public function store(Request $request)
    {
        return $this->redirectObsoleteModule();
    }

    public function edit($service)
    {
        return $this->redirectObsoleteModule();
    }

    public function update(Request $request, $service)
    {
        return $this->redirectObsoleteModule();
    }
}
