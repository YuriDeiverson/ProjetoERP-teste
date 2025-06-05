<!-- Pagina de finalizacao da compra -->

@extends('layouts.app')

@section('navbar-button') <a href="{{ url('/') }}" class="btn btn-secondary">Voltar</a> @endsection @section('content')

<div class="container mt-5">
  <h1 class="mb-4">Meu Carrinho</h1>@if(count($carrinho) > 0)

  <table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th>Nome</th>
        <th>Variação</th>
        <th>Quantidade</th>
        <th>Preço Unit.</th>
        <th>Total</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($carrinho as $item)
      @php
      $idParaRemover = $item['estoque_id'] ?? $item['produto_id'] ?? null;
      @endphp<tr>
        <td>{{ $item['nome'] }}</td>
        <td>{{ $item['variacao'] ?? '-' }}</td>
        <td>{{ $item['quantidade'] }}</td>
        <td>R$ {{ number_format(($item['quantidade'] * $item['preco']), 2, ',', '.') }}</td>
        <td>
          @if ($idParaRemover)
          <form action="{{ route('carrinho.remover', $idParaRemover) }}" method="POST" style="display:inline-block;">
            @csrf
            <button type="submit" class="btn btn-sm btn-danger">Remover</button>
          </form>
          @else
          <span class="text-danger">ID inválido</span>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>

  </table>@php $frete = 20.00; if ($subtotal >= 52 && $subtotal <= 166.59) { $frete=15.00; } elseif ($subtotal> 200) {
    $frete = 0.00; } $total = $subtotal + $frete; @endphp

    <div class="mt-4">
      <div class="mb-2">
        <h5>Subtotal: R$ {{ number_format($subtotal, 2, ',', '.') }}</h5>
      </div>
      <div class="mb-2">
        <h5>Frete: R$ {{ number_format($frete, 2, ',', '.') }}</h5>
      </div>
    </div>

    <hr class="my-4">
    <h5>CEP</h5>

    <form id="cepForm" class="mb-3">
      <div class="d-flex align-items-center gap-2">
        <input name="cep" type="text" id="cep" placeholder="Digite seu CEP" class="form-control w-auto" maxlength="9"
          style="max-width: 150px;" />
        <button type="button" id="buscarCep" class="btn btn-primary">OK</button>
      </div>

      <div id="endereco-container" class="mt-3 d-none">
        <div class="d-flex gap-3 mb-2">
          <input name="rua" type="text" id="rua" class="form-control" placeholder="Rua" readonly />
          <input name="bairro" type="text" id="bairro" class="form-control" placeholder="Bairro" readonly />
        </div>
        <div class="d-flex gap-3">
          <input name="cidade" type="text" id="cidade" class="form-control" placeholder="Cidade" readonly />
          <input name="uf" type="text" id="uf" class="form-control" placeholder="Estado" readonly />
        </div>
      </div>
    </form>

    <div class="mt-3">
      <label for="email" class="form-label">Seu e-mail</label>
      <input name="email" type="email" id="email" class="form-control" placeholder="Digite seu e-mail" required />
    </div>

    <form action="{{ route('carrinho.finalizar') }}" method="POST" id="finalizarCompraForm">
      @csrf
      <input type="hidden" name="cep_hidden" id="inputCep">
      <input type="hidden" name="rua_hidden" id="inputRua">
      <input type="hidden" name="bairro_hidden" id="inputBairro">
      <input type="hidden" name="cidade_hidden" id="inputCidade">
      <input type="hidden" name="uf_hidden" id="inputUf">
      <input type="hidden" name="email_hidden" id="inputEmail">
      <div class="d-flex justify-content-between align-items-center mt-4">
        <h5>Total com Frete: R$ {{ number_format($subtotal + $frete, 2, ',', '.') }}</h5>
        <button type="submit" class="btn btn-primary">Finalizar Compra</button>
      </div>
    </form>

    @else

    <div class="alert alert-info">Seu carrinho está vazio.</div>
    @endif
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
  function limparCampos() {
    $("#rua, #bairro, #cidade, #uf").val("");
    $("#endereco-container").addClass("d-none");
  }

  $('#buscarCep').on('click', function() {
    var cep = $('#cep').val().replace(/\D/g, '');

    if (cep !== "") {
      var validacep = /^[0-9]{8}$/;

      if (validacep.test(cep)) {
        $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {
          if (!("erro" in dados)) {
            $("#rua").val(dados.logradouro);
            $("#bairro").val(dados.bairro);
            $("#cidade").val(dados.localidade);
            $("#uf").val(dados.uf);
            $("#endereco-container").removeClass("d-none");
          } else {
            limparCampos();
            alert("CEP não encontrado.");
          }
        });
      } else {
        limparCampos();
        alert("Formato de CEP inválido.");
      }
    } else {
      limparCampos();
    }
  });

  $('#cep').on('input', function() {
    if ($(this).val().trim() === "") {
      limparCampos();
    }
  });

  $('#finalizarCompraForm').on('submit', function() {
    $('#inputCep').val($('#cep').val());
    $('#inputRua').val($('#rua').val());
    $('#inputBairro').val($('#bairro').val());
    $('#inputCidade').val($('#cidade').val());
    $('#inputUf').val($('#uf').val());
    $('#inputEmail').val($('#email').val());
  });
});
</script>@endsection @stack('scripts')