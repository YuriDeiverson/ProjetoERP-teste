<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\WebhookController;





// Página inicial / lista de produtos
Route::get('/', [ProdutoController::class, 'index'])->name('produtos.index');

// Rotas para produtos
Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
Route::put('/produtos/{produto}', [ProdutoController::class, 'update']);

// Rotas do carrinho
Route::post('/carrinho/adicionar', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
Route::get('/carrinho', [CarrinhoController::class, 'mostrar'])->name('carrinho.mostrar');
Route::post('carrinho/remover/{id}', [CarrinhoController::class, 'remover'])->name('carrinho.remover');



// Tela de finalizar pedido (GET e POST)
Route::get('/carrinho/finalizar', [CarrinhoController::class, 'mostrarFinalizar'])->name('carrinho.finalizar');
Route::post('/carrinho/finalizar', [PedidoController::class, 'finalizar'])->name('carrinho.finalizar.post');

// Webhook para atualização de pedidos
Route::post('/webhook/pedido', [WebhookController::class, 'atualizar']);

// Atualizar quantidade
Route::post('/estoques/{estoque}/atualizar-quantidade', [ProdutoController::class, 'atualizarQuantidade'])->name('estoques.atualizarQuantidade');

// Deletar produto
Route::delete('/produtos/{produto}', [ProdutoController::class, 'destroy'])->name('produtos.destroy');

//remover variacao
Route::delete('/variacoes/{estoque}', [ProdutoController::class, 'removerVariacao'])->name('variacoes.remover');

// Adicionar variação
Route::post('/produtos/{produto}/adicionar-variacao', [ProdutoController::class, 'adicionarVariacao'])->name('produtos.adicionar.variacao');