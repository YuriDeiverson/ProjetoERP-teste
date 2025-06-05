<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function atualizar(Request $request)
    {
        $pedido = Pedido::find($request->id);
        if (!$pedido) {
            return response()->json(['error' => 'Pedido não encontrado'], 404);
        }

        if ($request->status == 'cancelado') {
            $pedido->delete();
        } else {
            $pedido->status = $request->status;
            $pedido->save();
        }

        return response()->json(['status' => 'ok']);
    }
}