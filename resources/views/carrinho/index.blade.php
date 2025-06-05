<!-- Pagina do carrinho para compra-->

@extends('layouts.app')

@section('navbar-button')
<a href="{{ url('/') }}" class="btn btn-secondary">Voltar</a>
@endsection

@section('content')
<div class="container mt-5">
  <h1 class="mb-4">Meu Carrinho</h1>

  @if(count($carrinho) > 0)
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
      @endphp
      <tr>
        <td>{{ $item['nome'] }}</td>
        <td>{{ $item['variacao'] ?? '-' }}</td>
        <td>{{ $item['quantidade'] }}</td>
        <td>R$ {{ number_format(($item['preco']), 2, ',', '.') }}</td>
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
  </table>

  @php
  $frete = 20.00;
  if ($subtotal >= 52 && $subtotal <= 166.59) { $frete=15.00; } elseif ($subtotal> 200) {
    $frete = 0.00;
    }
    $total = $subtotal + $frete;
    @endphp

    <!-- FORMULÁRIO COMPLETO -->
    <form action="{{ route('carrinho.finalizar') }}" method="POST" id="finalizarCompraForm">
      @csrf

      <!-- Endereço via CEP -->
      <div class="mt-4">
        <h5>CEP</h5>
        <div class="d-flex align-items-center gap-2">
          <input name="cep" type="text" id="cep" placeholder="Digite seu CEP" class="form-control w-auto" maxlength="9"
            style="max-width: 150px;" required />
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
      </div>

      <!-- Campo de e-mail -->
      <div class="mt-3">
        <label for="email" class="form-label required:">Seu e-mail</label>
        <input name="email" type="email" id="email" class="form-control" placeholder="Digite seu e-mail" required />
      </div>

      <!-- Resumo -->
      <div class="d-flex justify-content-between align-items-center mt-4">
        <h5>Total com Frete: R$ {{ number_format($total, 2, ',', '.') }}</h5>
        <button type="submit" class="btn btn-primary">Finalizar Compra</button>
      </div>
    </form>

    @else
    <div class="alert alert-info">Seu carrinho está vazio.</div>
    @endif
</div>

<!-- jQuery + ViaCEP Script -->
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
        $("#rua, #bairro, #cidade, #uf").val("Carregando...");
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
      alert("Informe o CEP.");
    }
  });
});
</script>
@endsection