<?php
namespace App\Http\Controllers;

use App\Mail\CompraFinalizada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class PedidoController extends Controller
{
    public function finalizar(Request $request)
{
    $carrinho = session('carrinho', []);
    if (empty($carrinho)) {
        return redirect()->route('carrinho.mostrar')->with('error', 'Seu carrinho está vazio.');
    }

    $subtotal = array_sum(array_map(function ($item) {
        return $item['preco'] * $item['quantidade'];
    }, $carrinho));

    // Regra do frete
    $frete = 20.00;
    if ($subtotal >= 52 && $subtotal <= 166.59) {
        $frete = 15.00;
    } elseif ($subtotal > 200) {
        $frete = 0.00;
    }

    $total = $subtotal + $frete;

    // Pegando os dados do formulário
    $dados = $request->only(['cep', 'rua', 'bairro', 'cidade', 'uf', 'email']);

    // Limpando carrinho após finalizar (opcional)
    session()->forget('carrinho');

    // Enviando para view de confirmação
    return view('carrinho.confirmacao', [
        'carrinho' => $carrinho,
        'subtotal' => $subtotal,
        'frete' => $frete,
        'total' => $total,
        'dados' => $dados,
    ]);
}
}