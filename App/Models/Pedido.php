<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['subtotal', 'frete', 'total', 'cep', 'endereco', 'email', 'status'];

    public function produtos()
    {
        return $this->belongsToMany(Estoque::class, 'pedido_produto')
            ->withPivot('quantidade', 'preco_unitario')
            ->withTimestamps();
    }
}