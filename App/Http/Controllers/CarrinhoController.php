<?php
namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Produto;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Mail;

class CarrinhoController extends Controller
{
public function adicionar(Request $request)
{
    $produtoId = $request->input('produto_id');
    $estoqueId = $request->input('estoque_id');
    $quantidade = (int) $request->input('quantidade');

    $estoque = Estoque::findOrFail($estoqueId);

    // Verifica se tem estoque suficiente
    if ($quantidade > $estoque->quantidade) {
        return back()->with('error', 'Quantidade solicitada maior que o estoque disponível.');
    }

    // Diminui a quantidade do estoque no banco de dados
    $estoque->quantidade -= $quantidade;
    $estoque->save();

    // Adiciona ao carrinho (sessão)
    $carrinho = session()->get('carrinho', []);

    $chave = $produtoId . '-' . $estoqueId;

    if (isset($carrinho[$chave])) {
        $carrinho[$chave]['quantidade'] += $quantidade;
    } else {
        $carrinho[$chave] = [
            'produto_id' => $produtoId,
            'estoque_id' => $estoqueId,
            'quantidade' => $quantidade,
            'preco' => $estoque->produto->preco,
            'nome' => $estoque->produto->nome,
            'variacao' => $estoque->variacao,
        ];
    }

    session()->put('carrinho', $carrinho);

    return back()->with('success', 'Produto adicionado ao carrinho!');
}
     public function mostrar()
    {
        $carrinho = array_values(session('carrinho', []));
        $subtotal = collect($carrinho)->reduce(fn($t, $i) => $t + ($i['preco'] * $i['quantidade']), 0);

        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15;
        } elseif ($subtotal > 200) {
            $frete = 0;
        } else {
            $frete = 20;
        }

        return view('carrinho.index', compact('carrinho', 'subtotal', 'frete'));
    }

    // Novo método para mostrar a tela de finalizar pedido
    public function mostrarFinalizar()
    {
        $carrinho = session('carrinho', []);
        if (empty($carrinho)) {
            return redirect()->route('produtos.index')->with('error', 'Carrinho vazio');
        }

        $subtotal = collect($carrinho)->reduce(fn($t, $i) => $t + ($i['preco'] * $i['quantidade']), 0);

        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15;
        } elseif ($subtotal > 200) {
            $frete = 0;
        } else {
            $frete = 20;
        }

        return view('carrinho.index', compact('carrinho', 'subtotal', 'frete'));
    }

   
public function remover($id)
{
    $carrinho = session('carrinho', []);

    foreach ($carrinho as $chave => $item) {
        // Identifica o item pelo estoque_id
        if ($item['estoque_id'] == $id) {
            // Repor o estoque no banco
            $estoque = Estoque::find($item['estoque_id']);
            if ($estoque) {
                $estoque->quantidade += $item['quantidade'];
                $estoque->save();
            }

            // Remove o item do carrinho
            unset($carrinho[$chave]);
        }
    }

    session(['carrinho' => array_values($carrinho)]); // Reindexa o array

    return back()->with('success', 'Item removido do carrinho e estoque restaurado.');
}
}