<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoProduto extends Model
{
    protected $table = 'pedido_produto';

    protected $fillable = ['pedido_id', 'estoque_id', 'quantidade', 'preco_unitario'];
}