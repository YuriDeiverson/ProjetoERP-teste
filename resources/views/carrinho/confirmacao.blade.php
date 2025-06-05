<!-- Pagina de confirmação da compra -->

@extends('layouts.app')

@section('content')
<h2>Confirmação do Pedido</h2>

<h4>Endereço de entrega:</h4>
<p>{{ $dados['rua'] }}, {{ $dados['bairro'] }}, {{ $dados['cidade'] }} - {{ $dados['uf'] }}</p>
<p>CEP: {{ $dados['cep'] }}</p>

<h4>Itens do Pedido:</h4>
<ul>
  @foreach ($carrinho as $item)
  <li>
    {{ $item['nome'] }} - Quantidade: {{ $item['quantidade'] }} -
    Total: R$ {{ number_format($item['preco'] * $item['quantidade'], 2, ',', '.') }}
  </li>
  @endforeach
</ul>

<p>Subtotal: R$ {{ number_format($subtotal, 2, ',', '.') }}</p>
<p>Frete: R$ {{ number_format($frete, 2, ',', '.') }}</p>
<p><strong>Total: R$ {{ number_format($total, 2, ',', '.') }}</strong></p>

@endsection