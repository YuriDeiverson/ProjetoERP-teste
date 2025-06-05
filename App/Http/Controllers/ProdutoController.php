<?php
namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Estoque;
use App\Models\Pedido;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::with('estoques')->get();
        session()->put('carrinho', session('carrinho', []));
return view('produtos.index', compact('produtos'));
    }

 public function store(Request $request)
{
    $request->validate([
        'nome' => 'required|string',
        'preco' => 'required',
    ]);

    $preco = str_replace(',', '.', $request->input('preco'));

    $produto = Produto::create([
        'nome' => $request->input('nome'),
        'preco' => $preco,
    ]);

    $variacoes = $request->input('variacoes', []);
    $quantidades = $request->input('quantidades', []);

    foreach ($variacoes as $index => $variacao) {
        if (!empty($variacao) && isset($quantidades[$index])) {
            Estoque::create([
                'produto_id' => $produto->id,
                'variacao' => $variacao,
                'quantidade' => $quantidades[$index],
            ]);
        }
    }

    return redirect()->back()->with('success', 'Produto cadastrado com variações');
}

    public function update(Request $request, Produto $produto)
{
    $produto->update($request->only('nome', 'preco'));

    foreach ($request->input('estoques', []) as $id => $dados) {
    $estoque = Estoque::find($id);
    if ($estoque) {
        $estoque->variacao = $dados['variacao'] ?? $estoque->variacao;

        if (isset($dados['quantidade'])) {
            $estoque->quantidade += (int) $dados['quantidade'];
        }

        $estoque->save();
    }
}

    return back()->with('success', 'Produto atualizado com sucesso');
}

    public function adicionarCarrinho(Request $request)
    {
        $carrinho = session('carrinho', []);
        $carrinho[] = $request->all();
        session(['carrinho' => $carrinho]);
        return back();
    }

    public function finalizarPedido(Request $request)
    {
        $carrinho = session('carrinho', []);
        $total = collect($carrinho)->sum(fn($item) => $item['preco'] * $item['quantidade']);

        if ($request->frete > 0) {
            $total += $request->frete;
        }

        Pedido::create(['total' => $total]);
        session()->forget('carrinho');
        return back()->with('success', 'Pedido finalizado!');
    }

    public function destroy(Produto $produto)
{
    // Remove estoques vinculados antes
    $produto->estoques()->delete();

    // Depois remove o produto
    $produto->delete();

    return redirect()->back()->with('success', 'Produto removido com sucesso.');
}

public function atualizarQuantidade(Request $request, Estoque $estoque)
{
    $request->validate([
        'quantidade' => 'required|integer|min:0'
    ]);

    // Soma a quantidade enviada à quantidade atual
    $estoque->quantidade += $request->quantidade;
    $estoque->save();

    return response()->json(['success' => true, 'nova_quantidade' => $estoque->quantidade]);
}

public function adicionarVariacao(Request $request, Produto $produto)
{
    $request->validate([
        'variacao' => 'required|string',
        'quantidade' => 'required|integer|min:0',
    ]);

    $produto->estoques()->create([
        'variacao' => $request->variacao,
        'quantidade' => $request->quantidade,
    ]);

    return back()->with('success', 'Nova variação adicionada!');
}

public function removerVariacao(Estoque $estoque)
{
    $produtoId = $estoque->produto_id;
    $estoque->delete();

    return redirect()->route('produtos.index')
        ->with('success', 'Variação removida com sucesso.');
}
}